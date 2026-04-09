<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\StockTransaction;
use App\Models\Product;
use App\Models\Size;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class StockTransactionTable extends Component
{
    use WithPagination;

    // Properti sesuai fillable Model
    public $selected_id, $product_id, $size_id, $quantity, $type = 'in', $reference_id, $description;
    public $old_stock, $new_stock;
    public $isOpen = false;
    public $perPage = 10;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $typeFilter = '';
    public $productFilter = '';
    public $sizeFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $referenceFilter = '';
    public $showTrashed = false;
    public $confirmAction = '';
    public $confirmId = null;
    public $isConfirmOpen = false;
    public $confirmMessage = '';
    public $confirmTitle = '';


    // Modal Konfirmasi
    public function confirmDelete($id)
    {
        $this->confirmId = $id;
        $this->confirmAction = 'delete';
        $this->confirmTitle = 'Hapus Transaksi';
        $this->confirmMessage = 'Apakah Anda yakin ingin menghapus transaksi ini? Data akan dipindahkan ke sampah.';
        $this->isConfirmOpen = true;
    }

    public function confirmRestore($id)
    {
        $this->confirmId = $id;
        $this->confirmAction = 'restore';
        $this->confirmTitle = 'Restore Transaksi';
        $this->confirmMessage = 'Apakah Anda yakin ingin mengembalikan transaksi ini?';
        $this->isConfirmOpen = true;
    }

    public function closeConfirm()
    {
        $this->isConfirmOpen = false;
        $this->confirmId = null;
        $this->confirmAction = '';
        $this->confirmMessage = '';
        $this->confirmTitle = '';
    }

    public function executeConfirm()
    {
        if ($this->confirmAction === 'delete' && $this->confirmId) {
            StockTransaction::findOrFail($this->confirmId)->delete();
            session()->flash('message', 'Transaksi berhasil dipindahkan ke sampah.');
        } elseif ($this->confirmAction === 'restore' && $this->confirmId) {
            StockTransaction::withTrashed()->findOrFail($this->confirmId)->restore();
            session()->flash('message', 'Transaksi berhasil dikembalikan.');
        }

        $this->closeConfirm();
    }

    // Logic Hapus (Soft Delete) - deprecated, pakai modal
    public function delete($id)
    {
        StockTransaction::findOrFail($id)->delete();
        session()->flash('message', 'Transaksi berhasil dipindahkan ke sampah.');
    }

    // Logic Restore (Mengembalikan data) - deprecated, pakai modal
    public function restore($id)
    {
        StockTransaction::withTrashed()->findOrFail($id)->restore();
        session()->flash('message', 'Transaksi berhasil dikembalikan.');
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProductFilter()
    {
        $this->resetPage();
    }

    public function updatingSizeFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function updatingReferenceFilter()
    {
        $this->resetPage();
    }

    public function updatingShowTrashed()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'typeFilter', 'productFilter', 'sizeFilter', 'dateFrom', 'dateTo', 'referenceFilter']);
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
        $this->dispatch('contentChanged');
    }

    public function store()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reference_id' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () {
                \Log::info('Store transaction', ['product_id' => $this->product_id, 'size_id' => $this->size_id, 'selected_id' => $this->selected_id]);

                $inventory = \App\Models\Inventory::where('product_id', $this->product_id)
                    ->where('sizes_id', $this->size_id)
                    ->first();

                if (!$inventory) {
                    throw new \Exception("Inventory tidak ditemukan untuk produk dan ukuran ini!");
                }

                $oldStock = $inventory->stock;

                // Jika edit mode, reverse stok dari transaksi lama
                if ($this->selected_id) {
                    $oldTransaction = StockTransaction::find($this->selected_id);
                    if ($oldTransaction) {
                        if ($oldTransaction->type === 'in') {
                            $inventory->decrement('stock', $oldTransaction->quantity);
                        } else {
                            $inventory->increment('stock', $oldTransaction->quantity);
                        }
                    }
                }

                // Apply stok baru
                if ($this->type === 'in') {
                    $inventory->increment('stock', $this->quantity);
                } else {
                    if ($inventory->fresh()->stock < $this->quantity) {
                        throw new \Exception("Stok tidak cukup!");
                    }
                    $inventory->decrement('stock', $this->quantity);
                }
                $inventory->update(['last_updated' => now()]);

                $newStock = $inventory->fresh()->stock;

                StockTransaction::updateOrCreate(['id' => $this->selected_id], [
                    'transaction_id' => $this->selected_id ? null : 'TRX-' . strtoupper(uniqid()),
                    'product_id' => $this->product_id,
                    'size_id' => $this->size_id,
                    'quantity' => $this->quantity,
                    'type' => $this->type,
                    'reference_id' => $this->reference_id,
                    'old_stock' => $oldStock,
                    'new_stock' => $newStock,
                    'description' => $this->description,
                ]);
            });

            session()->flash('message', 'Transaksi berhasil disimpan.');
            $this->isOpen = false;
            $this->resetInputFields();
            $this->dispatch('contentChanged');
        } catch (\Exception $e) { 
            session()->flash('error', $e->getMessage());
            $this->dispatch('notify', ['message' => $e->getMessage(), 'type' => 'error']);
        }
    }

    public function edit($id)
    {
        $transaction = StockTransaction::findOrFail($id);
        $this->selected_id = $id;
        $this->product_id = $transaction->product_id;
        $this->size_id = $transaction->size_id;
        $this->quantity = $transaction->quantity;
        $this->type = $transaction->type;
        $this->reference_id = $transaction->reference_id;
        $this->description = $transaction->description;
        $this->isOpen = true;
    }

    private function resetInputFields()
    {
        $this->reset(['selected_id', 'product_id', 'size_id', 'quantity', 'reference_id', 'description']);
        $this->type = 'in';
    }

    public function render()
    {
        $query = StockTransaction::with(['product', 'size'])
            ->when($this->showTrashed, function ($q) {
                $q->onlyTrashed();
            })
            ->whereHas('product', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });

        // Type filter
        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        // Product filter
        if ($this->productFilter) {
            $query->where('product_id', $this->productFilter);
        }

        // Size filter
        if ($this->sizeFilter) {
            $query->where('size_id', $this->sizeFilter);
        }

        // Date range filter
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Reference filter
        if ($this->referenceFilter) {
            $query->where('reference_id', 'like', '%' . $this->referenceFilter . '%');
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.admin.stock-transaction-table', [
            'transactions' => $query->paginate($this->perPage),
            'products' => Product::all(),
            'sizes' => Size::all()
        ]);
    }
}