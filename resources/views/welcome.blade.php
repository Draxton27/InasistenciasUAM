<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Justificaciones</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
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
            color: var(--dark);
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            width: 100%;
            max-width: 900px;
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            border-radius: 10px;
        }
        .logo {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }
        nav a {
            margin-left: 1rem;
            padding: 0.5rem 1.2rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            color: var(--dark);
            background: var(--secondary);
            border: 1px solid var(--primary);
            transition: background .2s, color .2s;
        }
        nav a:hover {
            background: var(--primary);
            color: #fff;
        }
        .container {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px #48506522;
            max-width: 900px;
            width: 100%;
            margin: 2rem 0;
            padding: 2.5rem 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }
        .intro {
            flex: 1 1 100%;
        }
        .intro h1 {
            color: var(--primary);
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        .intro p {
            color: var(--dark);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }
        .features {
            margin-top: 1rem;
            list-style: none;
            padding: 0;
        }
        .features li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        .features .icon {
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        @media (max-width: 800px) {
            .container {
                flex-direction: column;
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Formación con Excelencia Académica y Deportiva</div>
        @if (Route::has('login'))
            <nav>
                @auth
                    <a href="{{ url('/redirect') }}">Panel</a>
                @else
                    <a href="{{ route('login') }}">Iniciar sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Registrarse</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>
    <main class="container">
        <section class="intro">
            <h1>Justificaciones</h1>
            <p>
                Administra tus inasistencias de forma organizada, ya seas estudiante, profesor o administrador. Accede fácilmente, carga documentos y mantente al día.
            </p>
            <ul class="features">
                <li>
                    <span class="icon">📄</span>
                    Envía justificaciones con respaldo de constancia.
                </li>
                <li>
                    <span class="icon">🧑</span>
                    Visualiza y gestiona las solicitudes según tu rol.
                </li>
                <li>
                    <span class="icon">📊</span>
                    Consulta el historial y estado de tus inasistencias.
                </li>
            </ul>
        </section>
    </main>
</body>
</html>
