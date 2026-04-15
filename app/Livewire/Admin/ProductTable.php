<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // Penting untuk menangani image_url
use Illuminate\Support\Str;

class ProductTable extends Component
{
    use WithPagination, WithFileUploads;

    // Properti Model
    public $selected_id, $category_id, $name, $slug, $description, $image_url, $is_active = true;

    // UI State
    public $isOpen = false;
    public $search = '';
    public $perPage = 10;

    public $new_image;

    protected $rules = [
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|min:3|max:255',
        'slug' => 'required|string|unique:products,slug',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
        'new_image' => 'nullable|image|max:2048', // Max 2MB
    ];

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
        $this->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $this->selected_id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        Product::updateOrCreate(['id' => $this->selected_id], $data);
        
        session()->flash('message', $this->selected_id ? 'Product Updated.' : 'Product Created.');
        
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->selected_id = $id;
        $this->category_id = $product->category_id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->description = $product->description;
        $this->is_active = $product->is_active;
        
        $this->isOpen = true;
    }   

    public function delete($id)
    {
        try {
            $product = Product::findOrFail($id);
            // Opsional: Hapus file gambar dari storage jika ada
            // if($product->image_url) Storage::disk('public')->delete($product->image_url);

            $product->delete();
            session()->flash('message', 'Product Deleted.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    private function resetInputFields()
    {
        $this->reset(['category_id', 'name', 'slug', 'description', 'image_url', 'is_active', 'selected_id', 'new_image']);
    }

    public function render()
    {
        return view('livewire.admin.product-table', [
            'products' => Product::with('category')
                ->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('slug', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate($this->perPage),
            'categories' => Category::all() // Untuk dropdown di modal
        ]);
    }
}