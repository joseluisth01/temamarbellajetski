<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Marbella JetSki">
    <link rel="icon" type="image/png" href="<?php echo mjsk_asset('media/photos/logo-circular.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo mjsk_asset('media/photos/logo-circular.png'); ?>">
    <meta name="theme-color" content="#0ea5e9">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9ZJN1GSH08"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-9ZJN1GSH08');
    </script>

    <!-- Google Search Console -->
    <meta name="google-site-verification" content="bQHQysLM0ETkXltm7PYsDEcV2qA-manU24rrGVgOrDg" />

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PDCL6B6S');</script>
    <!-- End Google Tag Manager -->

    <!-- Preconnect hints for external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="preconnect" href="https://flagcdn.com" crossorigin>

    <!-- Critical above-the-fold CSS inlined to eliminate render-blocking -->
    <style id="critical-css">
:root{--primary:#00b4d8;--primary-dark:#0096c7;--primary-light:#48cae4;--primary-glow:rgba(0,180,216,0.5);--secondary:#ff6b35;--accent-gold:#ffc300;--accent-teal:#00cec9;--dark:#0a1628;--dark-blue:#0f172a;--gray-800:#1f2937;--gray-500:#6b7280;--white:#fff;--gradient-primary:linear-gradient(135deg,#00b4d8 0%,#0096c7 50%,#00cec9 100%);--font-primary:'Montserrat',sans-serif;--font-display:'Playfair Display',serif;--section-padding:100px;--container-padding:20px;--button-radius:50px;--transition-normal:.3s ease;--shadow-glow:0 0 40px var(--primary-glow)}*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}html{scroll-behavior:smooth;scroll-padding-top:80px;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;text-rendering:optimizeLegibility;overflow-x:hidden}html.menu-open,html.menu-open body{overflow:hidden!important;position:relative;height:100%}body{font-family:var(--font-primary);font-size:16px;line-height:1.7;color:var(--gray-800);background:linear-gradient(180deg,#fff 0%,#f0f9ff 100%);overflow-x:hidden}.skip-link{position:absolute;top:-100%;left:16px;z-index:100000;padding:12px 24px;background:var(--primary);color:#fff;font-weight:600;border-radius:0 0 8px 8px;transition:top .2s}.skip-link:focus{top:0}img{max-width:100%;height:auto;display:block}a{text-decoration:none;color:inherit;transition:var(--transition-normal)}button{font-family:inherit;cursor:pointer;border:none;background:none}ul,ol{list-style:none}.container{max-width:1400px;margin:0 auto;padding:0 var(--container-padding)}.navbar{position:fixed;top:0;left:0;right:0;z-index:99999;padding:20px 0;background:0 0;box-shadow:none;border-radius:0;max-width:100%;transform:translateY(0);will-change:transform,max-width;transition:transform .5s ease,max-width .5s ease,padding .5s ease,border-radius .5s ease,background .5s ease,box-shadow .5s ease}html.menu-open .navbar,html.menu-open .navbar.scrolled{transform:none!important;backdrop-filter:none!important;-webkit-backdrop-filter:none!important;max-width:100%!important;border-radius:0!important;background:0 0!important;box-shadow:none!important}html.menu-open .nav-lang-mobile-header{opacity:0!important;pointer-events:none!important;visibility:hidden!important}.navbar.scrolled{transform:translateY(10px);max-width:min(94%,1400px);background:rgba(10,22,40,.92);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);padding:10px 0;box-shadow:0 8px 32px rgba(0,0,0,.35),0 0 0 1px rgba(255,255,255,.06);border-radius:50px;overflow:visible}.navbar.scrolled .logo-image{height:50px!important}.navbar.scrolled .logo-text{font-size:1.2rem}.navbar.scrolled .nav-menu{gap:10px}.navbar.scrolled .nav-link{font-size:13px}.navbar.scrolled .nav-actions{gap:12px}.navbar.scrolled .nav-cta{padding:10px 18px;font-size:13px}.navbar.scrolled .nav-container{gap:16px}.navbar.scrolled .nav-lang-btn{padding:6px 10px;font-size:13px}.navbar.scrolled .nav-lang-mobile-header .nav-lang-btn{padding:6px 8px;font-size:12px}.navbar.scrolled .nav-lang-mobile-header .nav-lang-btn img{width:26px;height:18px}.nav-container{max-width:1400px;margin:0 auto;padding:0 20px;display:flex;align-items:center;justify-content:space-between;gap:16px}.nav-logo{display:flex;align-items:center;gap:10px;font-size:24px;font-weight:800;color:var(--white);flex-shrink:0;min-width:0}.logo-text{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;letter-spacing:-1px}.logo-image{object-fit:contain}.logo-highlight{background:var(--gradient-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}.nav-menu{display:flex;align-items:center;gap:14px;flex:1;min-width:0;overflow:visible;justify-content:center}.nav-link{color:rgba(255,255,255,.8);font-weight:500;font-size:13px;position:relative;padding:5px 0;white-space:nowrap}.nav-link::after{content:'';position:absolute;bottom:0;left:0;width:0;height:2px;background:var(--gradient-primary);transition:var(--transition-normal)}.nav-link:hover,.nav-link.active{color:var(--white)}.nav-link:hover::after,.nav-link.active::after{width:100%}.nav-actions{display:flex;align-items:center;gap:20px;flex-shrink:0;z-index:1001;position:relative}.nav-cta{display:flex;align-items:center;gap:8px;background:var(--gradient-primary);color:var(--white);padding:10px 20px;border-radius:var(--button-radius);font-weight:600;font-size:13px;transition:var(--transition-normal);white-space:nowrap;z-index:1001;text-decoration:none}.nav-cta:hover{transform:translateY(-2px);box-shadow:var(--shadow-glow)}.nav-toggle{display:none;flex-direction:column;gap:6px;width:44px;min-height:44px;padding:10px;cursor:pointer;-webkit-tap-highlight-color:transparent}.nav-toggle span{width:100%;height:2px;background:var(--white);transition:var(--transition-normal)}.mobile-menu-footer{display:none}.nav-lang-mobile-header{display:none}.nav-lang-dropdown{position:relative;z-index:10002}.nav-lang-btn{display:flex;align-items:center;gap:6px;color:var(--white);font-weight:600;font-size:13px;padding:6px 12px;border-radius:var(--button-radius);border:none;background:0 0;cursor:pointer;transition:var(--transition-normal);white-space:nowrap;font-family:inherit}.nav-lang-btn img{border-radius:2px;height:auto}.nav-lang-btn:hover,.nav-lang-dropdown:hover .nav-lang-btn{background:rgba(0,180,216,.1)}.nav-lang-menu{position:absolute;top:calc(100% + 6px);right:0;background:rgba(15,23,42,.95);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:6px;min-width:160px;opacity:0;visibility:hidden;transform:translateY(-8px);transition:all .25s ease}.nav-lang-dropdown:hover .nav-lang-menu,.nav-lang-dropdown.open .nav-lang-menu{opacity:1;visibility:visible;transform:translateY(0)}.nav-lang-menu a{display:flex;align-items:center;gap:10px;padding:8px 12px;color:rgba(255,255,255,.85);text-decoration:none;font-size:13px;font-weight:500;border-radius:8px;transition:var(--transition-normal)}.nav-lang-menu a:hover{background:rgba(0,180,216,.15);color:var(--white)}.nav-lang-menu a img{border-radius:2px;flex-shrink:0;height:auto}.hero{position:relative;min-height:100vh;min-height:100dvh;display:flex;align-items:center;justify-content:center;padding:120px 0 80px;isolation:isolate}.hero-bg{position:absolute;inset:0;z-index:0;overflow:hidden}.hero-bg-image{width:100%;height:100%;object-fit:cover;filter:saturate(1.1) brightness(.95)}.hero-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(2,6,30,.8) 0%,rgba(2,20,60,.7) 35%,rgba(2,20,60,.7) 65%,rgba(2,6,30,.85) 100%);z-index:1}.hero-content{position:relative;text-align:center;max-width:820px;padding:0 24px;z-index:3}.hero-title{font-size:clamp(32px,5.5vw,64px);font-weight:800;color:var(--white);line-height:1.2;margin-bottom:20px;letter-spacing:-.5px}.title-line{display:block}.title-highlight{display:block;background:var(--gradient-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:800;font-size:1.05em;line-height:1.4;letter-spacing:-1px;padding:10px .15em;-webkit-box-decoration-break:clone;box-decoration-break:clone;filter:drop-shadow(0 2px 8px rgba(0,180,216,.4))}.hero-subtitle{font-size:16px;color:rgba(255,255,255,.7);margin-bottom:32px;line-height:1.6;font-weight:400}.hero-subtitle strong{color:var(--accent-gold)}.hero-stats{display:flex;align-items:center;justify-content:center;gap:32px;margin-bottom:40px;flex-wrap:wrap}.stat-item{text-align:center}.stat-number{display:block;font-size:42px;font-weight:800;color:var(--white);line-height:1}.stat-number::after{content:'+';color:var(--primary);font-weight:700}.stat-number.no-plus::after{content:none}.stat-number.no-plus{font-size:28px;font-weight:700;letter-spacing:1px}.stat-label{display:block;color:rgba(255,255,255,.5);font-size:11px;margin-top:6px;text-transform:uppercase;letter-spacing:2px;font-weight:600}.stat-divider{width:1px;height:40px;background:rgba(255,255,255,.15)}.hero-actions{display:flex;align-items:center;justify-content:center;gap:16px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;gap:10px;padding:14px 30px;border-radius:var(--button-radius);font-weight:600;font-size:15px;cursor:pointer}.btn-primary{background:var(--gradient-primary);color:var(--white);border:none;position:relative;overflow:hidden}.btn-primary:hover{transform:translateY(-3px);box-shadow:var(--shadow-glow)}.btn-outline{background:0 0;color:var(--white);border:2px solid rgba(255,255,255,.3)}.hero-video-container{position:absolute;inset:0;overflow:hidden;z-index:1}.hero-video-container video{position:absolute;top:50%;left:50%;min-width:100%;min-height:100%;width:auto;height:auto;transform:translate(-50%,-50%);object-fit:cover}.hero-video-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(2,6,30,.55) 0%,rgba(2,20,60,.45) 50%,rgba(2,6,30,.65) 100%);z-index:2}section{padding:var(--section-padding) 0;position:relative}.section-header{text-align:center;max-width:680px;margin:0 auto 56px}.section-tag{display:inline-block;background:var(--gradient-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-size:13px;font-weight:700;letter-spacing:3px;text-transform:uppercase;margin-bottom:12px}.section-title{font-size:clamp(30px,4.5vw,48px);font-weight:800;color:var(--dark);line-height:1.15;margin-bottom:16px;letter-spacing:-.3px}.gradient-text{background:var(--gradient-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}.section-subtitle{font-size:16px;color:var(--gray-500);line-height:1.7}.hero-promo-overlay{position:fixed;top:120px;left:50%;transform:translateX(-50%);z-index:999;display:flex;align-items:center;justify-content:center;gap:16px;padding:16px 36px;background:rgba(10,22,40,.55);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,195,0,.25);border-radius:60px;white-space:nowrap;max-width:90vw;opacity:0;pointer-events:none;transition:opacity .4s ease,top .4s ease}.hero-promo-overlay.promo-visible{opacity:1;pointer-events:auto}.hero-promo-overlay.promo-bottom{top:calc(100vh - 80px)}.hero-promo-overlay.promo-scrolled-top{top:80px}.hero-promo-overlay.promo-hide{opacity:0!important;pointer-events:none!important}.promo-close-btn{display:none;position:absolute;top:6px;right:6px;width:28px;height:28px;background:rgba(255,255,255,.15);border-radius:50%;color:#fff;font-size:14px;cursor:pointer;padding:0;z-index:1}.hero-promo-text{color:rgba(255,255,255,.95);font-size:.9rem;font-weight:500}.hero-promo-text strong{color:var(--accent-gold);font-weight:700;font-size:1rem}.hero-promo-text span{display:none}.hero-promo-btn{display:inline-flex;align-items:center;gap:6px;background:rgba(255,195,0,.2);color:#fff;font-weight:600;font-size:.8rem;padding:9px 20px;border-radius:40px;text-decoration:none;border:1px solid rgba(255,195,0,.3)}.fade-in{opacity:0;transform:translateY(20px);transition:opacity .6s ease,transform .6s ease}.fade-in.visible{opacity:1;transform:translateY(0)}@media(max-width:600px){.hero-promo-overlay{top:90px!important;left:50%;transform:translateX(-50%);max-width:88vw;gap:6px;padding:10px 18px;border-radius:16px;flex-direction:column;align-items:center;text-align:center}.hero-promo-overlay.promo-bottom,.hero-promo-overlay.promo-scrolled-top{top:90px!important}.hero-promo-overlay .hero-promo-icon{display:none}.promo-close-btn{display:flex;align-items:center;justify-content:center;top:-6px;right:-6px;width:18px;height:18px;font-size:10px}.hero-promo-text{font-size:.65rem;line-height:1.2}.hero-promo-text strong{font-size:.68rem}.hero-promo-btn{font-size:.58rem;padding:5px 10px;border-radius:14px;gap:3px}}@media(max-width:768px){.nav-lang-btn span,.nav-lang-btn .fa-chevron-down{display:none}:root{--section-padding:60px;--container-padding:16px}.section-header{margin-bottom:36px}.hero{padding:100px 0 60px}.hero-stats{gap:16px}.stat-divider{display:none}.stat-number{font-size:32px}.stat-number.no-plus{font-size:22px}.nav-toggle{width:44px;height:44px;padding:10px;display:flex;align-items:center;justify-content:center}.section-title{font-size:26px;line-height:1.2}.hero-title{font-size:30px;line-height:1.2}.hero-subtitle{font-size:15px}}@media(max-width:480px){:root{--section-padding:48px;--container-padding:14px}.hero-title{font-size:24px;line-height:1.2}.title-highlight{font-size:1em;padding:4px .1em}.hero-subtitle{font-size:15px;line-height:1.5}.hero-actions{flex-direction:column;width:100%;gap:12px}.btn{width:100%;justify-content:center;padding:14px 24px;font-size:15px;min-height:48px}}@media(max-width:1100px){.nav-container{gap:8px}.nav-logo{font-size:18px;gap:8px;flex-shrink:1!important;overflow:hidden}.logo-image{height:42px!important;flex-shrink:0}.logo-text{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0}.nav-menu{position:fixed;top:0;left:0;width:100vw;height:100vh;height:100dvh;background:rgba(10,22,40,.98);display:flex!important;flex-direction:column;justify-content:center;align-items:center;padding:80px 24px 100px;gap:0;z-index:9999;overflow-y:auto;opacity:0;visibility:hidden;transition:opacity .35s ease,visibility .35s ease;pointer-events:none}.nav-menu.active{opacity:1;visibility:visible;pointer-events:auto}.nav-link{display:block;padding:14px 32px;width:100%;max-width:320px;border-radius:10px;font-size:18px;font-weight:600;color:rgba(255,255,255,.75)!important;text-align:center;opacity:0;transform:translateY(12px);transition:color .2s,background .2s,opacity .35s ease,transform .35s ease}.nav-menu.active .nav-link{opacity:1;transform:translateY(0)}.nav-link:hover,.nav-link:active{color:#fff!important;background:rgba(0,180,216,.15)}.nav-link.active{color:#fff!important;background:none}.nav-link::after{display:none!important}.nav-actions{display:none!important}.nav-lang-mobile-header{display:flex!important;position:relative;z-index:10001;margin-right:8px;order:10}.nav-lang-mobile-header .nav-lang-btn{padding:4px 6px;gap:4px;font-size:12px;border:none;border-radius:8px;background:0 0;min-height:36px}.nav-lang-mobile-header .nav-lang-btn img{display:block;width:24px;height:16px;flex-shrink:0;border-radius:2px;box-shadow:0 0 0 1px rgba(255,255,255,.2)}.navbar.scrolled .nav-lang-mobile-header .nav-lang-btn{background:0 0}.nav-lang-mobile-header .nav-lang-menu{right:0;left:auto;top:calc(100% + 8px);min-width:140px}.nav-toggle{display:flex}.hero-promo-overlay{max-width:92vw}}@media(max-width:380px){.logo-image{display:none!important}.nav-logo{font-size:16px}}@media(max-width:1024px){.section-title{font-size:32px}.hero-title{font-size:36px}.container{padding-left:16px;padding-right:16px}}@media(max-height:500px) and (orientation:landscape){.hero{min-height:auto;padding:80px 0 40px}}
    </style>

    <?php wp_head(); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /* Reveal sections hidden by css-loaded guard */
            document.body.classList.add('css-loaded');

            /* Animate .fade-in elements on scroll */
            if ('IntersectionObserver' in window) {
                var obs = new IntersectionObserver(function(entries) {
                    entries.forEach(function(e) { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
                }, { threshold: 0.15 });
                document.querySelectorAll('.fade-in').forEach(function(el) { obs.observe(el); });
            } else {
                document.querySelectorAll('.fade-in').forEach(function(el) { el.classList.add('visible'); });
            }

            if (typeof AOS !== 'undefined') {
                AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 50, delay: 0 });
            }

            /* Cart badge — show count from localStorage */
            try {
                var cartData = JSON.parse(localStorage.getItem('mjsk_booking_cart') || '{}');
                var count = cartData.count || 0;
                var cartBtn = document.getElementById('navCartBtn');
                var cartBadge = document.getElementById('navCartBadge');
                if (cartBtn && count > 0) {
                    cartBtn.style.display = 'inline-flex';
                    if (cartBadge) cartBadge.textContent = count;
                }
            } catch(e) {}
        });
    </script>
