<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function generateReadablePassword(): string {
        $words = ['apple', 'smile', 'grape', 'flame', 'plane', 'chair', 'stone', 'light', 'drama', 'clock']; // Add more 5-letter words
        
        $letter = chr(rand(97, 122)); // a–z
        $number = rand(10, 99);
        $word   = $words[array_rand($words)];

        return $letter . $number . $word;
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:80',
            'surname' => 'required|string|max:40',
        ]);

        $plainPassword = $this->generateReadablePassword();
        $email = strtolower($request->name . '.' . $request->surname . '@eklase.lv');

        $user = User::create([
            'name' => Str::ucfirst(strtolower($request->name)),
            'surname' => Str::ucfirst(strtolower($request->surname)),
            'email' => $email,
            'password' => Hash::make($plainPassword),
            'role' => 'teacher',
        ]);

        return redirect()->route('admin.index')->with('generated_password', $plainPassword)->with('email', $email);;
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
