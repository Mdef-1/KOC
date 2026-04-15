<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\Product;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;

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
        $this->dispatch('refresh');
    }

    public function updatedProductId($value)
    {
        // Reset size_id when product changes
        $this->size_id = null;
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
        try {
            Inventory::findOrFail($id)->delete();
            session()->flash('message', 'Item Deleted Successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting item: ' . $e->getMessage());
        }
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
        // Rate limit: max 5 adjustments per minute per user
        $key = 'adjust-stock:' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('adjustmentQty', 'Terlalu banyak perubahan stok. Coba lagi dalam ' . RateLimiter::availableIn($key) . ' detik.');
            return;
        }
        RateLimiter::hit($key, 60);

        $this->validate([
            'adjustmentQty' => 'required|integer|min:1',
            'adjustmentType' => 'required|in:in,out',
            'adjustmentNote' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () {
                $inventory = Inventory::lockForUpdate()->findOrFail($this->adjustmentInventoryId);
                $oldStock = $inventory->stock;

                if ($this->adjustmentType === 'out' && $inventory->stock < $this->adjustmentQty) {
                    throw new \Exception('Stok tidak mencukupi untuk pengurangan.');
                }

                if ($this->adjustmentType === 'in') {
                    $inventory->increment('stock', $this->adjustmentQty);
                } else {
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
            });

            $this->closeStockModal();
            session()->flash('message', 'Stok berhasil di' . ($this->adjustmentType === 'in' ? 'tambah' : 'kurang') . '.');
        } catch (\Exception $e) {
            $this->addError('adjustmentQty', $e->getMessage());
        }
    }

    public function quickAdjustStock($id, $amount)
    {
        // Rate limit: max 10 quick adjustments per minute per user
        $key = 'quick-adjust-stock:' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            session()->flash('error', 'Terlalu banyak perubahan stok cepat. Coba lagi nanti.');
            return;
        }
        RateLimiter::hit($key, 60);

        try {
            DB::transaction(function () use ($id, $amount) {
                $inventory = Inventory::lockForUpdate()->findOrFail($id);
                $oldStock = $inventory->stock;

                if ($amount > 0) {
                    $inventory->increment('stock', $amount);
                    $type = 'in';
                } else {
                    $deduct = abs($amount);
                    if ($inventory->stock < $deduct) {
                        throw new \Exception('Stok tidak mencukupi.');
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
            });

            session()->flash('message', 'Stok berhasil diperbarui.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    private function resetInputFields()
    {
        $this->reset(['product_id', 'size_id', 'stock', 'warehouse_location', 'cost_price', 'sku', 'price', 'selected_id']);
    }

    public function render()
    {
        // ERROR: Undefined variable $searchTerm (should be $this->search)
        $inventoryData = Inventory::with(['size', 'product.category'])
            ->where('products.name', 'like', '%' . $this->search . '%')
            ->join('products', 'products.id', '=', 'inventory.product_id')
            ->where(function($query) {
                $query->where('products.name', 'like', '%' . $this->search . '%')
                      ->orWhere('inventory.sku', 'like', '%' . $this->search . '%');
            })
            ->orderBy('products.name', 'asc')
            ->orderBy('inventory.sizes_id', 'asc')
            ->select('inventory.*') // Prevent column ambiguity
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

        // Get sizes based on selected product's category
        $sizes = collect();
        if ($this->product_id) {
            $product = Product::with('category')->find($this->product_id);
            if ($product && $product->category_id) {
                $sizes = \App\Models\Size::where('category_id', $product->category_id)
                    ->orderBy('label', 'asc')
                    ->get();
            } else {
                // If product has no category, show all sizes
                $sizes = \App\Models\Size::orderBy('label', 'asc')->get();
            }
        } else {
            // No product selected, show all sizes
            $sizes = \App\Models\Size::orderBy('label', 'asc')->get();
        }

        return view('livewire.admin.inventory-table', [
            'groupedInventory' => $productsPaginator,
            'expandedProducts' => $this->expandedProducts,
            'sizes' => $sizes,
        ]);
    }
}