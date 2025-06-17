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
        @media (max-width: 768px) {
    body {
        padding: 0.5rem;
    }

    header {
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem;
        margin-top: 1rem;
        max-width: 100%;
    }

    .logo {
        font-size: 1rem;
        font-weight: 600;
        text-align: left;
        line-height: 1.3;
    }

    nav {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 0.5rem;
    }

    nav a {
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
        border-radius: 6px;
        border: 1px solid var(--primary);
        background-color: var(--secondary);
        color: var(--dark);
        text-decoration: none;
        flex: 1 0 auto;
    }

    nav a:hover {
        background-color: var(--primary);
        color: #fff;
    }

    .container {
        margin: 1.5rem 0;
        padding: 1.25rem;
        border-radius: 14px;
        gap: 1rem;
    }

    .intro h1 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .intro p {
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .features li {
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .features .icon {
        width: 26px;
        height: 26px;
        font-size: 1rem;
        margin-right: 0.75rem;
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
    <footer style="width:100%;max-width:900px;margin:0 auto 2rem auto;padding:2rem 1.5rem;background:rgba(255,255,255,0.97);border-radius:18px;text-align:center;font-size:1.08rem;color:var(--dark);box-shadow:0 6px 24px #48506533;display:flex;flex-direction:row;justify-content:space-between;align-items:center;gap:1.5rem;flex-wrap:wrap;">
        <div style="display:flex;flex-direction:column;align-items:flex-start;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.7rem;font-weight:700;color:var(--primary);font-size:1.18rem;letter-spacing:0.5px;">
                <svg width="22" height="22" fill="#009CA9" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-3.31 3.58-6 8-6s8 2.69 8 6" /></svg>
                Contacto
            </div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <svg width="18" height="18" fill="#D24F6B" viewBox="0 0 24 24"><path d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.07 21 3 13.93 3 5a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.46.57 3.58a1 1 0 0 1-.24 1.01l-2.2 2.2Z"/></svg>
                PBX: <a href="tel:+50522783800" style="color:var(--accent);text-decoration:none;font-weight:500;">+(505) 2278-3800</a>
            </div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <svg width="18" height="18" fill="#009CA9" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"/></svg>
                Costado Noroeste Camino de Oriente.
            </div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <svg width="18" height="18" fill="#009CA9" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6" fill="#fff"/><circle cx="12" cy="12" r="4" fill="#009CA9"/></svg>
                Managua, Nicaragua.
            </div>
        </div>
</body>
</html>
