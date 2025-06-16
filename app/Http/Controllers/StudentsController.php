<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\User;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Code to list all students
        $students = Students::all();
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Code to show form to create a new student
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Code to store a new user in users table
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|email|unique:users,email',
            // Add other fields as necessary
        ]);

        \App\Models\User::create([
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'password' => Hash::make($validatedData['password']),
            'email' => $validatedData['email'],
            'role' => 'student',
        ]);

        return redirect()->route('dashboard')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Students $students)
    {
        // Code to display a single student
        return view('students.show', compact('students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Students $students)
    {
        // Code to show form to edit a student
        return view('students.edit', compact('students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Students $students)
    {
        // Code to update a student
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $students->id,
            // Add other fields as necessary
        ]);

        $students->update($validatedData);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Students $students)
    {
        // Code to delete a student
        $students->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}

