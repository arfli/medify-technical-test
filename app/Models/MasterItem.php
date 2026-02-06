<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'harga_beli',
        'laba',
        'supplier',
        'jenis',
        'foto'
    ];

    /**
     * Get categories for this item
     */
    public function categories()
    {
        return $this->belongsToMany(
            CategoryItem::class,
            'master_item_categories',  // pivot table name
            'master_item_id',           // foreign key on pivot table for this model
            'category_id'               // foreign key on pivot table for related model
        )->withTimestamps();
    }

    /**
     * Get category relation through pivot table
     */
    public function masterItemCategories()
    {
        return $this->hasMany(MasterItemCategories::class, 'master_item_id');
    }
}