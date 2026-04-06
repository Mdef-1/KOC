<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\Product;
use Livewire\WithPagination;

class InventoryTable extends Component
{
    use WithPagination; // Wajib untuk pagination

    public $selected_id, $product_id, $stock, $sku, $price, $cost_price, $warehouse_location, $size_id;
    public $isOpen = false;
    public $isStockModalOpen = false;
    public $adjustmentQty = 0;
    public $adjustmentType = 'in'; // 'in' or 'out'
    public $adjustmentNote = '';
    public $adjustmentInventoryId = null;
    public $perPage = 10;
    public $search = '';
    public $expandedProducts = []; // Track expanded product IDs

    // Toggle expand/collapse for a product
    public function toggleProduct($productId)
    {
        if (in_array($productId, $this->expandedProducts)) {
            $this->expandedProducts = array_diff($this->expandedProducts, [$productId]);
        } else {
            $this->expandedProducts[] = $productId;
        }
    }

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

    public function createWithProduct($productId)
    {
        $this->resetInputFields();
        $this->product_id = $productId;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'warehouse_location' => 'required|string|max:100', 
            'cost_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
        ]);

        Inventory::updateOrCreate(['id' => $this->selected_id], [
            'product_id' => $this->product_id,
            'sizes_id' => $this->size_id,
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
        $this->size_id = $inventory->sizes_id;
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

    public function openStockModal($id)
    {
        $this->adjustmentInventoryId = $id;
        $this->adjustmentQty = 0;
        $this->adjustmentType = 'in';
        $this->adjustmentNote = '';
        $this->isStockModalOpen = true;
    }

    public function closeStockModal()
    {
        $this->isStockModalOpen = false;
        $this->adjustmentInventoryId = null;
        $this->adjustmentQty = 0;
        $this->adjustmentNote = '';
    }

    public function adjustStock()
    {
        $this->validate([
            'adjustmentQty' => 'required|integer|min:1',
            'adjustmentType' => 'required|in:in,out',
            'adjustmentNote' => 'nullable|string|max:255',
        ]);

        $inventory = Inventory::findOrFail($this->adjustmentInventoryId);
        $oldStock = $inventory->stock;

        if ($this->adjustmentType === 'in') {
            $inventory->increment('stock', $this->adjustmentQty);
        } else {
            if ($inventory->stock < $this->adjustmentQty) {
                $this->addError('adjustmentQty', 'Stok tidak mencukupi untuk pengurangan.');
                return;
            }
            $inventory->decrement('stock', $this->adjustmentQty);
        }

        $inventory->update(['last_updated' => now()]);

        // Create stock transaction record
        \App\Models\StockTransaction::create([
            'transaction_id' => 'TXN-' . now()->format('YmdHis') . '-' . uniqid() . '-' . $inventory->id,
            'product_id' => $inventory->product_id,
            'size_id' => $inventory->sizes_id,
            'type' => $this->adjustmentType,
            'quantity' => $this->adjustmentQty,
            'old_stock' => $oldStock,
            'new_stock' => $inventory->fresh()->stock,
            'reference_id' => $this->adjustmentNote ?: 'Manual Adjustment',
            'created_at' => now(),
        ]);

        $this->closeStockModal();
        session()->flash('message', 'Stok berhasil di' . ($this->adjustmentType === 'in' ? 'tambah' : 'kurang') . '.');
    }

    public function quickAdjustStock($id, $amount)
    {
        $inventory = Inventory::findOrFail($id);
        $oldStock = $inventory->stock;

        if ($amount > 0) {
            $inventory->increment('stock', $amount);
            $type = 'in';
        } else {
            $deduct = abs($amount);
            if ($inventory->stock < $deduct) {
                session()->flash('error', 'Stok tidak mencukupi.');
                return;
            }
            $inventory->decrement('stock', $deduct);
            $type = 'out';
        }

        $inventory->update(['last_updated' => now()]);

        \App\Models\StockTransaction::create([
            'transaction_id' => 'TXN-' . now()->format('YmdHis') . '-' . uniqid() . '-' . $inventory->id,
            'product_id' => $inventory->product_id,
            'size_id' => $inventory->sizes_id,
            'type' => $type,
            'quantity' => abs($amount),
            'old_stock' => $oldStock,
            'new_stock' => $inventory->fresh()->stock,
            'reference_id' => 'Quick Adjust',
            'created_at' => now(),
        ]);

        session()->flash('message', 'Stok berhasil diperbarui.');
    }

    private function resetInputFields()
    {
        $this->reset(['product_id', 'size_id', 'stock', 'warehouse_location', 'cost_price', 'sku', 'price', 'selected_id']);
    }

    public function render()
    {
        // Get all inventory items with their relationships, sorted by product name then size
        $inventoryData = Inventory::with(['product', 'size', 'product.category'])
            ->whereHas('product', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->orderByRaw('(SELECT name FROM products WHERE products.id = inventory.product_id) ASC')
            ->orderBy('sizes_id', 'asc')
            ->get();

        // Group by product
        $groupedByProduct = $inventoryData->groupBy(function($item) {
            return $item->product_id;
        });

        // Get unique products for pagination
        $productIds = $inventoryData->pluck('product_id')->unique()->values();
        $currentPage = $this->getPage();
        $perPage = $this->perPage;
        $total = $productIds->count();
        $offset = ($currentPage - 1) * $perPage;
        $paginatedProductIds = $productIds->slice($offset, $perPage)->values();

        // Build paged data manually to avoid getKey error
        $pagedGroupedData = [];
        foreach ($paginatedProductIds as $productId) {
            if (isset($groupedByProduct[$productId])) {
                $pagedGroupedData[$productId] = $groupedByProduct[$productId];
            }
        }

        // Create paginator for products
        $productsPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $pagedGroupedData,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        return view('livewire.admin.inventory-table', [
            'groupedInventory' => $productsPaginator,
            'expandedProducts' => $this->expandedProducts,
        ]);
    }
}