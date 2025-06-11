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
    <footer style="width:100%;max-width:900px;margin:0 auto 2rem auto;padding:2rem 1.5rem;background:rgba(255,255,255,0.97);border-radius:18px;text-align:center;font-size:1.08rem;color:var(--dark);box-shadow:0 6px 24px #48506533;display:flex;flex-direction:row;justify-content:space-between;align-items:center;gap:1.5rem;flex-wrap:wrap;">
        <div style="display:flex;flex-direction:column;align-items:flex-start;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.7rem;font-weight:700;color:var(--primary);font-size:1.18rem;letter-spacing:0.5px;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="#009CA9" stroke-width="2"/><path d="M4 20c0-3.31 3.58-6 8-6s8 2.69 8 6" stroke="#009CA9" stroke-width="2"/></svg>
                Contacto
            </div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.07 21 3 13.93 3 5a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.46.57 3.58a1 1 0 0 1-.24 1.01l-2.2 2.2Z" stroke="#D24F6B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                PBX: <a href="tel:+50522783800" style="color:var(--accent);text-decoration:none;font-weight:500;">+(505) 2278-3800</a>
            </div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z" stroke="#009CA9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Costado Noroeste Camino de Oriente.
            </div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2Zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8Zm0-14a6 6 0 1 0 6 6 6.006 6.006 0 0 0-6-6Zm0 10a4 4 0 1 1 4-4 4.004 4.004 0 0 1-4 4Z" fill="#009CA9"/></svg>
                Managua, Nicaragua.
            </div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:0.7rem;">
            <a href="https://www.instagram.com/uam.nicaragua/" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:0.5rem;color:#e1306c;text-decoration:none;font-weight:600;font-size:1.18rem;transition:color .2s;">
                <svg width="26" height="26" viewBox="0 0 448 512" style="vertical-align:middle;">
                    <defs>
                        <linearGradient id="ig-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#405de6"/>
                            <stop offset="20%" stop-color="#5851db"/>
                            <stop offset="40%" stop-color="#833ab4"/>
                            <stop offset="60%" stop-color="#c13584"/>
                            <stop offset="80%" stop-color="#e1306c"/>
                            <stop offset="90%" stop-color="#fd1d1d"/>
                            <stop offset="95%" stop-color="#f56040"/>
                            <stop offset="98%" stop-color="#f77737"/>
                            <stop offset="99%" stop-color="#fcaf45"/>
                            <stop offset="100%" stop-color="#ffdc80"/>
                        </linearGradient>
                    </defs>
                    <path fill="url(#ig-gradient)" d="M224 202.66A53.34 53.34 0 1 0 277.34 256 53.38 53.38 0 0 0 224 202.66Zm124.71-41a54 54 0 0 0-30.36-30.36C293.19 120 224 120 224 120s-69.19 0-94.35 11.32a54 54 0 0 0-30.36 30.36C88 162.81 88 224 88 224s0 61.19 11.32 94.35a54 54 0 0 0 30.36 30.36C154.81 392 224 392 224 392s69.19 0 94.35-11.32a54 54 0 0 0 30.36-30.36C360 285.19 360 224 360 224s0-61.19-11.29-94.34ZM224 338a82 82 0 1 1 82-82 82 82 0 0 1-82 82Zm85.4-148.6a19.2 19.2 0 1 1-19.2-19.2 19.2 19.2 0 0 1 19.2 19.2Z"/>
                </svg>
                Instagram
            </a>
            <span style="font-size:0.98rem;color:#888;">&copy; {{ date('Y') }} Universidad Americana (UAM)</span>
        </div>
</body>
</html>
