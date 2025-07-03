<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_manajemen extends Model
{
    use HasFactory;
    protected $table = 'user_manajemen';
    protected $fillable = ['nama', 'nik', 'password', 'posisi', 'area'];
    
}
