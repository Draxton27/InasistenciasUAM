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
        }

        .login-form {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.0); /* totalmente transparente */
            border-radius: 0;
            box-shadow: none;
        }

        .login-form h2 {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-weight: 700;
            text-align: center;
        }

        .login-form label {
            color: var(--dark);
            font-weight: 500;
        }

        .login-form input[type="email"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 0.3rem;
            font-size: 1rem;
            background-color: #fff;
        }

        .login-form input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px var(--secondary);
        }

        .remember-me {
            margin-top: 1rem;
            display: flex;
            align-items: center;
        }

        .remember-me label {
            margin-left: 0.5rem;
            font-size: 0.95rem;
        }

        .actions {
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .actions a {
            font-size: 0.9rem;
            color: var(--primary);
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .login-btn {
            background-color: var(--primary);
            color: #fff;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease-in-out;
        }

        .login-btn:hover {
            background-color: #007e8c;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .text-error {
            color: var(--accent);
            font-size: 0.9rem;
        }
    </style>

    <div class="login-form">
        <h2>Iniciar sesión</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Correo electrónico')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="text-error mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="text-error mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">{{ __('Recuérdame') }}</label>
            </div>
            <div class="actions flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 gap-4">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline transition-all duration-150">
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif

                <button type="submit" class="login-btn bg-primary text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-cyan-700 transition-all duration-150">
                    {{ __('Entrar') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
