<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'sizes_id',
        'sku',
        'warehouse_location',   
        'stock',
        'price',
        'cost_price',
        'last_updated',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'last_updated' => 'datetime',
    ];

    protected $appends = ['final_price'];

    public function getFinalPriceAttribute(): float
    {
        $basePrice = (float) $this->price;
        $extraPrice = (float) ($this->size->extra_price ?? 0);
        return $basePrice + $extraPrice;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'sizes_id');
    }

    public function stockTransaction(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }
}
