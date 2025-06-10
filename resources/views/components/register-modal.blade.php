<!-- resources/views/components/auth/register-modal.blade.php -->
<div x-show="showRegister" x-transition @click.away="showRegister = false" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-2xl p-6 rounded shadow-lg" @click.stop>
        <h2 class="text-2xl font-bold mb-4">Registrarse</h2>
        <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input name="name" required placeholder="Nombre de usuario" class="input" />
            <input type="email" name="email" required placeholder="Email" class="input" />
            <input type="password" name="password" required placeholder="Contraseña" class="input" />
            <input type="password" name="password_confirmation" required placeholder="Confirmar contraseña"
                class="input" />
            <input name="cuit" placeholder="Cuit" class="input" />
            <input name="direccion" placeholder="Dirección" class="input" />
            <input name="telefono" placeholder="Teléfono" class="input" />

            <div class="col-span-1 md:col-span-2">
                <select name="provincia" required class="input">
                    <option value="" disabled selected>Selecciona una provincia</option>
                    @foreach($provincias as $provincia)
                        <option value="{{ $provincia['name'] }}">{{ $provincia['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-1 md:col-span-2">
                {{-- Aquí deberías usar JS para cargar dinámicamente las localidades según provincia seleccionada --}}
                <select name="localidad" required class="input">
                    <option value="" disabled selected>Selecciona una localidad</option>
                </select>
            </div>

            <button type="submit"
                class="bg-orange-500 text-white py-2 mt-2 col-span-1 md:col-span-2">Registrarse</button>
        </form>

        <p class="mt-4 text-center text-sm">
            ¿Ya tienes cuenta? <button class="text-orange-500 underline"
                @click="showRegister = false; showLogin = true">Iniciar sesión</button>
        </p>
    </div>
</div>