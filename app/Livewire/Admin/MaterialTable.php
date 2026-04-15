<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Material;
use Livewire\WithPagination;

class MaterialTable extends Component
{
    use WithPagination;

    public $selected_id, $name, $code, $description, $sort_order, $is_active = true;
    
    public $isOpen = false;
    public $search = '';
    public $perPage = 10;

    protected $rules = [
        'name' => 'required|string|min:2|max:100',
        'code' => 'nullable|string|max:50|unique:materials,code',
        'description' => 'nullable|string|max:500',
        'sort_order' => 'integer|min:0',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|min:2|max:100',
            'code' => 'nullable|string|max:50|unique:materials,code,' . $this->selected_id,
            'description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        Material::updateOrCreate(['id' => $this->selected_id], [
            'name' => $this->name,
            'code' => $this->code ?: null,
            'description' => $this->description,
            'sort_order' => $this->sort_order ?? 0,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', $this->selected_id ? 'Material Updated Successfully.' : 'Material Created Successfully.');
        
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $material->name;
        $this->code = $material->code;
        $this->description = $material->description;
        $this->sort_order = $material->sort_order;
        $this->is_active = $material->is_active;
        
        $this->isOpen = true;
    }

    public function delete($id)
    {
        $material = Material::findOrFail($id);
        
        if ($material->orders()->count() > 0) {
            session()->flash('error', 'Cannot delete material that has orders.');
            return;
        }

        $material->delete();
        session()->flash('message', 'Material Deleted Successfully.');
    }

    public function toggleActive($id)
    {
        $material = Material::findOrFail($id);
        $material->update(['is_active' => !$material->is_active]);
        session()->flash('message', 'Material status updated.');
    }

    private function resetInputFields()
    {
        $this->reset(['name', 'code', 'description', 'sort_order', 'selected_id', 'is_active']);
        $this->is_active = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        $materials = Material::withCount('orders')
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.admin.material-table', [
            'materials' => $materials,
        ]);
    }
}
