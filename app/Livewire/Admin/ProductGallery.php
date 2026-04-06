<?php

namespace App\Livewire\Admin;

use App\Models\ProductGalleryModel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductGallery extends Component
{
    use WithFileUploads, WithPagination;

    public $selectedProduct = null;
    public $productSearch = '';
    public $images = [];
    public $newImages = [];
    public $uploading = false;
    public $editingImage = null;
    public $editSortOrder = 0;
    public $editIsPrimary = false;
    public $showEditModal = false;
    public $perPage = 12;

    public function updatingProductSearch()
    {
        $this->resetPage();
    }

    public function selectProduct($productId)
    {
        $this->selectedProduct = Product::with(['category', 'gallery'])->find($productId);
        $this->loadProductGallery();
        $this->newImages = [];
    }

    public function closeProductGallery()
    {
        $this->selectedProduct = null;
        $this->images = [];
        $this->newImages = [];
        $this->editingImage = null;
        $this->showEditModal = false;
    }

    public function loadProductGallery()
    {
        if ($this->selectedProduct) {
            $this->images = ProductGalleryModel::where('product_id', $this->selectedProduct->id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->toArray();
        }
    }

    public function updatedNewImages()
    {
        if (empty($this->newImages)) return;
        
        $this->uploading = true;
        
        try {
            $maxOrder = ProductGalleryModel::where('product_id', $this->selectedProduct->id)->max('sort_order') ?? 0;
            
            foreach ($this->newImages as $index => $image) {
                $path = $image->store('product-gallery', 'public');
                
                ProductGalleryModel::create([
                    'product_id' => $this->selectedProduct->id,
                    'image_url' => $path,
                    'is_primary' => ($maxOrder === 0 && $index === 0),
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
            
            session()->flash('message', count($this->newImages) . ' gambar berhasil ditambahkan.');
            $this->newImages = [];
            $this->loadProductGallery();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal upload: ' . $e->getMessage());
        }
        
        $this->uploading = false;
    }

    public function deleteImage($imageId)
    {
        $image = ProductGalleryModel::find($imageId);
        if ($image) {
            if ($image->image_url) {
                Storage::disk('public')->delete($image->image_url);
            }
            
            $wasPrimary = $image->is_primary;
            $image->delete();
            
            if ($wasPrimary) {
                $firstImage = ProductGalleryModel::where('product_id', $this->selectedProduct->id)
                    ->orderBy('sort_order')
                    ->first();
                if ($firstImage) {
                    $firstImage->update(['is_primary' => true]);
                }
            }
            
            $this->loadProductGallery();
            session()->flash('message', 'Gambar dihapus.');
        }
    }

    public function setPrimary($imageId)
    {
        ProductGalleryModel::where('product_id', $this->selectedProduct->id)
            ->update(['is_primary' => false]);
        
        ProductGalleryModel::where('id', $imageId)
            ->update(['is_primary' => true]);
        
        $this->loadProductGallery();
        session()->flash('message', 'Gambar utama diubah.');
    }

    public function openEditModal($imageId)
    {
        $image = ProductGalleryModel::find($imageId);
        if ($image) {
            $this->editingImage = $image->toArray();
            $this->editSortOrder = $image->sort_order;
            $this->editIsPrimary = $image->is_primary;
            $this->showEditModal = true;
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingImage = null;
        $this->editSortOrder = 0;
        $this->editIsPrimary = false;
    }

    public function saveEdit()
    {
        if ($this->editingImage) {
            $image = ProductGalleryModel::find($this->editingImage['id']);
            if ($image) {
                if ($this->editIsPrimary && !$image->is_primary) {
                    ProductGalleryModel::where('product_id', $image->product_id)
                        ->update(['is_primary' => false]);
                }
                
                $image->update([
                    'sort_order' => $this->editSortOrder,
                    'is_primary' => $this->editIsPrimary,
                ]);
                
                $this->loadProductGallery();
                $this->closeEditModal();
                session()->flash('message', 'Perubahan disimpan.');
            }
        }
    }

    public function moveUp($imageId)
    {
        $image = ProductGalleryModel::find($imageId);
        if ($image && $image->sort_order > 1) {
            $prevImage = ProductGalleryModel::where('product_id', $image->product_id)
                ->where('sort_order', '<', $image->sort_order)
                ->orderByDesc('sort_order')
                ->first();
            
            if ($prevImage) {
                $tempOrder = $image->sort_order;
                $image->update(['sort_order' => $prevImage->sort_order]);
                $prevImage->update(['sort_order' => $tempOrder]);
            } else {
                $image->update(['sort_order' => $image->sort_order - 1]);
            }
            
            $this->loadProductGallery();
        }
    }

    public function moveDown($imageId)
    {
        $image = ProductGalleryModel::find($imageId);
        if ($image) {
            $image->update(['sort_order' => $image->sort_order + 1]);
            $this->loadProductGallery();
        }
    }

    public function render()
    {
        $productsQuery = Product::query()
            ->with(['category'])
            ->withCount('gallery')
            ->when($this->productSearch, function ($q) {
                $q->where('name', 'like', '%' . $this->productSearch . '%');
            })
            ->orderByDesc('gallery_count')
            ->orderBy('name');

        return view('livewire.admin.product-gallery', [
            'products' => $productsQuery->paginate($this->perPage),
        ])->layout('components.layouts.app');
    }
}
