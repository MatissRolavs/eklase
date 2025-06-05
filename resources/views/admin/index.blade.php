<x-app-layout>
    <h1 class="text-[2em] text-center">Add a new teacher:</h1>
    <form action="{{ route('admin.store') }}" method="POST" class="flex flex-col justify-center items-center">
        @csrf
        <label class="flex flex-col justify-center items-center mt-[10px]">
            Name:
            <input name="name" class="rounded-lg w-[250px]" placeHolder="Jānis">
        </label>
        <label class="flex flex-col justify-center items-center mt-[10px]">
            Surname:
            <input name="surname" class="rounded-lg w-[250px]" placeHolder="Bērziņš">
        </label>
        <button class="bg-green-500 py-[3px] px-[7px] rounded-lg mt-[10px] hover:bg-green-600 duration-[0.3s]">Create</button>
    </form>

    @if(session('generated_password'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const password = @json(session('generated_password'));
                const email = @json(session('email'));

                // Custom "alert"
                const popup = document.createElement('div');
                popup.innerHTML = `New user created! <br> Email: ${email} <br> Password: ${password}`;
                Object.assign(popup.style, {
                    position: 'fixed',
                    top: '50%',
                    left: '50%',
                    transform: 'translate(-50%, -50%)',
                    backgroundColor: '#fef3c7',
                    color: '#111827',
                    padding: '20px 30px',
                    border: '2px solid #facc15',
                    borderRadius: '10px',
                    boxShadow: '0 5px 15px rgba(0,0,0,0.3)',
                    fontSize: '16px',
                    zIndex: '9999',
                });

                document.body.appendChild(popup);

                // Remove after 30 seconds
                setTimeout(() => {
                    popup.remove();
                }, 10000);
            });
        </script>
    @endif
</x-app-layout>