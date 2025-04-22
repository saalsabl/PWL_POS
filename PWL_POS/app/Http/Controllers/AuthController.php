<?php
namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if(Auth::check()){ // jika sudah login, maka redirect ke halaman home 
            return redirect('/');
        }
        return view('auth.login');
}

public function postlogin(Request $request)
{
    if($request->ajax() || $request->wantsJson()){
        $credentials = $request->only('username', 'password');
    
        if (Auth::attempt($credentials)) { 
            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil', 
                'redirect' => url('/')
            ]);
        }
        return response()->json([ 
            'status' => false, 
            'message' => 'Login Gagal'
        ]);
    }

    return redirect('login');
}

public function register(){
    $level = LevelModel::select('level_id', 'level_nama')->get();
    return view('auth.register')
    ->with('level', $level);
}

// public function register_proses(Request $request)
// {
//     try {
//         $request->validate([
//             'username' => 'required|min:4|max:20|unique:m_user,username',
//             'nama' => 'required|min:4|max:20',
//             'password' => 'required|min:6|max:20',
//             'level_id' => 'required|integer',
//         ]);

//         UserModel::create([
//             'username' => $request->username,
//             'nama' => $request->nama,
//             'password' => bcrypt($request->password),
//             'level_id' => $request->level_id
//         ]);

//         return response()->json([
//             'status' => true,
//             'message' => 'Registrasi berhasil!',
//             'redirect' => route('login'),
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Gagal registrasi: ' . $e->getMessage(),
//             'msgField' => [],
//         ]);
//     }
// }
public function store(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id'  => 'required|integer',
                'username'  => 'required|string|min:3|unique:m_user,username',
                'nama'      => 'required|string|max:100',
                'password'  => 'required|min:6'
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'    => false, // response status, false: error/gagal, true: berhasil
                    'message'   => 'Validasi Gagal',
                    'msgField'  => $validator->errors(), // pesan error validasi
                ]);
            }
            UserModel::create($request->all());
            return response()->json([
                'status'    => true,
                'message'   => 'Data user berhasil disimpan',
                'redirect' => url('login')
            ]);
        }
        return redirect('login');
    }

public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken(); 
    return redirect('login');
    }
}