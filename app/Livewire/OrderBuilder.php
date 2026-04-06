<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderBuilder extends Component
{
    use WithFileUploads;

    // Step 1: Product Selection
    public $selectedCategory = null;
    public $selectedProduct = null;
    
    // Step 2: Design
    public $designType = 'custom'; // 'custom' or 'repeat'
    public $designFile = null;
    public $designNotes = '';
    public $selectedPreviousDesign = null;
    
    // Step 3: Size Grid
    public $sizeGrid = []; // ['S' => 5, 'M' => 10, ...]
    public $availableSizes = [];
    
    // Step 4: Customer Info
    public $customerName = '';
    public $customerContact = '';
    public $customerAddress = '';
    
    // UI State
    public $currentStep = 1;
    public $totalQty = 0;
    public $moqMet = false;
    const MOQ = 12;
    
    // Order Summary Modal
    public $showSummary = false;
    public $orderNumber = '';
    
    protected $rules = [
        'selectedProduct' => 'required|exists:products,id',
        'designFile' => 'nullable|image|max:5120', // 5MB max
        'designNotes' => 'nullable|string|max:500',
        'customerName' => 'required|string|min:3|max:100',
        'customerContact' => 'required|string|min:10|max:20',
        'customerAddress' => 'required|string|min:10|max:500',
    ];
    
    protected $listeners = ['refreshOrderBuilder' => '$refresh'];
    
    public function mount()
    {
        $this->resetSizeGrid();
    }
    
    public function resetSizeGrid()
    {
        // Reset only quantities, keep available sizes
        foreach ($this->availableSizes as $size) {
            $this->sizeGrid[$size] = 0;
        }
        $this->totalQty = 0;
        $this->moqMet = false;
    }
    
    public function updatedSelectedCategory($value)
    {
        $this->selectedProduct = null;
        $this->resetSizeGrid();
        $this->currentStep = 1;
    }
    
    public function updatedSelectedProduct($value)
    {
        if ($value) {
            $product = Product::with('category.sizes')->find($value);
            if ($product && $product->category) {
                $this->availableSizes = $product->category->sizes
                    ->sortBy('sort_order')
                    ->pluck('label')
                    ->toArray();
                
                // Initialize size grid with 0
                foreach ($this->availableSizes as $size) {
                    $this->sizeGrid[$size] = 0;
                }
            }
        }
        $this->calculateTotal();
    }
    
    public function updatedSizeGrid()
    {
        $this->calculateTotal();
    }
    
    public function calculateTotal()
    {
        $this->totalQty = array_sum(array_map('intval', $this->sizeGrid));
        $this->moqMet = $this->totalQty >= self::MOQ;
    }
    
    public function incrementSize($size)
    {
        $this->sizeGrid[$size] = (int)($this->sizeGrid[$size] ?? 0) + 1;
        $this->calculateTotal();
    }
    
    public function decrementSize($size)
    {
        $current = (int)($this->sizeGrid[$size] ?? 0);
        if ($current > 0) {
            $this->sizeGrid[$size] = $current - 1;
            $this->calculateTotal();
        }
    }
    
    public function setSizeQty($size, $qty)
    {
        $this->sizeGrid[$size] = max(0, intval($qty));
        $this->calculateTotal();
    }
    
    public function goToStep($step)
    {
        // Validate current step before proceeding
        if ($step > $this->currentStep) {
            if (!$this->validateStep($this->currentStep)) {
                return;
            }
        }
        
        $this->currentStep = $step;
    }
    
    public function validateStep($step)
    {
        switch ($step) {
            case 1:
                if (!$this->selectedProduct) {
                    session()->flash('error', 'Pilih produk terlebih dahulu.');
                    return false;
                }
                break;
            case 2:
                if ($this->designType === 'custom' && !$this->designFile && empty($this->designNotes)) {
                    session()->flash('error', 'Upload desain atau beri keterangan desain.');
                    return false;
                }
                break;
            case 3:
                if (!$this->moqMet) {
                    session()->flash('error', 'Minimum order ' . self::MOQ . ' pcs. Tambah quantity.');
                    return false;
                }
                break;
        }
        return true;
    }
    
    public function nextStep()
    {
        if ($this->validateStep($this->currentStep)) {
            $this->currentStep++;
        }
    }
    
    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }
    
    public function submitOrder()
    {
        $this->validate();
        
        if (!$this->moqMet) {
            session()->flash('error', 'Minimum order ' . self::MOQ . ' pcs.');
            return;
        }
        
        // Generate order number
        $this->orderNumber = 'PO' . date('Ymd') . rand(100, 999);
        
        // Filter size grid (only include > 0)
        $sizeDetails = [];
        foreach ($this->sizeGrid as $size => $qty) {
            if ($qty > 0) {
                $sizeDetails[] = "$size: $qty pcs";
            }
        }
        
        // Create WhatsApp message
        $product = Product::find($this->selectedProduct);
        $waMessage = "Halo KOC, saya mau order:\n\n";
        $waMessage .= "📋 *Order Number:* {$this->orderNumber}\n";
        $waMessage .= "👕 *Produk:* {$product->name}\n";
        $waMessage .= "📊 *Total:* {$this->totalQty} pcs\n\n";
        $waMessage .= "📏 *Rincian Size:*\n" . implode("\n", $sizeDetails) . "\n\n";
        
        if ($this->designFile) {
            $waMessage .= "🎨 *Desain:* (File akan diupload di WhatsApp)\n";
        }
        if ($this->designNotes) {
            $waMessage .= "📝 *Keterangan:* {$this->designNotes}\n";
        }
        
        $waMessage .= "\n👤 *Customer:* {$this->customerName}\n";
        $waMessage .= "📱 *Kontak:* {$this->customerContact}\n";
        $waMessage .= "📍 *Alamat:* {$this->customerAddress}\n\n";
        $waMessage .= "Mohon info harga dan cara pembayaran. Terima kasih!";
        
        // Store order in database
        \App\Models\Order::create([
            'order_number' => $this->orderNumber,
            'product_id' => $this->selectedProduct,
            'customer_name' => $this->customerName,
            'customer_contact' => $this->customerContact,
            'customer_address' => $this->customerAddress,
            'size_quantities' => array_filter($this->sizeGrid, fn($qty) => $qty > 0),
            'total_quantity' => $this->totalQty,
            'design_notes' => $this->designNotes,
            'design_file_path' => $this->designFile ? $this->designFile->store('designs', 'public') : null,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Increment product order count
        $product->increment('order_count');
        
        $this->showSummary = true;
        
        // Dispatch event to open WhatsApp
        $waUrl = "https://wa.me/6289513229597?text=" . urlencode($waMessage);
        $this->dispatch('openWhatsApp', url: $waUrl);
    }
    
    public function resetForm()
    {
        $this->reset([
            'selectedCategory', 'selectedProduct', 'designType', 'designFile',
            'designNotes', 'selectedPreviousDesign', 'customerName',
            'customerContact', 'customerAddress', 'showSummary', 'orderNumber'
        ]);
        $this->resetSizeGrid();
        $this->currentStep = 1;
    }
    
    public function render()
    {
        $categories = Category::with(['sizes', 'products' => function($q) {
            $q->where('is_active', true);
        }])->get();
        
        $products = $this->selectedCategory 
            ? Product::where('category_id', $this->selectedCategory)
                ->where('is_active', true)
                ->get()
            : collect();
        
        return view('livewire.order-builder', [
            'categories' => $categories,
            'products' => $products,
        ])->layout('components.layouts.guest');
    }
}
