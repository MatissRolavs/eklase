<form action="{{ route('grades.store') }}" method="POST">
    @csrf
    <label for="student_id">Student:</label>
    <select name="student_id">
        @foreach($students as $student)
            <option value="{{ $student->id }}">{{ $student->name }} {{ $student->surname }}</option>
        @endforeach
    </select>

    <label for="subject_id">Subject:</label>
    <select name="subject_id">
        @foreach($subjects as $subject)
            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
        @endforeach
    </select>

    <label for="grade">Grade:</label>
    <input type="number" name="grade" min="1" max="100" required>

    <button type="submit">Submit Grade</button>
</form>