</head>
<body <?php body_class(); ?>>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PDCL6B6S"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php wp_body_open(); ?>
    <a href="#main-content" class="skip-link"><?php echo mjsk_t('skip_content'); ?></a>

    <?php
    $lang        = mjsk_get_lang();
    $is_home     = mjsk_is_homepage();
    $nav_items   = mjsk_get_nav_items($lang);
    $cta_text    = mjsk_get_cta_text($lang);
    $booking_url = mjsk_get_booking_url($lang);
    $home_url    = mjsk_get_home_url($lang);
    $lang_data   = mjsk_get_lang_switcher();
    $current_flag_code = 'gb';
    $current_lang_code = 'EN';
    foreach ($lang_data as $ld) {
        if ($ld[4]) { $current_flag_code = $ld[1]; $current_lang_code = strtoupper($ld[0]); break; }
    }
    ?>

    <!-- Navigation -->
    <nav class="navbar scrolled" id="navbar">
        <div class="nav-container">
            <a href="<?php echo esc_url($home_url); ?>" class="nav-logo">
                <img src="<?php echo mjsk_asset('media/photos/logo-circular.webp'); ?>" alt="Marbella JetSki Logo" class="logo-image" style="height: 75px;" width="75" height="75">
                <span class="logo-text">MARBELLA<span class="logo-highlight">JETSKI</span></span>
            </a>

            <div class="nav-menu" id="navMenu">
                <?php foreach ($nav_items as $idx => $item) : ?>
                    <?php if ( isset( $item[2] ) && is_array( $item[2] ) ) : ?>
                        <div class="nav-dropdown" data-dropdown="dd-<?php echo $idx; ?>">
                            <a href="<?php echo esc_url($item[1]); ?>" class="nav-dropdown-toggle">
                                <?php echo esc_html($item[0]); ?>
                                <i class="fas fa-chevron-down nav-dd-arrow"></i>
                            </a>
                            <div class="nav-dropdown-menu">
                                <?php foreach ( $item[2] as $sub ) : ?>
                                    <a href="<?php echo esc_url($sub[1]); ?>" class="nav-dropdown-item"><?php echo esc_html($sub[0]); ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url($item[1]); ?>" class="nav-link"><?php echo esc_html($item[0]); ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Mobile-only language & CTA -->
                <div class="mobile-menu-footer">
                    <div class="nav-lang-dropdown">
                        <button class="nav-lang-btn" aria-label="<?php echo esc_attr(mjsk_t('language')); ?>">
                            <img src="https://flagcdn.com/w40/<?php echo $current_flag_code; ?>.png" alt="<?php echo $current_lang_code; ?>" width="24" height="12">
                            <span><?php echo $current_lang_code; ?></span>
                            <i class="fas fa-chevron-down" style="font-size:10px;margin-left:2px"></i>
                        </button>
                        <div class="nav-lang-menu">
                            <?php foreach ($lang_data as $ld) : ?>
                                <a href="<?php echo esc_url($ld[3]); ?>"<?php echo $ld[4] ? ' class="active"' : ''; ?>>
                                    <img src="https://flagcdn.com/w40/<?php echo $ld[1]; ?>.png" alt="<?php echo esc_attr($ld[2]); ?>" width="20" height="10">
                                    <?php echo esc_html($ld[2]); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a href="<?php echo esc_url($booking_url); ?>" class="nav-cta">
                        <span><?php echo esc_html($cta_text); ?></span>
                        <i class="fas fa-calendar-check"></i>
                    </a>
                </div>
            </div>

            <div class="nav-actions">
                <!-- Cart button -->
                <a href="<?php echo esc_url($booking_url); ?>" class="nav-cart-btn" id="navCartBtn" title="<?php echo esc_attr(mjsk_t('your_booking')); ?>" style="display:none;">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="nav-cart-badge" id="navCartBadge">0</span>
                </a>
                <!-- Desktop language switcher -->
                <div class="nav-lang-dropdown">
                    <button class="nav-lang-btn" aria-label="<?php echo esc_attr(mjsk_t('language')); ?>">
                        <img src="https://flagcdn.com/w40/<?php echo $current_flag_code; ?>.png" alt="<?php echo $current_lang_code; ?>" width="24" height="12">
                        <span><?php echo $current_lang_code; ?></span>
                        <i class="fas fa-chevron-down" style="font-size:10px;margin-left:2px"></i>
                    </button>
                    <div class="nav-lang-menu">
                        <?php foreach ($lang_data as $ld) : ?>
                            <a href="<?php echo esc_url($ld[3]); ?>"<?php echo $ld[4] ? ' class="active"' : ''; ?>>
                                <img src="https://flagcdn.com/w40/<?php echo $ld[1]; ?>.png" alt="<?php echo esc_attr($ld[2]); ?>" width="20" height="10">
                                <?php echo esc_html($ld[2]); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="<?php echo esc_url($booking_url); ?>" class="nav-cta">
                    <span><?php echo esc_html($cta_text); ?></span>
                    <i class="fas fa-calendar-check"></i>
                </a>
            </div>

            <!-- Mobile language switcher (next to hamburger) -->
            <div class="nav-lang-dropdown nav-lang-mobile-header">
                <button class="nav-lang-btn" aria-label="<?php echo esc_attr(mjsk_t('language')); ?>">
                    <img src="https://flagcdn.com/w40/<?php echo $current_flag_code; ?>.png" alt="<?php echo $current_lang_code; ?>" width="24" height="12">
                </button>
                <div class="nav-lang-menu">
                    <?php foreach ($lang_data as $ld) : ?>
                        <a href="<?php echo esc_url($ld[3]); ?>"<?php echo $ld[4] ? ' class="active"' : ''; ?>>
                            <img src="https://flagcdn.com/w40/<?php echo $ld[1]; ?>.png" alt="<?php echo esc_attr($ld[2]); ?>" width="20" height="10">
                            <?php echo esc_html($ld[2]); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="nav-toggle" id="navToggle" aria-label="<?php echo esc_attr(mjsk_t('toggle_nav')); ?>">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>
    <script>
    /* Mobile: toggle dropdown on tap (desktop uses CSS hover) */
    document.querySelectorAll('.nav-dropdown > .nav-dropdown-toggle').forEach(function(btn){
        btn.addEventListener('click',function(e){
            if(window.innerWidth<=1024){
                e.preventDefault();
                var dd=this.parentElement;
                var wasOpen=dd.classList.contains('open');
                document.querySelectorAll('.nav-dropdown.open').forEach(function(d){d.classList.remove('open');});
                if(!wasOpen)dd.classList.add('open');
            }
        });
    });
    document.addEventListener('click',function(e){
        if(!e.target.closest('.nav-dropdown')){
            document.querySelectorAll('.nav-dropdown.open').forEach(function(d){d.classList.remove('open');});
        }
    });
    </script>

    <?php if (mjsk_get('mjsk_promo_enabled')) : ?>
    <div class="hero-promo-overlay promo-scrolled-top">
        <button class="promo-close-btn" aria-label="Close">&times;</button>
        <span class="hero-promo-icon">☀️</span>
        <div class="hero-promo-text">
            <strong><?php echo esc_html(mjsk_t('promo_title')); ?></strong>
            <span><?php echo esc_html(mjsk_t('promo_text')); ?></span>
        </div>
        <a href="<?php echo esc_url($booking_url . '?promo=earlybird'); ?>" class="hero-promo-btn">
            <i class="fas fa-calendar-check"></i>
            <?php echo esc_html($cta_text); ?>
        </a>
    </div>
    <?php endif; ?>
