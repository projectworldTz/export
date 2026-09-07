<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
 protected $guarded=[];
 protected $casts=['specifications'=>'array','featured'=>'boolean','price'=>'decimal:2','fob_price'=>'decimal:2','cif_price'=>'decimal:2','shipping_cost'=>'decimal:2'];
 public function images(){return $this->hasMany(ProductImage::class)->orderBy('sort_order');}
 public function getPrimaryImageAttribute(){return $this->images->first()?->path ?? $this->image;}
 public function getDisplayNameAttribute(){return app()->isLocale('fr')&&$this->name_fr?$this->name_fr:$this->name;}
 public function getDisplayDescriptionAttribute(){return app()->isLocale('fr')&&$this->description_fr?$this->description_fr:$this->description;}
}
