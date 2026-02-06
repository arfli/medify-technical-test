<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterItemCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'master_item_id',
    ];

    /**
     * Get the master item
     */
    public function masterItem()
    {
        return $this->belongsTo(MasterItem::class, 'master_item_id');
    }

    /**
     * Get the category
     */
    public function categoryItem()
    {
        return $this->belongsTo(CategoryItem::class, 'category_id');
    }
}