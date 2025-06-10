<x-guest-layout>
    <style>
        :root {
            --primary: #009CA9;
            --secondary: #EAFBFD;
            --accent: #D24F6B;
            --dark: #1C2A36;
            --font-sans: 'Poppins', ui-sans-serif, system-ui, sans-serif;
        }

        body {
            font-family: var(--font-sans);
            background: url('/images/fondopagina.jpeg') no-repeat center center;
            background-size: cover;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }

        form {
            background-color: white;
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 1100px;
            margin: 2rem auto;
            z-index: 2;
        }

        .register-columns {
            display: flex;
            gap: 2rem;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
        }

        .register-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        @media (max-width: 900px) {
            .register-columns {
                flex-direction: column;
                gap: 1.5rem;
            }
        }

        h2 {
            font-size: 2rem;
            color: var(--primary);
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
        }

        label {
            font-weight: 500;
            display: block;
            margin-bottom: 0.3rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            background: #fff;
        }

        input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px var(--secondary);
        }

        .text-error {
            color: var(--accent);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .form-footer {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .form-footer a {
            font-size: 0.9rem;
            color: var(--primary);
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .primary-button {
            background-color: var(--primary);
            color: #fff;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease-in-out;
        }

        .primary-button:hover {
            background-color: #007e8c;
        }
    </style>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        <h2>Registro</h2>
        @csrf
        <div class="register-columns">
            <!-- Primera columna -->
            <div class="register-column">
                <div>
                    <x-input-label for="nombre" :value="__('Nombres')" />
                    <x-text-input id="nombre" type="text" name="nombre" :value="old('nombre')" required autofocus />
                    <x-input-error :messages="$errors->get('nombre')" class="text-error" />
                </div>
                <div>
                    <x-input-label for="apellido" :value="__('Apellidos')" />
                    <x-text-input id="apellido" type="text" name="apellido" :value="old('apellido')" required />
                    <x-input-error :messages="$errors->get('apellido')" class="text-error" />
                </div>
                <div>
                    <x-input-label for="cif" :value="__('CIF')" />
                    <x-text-input id="cif" type="text" name="cif" :value="old('cif')" required />
                    <x-input-error :messages="$errors->get('cif')" class="text-error" />
                </div>
                <div>
                    <x-input-label for="email" :value="__('Correo electrónico')" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="text-error" />
                </div>
            </div>

            <!-- Segunda columna -->
            <div class="register-column">
                <div>
                    <x-input-label for="foto" :value="__('Foto (opcional)')" />
                    <input type="file" name="foto" accept="image/*" />
                    <x-input-error :messages="$errors->get('foto')" class="text-error" />
                </div>
                <div>
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="text-error" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="text-error" />
                </div>
                <div class="form-footer">
                    <a href="{{ route('login') }}">{{ __('¿Ya tienes una cuenta?') }}</a>
                    <button type="submit" class="primary-button">{{ __('Registrarse') }}</button>
                </div>
            </div>
        </div>
    </form>
</x-guest-layout>
