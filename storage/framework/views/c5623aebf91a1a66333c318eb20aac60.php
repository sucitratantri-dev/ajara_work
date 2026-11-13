<!doctype html>
<html lang="id">
<head>
 <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
 <title><?php echo $__env->yieldContent('title','AJARA'); ?> Â· Ajang dan Jejaring Prestasi Mahasiswa</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <script>tailwind.config={theme:{extend:{colors:{ajara:{gold:'#D8B66A',red:'#8D2638',teal:'#075B55',cream:'#F5E7B0',ink:'#173F3B'}},fontFamily:{sans:['Inter','ui-sans-serif','system-ui']},boxShadow:{soft:'0 16px 45px rgba(32,60,67,.09)'}}}}</script>
 <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
 <style>
  :root{--gold:#D8B66A;--red:#8D2638;--teal:#075B55;--cream:#F5E7B0}*{scroll-behavior:smooth}body{font-family:'DM Sans',sans-serif;color:#203b38;background:#F5F7F5}h1,h2,h3,.brand{font-family:'Plus Jakarta Sans',sans-serif}.container-page{width:min(1180px,calc(100% - 40px));margin-inline:auto}.nav-link{color:#53666a;font-weight:600;font-size:14px;transition:.2s}.nav-link:hover,.nav-link.active{color:var(--red)}.btn-primary{background:var(--red);color:#fff;transition:.2s}.btn-primary:hover{background:#812122;transform:translateY(-1px)}.btn-gold{background:var(--gold);color:#2f3b3b;transition:.2s}.btn-gold:hover{filter:brightness(.96);transform:translateY(-1px)}.soft-shadow{box-shadow:0 16px 45px rgba(32,60,67,.09)}.competition-card{transition:transform .25s,box-shadow .25s}.competition-card:hover{transform:translateY(-5px);box-shadow:0 20px 45px rgba(32,60,67,.13)}.poster-img{width:100%;height:190px;object-fit:cover}.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}.mobile-menu{display:none}@media(max-width:800px){.desktop-nav{display:none}.mobile-menu{display:block}.hero-grid{grid-template-columns:1fr}.hero-art{display:none}}
 </style>
 <?php echo $__env->yieldPushContent('styles'); ?>
<!-- AJARA_GLOBAL_UI_V1 -->
<style>
:root {
    --ajara-teal: #075e56;
    --ajara-maroon: #98283b;
    --ajara-gold: #d9b768;
    --ajara-white: #f7f8f6;
}

html {
    scroll-behavior: smooth;
}

body {
    background-color: var(--ajara-white);
    color: #20343a;
    overflow-x: hidden;
}

button, a, input, select, textarea {
    transition: all .25s ease;
}

button:hover, a:hover {
    transition: all .25s ease;
}

button:focus-visible, a:focus-visible,
input:focus-visible, select:focus-visible {
    outline: 3px solid rgba(217,183,104,.55);
    outline-offset: 3px;
}

.ajara-animated {
    animation: ajaraFadeUp .65s ease both;
}

@keyframes ajaraFadeUp {
    from {
        opacity: 0;
        transform: translateY(18px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes ajaraPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.025); }
}

.ajara-card {
    transition: transform .3s ease, box-shadow .3s ease;
}

.ajara-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(7,94,86,.10);
}

.ajara-toast {
    position: fixed;
    top: 90px;
    right: 22px;
    z-index: 9999;
    max-width: min(380px, calc(100vw - 32px));
    padding: 16px 20px;
    border-radius: 14px;
    background: white;
    color: #20343a;
    box-shadow: 0 12px 35px rgba(0,0,0,.13);
    border-left: 5px solid var(--ajara-teal);
    animation: ajaraFadeUp .4s ease both;
}

.ajara-toast.is-hiding {
    opacity: 0;
    transform: translateY(-10px);
    transition: .35s ease;
}

.ajara-toast button {
    margin-left: 12px;
    font-weight: bold;
    color: var(--ajara-maroon);
}

@media(max-width:640px) {
    .ajara-toast {
        top: 78px;
        left: 12px;
        right: 12px;
        max-width: none;
    }

    body {
        font-size: 14px;
    }
}

