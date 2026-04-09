<?php

namespace App\Livewire\Public;

use App\Models\Order;
use Livewire\Component;

class OrderTracking extends Component
{
    public $orderNumber = '';
    public $phoneNumber = '';
    public $order = null;
    public $searched = false;
    public $error = '';

    public function search()
    {
        $this->validate([
            'orderNumber' => 'required|string|min:3',
            'phoneNumber' => 'required|string|min:10',
        ]);

        $this->searched = true;
        $this->error = '';

        // Escape special LIKE characters to prevent unexpected matches
        $escapedPhone = str_replace(['%', '_'], ['\%', '\_'], $this->phoneNumber);

        // Search in orders table
        $foundOrder = Order::where('order_number', $this->orderNumber)
            ->where('customer_contact', 'like', '%' . $escapedPhone . '%')
            ->first();

        if ($foundOrder) {
            $this->order = [
                'number' => $foundOrder->order_number,
                'status' => $foundOrder->status,
                'date' => $foundOrder->created_at,
                'product' => $foundOrder->product?->name ?? 'Custom Order',
                'customer' => $foundOrder->customer_name,
                'total_quantity' => $foundOrder->total_quantity,
                'size_quantities' => $foundOrder->size_quantities,
            ];
        } else {
            $this->error = 'Order tidak ditemukan. Periksa nomor order dan nomor WhatsApp Anda.';
        }
    }

    public function resetSearch()
    {
        $this->reset(['orderNumber', 'phoneNumber', 'order', 'searched', 'error']);
    }

    public function getStatusStep()
    {
        $steps = [
            'pending' => 1,
            'confirmed' => 2,
            'production' => 3,
            'sewing' => 4,
            'packing' => 5,
            'shipped' => 6,
            'completed' => 7,
        ];
        
        return $steps[$this->order['status']] ?? 1;
    }

    public function render()
    {
        return view('livewire.public.order-tracking')->layout('layouts.guest');
    }
}
