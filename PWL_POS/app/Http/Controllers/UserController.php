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
        $user = UserModel::create([
            'username' => 'manager11',
            'nama' => 'Manager11',
            'password' => Hash::make('12345'),
            'level_id' => 2,
        ]);
        $user->username = 'manager12';

        $user->save();

        $user->wasChanged(); //true
        $user->wasChanged('username'); //true
        $user->wasChanged(['username', 'level_id']); //true
        $user->wasChanged('nama'); //true
        // $user->wasChanged(['nama', 'username']); //true
        dd($user->wasChanged(['nama', 'username']));
            // [
            //     'username' => 'manager33',
            //     'nama' => 'Manager Tiga Tiga',
            //     'password' => hash::make('12345'),
            //     'level_id' => 2
            // ]);

            // $user->username = 'manager56';

            // $user->isDirty(); //true
            // $user->isDirty('username'); //true
            // $user->isDirty('nama'); //false
            // $user->isDirty(['nama', 'username']); //true

            // $user->isClean(); //true
            // $user->isClean('username'); //true
            // $user->isClean('nama'); //false
            // $user->isClean(['nama', 'username']); //true

            //  $user->save();

            //  $user->isDirty(); //false
            //  $user->isClean(); //true
            //  dd($user->isDirty());
    }
}
