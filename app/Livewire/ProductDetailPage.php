<?php

namespace App\Livewire;

use App\Models\Inventory;
use App\Models\Product;
use Livewire\Component;

class ProductDetailPage extends Component
{
    public $product;
    public $inventories;
    public $selectedInventoryId = null;
    public $selectedSize = null;

    public function mount($id)
    {
        // Get product with gallery
        $this->product = Product::with(['gallery', 'category'])->findOrFail($id);
        
        // Increment view count
        $this->product->increment('view_count');
        
        // Get all inventory items for this product (all sizes with stock)
        $this->inventories = Inventory::with(['size'])
            ->where('product_id', $id)
            ->orderBy('sizes_id')
            ->get();
        
        // Set default selected inventory (first available with stock, or just first)
        $defaultInventory = $this->inventories->firstWhere('stock', '>', 0) 
            ?? $this->inventories->first();
        
        if ($defaultInventory) {
            $this->selectedInventoryId = $defaultInventory->id;
            $this->selectedSize = $defaultInventory;
        }
    }

    public function selectSize($inventoryId)
    {
        $this->selectedInventoryId = $inventoryId;
        $this->selectedSize = $this->inventories->firstWhere('id', $inventoryId);
    }

    public function render()
    {
        return view('livewire.product-detail-page')
                ->layout('components.layouts.guest'); 
    }
}