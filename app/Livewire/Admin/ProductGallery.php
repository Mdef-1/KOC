<?php

namespace App\Livewire\Admin;

use App\Models\ProductGalleryModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Product;

use Illuminate\Support\Facades\Storage;

class ProductGallery extends Component
{
    use WithPagination, WithFileUploads;

    // Model fields
    public $selected_id;
    public $product_id;
    public $image;          // upload file
    public $is_primary = false;
    public $sort_order = 0;

    // UI state
    public $isOpen = false;
    public $search = '';
    public $perPage = 12;

    protected function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'image'      => $this->selected_id
                ? 'nullable|image|max:2048'
                : 'required|image|max:2048',
            'is_primary' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'product_id' => $this->product_id,
            'is_primary' => $this->is_primary,
            'sort_order' => $this->sort_order ?? 0,
        ];

        // Upload image
        if ($this->image) {
            $data['image_url'] = $this->image->store('product-gallery', 'public');
        }

        // Jika primary → set lainnya false
        if ($this->is_primary) {
            ProductGalleryModel::where('product_id', $this->product_id)
                ->where('id', '!=', $this->selected_id)
                ->update(['is_primary' => false]);
        }

        ProductGalleryModel::updateOrCreate(
            ['id' => $this->selected_id],
            $data
        );

        session()->flash(
            'message',
            $this->selected_id
                ? 'Gallery image updated.'
                : 'Gallery image added.'
        );

        $this->isOpen = false;
        $this->resetInput();
    }

    public function edit($id)
    {
        $gallery = ProductGalleryModel::findOrFail($id);

        $this->selected_id = $gallery->id;
        $this->product_id = $gallery->product_id;
        $this->is_primary = $gallery->is_primary;
        $this->sort_order = $gallery->sort_order;

        $this->image = null; // tidak preload file

        $this->isOpen = true;
    }

    public function delete($id)
    {
        $gallery = ProductGalleryModel::findOrFail($id);

        if ($gallery->image_url) {
            Storage::disk('public')->delete($gallery->image_url);
        }

        $gallery->delete();

        session()->flash('message', 'Gallery image deleted.');
    }

    private function resetInput()
    {
        $this->reset([
            'selected_id',
            'product_id',
            'image',
            'is_primary',
            'sort_order',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.product-gallery', [
            'galleries' => ProductGalleryModel::with('product')
                ->when($this->search, function ($q) {
                    $q->whereHas('product', function ($p) {
                        $p->where('name', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('sort_order')
                ->paginate($this->perPage),

            'products' => Product::orderBy('name')->get(),
        ]);
    }
}
