<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'inventory_count',
        'is_available',
        'category_id',
        'image',
        'code',
        'model'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function buyers()
    {
        return $this->belongsToMany(User::class, 'user_bought_products')
            ->withPivot('bought_at', 'price_at_purchase', 'status')
            ->withTimestamps();
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                    ->where('inventory_count', '>', 0);
    }

    public function reduceInventory()
    {
        $this->decrement('inventory_count');
        
        if ($this->inventory_count === 0) {
            $this->update(['is_available' => false]);
        }
        
        return $this;
    }
}