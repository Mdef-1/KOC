<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\Product;
use Livewire\WithPagination;

class InventoryTable extends Component
{
    use WithPagination; // Wajib untuk pagination

    public $selected_id, $product_id, $stock, $sku, $price, $cost_price, $warehouse_location;
    public $isOpen = false;
    public $perPage = 10;
    public $search = '';

    // Reset halaman ke 1 setiap kali user mengetik di kolom pencarian
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
            'product_id' => 'required|exists:products,id',
            'warehouse_location' => 'required|string|max:100', 
            'cost_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        Inventory::updateOrCreate(['id' => $this->selected_id], [
            'product_id' => $this->product_id,
            'stock' => $this->stock,
            'cost_price' => $this->cost_price,
            'warehouse_location' => $this->warehouse_location,
            'sku' => $this->sku,
            'price' => $this->price,
            'last_updated' => now(),
        ]);

        session()->flash('message', $this->selected_id ? 'Item Updated Successfully.' : 'Item Created Successfully.');
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $this->selected_id = $id;
        $this->product_id = $inventory->product_id;
        $this->stock = $inventory->stock;
        $this->cost_price = $inventory->cost_price;
        $this->warehouse_location = $inventory->warehouse_location;
        $this->sku = $inventory->sku;
        $this->price = $inventory->price;
        $this->isOpen = true;
    }

    public function delete($id)
    {
        Inventory::find($id)->delete();
        session()->flash('message', 'Item Deleted Successfully.');
    }

    private function resetInputFields()
    {
        $this->reset(['product_id', 'stock', 'warehouse_location', 'cost_price', 'sku', 'price', 'selected_id']);
    }

    public function render()
    {
        // Pindahkan logic query ke sini agar pagination berjalan otomatis
        $inventoryData = Inventory::with('product')
            ->whereHas('product', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderBy('last_updated', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.inventory-table', [
            'inventory' => $inventoryData, // Mengirim objek LengthAwarePaginator
        ]);
    }
}