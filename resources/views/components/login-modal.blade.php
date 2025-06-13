<!-- resources/views/components/auth/login-modal.blade.php -->

<div x-show="showLogin" x-transition.opacity x-cloak @click.away="showLogin = false"
    class="absolute bg-white top-14 right-0 w-full max-w-md p-6 rounded shadow-lg z-10" @click.stop>
    <h2 class="text-2xl font-bold mb-4">Iniciar sesión</h2>

    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-7 h-full">
        @csrf
        <input name="name" required placeholder="Usuario"
            class="outline outline-gray-200 py-2 px-4 focus:outline-primary-orange transition duration-300 rounded-full" />
        <input type="password" name="password" required placeholder="Contraseña"
            class="outline outline-gray-200 py-2 px-4 focus:outline-primary-orange transition duration-300 rounded-full" />
        <div class="h-[1px] bg-gray-200 w-full" />
        <button type="submit" class="bg-primary-orange w-full text-white py-2 rounded-full font-bold">Ingresar</button>
    </form>



    <button class="text-sm text-primary-orange underline mt-2 " @click="showLogin = false">
        Cerrar
    </button>

</div>