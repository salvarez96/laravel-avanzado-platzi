<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function categorizedProducts() {
        return $this->belongsToMany(Category::class);
    }

    public function createdBy() {
        return $this->belongsTo(User::class);
    }
}
