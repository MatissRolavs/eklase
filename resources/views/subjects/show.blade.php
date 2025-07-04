<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subject') }} {{ $subject->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="mb-4 text-2xl">Students & Grades</h2>

                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Grade</th> <!-- New column for grades -->
                            </tr>
                        </thead>
                        <tbody>
                            
                            @if(Auth::check() && Auth::user()->role === 'student')
                                @foreach($students as $student)
                                    @if($student->id === Auth::user()->id)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $student->name }}</td>
                                            <td class="border px-4 py-2">
                                               @foreach ($grades as $grade)
                                                    @if ($grade->student_id === $student->id && $grade->subject_id === $subject->id)
                                                        {{ $grade->grade }}
                                                   
                                                    
                                                    @endif
                                                @endforeach 
                                               
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                @foreach($students as $student)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $student->name }}</td>
                                        <td class="border px-4 py-2">
                                        @foreach ($grades as $grade)
                                                    @if ($grade->student_id === $student->id && $grade->subject_id === $subject->id)
                                                        {{ $grade->grade }}
                                                   
                                                   
                                                    @endif
                                                @endforeach 
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
