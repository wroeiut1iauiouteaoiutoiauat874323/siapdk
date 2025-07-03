<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class area_user_aplikasi extends Model
{
    use HasFactory;
    protected $table = 'area_user_aplikasi';
    protected $fillable = ['nik', 'area_user'];
}
