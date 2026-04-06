<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class FeaturedProducts extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingProduct = null;
    public $featuredOrder = null;

    protected $rules = [
        'featuredOrder' => 'nullable|integer|min:1|max:10',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function toggleFeatured($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update([
            'is_featured' => !$product->is_featured,
            'featured_order' => $product->is_featured ? null : 1,
        ]);
        session()->flash('message', $product->is_featured ? 'Produk ditambahkan ke unggulan.' : 'Produk dihapus dari unggulan.');
    }

    public function openOrderModal($productId)
    {
        $this->editingProduct = Product::findOrFail($productId);
        $this->featuredOrder = $this->editingProduct->featured_order;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingProduct = null;
        $this->featuredOrder = null;
        $this->resetValidation();
    }

    public function saveOrder()
    {
        $this->validate();

        if ($this->editingProduct) {
            $this->editingProduct->update([
                'featured_order' => $this->featuredOrder,
            ]);
            session()->flash('message', 'Urutan produk unggulan diperbarui.');
        }

        $this->closeModal();
    }

    public function moveUp($productId)
    {
        $product = Product::findOrFail($productId);
        $currentOrder = $product->featured_order ?? 1;

        if ($currentOrder > 1) {
            $product->update(['featured_order' => $currentOrder - 1]);
        }
    }

    public function moveDown($productId)
    {
        $product = Product::findOrFail($productId);
        $currentOrder = $product->featured_order ?? 1;

        $product->update(['featured_order' => $currentOrder + 1]);
    }

    public function render()
    {
        // Get all products for selection
        $query = Product::query()
            ->with(['category'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('is_featured')
            ->orderBy('featured_order')
            ->orderBy('id');

        $products = $query->paginate(10);

        // Get currently featured products count
        $featuredCount = Product::where('is_featured', true)->count();

        return view('livewire.admin.featured-products', [
            'products' => $products,
            'featuredCount' => $featuredCount,
        ])->layout('components.layouts.app');
    }
}
