<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model  {

	protected $fillable = [
        'name', 'description', 'price', 'inventory_count', 'is_available'
    ];
    public function purchases()
{
    return $this->hasMany(Purchase::class);
}

protected $casts = [
    'is_available' => 'boolean'
];

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