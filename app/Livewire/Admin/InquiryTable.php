<?php

namespace App\Livewire\admin;

use Livewire\Component;
use App\Models\Inquiry;
use App\Models\Product;
use Livewire\WithPagination;

class InquiryTable extends Component
{
    use WithPagination;

    // Properti Model (Sesuai fillable & Blade)
    public $selected_id, $customer_name, $customer_contact, $product_id, $message, $status;
    
    // UI State
    public $isOpen = false;
    public $search = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        // Disamakan dengan opsi pertama di select Blade
        $this->status = 'pending'; 
        $this->isOpen = true;
    }

    public function store()
    {
        // Validasi disesuaikan dengan opsi status di Blade
        $this->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_contact' => 'required|string|max:100',
            'product_id'       => 'required|exists:products,id',
            'message'          => 'nullable|string|max:1000',
            'status'           => 'required|in:pending,contacted,deal,cancelled',
        ]);

        Inquiry::updateOrCreate(['id' => $this->selected_id], [
            'customer_name'    => $this->customer_name,
            'customer_contact' => $this->customer_contact,
            'product_id'       => $this->product_id,
            'message'          => $this->message,
            'status'           => $this->status,
        ]);

        session()->flash('message', $this->selected_id ? 'Inquiry Updated.' : 'Inquiry Recorded.');
        
        $this->isOpen = false;
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $this->selected_id      = $id;
        $this->customer_name    = $inquiry->customer_name;
        $this->customer_contact = $inquiry->customer_contact;
        $this->product_id       = $inquiry->product_id;
        $this->message          = $inquiry->message;
        $this->status           = $inquiry->status;
        
        $this->isOpen = true;
    }

    public function delete($id)
    {
        Inquiry::find($id)->delete();
        session()->flash('message', 'Inquiry Deleted Successfully.');
    }

    private function resetInputFields()
    {
        $this->reset(['customer_name', 'customer_contact', 'product_id', 'message', 'status', 'selected_id']);
    }

    public function render()
    {
        // Mengirimkan variabel $inquiries (Bukan $categories) ke Blade
        $inquiryData = Inquiry::with('product')
            ->where(function($query) {
                $query->where('customer_name', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_contact', 'like', '%' . $this->search . '%')
                      ->orWhereHas('product', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.inquiry-table', [
            'inquiries' => $inquiryData, // Nama variabel ini harus @foreach($inquiries...) di blade
            'products'  => Product::all(),
        ]);
    }
}