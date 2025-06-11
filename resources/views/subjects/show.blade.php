    <div class="container">
        <h1 class="mb-4">{{ $subject->name }}</h1>

        <h2 class="mb-4">Students</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Grades</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

