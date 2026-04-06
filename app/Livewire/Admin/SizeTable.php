<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Size;
use App\Models\Category;
use Livewire\WithPagination;

class SizeTable extends Component
{
    use WithPagination;

    // Properti Model
    public $selected_id, $category_id, $label, $width, $length, $sleeve_length, $extra_price;
    
    // UI State
    public $isOpen = false;
    public $search = '';
    public $perPage = 10;

    protected $rules = [
        'category_id' => 'nullable|exists:categories,id',
        'label' => 'required|string|max:50',
        'width' => 'required|numeric|min:0',
        'length' => 'required|numeric|min:0',
        'sleeve_length' => 'nullable|numeric|min:0',
        'extra_price' => 'required|numeric|min:0',
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
            'category_id' => 'nullable|exists:categories,id',
            'label' => 'required|string|max:50',
            'width' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:0',
            'sleeve_length' => 'nullable|numeric|min:0',
            'extra_price' => 'required|numeric|min:0',
        ]);

        Size::updateOrCreate(['id' => $this->selected_id], [
            'category_id' => $this->category_id,
            'label' => $this->label,
            'width' => $this->width,
            'length' => $this->length,
            'sleeve_length' => $this->sleeve_length,
            'extra_price' => $this->extra_price,
        ]);

        session()->flash('message', $this->selected_id ? 'Size Updated Successfully.' : 'Size Created Successfully.');
        
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $size = Size::findOrFail($id);
        $this->selected_id = $id;
        $this->category_id = $size->category_id;
        $this->label = $size->label;
        $this->width = $size->width;
        $this->length = $size->length;
        $this->sleeve_length = $size->sleeve_length;
        $this->extra_price = $size->extra_price;
        
        $this->isOpen = true;
    }

    public function delete($id)
    {
        $size = Size::findOrFail($id);
        
        // Cek apakah size masih digunakan di inventory
        if ($size->inventories()->count() > 0) {
            session()->flash('error', 'Cannot delete size that is used in inventory.');
            return;
        }

        $size->delete();
        session()->flash('message', 'Size Deleted Successfully.');
    }

    private function resetInputFields()
    {
        $this->reset(['category_id', 'label', 'width', 'length', 'sleeve_length', 'extra_price', 'selected_id']);
    }

    public function render()
    {
        $sizesData = Size::with('category')
            ->where('label', 'like', '%' . $this->search . '%')
            ->orWhereHas('category', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('label', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.size-table', [
            'sizes' => $sizesData,
        ]);
    }
}
