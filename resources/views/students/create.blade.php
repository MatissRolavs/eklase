<x-app-layout>
    <form action="{{ route('students.store') }}" method="post" class="max-w-lg mx-auto p-6 bg-white shadow-md rounded-md">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @if ($errors->has('name'))
                <div class="text-red-500 text-sm mt-1">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div class="mb-4">
            <label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
            <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('surname') border-red-500 @enderror" id="surname" name="surname" value="{{ old('surname') }}" required>
            @if ($errors->has('surname'))
                <div class="text-red-500 text-sm mt-1">{{ $errors->first('surname') }}</div>
            @endif
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
            <input type="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <div class="text-red-500 text-sm mt-1">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror" id="password" name="password" required>
            @if ($errors->has('password'))
                <div class="text-red-500 text-sm mt-1">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('password_confirmation') border-red-500 @enderror" id="password_confirmation" name="password_confirmation" required>
            @if ($errors->has('password_confirmation'))
                <div class="text-red-500 text-sm mt-1">{{ $errors->first('password_confirmation') }}</div>
            @endif
        </div>

        <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Create Student</button>
    </form>
</x-app-layout>

