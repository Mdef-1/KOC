<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Inquiry;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.guest')]
class CatalogPage extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'cat')]
    public string $category = '';

    public int $perPage = 12;

    public bool $showInquiryModal = false;
    public ?int $selectedProductId = null;
    public $selectedProduct = null;
    public string $customer_name = '';
    public string $customer_contact = '';
    public string $message = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function setCategory(string $slug = ''): void
    {
        $this->category = $this->category === $slug ? '' : $slug;
        
    }
    public function openInquiry(int $productId): void
    {
        $this->resetValidation();
        $this->selectedProductId = $productId;
        $this->selectedProduct = \App\Models\Product::find($productId);
        $this->customer_name = '';
        $this->customer_contact = '';
        $this->message = '';
        $this->showInquiryModal = true;
    }

    public function closeInquiry(): void
    {
        $this->showInquiryModal = false;
        $this->selectedProductId = null;
    }

    public function submitInquiry(): void
    {
        $data = $this->validate([
            'customer_name' => ['required', 'string', 'min:3', 'max:100'],
            'customer_contact' => ['required', 'string', 'min:3', 'max:150'],
            'message' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        if (!$this->selectedProductId) {
            $this->addError('selectedProductId', 'Produk tidak ditemukan.');
            return;
        }

        Inquiry::create([
            'customer_name' => $data['customer_name'],
            'customer_contact' => $data['customer_contact'],
            'product_id' => $this->selectedProductId,
            'message' => $data['message'],
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $this->showInquiryModal = false;
        $this->selectedProductId = null;
        session()->flash('success', 'Inquiry berhasil dikirim. Kami akan menghubungi Anda segera.');
    }

    public function render()
    {
        $query = Product::query()
            ->with(['category', 'gallery' => function ($q) {
                $q->orderByDesc('is_primary')->orderBy('sort_order');
            }])
            ->select('products.*')
            ->selectSub(function ($query) {
                $query->from('inventory')
                    ->selectRaw('MIN(price)')
                    ->whereColumn('inventory.product_id', 'products.id');
            }, 'price')
            ->active();

        if ($this->search !== '') {
            $query->where('products.name', 'like', '%' . $this->search . '%');
        }

        if ($this->category !== '') {
            $query->whereRelation('category', 'slug', $this->category);
        }

        $products = $query->orderByDesc('products.id')->paginate($this->perPage);

        $categories = Category::query()->orderBy('name')->get(['id', 'name', 'slug']);

        $selectedProduct = null;
        if ($this->showInquiryModal && $this->selectedProductId) {
            $selectedProduct = Product::with(['gallery' => function ($q) {
                $q->orderByDesc('is_primary')->orderBy('sort_order');
            }])->find($this->selectedProductId);
        }

        return view('livewire.catalog-page', [
            'title' => 'Katalog Produk • K.O.C',
            'products' => $products,
            'categories' => $categories,
            'selectedProduct' => $selectedProduct,
        ]);
    }
}
