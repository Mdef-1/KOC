<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'is_active',
        'is_featured',
        'featured_order',
        'view_count',
        'order_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'view_count' => 'integer',
        'order_count' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->orderBy('featured_order')->orderBy('id');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(ProductGalleryModel::class);
    }
    

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
