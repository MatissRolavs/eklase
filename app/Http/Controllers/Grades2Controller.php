<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGrades2Request;
use App\Http\Requests\UpdateGrades2Request;
use App\Models\Subjects;
use App\Models\User;
use App\Models\Grades2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Grades2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grades2::with(['student', 'subject'])->get();

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
        $studentId = Auth::id();
    
        $grades = Grades2::with('subject')
            ->where('student_id', $studentId)
            ->get();

        return view('grades.show', compact('grades'));
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

    public function bulkUpdate(Request $request)
    {
        $grades = $request->input('grades', []);
        $deleteIds = $request->input('delete_ids', []);

        // Process deletions
        if (!empty($deleteIds)) {
            Grades2::whereIn('id', $deleteIds)->delete();
        }

        // Process updates (ignore ones marked for deletion)
        foreach ($grades as $id => $newGrade) {
            if (!in_array($id, $deleteIds)) {
                $grade = Grades2::find($id);
                if ($grade && $grade->grade != $newGrade) {
                    $grade->grade = $newGrade;
                    $grade->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Changes applied successfully!');
    }

}
