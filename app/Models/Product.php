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
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
