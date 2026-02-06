<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get items in this category
     */
    public function masterItems()
    {
        return $this->belongsToMany(
            MasterItem::class,
            'master_item_categories',
            'category_id',
            'master_item_id'
        )->withTimestamps();
    }
}