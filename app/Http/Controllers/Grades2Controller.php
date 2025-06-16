<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGrades2Request;
use App\Http\Requests\UpdateGrades2Request;
use App\Models\Subjects;
use App\Models\User;
use App\Models\Grades2;

class Grades2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreGrades2Request $request)
    {
        $grade = new Grades2([
            'student_id' => $request->get('student_id'),
            'subject_id' => $request->get('subject_id'),
            'grade' => $request->get('grade'),
        ]);
        $grade->save();

        return redirect()->route('dashboard')->with('success', 'Grade saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grades2 $grades2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grades2 $grades2)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGrades2Request $request, Grades2 $grades2)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grades2 $grades2)
    {
        //
    }
}
