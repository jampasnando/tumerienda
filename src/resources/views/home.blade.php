<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TuMerienda – Práctica & Perfecta</title>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --teal:   #0097B2;
    --orange: #F5A623;
    --pink:   #E91E8C;
    --cream:  #FFF8F0;
    --dark:   #1A1A2E;
    --light-gray: #f0f4f8;
  }

  html { scroll-behavior: smooth; }

  body {
    font-family: 'Inter', sans-serif;
    background: var(--cream);
    color: var(--dark);
    overflow-x: hidden;
  }

  /* ── NAV ── */
  nav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 40px;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(12px);
    box-shadow: 0 2px 20px rgba(0,0,0,0.08);
  }

  .nav-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
  }

  .nav-logo img {
    height: 52px;
    width: auto;
  }

  .nav-links {
    display: flex;
    gap: 32px;
    list-style: none;
  }

  .nav-links a {
    text-decoration: none;
    font-family: 'Nunito', sans-serif;
    font-weight: 700;
    font-size: 15px;
    color: var(--dark);
    transition: color .2s;
  }

  .nav-links a:hover { color: var(--teal); }

  .nav-cta {
    background: var(--pink);
    color: #fff !important;
    padding: 10px 24px;
    border-radius: 50px;
    transition: background .2s, transform .15s !important;
  }

  .nav-cta:hover {
    background: #c7166f !important;
    transform: translateY(-1px);
    color: #fff !important;
  }

  /* ── HERO SLIDESHOW ── */
  .hero {
    position: relative;
    width: 100%;
    height: 100vh;
    min-height: 560px;
    overflow: hidden;
  }

  .slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 1.4s ease-in-out;
    will-change: opacity;
  }

  .slide.active { opacity: 1; }

  .slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
  }

  /* dark gradient overlay */
  .hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
      to bottom,
      rgba(26,26,46,0.25) 0%,
      rgba(26,26,46,0.55) 60%,
      rgba(26,26,46,0.75) 100%
    );
    z-index: 2;
    pointer-events: none;
  }

  .hero-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 3;
    padding: 60px 60px 80px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--orange);
    color: #fff;
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 6px 18px;
    border-radius: 50px;
  }

  .hero-title {
    font-family: 'Nunito', sans-serif;
    font-weight: 900;
    font-size: clamp(38px, 6vw, 80px);
    color: #fff;
    line-height: 1.08;
    max-width: 700px;
  }

  .hero-title span { color: var(--orange); }

  .hero-subtitle {
    font-size: clamp(15px, 1.8vw, 19px);
    color: rgba(255,255,255,0.88);
    max-width: 520px;
    line-height: 1.6;
    font-weight: 500;
  }

  .hero-actions {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
  }

  .btn-primary {
    background: var(--pink);
    color: #fff;
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 16px;
    padding: 14px 36px;
    border-radius: 50px;
    text-decoration: none;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 6px 20px rgba(233,30,140,0.4);
  }

  .btn-primary:hover {
    background: #c7166f;
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(233,30,140,0.5);
  }

  .btn-secondary {
    border: 2px solid rgba(255,255,255,0.7);
    color: #fff;
    font-family: 'Nunito', sans-serif;
    font-weight: 700;
    font-size: 16px;
    padding: 12px 32px;
    border-radius: 50px;
    text-decoration: none;
    transition: background .2s, border-color .2s;
    backdrop-filter: blur(6px);
    background: rgba(255,255,255,0.1);
  }

  .btn-secondary:hover {
    background: rgba(255,255,255,0.25);
    border-color: #fff;
  }

  /* slide dots */
  .hero-dots {
    position: absolute;
    bottom: 32px;
    right: 60px;
    z-index: 4;
    display: flex;
    gap: 8px;
  }

  .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    cursor: pointer;
    border: none;
    transition: background .3s, transform .3s;
    padding: 0;
  }

  .dot.active {
    background: var(--orange);
    transform: scale(1.3);
  }

  /* ── FEATURES ── */
  .features {
    padding: 100px 60px;
    background: var(--cream);
  }

  .section-eyebrow {
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--teal);
    margin-bottom: 12px;
  }

  .section-title {
    font-family: 'Nunito', sans-serif;
    font-weight: 900;
    font-size: clamp(28px, 4vw, 46px);
    color: var(--dark);
    line-height: 1.15;
    max-width: 580px;
    margin-bottom: 64px;
  }

  .section-title em {
    font-style: normal;
    color: var(--pink);
  }

  .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 28px;
  }

  .feature-card {
    background: #fff;
    border-radius: 20px;
    padding: 36px 32px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    transition: transform .25s, box-shadow .25s;
  }

  .feature-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.1);
  }

  .feature-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
  }

  .icon-teal   { background: rgba(0,151,178,.12); }
  .icon-orange { background: rgba(245,166,35,.14); }
  .icon-pink   { background: rgba(233,30,140,.12); }
  .icon-green  { background: rgba(76,175,80,.12); }

  .feature-title {
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 19px;
    color: var(--dark);
  }

  .feature-desc {
    font-size: 15px;
    color: #6b7280;
    line-height: 1.65;
  }

  /* ── SPLIT SECTION ── */
  .split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 540px;
  }

  .split-image {
    overflow: hidden;
    position: relative;
  }

  .split-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    transition: transform .5s;
  }

  .split-image:hover img { transform: scale(1.04); }

  .split-text {
    background: var(--dark);
    padding: 80px 70px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 24px;
  }

  .split-text .section-eyebrow { color: var(--orange); }

  .split-text .section-title {
    color: #fff;
    margin-bottom: 0;
  }

  .split-text p {
    color: rgba(255,255,255,0.72);
    font-size: 16px;
    line-height: 1.7;
    max-width: 440px;
  }

  /* ── MENU PREVIEW ── */
  .menu-preview {
    padding: 100px 60px;
    background: var(--light-gray);
  }

  .menu-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 48px;
    flex-wrap: wrap;
    gap: 20px;
  }

  .menu-header .section-title { margin-bottom: 0; }

  .menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
  }

  .menu-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    transition: transform .25s, box-shadow .25s;
    cursor: default;
  }

  .menu-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.11);
  }

  .menu-card-img {
    height: 150px;
    overflow: hidden;
  }

  .menu-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s;
  }

  .menu-card:hover .menu-card-img img { transform: scale(1.08); }

  .menu-card-body {
    padding: 18px 20px;
  }

  .menu-tag {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 50px;
    margin-bottom: 8px;
  }

  .tag-teal   { background: rgba(0,151,178,.12); color: var(--teal); }
  .tag-orange { background: rgba(245,166,35,.14); color: #c47d0a; }
  .tag-pink   { background: rgba(233,30,140,.12); color: var(--pink); }

  .menu-card-name {
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 16px;
    color: var(--dark);
    margin-bottom: 6px;
  }

  .menu-card-desc {
    font-size: 13px;
    color: #9ca3af;
    line-height: 1.5;
  }

  /* ── CTA BANNER ── */
  .cta-banner {
    background: linear-gradient(135deg, var(--teal) 0%, #005f75 100%);
    padding: 90px 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 28px;
    position: relative;
    overflow: hidden;
  }

  .cta-banner::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 320px; height: 320px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    pointer-events: none;
  }

  .cta-banner::after {
    content: '';
    position: absolute;
    bottom: -100px; left: -60px;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    pointer-events: none;
  }

  .cta-banner h2 {
    font-family: 'Nunito', sans-serif;
    font-weight: 900;
    font-size: clamp(28px, 4.5vw, 50px);
    color: #fff;
    line-height: 1.15;
    max-width: 620px;
    position: relative;
    z-index: 1;
  }

  .cta-banner h2 span { color: var(--orange); }

  .cta-banner p {
    color: rgba(255,255,255,0.8);
    font-size: 17px;
    max-width: 480px;
    line-height: 1.65;
    position: relative;
    z-index: 1;
  }

  .cta-btn {
    background: var(--orange);
    color: #fff;
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 17px;
    padding: 16px 48px;
    border-radius: 50px;
    text-decoration: none;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 8px 24px rgba(245,166,35,0.45);
    position: relative;
    z-index: 1;
  }

  .cta-btn:hover {
    background: #d4880d;
    transform: translateY(-2px);
    box-shadow: 0 14px 32px rgba(245,166,35,0.55);
  }

  /* ── FOOTER ── */
  footer {
    background: var(--dark);
    padding: 56px 60px 32px;
    color: rgba(255,255,255,0.55);
  }

  .footer-inner {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 48px;
    padding-bottom: 40px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }

  .footer-brand img {
    height: 58px;
    margin-bottom: 18px;
  }

  .footer-brand p {
    font-size: 14px;
    line-height: 1.7;
    max-width: 280px;
  }

  .footer-col h4 {
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 15px;
    color: #fff;
    margin-bottom: 18px;
  }

  .footer-col ul {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .footer-col a {
    color: rgba(255,255,255,0.55);
    text-decoration: none;
    font-size: 14px;
    transition: color .2s;
  }

  .footer-col a:hover { color: var(--orange); }

  .footer-bottom {
    padding-top: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 13px;
  }

  .footer-bottom span { color: var(--orange); }

  /* ── RESPONSIVE ── */
  @media (max-width: 900px) {
    nav { padding: 12px 24px; }
    .nav-links { display: none; }
    .hero-content { padding: 40px 28px 60px; }
    .hero-dots { right: 28px; bottom: 24px; }
    .features, .menu-preview, .cta-banner { padding: 72px 28px; }
    .split { grid-template-columns: 1fr; }
    .split-image { height: 320px; }
    .split-text { padding: 52px 32px; }
    .footer-inner { grid-template-columns: 1fr; gap: 32px; }
    footer { padding: 48px 28px 24px; }
    .footer-bottom { flex-direction: column; align-items: flex-start; }
  }
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="#" class="nav-logo">
    <img src="{{ asset('storage/paginaweb/logo.png') }}" alt="TuMerienda logo">
  </a>
  <ul class="nav-links">
    <li><a href="#nosotros">Nosotros</a></li>
    <li><a href="#menu">Menú</a></li>
    <li><a href="#beneficios">Beneficios</a></li>
    <li><a href="#contacto" class="nav-cta">Suscribirse</a></li>
  </ul>
</nav>

<!-- HERO SLIDESHOW -->
<section class="hero" id="inicio">
  <div class="slide active" data-index="0">
    <img src="{{ asset('storage/paginaweb/slide1.jpg') }}" alt="Desayuno nutritivo">
  </div>
  <div class="slide" data-index="1">
    <img src="{{ asset('storage/paginaweb/slide2.jpg') }}" alt="Almuerzo saludable">
  </div>
  <div class="slide" data-index="2">
    <img src="{{ asset('storage/paginaweb/slide3.jpg') }}" alt="Snack delicioso">
  </div>

  <div class="hero-content">
    <span class="hero-badge">🍎 La merienda que tu hijo merece</span>
    <h1 class="hero-title">Comida rica,<br><span>sana y lista</span><br>para la escuela.</h1>
    <p class="hero-subtitle">Preparamos meriendas frescas y nutritivas cada día, diseñadas especialmente para niños. Sin esfuerzo para ti, con mucha energía para ellos.</p>
    <div class="hero-actions">
      <a href="#menu" class="btn-primary">Ver el menú</a>
      <a href="#contacto" class="btn-secondary">Suscribirse</a>
    </div>
  </div>

  <div class="hero-dots">
    <button class="dot active" data-dot="0" aria-label="Slide 1"></button>
    <button class="dot" data-dot="1" aria-label="Slide 2"></button>
    <button class="dot" data-dot="2" aria-label="Slide 3"></button>
  </div>
</section>

<!-- FEATURES -->
<section class="features" id="beneficios">
  <p class="section-eyebrow">¿Por qué TuMerienda?</p>
  <h2 class="section-title">Lo mejor para tu hijo, <em>sin complicaciones.</em></h2>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon icon-teal">🥗</div>
      <h3 class="feature-title">100% fresca y balanceada</h3>
      <p class="feature-desc">Cada merienda se prepara el mismo día con ingredientes frescos y un balance nutricional pensado para niños en etapa escolar.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon icon-orange">⚡</div>
      <h3 class="feature-title">Energía para todo el día</h3>
      <p class="feature-desc">Nuestras recetas aportan carbohidratos complejos, proteínas y vitaminas para mantener la concentración durante las clases.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon icon-pink">📦</div>
      <h3 class="feature-title">Entrega puntual en la escuela</h3>
      <p class="feature-desc">Coordinamos directamente con cada institución para que la merienda llegue lista a la hora del recreo. Cero retrasos.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon icon-green">🌿</div>
      <h3 class="feature-title">Sin conservantes ni aditivos</h3>
      <p class="feature-desc">Creemos que los niños merecen comida real. Nada de colorantes artificiales, conservantes ni azúcares añadidos innecesarios.</p>
    </div>
  </div>
</section>

<!-- SPLIT -->
<section class="split" id="nosotros">
  <div class="split-image">
    <img src="{{ asset('storage/paginaweb/fondochefsx.png') }}" alt="Chefs preparando meriendas">
  </div>
  <div class="split-text">
    <p class="section-eyebrow">Nuestro equipo</p>
    <h2 class="section-title">Chefs dedicados a los más pequeños.</h2>
    <p>Cada merienda sale de una cocina profesional donde el cuidado y la higiene son prioridad. Nuestro equipo de chefs especializados en alimentación infantil prepara cada plato con cariño y criterio nutricional.</p>
    <a href="#menu" class="btn-primary" style="width:fit-content; margin-top:8px;">Explorar recetas</a>
  </div>
</section>

<!-- MENU PREVIEW -->
<section class="menu-preview" id="menu">
  <div class="menu-header">
    <div>
      <p class="section-eyebrow">Menú de la semana</p>
      <h2 class="section-title">Variado, rico y <em>nutritivo.</em></h2>
    </div>
    <a href="#contacto" class="btn-primary">Ver menú completo</a>
  </div>

  <div class="menu-grid">
    <div class="menu-card">
      <div class="menu-card-img">
        <img src="{{ asset('storage/paginaweb/slide1.jpg') }}" alt="Desayuno">
      </div>
      <div class="menu-card-body">
        <span class="menu-tag tag-teal">Desayuno</span>
        <p class="menu-card-name">Pan integral con mantequilla</p>
        <p class="menu-card-desc">Pan de semillas con mantequilla y huevo. Acompañado de leche fresca.</p>
      </div>
    </div>
    <div class="menu-card">
      <div class="menu-card-img">
        <img src="{{ asset('storage/paginaweb/slide2.jpg') }}" alt="Almuerzo">
      </div>
      <div class="menu-card-body">
        <span class="menu-tag tag-orange">Almuerzo</span>
        <p class="menu-card-name">Sándwich de pollo a la plancha</p>
        <p class="menu-card-desc">Pechuga de pollo con lechuga, tomate y salsa de la casa.</p>
      </div>
    </div>
    <div class="menu-card">
      <div class="menu-card-img">
        <img src="{{ asset('storage/paginaweb/slide3.jpg') }}" alt="Snack">
      </div>
      <div class="menu-card-body">
        <span class="menu-tag tag-pink">Snack</span>
        <p class="menu-card-name">Sándwich BBQ especial</p>
        <p class="menu-card-desc">Jugosa pechuga con salsa BBQ artesanal y verduras frescas.</p>
      </div>
    </div>
    <div class="menu-card">
      <div class="menu-card-img">
        <img src="{{ asset('storage/paginaweb/fondox1.png') }}" alt="Variedad">
      </div>
      <div class="menu-card-body">
        <span class="menu-tag tag-teal">Variedad</span>
        <p class="menu-card-name">Opciones sin límites</p>
        <p class="menu-card-desc">Pizza, tacos, sushi y más. El menú rota cada semana para sorprender a los niños.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-banner" id="contacto">
  <h2>¿Listo para que tu hijo coma <span>mejor en la escuela?</span></h2>
  <p>Suscríbete hoy y recibe el menú de la semana, alertas de nuevas recetas y descuentos exclusivos para familias.</p>
  <a href="https://apptumerienda.sistembo.online" class="cta-btn">Suscribirse ahora →</a>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <img src="{{ asset('storage/paginaweb/logo.png') }}" alt="TuMerienda">
      <p>Meriendas escolares frescas, nutritivas y deliciosas. Porque la energía para aprender empieza con una buena comida.</p>
    </div>
    <div class="footer-col">
      <h4>Navegación</h4>
      <ul>
        <li><a href="#inicio">Inicio</a></li>
        <li><a href="#nosotros">Nosotros</a></li>
        <li><a href="#menu">Menú</a></li>
        <li><a href="#beneficios">Beneficios</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Contacto</h4>
      <ul>
        <li><a href="mailto:hola@tumerienda.com">hola@tumerienda.com</a></li>
        <li><a href="tel:+59112345678">+591 123 45678</a></li>
        <li><a href="#">Instagram</a></li>
        <li><a href="#">Facebook</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 <span>TuMerienda</span>. Todos los derechos reservados.</p>
    <p>Hecho con ❤️ para los niños de Bolivia</p>
  </div>
</footer>

<script>
  // Crossfade slideshow
  const slides = document.querySelectorAll('.slide');
  const dots   = document.querySelectorAll('.dot');
  let current  = 0;
  let timer;

  function goTo(idx) {
    slides[current].classList.remove('active');
    dots[current].classList.remove('active');
    current = idx;
    slides[current].classList.add('active');
    dots[current].classList.add('active');
  }

  function next() {
    goTo((current + 1) % slides.length);
  }

  function startTimer() {
    clearInterval(timer);
    timer = setInterval(next, 5000);
  }

  dots.forEach(dot => {
    dot.addEventListener('click', () => {
      goTo(parseInt(dot.dataset.dot));
      startTimer();
    });
  });

  startTimer();

  // Respect reduced-motion preference
  if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
    clearInterval(timer);
  }
</script>
</body>
</html>
