<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\StockTransaction;
use App\Models\Inventory;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class StockTransactionTable extends Component
{
    use WithPagination;

    // Properti sesuai fillable Model
    public $selected_id, $inventory_id, $quantity, $type = 'in', $description;
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
    }

    public function store()
    {
        $this->validate([
            'inventory_id' => 'required|exists:inventory,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () {
                $inventory = Inventory::findOrFail($this->inventory_id);

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

                StockTransaction::updateOrCreate(['id' => $this->selected_id], [
                    'inventory_id' => $this->inventory_id,
                    'user_id' => auth()->id(),
                    'quantity' => $this->quantity,
                    'type' => $this->type,
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
        $this->inventory_id = $transaction->inventory_id;
        $this->quantity = $transaction->quantity;
        $this->type = $transaction->type;
        $this->description = $transaction->description;
        $this->isOpen = true;
    }

    private function resetInputFields()
    {
        $this->reset(['selected_id', 'inventory_id', 'quantity', 'description']);
        $this->type = 'in';
    }

    public function render()
    {
        return view('livewire.admin.stock-transaction-table', [
            'transactions' => StockTransaction::with(['inventory.product'])
                ->whereHas('inventory.product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })
                ->latest('id')
                ->paginate($this->perPage),
            'inventory_items' => Inventory::with('product')->get()
        ]);
    }
}