<div class="container">
    <h1 class="mb-4">{{ $student->name }} {{ $student->surname }}</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Name</td>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <td>Surname</td>
                <td>{{ $student->surname }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $student->email }}</td>
            </tr>
        </tbody>
    </table>
</div>
