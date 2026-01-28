<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class CategoryTable extends Component
{
    use WithPagination;

    // Properti Model
    public $selected_id, $name, $slug, $description;
    
    // UI State
    public $isOpen = false;
    public $search = '';
    public $perPage = 10;

    protected $rules = [
        'name' => 'required|string|min:3|max:50',
        'slug' => 'required|string|unique:categories,slug',
        'description' => 'nullable|string|max:255',
    ];

    /**
     * Otomatis update slug saat nama diisi
     */
    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

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
        // Validasi unik kecuali untuk ID yang sedang diedit
        $this->validate([
            'name' => 'required|string|min:3|max:50',
            'slug' => 'required|string|unique:categories,slug,' . $this->selected_id,
            'description' => 'nullable|string|max:255',
        ]);

        Category::updateOrCreate(['id' => $this->selected_id], [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ]);

        session()->flash('message', $this->selected_id ? 'Category Updated Successfully.' : 'Category Created Successfully.');
        
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        
        $this->isOpen = true;
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        
        // Opsional: Cek apakah kategori masih memiliki produk sebelum dihapus
        if ($category->products()->count() > 0) {
            session()->flash('error', 'Cannot delete category that has products.');
            return;
        }

        $category->delete();
        session()->flash('message', 'Category Deleted Successfully.');
    }

    private function resetInputFields()
    {
        $this->reset(['name', 'slug', 'description', 'selected_id']);
    }

    public function render()
    {
        // Menggunakan withCount untuk menampilkan jumlah produk di tiap kategori
        $categoriesData = Category::withCount('products')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('slug', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.category-table', [
            'categories' => $categoriesData,
        ]);
    }
}