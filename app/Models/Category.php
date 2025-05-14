<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Jika nama tabel bukan jamak dari model, tambahkan:
    // protected $table = 'categories';

    protected $fillable = ['title', 'user_id'];

    // Relasi: Satu Category memiliki banyak Todo
    public function todos()
    {
        return $this->hasMany(Todo::class);
    }
}
