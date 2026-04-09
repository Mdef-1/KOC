<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Modal states
    public $showModal = false;
    public $showDeleteModal = false;
    public $editingOrder = null;
    public $orderToDelete = null;

    // Form fields
    public $status = '';
    public $adminNotes = '';
    public $unitPrice = '';
    public $totalPrice = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openEditModal($orderId)
    {
        $this->editingOrder = Order::with('product')->find($orderId);
        if ($this->editingOrder) {
            $this->status = $this->editingOrder->status;
            $this->adminNotes = $this->editingOrder->admin_notes ?? '';
            $this->unitPrice = $this->editingOrder->unit_price ?? '';
            $this->totalPrice = $this->editingOrder->total_price ?? '';
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingOrder = null;
        $this->reset(['status', 'adminNotes', 'unitPrice', 'totalPrice']);
    }

    public function saveOrder()
    {
        if (!$this->editingOrder) return;

        $this->validate([
            'status' => 'required|in:pending,confirmed,production,sewing,packing,shipped,completed,cancelled',
            'adminNotes' => 'nullable|string',
            'unitPrice' => 'nullable|numeric|min:0',
            'totalPrice' => 'nullable|numeric|min:0',
        ]);

        $this->editingOrder->update([
            'status' => $this->status,
            'admin_notes' => $this->adminNotes,
            'unit_price' => $this->unitPrice ?: null,
            'total_price' => $this->totalPrice ?: null,
        ]);

        $this->closeModal();
        $this->dispatch('notify', type: 'success', message: 'Order updated successfully');
    }

    public function confirmDelete($orderId)
    {
        $this->orderToDelete = Order::find($orderId);
        $this->showDeleteModal = true;
    }

    public function deleteOrder()
    {
        if ($this->orderToDelete) {
            $this->orderToDelete->delete();
            $this->showDeleteModal = false;
            $this->orderToDelete = null;
            $this->dispatch('notify', type: 'success', message: 'Order deleted successfully');
        }
    }

    public function render()
    {
        $query = Order::with('product');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_contact', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $orders = $query->orderBy($this->sortField, $this->sortDirection)
                        ->paginate($this->perPage);

        $statuses = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'production' => 'Produksi',
            'sewing' => 'Penjahitan',
            'packing' => 'Packing',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return view('livewire.admin.order-table', [
            'orders' => $orders,
            'statuses' => $statuses,
        ])->layout('layouts.app');
    }
}
