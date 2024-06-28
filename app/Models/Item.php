<?php

namespace App\Models;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['todo_id', 'name', 'completed'];

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }
}