@media(prefers-reduced-motion:reduce) {
    *, *::before, *::after {
        animation-duration: .01ms !important;
        transition-duration: .01ms !important;
        scroll-behavior: auto !important;
    }
}
</style>
</head>
<body class="min-h-screen">
 <?php if(session('success')): ?><div id="flash-success" class="flash-message fixed top-4 left-1/2 z-[80] -translate-x-1/2 rounded-xl bg-emerald-800 px-5 py-3 text-sm font-semibold text-white shadow-lg"><?php echo e(session('success')); ?></div><?php endif; ?>
 <?php if(session('status')): ?><div id="flash-status" class="flash-message fixed top-4 left-1/2 z-[80] -translate-x-1/2 rounded-xl bg-ajara-teal px-5 py-3 text-sm font-semibold text-white shadow-lg"><?php echo e(session('status')); ?></div><?php endif; ?>
 <header class="sticky top-0 z-50 border-b border-slate-100 bg-white/95 backdrop-blur-xl">
  <div class="container-page flex h-[78px] items-center justify-between gap-6">
   <a href="<?php echo e(route('welcome')); ?>" class="flex shrink-0 items-center gap-2.5">
    <img src="<?php echo e(asset('images/ajara-logo.jpeg')); ?>" alt="Logo AJARA" class="h-12 w-12 rounded-xl object-contain">
    <span class="brand text-[25px] font-extrabold tracking-[.11em] text-ajara-teal">AJARA<span class="ml-1 inline-block h-2 w-2 rounded-full bg-ajara-gold"></span><small class="mt-[-2px] block text-[9px] font-bold tracking-[.13em] text-slate-400">AJANG & JEJARING PRESTASI</small></span>
   </a>
   <nav class="desktop-nav flex items-center gap-8">
    <a class="nav-link <?php echo e(request()->routeIs('welcome')?'active':''); ?>" href="<?php echo e(route('welcome')); ?>">Beranda</a>
    <a class="nav-link <?php echo e(request()->routeIs('competitions.*')?'active':''); ?>" href="<?php echo e(route('competitions.index')); ?>">Jelajahi Lomba</a>
    <a class="nav-link" href="<?php echo e(route('welcome')); ?>#kategori">Kategori</a>
    <a class="nav-link" href="<?php echo e(route('welcome')); ?>#tentang">Tentang AJARA</a>
   </nav>
   <div class="desktop-nav flex items-center gap-3">
    <?php if(auth()->guard()->check()): ?>
     <a href="<?php echo e(auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'penyelenggara' ? route('organizer.dashboard') : route('dashboard'))); ?>" class="flex items-center gap-2 rounded-full border border-slate-200 px-3 py-2 text-sm font-bold text-ajara-teal"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-ajara-cream text-ajara-teal"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></span><?php echo e(Str::limit(auth()->user()->name,14)); ?></a><form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button type="submit" class="rounded-full px-3 py-2 text-sm font-bold text-ajara-red hover:bg-red-50">Keluar</button></form>
    <?php else: ?>
     <a href="<?php echo e(route('login')); ?>" class="rounded-full px-4 py-2.5 text-sm font-bold text-ajara-teal hover:bg-slate-50">Masuk</a>
     <a href="<?php echo e(route('register')); ?>" class="btn-primary rounded-full px-5 py-2.5 text-sm font-bold shadow-md shadow-red-900/10">Daftar Sekarang <i class="bi bi-arrow-up-right ml-1"></i></a>
    <?php endif; ?>
   </div>
   <button class="mobile-menu rounded-xl border border-slate-200 p-2 text-xl text-ajara-teal" onclick="document.getElementById('mobileNav').classList.toggle('hidden')" aria-label="Buka menu"><i class="bi bi-list"></i></button>
  </div>
  <div id="mobileNav" class="hidden border-t border-slate-100 bg-white px-5 py-4 md:hidden"><div class="container-page flex flex-col gap-4"><a class="nav-link" href="<?php echo e(route('welcome')); ?>">Beranda</a><a class="nav-link" href="<?php echo e(route('competitions.index')); ?>">Jelajahi Lomba</a><a class="nav-link" href="<?php echo e(route('welcome')); ?>#kategori">Kategori</a><a class="nav-link" href="<?php echo e(route('welcome')); ?>#tentang">Tentang AJARA</a><div class="flex gap-3"><a href="<?php echo e(route('login')); ?>" class="flex-1 rounded-xl border p-3 text-center text-sm font-bold">Masuk</a><a href="<?php echo e(route('register')); ?>" class="btn-primary flex-1 rounded-xl p-3 text-center text-sm font-bold">Daftar</a></div></div></div>
 </header>
 <?php echo $__env->yieldContent('content'); ?>

 <?php if(auth()->guard()->check()): ?>
 <?php if(auth()->user()->role === 'mahasiswa'): ?>
 <nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 px-2 py-2 shadow-lg backdrop-blur md:hidden">
  <div class="mx-auto grid max-w-lg grid-cols-4 gap-1 text-center text-[11px] font-semibold">
   <a href="<?php echo e(route('dashboard')); ?>" class="px-2 py-2 text-ajara-teal"><i class="bi bi-house-door block text-xl"></i>Beranda</a>
   <a href="<?php echo e(route('competitions.index')); ?>" class="px-2 py-2 text-ajara-teal"><i class="bi bi-trophy block text-xl"></i>Lomba</a>
   <a href="<?php echo e(route('notifications.index')); ?>" class="px-2 py-2 text-ajara-teal"><i class="bi bi-bell block text-xl"></i>Notifikasi</a>
   <a href="<?php echo e(route('profile.show')); ?>" class="px-2 py-2 text-ajara-teal"><i class="bi bi-person block text-xl"></i>Profil</a>
  </div>
 </nav><div class="h-16 md:hidden"></div>
 <?php endif; ?>
 <?php endif; ?>
 <script>
 document.addEventListener('DOMContentLoaded', () => {
   document.querySelectorAll('.flash-message').forEach((item) => {
     setTimeout(() => { item.style.transition='opacity .35s'; item.style.opacity='0'; setTimeout(()=>item.remove(),400); }, 4500);
   });
 });
 </script>

 <footer id="tentang" class="mt-20 bg-ajara-teal text-white">
  <div class="container-page grid gap-10 py-14 md:grid-cols-[1.4fr_1fr_1fr_1fr]">
   <div><div class="mb-4 flex items-center gap-3"><img src="<?php echo e(asset('images/ajara-logo.jpeg')); ?>" class="h-14 w-14 rounded-xl object-contain" alt="Logo AJARA"><div class="brand text-2xl font-extrabold tracking-widest">AJARA</div></div><p class="max-w-xs text-sm leading-7 text-white/70">Ajang dan Jejaring Prestasi Mahasiswa. Satu tempat untuk menemukan peluang, berjejaring, dan bertumbuh melalui kompetisi.</p></div>
   <div><h3 class="mb-4 font-bold">Jelajahi</h3><div class="space-y-3 text-sm text-white/70"><a class="block hover:text-white" href="<?php echo e(route('competitions.index')); ?>">Semua Lomba</a><a class="block hover:text-white" href="<?php echo e(route('welcome')); ?>#kategori">Kategori Lomba</a><a class="block hover:text-white" href="<?php echo e(route('competitions.index',['category'=>'Nasional'])); ?>">Lomba Nasional</a></div></div>
   <div><h3 class="mb-4 font-bold">AJARA</h3><div class="space-y-3 text-sm text-white/70"><a class="block hover:text-white" href="<?php echo e(route('welcome')); ?>#tentang">Tentang Kami</a><a class="block hover:text-white" href="mailto:halo@ajara.id">Hubungi Kami</a><a class="block hover:text-white" href="<?php echo e(route('register')); ?>">Gabung AJARA</a></div></div>
   <div><h3 class="mb-4 font-bold">Ikuti Kami</h3><p class="mb-4 text-sm leading-6 text-white/70">Temukan kabar dan peluang prestasi terbaru.</p><div class="flex gap-3 text-xl"><a href="#" aria-label="Instagram" class="hover:text-ajara-gold"><i class="bi bi-instagram"></i></a><a href="#" aria-label="TikTok" class="hover:text-ajara-gold"><i class="bi bi-tiktok"></i></a><a href="mailto:halo@ajara.id" aria-label="Email" class="hover:text-ajara-gold"><i class="bi bi-envelope"></i></a></div></div>
  </div><div class="border-t border-white/15"><div class="container-page flex flex-col justify-between gap-2 py-5 text-xs text-white/55 sm:flex-row"><span>Â© <?php echo e(date('Y')); ?> AJARA. Seluruh hak cipta dilindungi.</span><span>Temukan Ajangnya, Wujudkan Prestasimu.</span></div></div>
 </footer>
 <?php echo $__env->yieldPushContent('scripts'); ?>
 <script>document.addEventListener('DOMContentLoaded',()=>['flash-success','flash-status'].forEach(id=>{const e=document.getElementById(id);if(e)setTimeout(()=>{e.style.transition='opacity .35s';e.style.opacity='0';setTimeout(()=>e.remove(),400)},4200)}));</script>
<!-- AJARA_GLOBAL_UI_V1_SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Animasi elemen konten
    const cards = document.querySelectorAll(
        '.ajara-card, .competition-card'
    );

    cards.forEach((card, index) => {
        card.style.animationDelay = Math.min(index * 70, 500) + 'ms';
        card.classList.add('ajara-animated');
    });

    // Tutup notifikasi sukses secara otomatis
    const alerts = document.querySelectorAll(
        '.flash-message, .alert-success, .toast-success, [data-flash]'
    );

    alerts.forEach(alert => {
        if (alert.dataset.autoDismiss === 'false') return;

        setTimeout(() => {
            alert.classList.add('ajara-toast', 'is-hiding');
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });

    // Tombol tutup notifikasi
    document.querySelectorAll('[data-dismiss-alert]').forEach(button => {
        button.addEventListener('click', function () {
            const alert = this.closest(
                '.flash-message, .alert-success, .toast-success, [data-flash]'
            );
            if (alert) alert.remove();
        });
    });

});
</script>
</body></html>

<?php /**PATH D:\aplikasi coding tantri\ajara_work\resources\views/layouts/app.blade.php ENDPATH**/ ?>