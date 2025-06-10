<!-- resources/views/components/auth/login-modal.blade.php -->
<div x-show="showLogin" x-transition @click.away="showLogin = false" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-md p-6 rounded shadow-lg" @click.stop>
        <h2 class="text-2xl font-bold mb-4">Iniciar sesión</h2>

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
            @csrf
            <input name="name" required placeholder="Usuario" class="input" />
            <input type="password" name="password" required placeholder="Contraseña" class="input" />

            <button type="submit" class="bg-orange-500 text-white py-2">Ingresar</button>
        </form>

        <p class="mt-4 text-center text-sm">
            ¿No tienes cuenta? <button class="text-orange-500 underline"
                @click="showLogin = false; showRegister = true">Registrarse</button>
        </p>
    </div>
</div>