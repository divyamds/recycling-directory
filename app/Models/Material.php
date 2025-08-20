<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['name'];

   public function facilities()
{
    return $this->belongsToMany(Facility::class, 'facility_material');
}
}
