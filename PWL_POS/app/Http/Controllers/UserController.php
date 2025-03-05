<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index() 
    {
        $user = UserModel::where('level_id', 2) -> count();
        // dd($user);
        // $user = UserModel::all();
        return view('user', ['data' => $user]);

    }
}
