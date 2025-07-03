<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\user_manajemen;
use App\Helpers\jurnalhelper;

class salahController extends Controller
{

    public function salahlogin()
    {
        return redirect()->route('index');
    }
    public function salahweb($salahweb)
    {
        return redirect()->route('index');
    }

}
