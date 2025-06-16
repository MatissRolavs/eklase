<form action="{{ route('grades.store') }}" method="post">
    @csrf

    <!-- Student Search -->
    <div class="mb-3">
        <label for="student_id" class="form-label">Student</label>
        <select class="form-control @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
            @foreach(\App\Models\Students::all() as $student)
                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                    {{ $student->name }} {{ $student->surname }}
                </option>
            @endforeach
        </select>
        @if ($errors->has('student_id'))
            <div class="invalid-feedback">{{ $errors->first('student_id') }}</div>
        @endif
    </div>

    <!-- Grade Input -->
    <div class="mb-3">
        <label for="grade" class="form-label">Grade</label>
        <input type="number" class="form-control @error('grade') is-invalid @enderror" id="grade" name="grade" min="1" max="100" value="{{ old('grade') }}" required>
        @if ($errors->has('grade'))
            <div class="invalid-feedback">{{ $errors->first('grade') }}</div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Add Grade</button>
</form>

