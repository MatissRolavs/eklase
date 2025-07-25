<?php

namespace App\Http\Controllers;

use App\Models\Grades;
use App\Models\User;
use App\Models\Subjects;
use Illuminate\Http\Request;

class GradesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grades::all();
        return view('grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::where('role', 'student')->get();

        $subjects = Subjects::all();
        return view('grades.create', compact('students', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|integer|between:1,100',
        ]);

        $grades = new Grades([
            'student_id' => $request->get('student_id'),
            'subject_id' => $request->get('subject_id'),
            'grade' => $request->get('grade'),
        ]);
        $grades->save();

        return redirect()->route('grades.index')->with('success', 'Grade created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grades $grades)
    {
        return view('grades.show', compact('grades'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grades $grades)
    {
        return view('grades.edit', compact('grades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grades $grades)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|integer|between:1,100',
        ]);

        $grades->update($validatedData);

        return redirect()->route('grades.index')->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grades $grades)
    {
        $grades->delete();

        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully.');
    }
}

