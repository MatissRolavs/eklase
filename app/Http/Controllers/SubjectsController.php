<?php

namespace App\Http\Controllers;

use App\Models\Subjects;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subjects::all();
        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:subjects|max:255',
        ]);

        $subject = new Subjects([
            'name' => $request->get('name'),
        ]);
        $subject->save();
        return redirect()->route('subjects.index')->with('success', 'Subject saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subjects $subject)
    {
        $students = User::where('role', 'student')->get();
        return view('subjects.show', compact('subject', 'students'));
    }
     

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subjects $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subjects $subject)
    {
        $request->validate([
            'name' => 'required|unique:subjects|max:255',
        ]);

        $subject->update($request->all());
        return redirect()->route('subjects.index')->with('success', 'Subject updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subjects $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted!');
    }
}

