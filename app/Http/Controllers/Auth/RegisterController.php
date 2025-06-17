<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Page; // Diperlukan untuk layout
use App\Models\Category; // Diperlukan untuk layout

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home'; // Atau ke '/' jika Anda ingin ke homepage

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Hapus baris ini jika ada: $this->middleware('guest');
        // Atau jika ada yang lain seperti $this->middleware('auth');
        // Register dan Login seharusnya tidak punya middleware 'auth' di construct-nya
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // Role default 'pembeli'
            'role' => 'pembeli',
        ]);
    }

    // Metode untuk form register penjual
    public function showPenjualForm()
    {
        $menu = Page::where(['is_group'=>0,'is_active'=>1])->get();
        $submenu = Page::where(['is_group'=>1,'is_active'=>1])->get();
        $categories = Category::all();
        $appname = config('app.name');
        return view('auth.register-penjual', compact('menu', 'submenu', 'categories', 'appname'));
    }

    public function registerPenjual(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'penjual',
        ]);
        // Login user setelah registrasi (opsional)
        auth()->login($user);
        return redirect($this->redirectTo);
    }

    // Metode untuk form register pembeli
    public function showPembeliForm()
    {
        $menu = Page::where(['is_group'=>0,'is_active'=>1])->get();
        $submenu = Page::where(['is_group'=>1,'is_active'=>1])->get();
        $categories = Category::all();
        $appname = config('app.name');
        return view('auth.register-pembeli', compact('menu', 'submenu', 'categories', 'appname'));
    }

    public function registerPembeli(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pembeli',
        ]);
        // Login user setelah registrasi (opsional)
        auth()->login($user);
        return redirect($this->redirectTo);
    }
}