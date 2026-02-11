<?php

namespace App\Livewire;

use App\Models\Inventory;
use Livewire\Component;

class ProductDetailPage extends Component
{
    public $inventory;

    public function mount($id)
    {
        $this->inventory = Inventory::with(['product.gallery'])->findOrFail($id);
    }

    public function render()
    {
        // Paksa pakai layout guest di sini
        return view('livewire.product-detail-page')
                ->layout('components.layouts.guest'); 
    }
}