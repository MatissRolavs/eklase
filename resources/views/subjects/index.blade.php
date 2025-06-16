<!-- resources/views/subjects/index.blade.php -->

<div class="container">
    <h1 class="mb-4">Subjects</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
               
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $subject)
                <tr>
                    <td>{{ $subject->id }}</td>
                    <td><a href="{{ route('subjects.show', $subject->id) }}">{{ $subject->name }}</a></td>
                 
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


