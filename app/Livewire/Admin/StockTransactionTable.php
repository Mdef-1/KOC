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


    // Logic Hapus (Soft Delete)
    public function delete($id)
    {
        StockTransaction::findOrFail($id)->delete();
        session()->flash('message', 'Transaksi berhasil dipindahkan ke sampah.');
    }

    // Logic Restore (Mengembalikan data)
    public function restore($id)
    {
        StockTransaction::withTrashed()->findOrFail($id)->restore();
        session()->flash('message', 'Transaksi berhasil dikembalikan.');
    }
    public function updatingSearch()
    {
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
                $inventory = \App\Models\Inventory::where('product_id', $this->product_id)
                    ->where('sizes_id', $this->size_id)
                    ->first();

                if (!$inventory) {
                    throw new \Exception("Inventory tidak ditemukan untuk produk dan ukuran ini!");
                }

                $oldStock = $inventory->stock;

                // Update Stok di tabel Inventory
                if (!$this->selected_id) {
                    if ($this->type === 'in') {
                        $inventory->increment('stock', $this->quantity);
                    } else {
                        if ($inventory->stock < $this->quantity) {
                            throw new \Exception("Stok tidak cukup!");
                        }
                        $inventory->decrement('stock', $this->quantity);
                    }
                    $inventory->update(['last_updated' => now()]);
                }

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
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
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
        return view('livewire.admin.stock-transaction-table', [
            'transactions' => StockTransaction::with(['product', 'size'])
                ->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->latest('created_at')
                ->paginate($this->perPage),
            'products' => Product::all(),
            'sizes' => Size::all()
        ]);
    }
}