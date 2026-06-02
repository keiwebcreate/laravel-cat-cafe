<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Cat;

class Blog extends Model
{
    protected $fillable = ['title', 'body', 'category_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function cats() {
        return $this->belongsToMany(Cat::class)->withTimestamps();
    }
}
