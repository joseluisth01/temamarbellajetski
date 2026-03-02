# MASTER MIGRATION SPECIFICATION

**Project:** Marbella JetSki WordPress Theme — Plugin-Compatible Conversion  
**Version:** 2.0  
**Date:** 2026-02-16  
**Status:** AUTHORITATIVE — Single source of truth for all agents  
**Repo (source):** `github.com/munyanyo92/marbellajetski-wordpress-theme`  
**Repo (delivery):** `github.com/munyanyo92/marbellajetski-wordpress-theme-delivery` (PRIVATE)  
**Receiving developer:** Juan — TicTac Comunicación  

---

## 1. CURRENT STATE INVENTORY

### 1.1 Theme Files

| File | Lines | Purpose |
|------|-------|---------|
| `functions.php` | 942 | Language detection, URL routing, translations, auto-setup, customizer, hreflang, repair tool |
| `header.php` | 162 | `<head>`, navbar, language switcher, promo banner |
| `footer.php` | 112 | Footer grid, social links, WhatsApp float, back-to-top |
| `front-page.php` | 20 | EN homepage — loads `home.html` + `home-modal.html` |
| `page.php` | 57 | Generic page template — maps slug to `page-content/{lang}-{slug}.html` |
| `index.php` | 17 | Fallback blog listing (bare-bones, needs replacement) |
| `404.php` | 13 | Static 404 page |
| `style.css` | 30 | Theme metadata header only |

### 1.2 Assets

| Path | Size | Contents |
|------|------|----------|
| `assets/css/main.css` | 5,410 lines | All theme styles |
| `assets/js/script.js` | 1,121 lines | All theme JS (navbar, booking form, animations) |
| `assets/media/` | 608 MB | Photos, videos, logos, thumbnails |
| `assets/boats/` | 4 files | Individual boat detail HTML (azimut, catamaran, cranchi, rinker) |
| `assets/fonts/` | — | Empty (uses Google Fonts CDN) |
| **Total theme size** | **1.3 GB** | Dominated by media files |

### 1.3 Page Content Files (33 total, 51,182 lines)

| Page | EN | ES | FR | NL | Shared Styles |
|------|----|----|----|----|---------------|
| Homepage | `home.html` (1,736 lines) | `es-home.html` (1,731) | `fr-home.html` (1,731) | `nl-home.html` (1,643) | — |
| Homepage Modal | `home-modal.html` | `es-home-modal.html` | `fr-home-modal.html` | `nl-home-modal.html` | — |
| Booking | `booking.html` (2,446) | `es-booking.html` (2,444) | `fr-booking.html` (2,444) | `nl-booking.html` (2,444) | `booking-styles.html` |
| About Us | `about-us.html` | `es-about-us.html` | `fr-about-us.html` | `nl-about-us.html` | `about-us-styles.html` |
| Lessons | `lessons.html` | `es-lessons.html` | `fr-lessons.html` | `nl-lessons.html` | `lessons-styles.html` |
| Terms | `terms.html` | `es-terms.html` | `fr-terms.html` | `nl-terms.html` | `terms-styles.html` |
| Weather Policy | `weather-policy.html` | `es-weather-policy.html` | `fr-weather-policy.html` | `nl-weather-policy.html` | `weather-policy-styles.html` |

### 1.4 Custom Functions to Remove or Replace

| Section # | Function(s) | What it does | Replacement |
|-----------|-------------|--------------|-------------|
| 2 | `mjsk_get_lang()` | Detects language from URL path (`/es/`, `/fr/`, `/nl/`) | Polylang `pll_current_language()` |
| 4 | `mjsk_get_home_url()`, `mjsk_get_booking_url()`, `mjsk_get_page_in_lang()` | Builds hardcoded language URLs | Polylang `pll_home_url()`, `get_permalink()` with Polylang translation lookup |
| 5 | `mjsk_get_lang_switcher()` | Returns language switcher array with flags | Polylang `pll_the_languages()` |
| 6 | `mjsk_get_nav_items()` | Returns hardcoded nav items per language | WordPress nav menus (`wp_nav_menu()`) + Polylang per-language menus |
| 7 | `mjsk_get_cta_text()` | Hardcoded CTA text per language | Can remain (simple) or move to Polylang string translations |
| 9 | `mjsk_t()` | 35 hardcoded translation strings | Polylang string translations (`pll__()` / `pll_register_string()`) |
| 14 | `mjsk_auto_setup()` | Auto-creates 24 pages on activation | Must be rewritten — pages created differently with Polylang translation pairs |
| 17 | `mjsk_hreflang_tags()` | Outputs hardcoded hreflang `<link>` tags | Polylang handles this automatically — REMOVE |

### 1.5 What Has NO Hardcoded SEO

The page-content HTML files contain **zero** meta descriptions, Open Graph tags, JSON-LD, or hreflang tags. All SEO currently lives in:
- `header.php` → hreflang via `mjsk_hreflang_tags()` (Section 17)
- WordPress `wp_head()` → title tag via `add_theme_support('title-tag')`

This means Rank Math can take over SEO cleanly with no conflicts to strip from content files.

---

## 2. TARGET STATE

### 2.1 Plugin Stack

| Plugin | Version | Purpose | Theme Impact |
|--------|---------|---------|--------------|
| **WooCommerce** | Latest | Product catalog + checkout + Redsys TPV | Theme must declare `woocommerce` support, provide template wrappers, style checkout |
| **Redsys for WooCommerce** | Latest | Payment gateway (BBVA) | No theme changes — WooCommerce handles it. Juan configures with Daniel's credentials |
| **Polylang** | Latest (free or Pro) | Multi-language routing, hreflang, string translations | Major — replaces Sections 2, 4, 5, 6, 9, 14, 17 of functions.php |
| **Rank Math** | Latest | SEO (meta, OG, JSON-LD, sitemaps) | Remove Section 17 (hreflang). No other conflicts |
| **Contact Form 7** | Latest | Contact/enquiry forms | Add shortcode to contact section if needed |
| **LiteSpeed Cache** | Latest | Page caching, CSS/JS minification | No theme changes. Ensure no inline `<style>` conflicts with critical CSS. Test dynamic content (language switcher) not cached incorrectly |
| **UpdraftPlus** | Latest | Backups | No theme changes |
| **Wordfence** | Latest | Security/firewall | No theme changes |

### 2.2 Tracking Codes to Inject

All codes go into `header.php`.

**Inside `<head>` (before `wp_head()`):**

```html
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
```

**Immediately after `<body>` tag (before `wp_body_open()`):**

```html
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PDCL6B6S"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
```

---

## 3. SERVICE LANDING PAGES

### 3.1 URL Matrix — English (16 pages)

| # | Service | Current Live URL | URL Slug |
|---|---------|-----------------|----------|
| 1 | Closed Circuit Jet Ski | `marbellajetski.com/closed-circuit-jet-ski/` | `closed-circuit-jet-ski` |
| 2 | Jet Ski Excursion Fuengirola | `marbellajetski.com/jet-ski-excursion-fuengirola/` | `jet-ski-excursion-fuengirola` |
| 3 | Jet Ski Tour Marbella | `marbellajetski.com/jet-ski-tour-marbella/` | `jet-ski-tour-marbella` |
| 4 | Jet Ski Tour Puerto Banús | `marbellajetski.com/jet-ski-tour-puerto-banus/` | `jet-ski-tour-puerto-banus` |
| 5 | Wakeboarding | `marbellajetski.com/wakeboarding-experiencie/` | `wakeboarding-experiencie` |
| 6 | Water Skiing | `marbellajetski.com/water-skiing-marbella/` | `water-skiing-marbella` |
| 7 | Pedal Boat | `marbellajetski.com/pedal-boat/` | `pedal-boat` |
| 8 | Donut | `marbellajetski.com/donut-watersports/` | `donut-watersports` |
| 9 | Crazy Sofa | `marbellajetski.com/crazy-sofa-ride/` | `crazy-sofa-ride` |
| 10 | Banana Boat | `marbellajetski.com/banana-boat-ride/` | `banana-boat-ride` |
| 11 | Air Stream | `marbellajetski.com/air-stream-marbella/` | `air-stream-marbella` |
| 12 | Water Bull | `marbellajetski.com/water-bull-ride/` | `water-bull-ride` |
| 13 | Paddleboarding | `marbellajetski.com/paddleboarding-marbella/` | `paddleboarding-marbella` |
| 14 | Yacht Charter | `marbellajetski.com/yacht-charter-marbella/` | `yacht-charter-marbella` |
| 15 | Sea Ray 240 Sundeck | `marbellajetski.com/sea-ray-240-sundeck/` | `sea-ray-240-sundeck` |
| 16 | Cranchi Endurance | `marbellajetski.com/cranchi-endurance-boat-charter/` | `cranchi-endurance-boat-charter` |

### 3.2 URL Matrix — Spanish (16 pages)

| # | Service | Current Live URL | URL Slug |
|---|---------|-----------------|----------|
| 1 | Circuito Cerrado | `marbellajetski.com/es/circuito-cerrado-motos-acuaticas/` | `circuito-cerrado-motos-acuaticas` |
| 2 | Excursión Fuengirola | `marbellajetski.com/es/excursion-moto-agua-fuengirola/` | `excursion-moto-agua-fuengirola` |
| 3 | Excursión Marbella | `marbellajetski.com/es/excursion-moto-agua-marbella/` | `excursion-moto-agua-marbella` |
| 4 | Tour Puerto Banús | `marbellajetski.com/es/tour-moto-agua-puerto-banus/` | `tour-moto-agua-puerto-banus` |
| 5 | Wakeboard | `marbellajetski.com/es/wakeboard/` | `wakeboard` |
| 6 | Esquí Acuático | `marbellajetski.com/es/esqui-acuatico/` | `esqui-acuatico` |
| 7 | Hidropedal | `marbellajetski.com/es/hidropedal-marbella/` | `hidropedal-marbella` |
| 8 | Donut Acuático | `marbellajetski.com/es/donut-acuatico/` | `donut-acuatico` |
| 9 | Sofá Loco | `marbellajetski.com/es/sofa-loco/` | `sofa-loco` |
| 10 | Banana Boat | `marbellajetski.com/es/banana-boat/` | `banana-boat` |
| 11 | Air Stream | `marbellajetski.com/es/air-stream-en-marbella/` | `air-stream-en-marbella` |
| 12 | Paddle Surf | `marbellajetski.com/es/paddle-surf/` | `paddle-surf` |
| 13 | Water Bull | `marbellajetski.com/es/water-bull/` | `water-bull` |
| 14 | Alquiler Yate | `marbellajetski.com/es/alquiler-yate-marbella/` | `alquiler-yate-marbella` |
| 15 | Sea Ray Sundeck | `marbellajetski.com/es/sea-ray-sundeck/` | `sea-ray-sundeck` |
| 16 | Cranchi Endurance | `marbellajetski.com/es/cranchi-endurance/` | `cranchi-endurance` |

### 3.3 Translation Pair Matrix (EN ↔ ES)

| EN Slug | ES Slug |
|---------|---------|
| `closed-circuit-jet-ski` | `circuito-cerrado-motos-acuaticas` |
| `jet-ski-excursion-fuengirola` | `excursion-moto-agua-fuengirola` |
| `jet-ski-tour-marbella` | `excursion-moto-agua-marbella` |
| `jet-ski-tour-puerto-banus` | `tour-moto-agua-puerto-banus` |
| `wakeboarding-experiencie` | `wakeboard` |
| `water-skiing-marbella` | `esqui-acuatico` |
| `pedal-boat` | `hidropedal-marbella` |
| `donut-watersports` | `donut-acuatico` |
| `crazy-sofa-ride` | `sofa-loco` |
| `banana-boat-ride` | `banana-boat` |
| `air-stream-marbella` | `air-stream-en-marbella` |
| `water-bull-ride` | `water-bull` |
| `paddleboarding-marbella` | `paddle-surf` |
| `yacht-charter-marbella` | `alquiler-yate-marbella` |
| `sea-ray-240-sundeck` | `sea-ray-sundeck` |
| `cranchi-endurance-boat-charter` | `cranchi-endurance` |

### 3.4 French & Dutch Landing Pages

The current live site has **NO** French or Dutch service landing pages. These can be created later by TicTac if needed. The theme template should support any language via Polylang.

### 3.5 Landing Page Template Requirements

Each service landing page must contain:
- Hero section with service-specific photo
- Service description (unique per page — no duplicate content)
- Pricing table
- CTA button linking to booking page with service pre-selected (`/booking/?activity=jet-ski-circuit`)
- Photo gallery (3-6 images)
- FAQ section (unique per service)
- Related services links
- Contact/WhatsApp CTA

Implementation approach: Create a reusable WordPress page template (`page-service-landing.php`) that TicTac can use for all 32+ pages. Content entered via WordPress editor. No hardcoded HTML files.

---

## 4. POLYLANG MIGRATION PLAN

### 4.1 Language Configuration

| Language | Code | Locale | Flag | URL Prefix | Default? |
|----------|------|--------|------|------------|----------|
| English | `en` | `en_GB` | 🇬🇧 | `/` (no prefix) | YES |
| Spanish | `es` | `es_ES` | 🇪🇸 | `/es/` | NO |
| French | `fr` | `fr_FR` | 🇫🇷 | `/fr/` | NO |
| Dutch | `nl` | `nl_NL` | 🇳🇱 | `/nl/` | NO |

**Polylang URL setting:** "The language is set from the directory name in pretty permalinks" (e.g., `/es/booking/`).

This matches the current URL structure exactly — **zero URL breakage**.

### 4.2 Page Translation Pairs

Each page must be created in WordPress and linked as a Polylang translation group.

| Page Type | EN Slug | ES Slug | FR Slug | NL Slug |
|-----------|---------|---------|---------|---------|
| Homepage | `home` (front page) | `inicio` | `accueil` | `home-nl` |
| Booking | `booking` | `booking` (under /es/) | `booking` (under /fr/) | `booking` (under /nl/) |
| About Us | `about-us` | `about-us` (under /es/) | `about-us` (under /fr/) | `about-us` (under /fr/) |
| Lessons | `lessons` | `lessons` (under /es/) | `lessons` (under /fr/) | `lessons` (under /nl/) |
| Terms | `terms` | `terms` (under /es/) | `terms` (under /fr/) | `terms` (under /nl/) |
| Weather Policy | `weather-policy` | `weather-policy` (under /es/) | `weather-policy` (under /fr/) | `weather-policy` (under /nl/) |

**Critical:** With Polylang, URL prefix is handled by the plugin, not by parent-child page hierarchy. The current auto-setup creates `/es/` as a parent page with child pages under it. Under Polylang, each page is a standalone page and Polylang adds the `/es/` prefix automatically.

### 4.3 What Changes in functions.php

| Current Function | Action | Polylang Replacement |
|------------------|--------|---------------------|
| `mjsk_get_lang()` | REPLACE | `pll_current_language()` — wrap in `function_exists()` check for fallback |
| `mjsk_get_home_url($lang)` | REPLACE | `pll_home_url($lang)` |
| `mjsk_get_booking_url($lang)` | REPLACE | Use Polylang translation API: get booking page ID, get translated ID, get permalink |
| `mjsk_get_page_in_lang($lang)` | REPLACE | `pll_get_post(get_the_ID(), $lang)` → `get_permalink()` |
| `mjsk_get_lang_switcher()` | REPLACE | `pll_the_languages(['raw' => 1])` |
| `mjsk_get_nav_items($lang)` | REMOVE | Use `wp_nav_menu()` — register one menu location, create per-language menus in Polylang |
| `mjsk_t($key)` | REPLACE | Register strings with `pll_register_string()`, retrieve with `pll__()` |
| `mjsk_hreflang_tags()` | REMOVE | Polylang outputs hreflang automatically |
| `mjsk_auto_setup()` | REWRITE | Must create pages per-language and link as Polylang translation groups |

### 4.4 Language Switcher Update

Current implementation in `header.php` uses `mjsk_get_lang_switcher()` to render flag dropdowns in 3 places:
1. Desktop navbar (line ~110)
2. Mobile navbar menu footer (line ~81)
3. Mobile header next to hamburger (line ~138)

All three must be updated to use Polylang data. The HTML/CSS structure stays the same — only the data source changes.

### 4.5 Navigation Menu Update

Current: Hardcoded array in `mjsk_get_nav_items()` per language.

Target: Register a WordPress menu location in `functions.php`:
```
register_nav_menus(['primary' => 'Primary Navigation']);
```

Create 4 menus in WP admin (one per language). Polylang links them. Use `wp_nav_menu()` in `header.php`.

The mobile menu footer (CTA + language switcher) remains custom PHP — only the nav links change to `wp_nav_menu()`.

---

## 5. WOOCOMMERCE INTEGRATION PLAN

### 5.1 Theme Support Declaration

Add to `functions.php` `mjsk_theme_setup()`:
```
add_theme_support('woocommerce');
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');
```

### 5.2 Template Wrapper

Create `woocommerce.php` at theme root:
- Wraps WooCommerce output in the theme's `<main id="main-content">` container
- Includes `get_header()` and `get_footer()`
- Ensures WooCommerce pages (shop, cart, checkout, account) match the site design

### 5.3 WooCommerce Product Structure

Juan will create WooCommerce products. This is a guide for him.

| Product Name | Type | Price | Category |
|-------------|------|-------|----------|
| Jet Ski Circuit — 20 min | Simple | €55 | Jet Ski |
| Jet Ski Circuit — 30 min | Simple | €70 | Jet Ski |
| Jet Ski Excursion — 30 min (Fuengirola) | Simple | €85 | Jet Ski |
| Jet Ski Excursion — 1 hour | Simple | €170 | Jet Ski |
| Jet Ski Excursion — 2 hours | Simple | €330 | Jet Ski |
| Wakeboarding — 15 min | Simple | €50 | Water Sports |
| Water Skiing — 15 min | Simple | €50 | Water Sports |
| Donut Ride — 15 min | Simple | €30 | Water Sports |
| Crazy Sofa — 15 min | Simple | €30 | Water Sports |
| Banana Boat — 15 min | Simple | €15/person | Water Sports |
| Air Stream — 15 min | Simple | €50 | Water Sports |
| Water Bull — 15 min | Simple | €50 | Water Sports |
| Paddleboarding — 1 hour | Simple | €15 | Water Sports |
| Pedal Boat — 30 min | Simple | €20 | Water Sports |
| Rinker 296 — 2 hours | Simple | €500 | Yacht Charter |
| Rinker 296 — 4 hours | Simple | €900 | Yacht Charter |
| Cranchi 39 — 2 hours | Simple | €700 | Yacht Charter |
| Cranchi 39 — 4 hours | Simple | €1,300 | Yacht Charter |
| Azimut 39 — 2 hours | Simple | €900 | Yacht Charter |
| Azimut 39 — 4 hours | Simple | €1,700 | Yacht Charter |
| Catamaran Bali — 3 hours | Simple | €1,800 | Yacht Charter |
| Catamaran Bali — 4 hours | Simple | €2,200 | Yacht Charter |
| Jet Ski Add-on (Yacht Charter) | Simple | €200/hour | Add-on |
| Racing Lesson — 1 hour | Simple | €200 | Racing |

**Note:** Prices must be verified with Daniel. Some may have seasonal variations.

### 5.4 Booking Flow Decision

**Option A — Full WooCommerce checkout:**
Replace the current multi-step booking form with WooCommerce's Add to Cart → Cart → Checkout flow. Redsys processes payment at checkout.
- Pro: Standard, Juan knows how to maintain it
- Con: Loses the custom UX of the current booking form

**Option B — Hybrid (recommended):**
Keep the existing booking form as the selection UI (steps 1-2). On "confirm", the form creates a WooCommerce order programmatically via JS/AJAX and redirects to WooCommerce checkout (step 3 = payment).
- Pro: Keeps the custom UX, gets Redsys payment
- Con: More complex to implement, harder for Juan to maintain

**Option C — Simple WooCommerce product pages:**
Each service is a WooCommerce product. Users browse products, add to cart, checkout. No custom booking form.
- Pro: Simplest, most maintainable
- Con: Loses the single-page booking experience

**Recommendation:** Option A or C — defer to Juan's preference since he will maintain it. The current booking form is complex (2,400+ lines per language with inline JS) and WooCommerce's standard flow is what Juan's team already knows.

### 5.5 Checkout Styling

WooCommerce checkout must be styled to match the theme's dark/blue design. Create:
- `woocommerce/checkout/form-checkout.php` (if template override needed)
- CSS overrides in `main.css` targeting `.woocommerce-checkout`, `.woocommerce-cart`, etc.

### 5.6 Redsys Configuration

**We do NOT configure Redsys.** Juan handles this. He needs from Daniel:
- Merchant code (número de comercio)
- Terminal number
- Secret key (SHA-256)
- Environment (test vs production URL)

---

## 6. BLOG TEMPLATE PLAN

### 6.1 Required Templates

| Template | Purpose | Notes |
|----------|---------|-------|
| `home.php` or blog page | Blog listing | Grid layout matching theme style. Shows featured image, title, excerpt, date, category. Paginated. |
| `single.php` | Single blog post | Full-width content area. Featured image hero. Author, date, categories, tags. Social share buttons. Related posts. |
| `archive.php` | Category/tag/date archives | Same grid layout as blog listing with archive title header. |
| `search.php` | Search results | Grid layout with search form at top. |
| `comments.php` | Comment template | Optional — depends on whether they want comments. |
| `sidebar.php` | Sidebar widget area | Optional — for blog sidebar (categories, recent posts, search). |

### 6.2 Blog Design Requirements

- Match the existing dark theme aesthetic
- Use the same font stack (Montserrat, Space Grotesk, Playfair Display)
- Card-based grid layout for post listings
- Responsive: 3 columns desktop, 2 tablet, 1 mobile
- Each card: featured image, category badge, title, excerpt (2 lines), read more link
- Polylang-aware: only show posts in the current language

### 6.3 WordPress Settings

- Create a "Blog" page (EN), "Blog" (ES) — link as Polylang translation pair
- Set Settings → Reading → Posts page to the Blog page
- Polylang will filter posts by language automatically

---

## 7. FUNCTIONS.PHP RESTRUCTURE

### 7.1 Sections to KEEP (no changes needed)

| Section | Function | Reason |
|---------|----------|--------|
| 1 | `mjsk_asset()` | Asset URL helper — still needed |
| 8 | `mjsk_get()` + Customizer defaults | Business data — still needed |
| 10 | `mjsk_load_page_content()`, `mjsk_get_content_file_for_page()` | Content loader — still needed |
| 11 | Asset enqueuing | Still needed |
| 12 | Theme setup | Keep, add WooCommerce support |
| 13 | Customizer sections | Still needed |
| 16 | Head cleanup | Still needed |
| 18 | Repair tool | Keep or update diagnostics |

### 7.2 Sections to REMOVE

| Section | Function | Reason |
|---------|----------|--------|
| 17 | `mjsk_hreflang_tags()` | Polylang handles hreflang |

### 7.3 Sections to REPLACE

| Section | Current | New |
|---------|---------|-----|
| 2 | `mjsk_get_lang()` — URL path parsing | Wrapper: `function mjsk_get_lang() { return function_exists('pll_current_language') ? pll_current_language() : 'en'; }` |
| 3 | `mjsk_is_homepage()` — slug check | Keep logic but remove hardcoded slug list, use `is_front_page()` + Polylang front page check |
| 4 | URL builders | Replace with Polylang API (`pll_home_url()`, translation lookups) |
| 5 | `mjsk_get_lang_switcher()` | Replace with Polylang `pll_the_languages()` formatted for our dropdown HTML |
| 6 | `mjsk_get_nav_items()` | Remove entirely — use `wp_nav_menu()` |
| 9 | `mjsk_t()` | Register strings with `pll_register_string()` on init, retrieve with `pll__()` |
| 14 | `mjsk_auto_setup()` | Major rewrite — create pages and link as Polylang translation groups |
| 15 | Flush rewrite rules | Keep as-is |

### 7.4 New Sections to ADD

| New Section | Purpose |
|-------------|---------|
| WooCommerce support | `add_theme_support('woocommerce')` in theme setup |
| Nav menu registration | `register_nav_menus(['primary' => 'Primary Navigation'])` |
| Widget areas | `register_sidebar()` for blog sidebar (if needed) |
| Polylang string registration | Hook into `init` to register all 35 translation strings |
| WooCommerce template wrapper | `woocommerce_content()` wrapper function |

---

## 8. HEADER.PHP CHANGES

### 8.1 Add Tracking Codes

Insert Google Analytics, GTM, and GSC codes as specified in Section 2.2.

### 8.2 Add GTM noscript

Insert after `<body>` tag, before `wp_body_open()`.

### 8.3 Update Language Switcher

Three instances of the language switcher dropdown must be updated:
1. Replace `$lang_data = mjsk_get_lang_switcher()` with Polylang API call
2. Adapt the `foreach` loop to Polylang's output format
3. Keep the exact same HTML structure (`.nav-lang-dropdown`, `.nav-lang-btn`, `.nav-lang-menu`)

### 8.4 Update Navigation

Replace the manual `foreach ($nav_items as $item)` loop with `wp_nav_menu()` call using a custom walker that outputs the same HTML structure (`.nav-link` class on each `<a>`).

---

## 9. PAGE.PHP CHANGES

### 9.1 Content Loading Logic

The current `mjsk_get_content_file_for_page()` maps slugs to `page-content/{lang}-{slug}.html`. Under Polylang:

- Language detection changes from URL parsing to `pll_current_language()`
- Slug mapping logic stays the same conceptually
- The function already has a fallback to `the_content()` — this fallback will be used for:
  - Service landing pages (content in WordPress editor)
  - Blog pages
  - WooCommerce pages
  - Any new pages TicTac creates

**No structural change needed** — just replace `mjsk_get_lang()` call inside the function.

### 9.2 New Page Template: Service Landing

Create `page-service-landing.php` with Template Name header:
```
<?php /* Template Name: Service Landing Page */ ?>
```

This template provides the structured layout for service landing pages. Content comes from WordPress editor + custom fields (or ACF if Juan uses it).

---

## 10. FOOTER.PHP CHANGES

### 10.1 URL Builders

Replace all manual URL construction (`$lang === 'en' ? home_url('/terms/') : ...`) with Polylang-aware permalinks:
```php
$terms_page_id = /* get terms page ID */;
$terms_url = get_permalink(pll_get_post($terms_page_id, $lang));
```

Or more simply: use `get_permalink()` on the translated page.

### 10.2 Translation Strings

Replace `mjsk_t('key')` calls with `pll__('string')` calls.

---

## 11. RISK REGISTER

| Risk | Severity | Mitigation |
|------|----------|------------|
| URL structure changes break SEO | HIGH | Polylang uses same `/es/page/` structure. Verify all URLs match 1:1 before go-live. No 301 redirects needed (confirmed by Juan). |
| Polylang free vs Pro limitations | MEDIUM | Free Polylang handles everything we need. Pro adds: URL slug translation per language, duplicate/sync content. Recommend Pro for service landing pages. |
| WooCommerce checkout style mismatch | MEDIUM | Must style WooCommerce checkout CSS to match dark theme. Test all screen sizes. |
| LiteSpeed Cache caches language-specific content | MEDIUM | LiteSpeed must be configured with "Vary by cookie" → `pll_language`. Juan should know this. Document it. |
| 1.3 GB theme size causes upload failures | HIGH | Media files (608 MB) should NOT be in the theme. Move to WordPress Media Library or external CDN. Theme zip should be < 50 MB. |
| Booking form JS conflicts with WooCommerce | MEDIUM | Booking form JS is inline in page-content HTML files. If switching to WooCommerce checkout, the existing booking JS needs to be either adapted or removed. |
| Google Analytics duplicate firing | LOW | If Rank Math also injects Analytics, disable Rank Math's Analytics module. Only use the hardcoded gtag snippet or GTM, not both approaches. |
| Loss of `mjsk_auto_setup()` convenience | LOW | Rewrite auto-setup for Polylang context. Or provide a setup guide for Juan to create pages manually. |

---

## 12. MEDIA FILE STRATEGY

### 12.1 Problem

The theme directory is 1.3 GB. Of this, 608 MB is media files (`assets/media/`). WordPress themes uploaded via the admin panel have a typical size limit of 2-50 MB depending on hosting.

### 12.2 Solution

Move media files OUT of the theme:
1. Upload all images/videos to WordPress Media Library
2. Or serve from a CDN (e.g., the existing Cloudflare setup)
3. Theme zip should contain only: PHP files, CSS, JS, fonts, and a few essential images (logo, icons)

### 12.3 Impact on Page Content HTML

All 33 HTML files reference images via paths like:
```html
<img src="/wp-content/themes/marbellajetski/assets/media/photos/hero.webp">
```

These paths will break if media moves. Options:
- **Option A:** Keep media in theme directory, upload theme via FTP (not WP admin). Keep paths as-is.
- **Option B:** Upload media to WP Media Library, update all paths in HTML files. More work, cleaner result.
- **Option C:** Serve media from a separate domain/CDN. Update paths once.

**Recommendation:** Option A for initial delivery (least work). Document for Juan that media lives in the theme folder and he should upload via FTP, not WP admin zip upload.

---

## 13. EXECUTION PHASES

### Phase 1 — Foundation (Est. 4 hours)

| Task | File(s) | Description |
|------|---------|-------------|
| 1.1 | `header.php` | Add Analytics, GTM, GSC tracking codes |
| 1.2 | `functions.php` | Add `add_theme_support('woocommerce')` |
| 1.3 | `functions.php` | Add `register_nav_menus()` |
| 1.4 | `functions.php` | Remove `mjsk_hreflang_tags()` and its `add_action` hook |
| 1.5 | `functions.php` | Replace `mjsk_get_lang()` with Polylang wrapper |
| 1.6 | `style.css` | Update theme description to mention plugin compatibility |

### Phase 2 — Polylang Integration (Est. 6 hours)

| Task | File(s) | Description |
|------|---------|-------------|
| 2.1 | `functions.php` | Replace URL builder functions with Polylang API |
| 2.2 | `functions.php` | Replace `mjsk_get_lang_switcher()` with Polylang wrapper |
| 2.3 | `functions.php` | Remove `mjsk_get_nav_items()` |
| 2.4 | `functions.php` | Replace `mjsk_t()` with `pll_register_string()` / `pll__()` |
| 2.5 | `header.php` | Update language switcher HTML to use Polylang data |
| 2.6 | `header.php` | Replace nav item loop with `wp_nav_menu()` |
| 2.7 | `footer.php` | Replace all URL builders with Polylang-aware permalinks |
| 2.8 | `footer.php` | Replace `mjsk_t()` calls with `pll__()` |
| 2.9 | `functions.php` | Rewrite `mjsk_auto_setup()` for Polylang page creation |
| 2.10 | `page.php` | Update `mjsk_get_content_file_for_page()` to use Polylang lang detection |

### Phase 3 — WooCommerce (Est. 4 hours)

| Task | File(s) | Description |
|------|---------|-------------|
| 3.1 | `woocommerce.php` | Create WooCommerce template wrapper |
| 3.2 | `assets/css/main.css` | Add WooCommerce checkout/cart styling |
| 3.3 | `functions.php` | Add WooCommerce-related hooks (if needed) |
| 3.4 | — | Document product creation guide for Juan |

### Phase 4 — Blog Templates (Est. 5 hours)

| Task | File(s) | Description |
|------|---------|-------------|
| 4.1 | `home.php` | Blog listing page template |
| 4.2 | `single.php` | Single post template |
| 4.3 | `archive.php` | Archive template |
| 4.4 | `search.php` | Search results template |
| 4.5 | `assets/css/main.css` | Blog-specific CSS |
| 4.6 | `functions.php` | Register blog sidebar widget area |

### Phase 5 — Service Landing Pages (Est. 3 hours for template)

| Task | File(s) | Description |
|------|---------|-------------|
| 5.1 | `page-service-landing.php` | Service landing page template |
| 5.2 | `assets/css/main.css` | Landing page CSS |
| 5.3 | — | Document for Juan: how to create landing pages using this template |

### Phase 6 — Testing & Documentation (Est. 4 hours)

| Task | Description |
|------|-------------|
| 6.1 | Test Polylang language switching on all pages |
| 6.2 | Test WooCommerce cart/checkout flow |
| 6.3 | Test all 4 languages load correct content |
| 6.4 | Test blog templates |
| 6.5 | Verify hreflang tags via Polylang |
| 6.6 | Write handover documentation for Juan |

---

## 14. AGENT EXECUTION GUIDELINES

### 14.1 Rules for All Agents

1. **Do NOT delete page-content HTML files.** They are the content source of truth. The page-content loading mechanism (`mjsk_load_page_content()`) stays.

2. **Do NOT modify page-content HTML files** unless explicitly instructed. Content changes (text, prices, images) are separate from structural migration.

3. **Always wrap Polylang function calls** in `function_exists()` checks so the theme doesn't crash if Polylang is deactivated:
   ```php
   $lang = function_exists('pll_current_language') ? pll_current_language() : 'en';
   ```

4. **Preserve all CSS class names** in header.php and footer.php. The `main.css` stylesheet targets these classes. Changing class names breaks styling.

5. **Do NOT touch `main.css` or `script.js`** unless the task specifically requires it (e.g., adding WooCommerce styles, blog styles).

6. **Test with Polylang disabled.** The theme must render correctly in English even if Polylang is not installed. All Polylang functions must have fallbacks.

7. **Keep `mjsk_` function prefix** for all theme functions. Do not introduce new prefixes.

8. **Media paths are absolute** (`/wp-content/themes/marbellajetski/assets/...`). Do not change them.

9. **No external dependencies** beyond what's already loaded (Font Awesome 6, Google Fonts, AOS). Do not add jQuery, Bootstrap, or other frameworks.

10. **Each agent works on ONE phase.** Do not cross phase boundaries without coordination.

### 14.2 Agent Assignment Strategy

| Agent | Phase | Input | Output |
|-------|-------|-------|--------|
| Agent A | Phase 1 | This spec, current `functions.php` + `header.php` | Updated `functions.php` + `header.php` |
| Agent B | Phase 2 | This spec, Phase 1 output | Updated `functions.php` + `header.php` + `footer.php` + `page.php` |
| Agent C | Phase 3 | This spec, Phase 2 output | New `woocommerce.php`, updated `main.css` |
| Agent D | Phase 4 | This spec, Phase 2 output | New `home.php`, `single.php`, `archive.php`, `search.php`, updated `main.css` |
| Agent E | Phase 5 | This spec, Phase 2 output | New `page-service-landing.php`, updated `main.css` |
| Agent F | Phase 6 | All outputs | Test report, handover documentation |

**Agents C, D, E can run in parallel** after Phase 2 completes.

### 14.3 File Locking

Only one agent may modify a given file at a time. If two agents need the same file:
- Agent priority: A > B > C > D > E > F
- Lower-priority agent waits for higher-priority agent to finish
- Exception: `main.css` — agents C, D, E can each add CSS at the END of the file (append-only) without conflict

---

## 15. HANDOVER CHECKLIST FOR JUAN (TICTAC)

### 15.1 What We Deliver

- [ ] WordPress theme zip (or Git repo access)
- [ ] This specification document
- [ ] Product creation guide (WooCommerce products, categories, prices)
- [ ] Page setup guide (which pages to create, how to link translations in Polylang)
- [ ] Menu setup guide (4 menus, one per language)
- [ ] Tracking code verification steps

### 15.2 What Juan Must Do After Receiving Theme

1. **Install WordPress** (if starting fresh) or use existing installation
2. **Upload theme** via FTP (due to size) or WP admin if media is separated
3. **Activate theme**
4. **Install plugins:** WooCommerce, Redsys for WooCommerce, Polylang, Rank Math, Contact Form 7, LiteSpeed Cache, UpdraftPlus, Wordfence
5. **Configure Polylang:**
   - Add 4 languages (EN default, ES, FR, NL)
   - Set URL format: directory name in pretty permalinks
   - Run the theme's auto-setup (creates pages and links translations)
6. **Configure WooCommerce:**
   - Create products per the product guide
   - Set currency: EUR
   - Install and configure Redsys gateway with Daniel's credentials
7. **Configure Rank Math:**
   - Run setup wizard
   - Set homepage SEO title/description
   - Rank Math will auto-detect Polylang
8. **Create WordPress menus:**
   - Create 4 menus (Primary EN, Primary ES, Primary FR, Primary NL)
   - Assign all to "Primary Navigation" location
   - Polylang will show language-specific menu assignment
9. **Create service landing pages:**
   - Use "Service Landing Page" template
   - Create 32 pages (16 EN + 16 ES) with exact slugs from Section 3
   - Link EN/ES pairs as Polylang translations
10. **Create blog pages:**
    - Create "Blog" page (EN) + translated versions
    - Set as Posts page in Settings → Reading
11. **Configure LiteSpeed Cache:**
    - Enable "Vary by cookie" with `pll_language` cookie
12. **Verify tracking codes:**
    - Check Google Analytics real-time report
    - Check GTM debug mode
    - Verify Search Console ownership
13. **Test all pages** in all 4 languages
14. **DNS switch** to production when ready

### 15.3 What Juan Must Get From Daniel

- Redsys merchant code (número de comercio)
- Terminal number
- Secret key (SHA-256)
- BBVA Redsys environment (test or production)

---

## 16. OPEN QUESTIONS

| # | Question | Who Decides | Impact |
|---|----------|-------------|--------|
| 1 | WooCommerce checkout flow — Option A, B, or C? (Section 5.4) | Daniel + Juan | Determines whether we keep or replace the current booking form |
| 2 | Media files — keep in theme (FTP upload) or move to Media Library? (Section 12) | Juan | Affects delivery method and all image paths |
| 3 | Do we build all 32 service landing pages with content, or just the template? | Daniel | 8-12 hours difference |
| 4 | Blog sidebar — yes or no? | Daniel/Juan | Affects blog template design |
| 5 | Contact Form 7 — where does it go? Replace existing WhatsApp contact, or add a separate form? | Daniel | Affects homepage + contact section |
| 6 | Should FR/NL service landing pages be created now or later? | Daniel | 0 or 32 additional pages |
| 7 | Polylang Free or Pro? | Juan | Pro enables URL slug translation per language |
| 8 | WooCommerce email customization — match theme design? | Juan | Additional styling work |

---

## 17. LIVE SITE SEO PRESERVATION RULES

### 17.1 Absolute Constraints

These rules are NON-NEGOTIABLE. Any violation risks SEO loss on the live site which has established rankings.

| Rule | Description |
|------|-------------|
| **URL Identity** | Every URL that exists on `marbellajetski.com` today must continue to resolve to the same content at the same path after migration. No slug changes, no path restructuring. |
| **Language Prefixes** | EN = no prefix (`/`), ES = `/es/`, FR = `/fr/`, NL = `/nl/`. This must be replicated exactly in Polylang configuration. |
| **Page Hierarchy** | Pages must not be nested under new parents that change their URL. Polylang uses language prefix, not parent-child relationships, for URL routing. |
| **No Deletions** | No existing page or service landing page may be deleted. Even if content is moved to WooCommerce products, the original URL must still resolve (via redirect or direct page). |
| **No Duplicates** | Each piece of content must exist at exactly one URL per language. Do not create duplicate pages that produce the same content at different URLs. |
| **Canonical Tags** | Rank Math will handle canonical tags. Do not add manual canonical tags. |
| **Hreflang** | Polylang generates hreflang tags automatically when translation pairs are linked. Do not add manual hreflang. Remove the existing `mjsk_hreflang_tags()` function. |
| **Meta & OG** | Rank Math handles all meta descriptions, Open Graph, and JSON-LD. Do not hardcode any SEO meta tags in templates or content files. |
| **Sitemap** | Rank Math generates the sitemap. Do not add a manual sitemap. Ensure Rank Math's sitemap module is enabled post-migration. |
| **Robots.txt** | Do not modify robots.txt. WordPress generates it. Rank Math can customise it if needed. |

### 17.2 Visual & UI Preservation

| Rule | Description |
|------|-------------|
| **Design Integrity** | The visual design, layout, colours, typography, spacing, and animations must remain exactly as built. No UI changes during migration. |
| **CSS Class Names** | Do not rename or remove any CSS class used in `main.css` (5,410 lines) or page-content HTML files. Adding new classes is permitted. |
| **JavaScript Behaviour** | Do not modify `script.js` (1,121 lines) behaviour. Booking form logic, animations, modals, and interactive elements must function identically. |
| **Responsive Behaviour** | All breakpoints and mobile layouts must remain unchanged. |
| **Media Paths** | All image/video `src` attributes in page-content HTML files use `/wp-content/themes/marbellajetski/assets/...` paths. These must not change unless media is formally migrated to WordPress Media Library (separate decision). |

### 17.3 Plugin Configuration Ownership

All plugin installation and configuration is the responsibility of Juan (TicTac Comunicación). The theme must be delivered plugin-COMPATIBLE, not plugin-CONFIGURED.

| Plugin | Our Responsibility | Juan's Responsibility |
|--------|-------------------|----------------------|
| WooCommerce | `add_theme_support('woocommerce')`, template wrapper, CSS | Install, create products, configure Redsys with Daniel's credentials |
| Polylang | Theme functions wrapped in `function_exists()` checks, Polylang-compatible lang switcher | Install, configure languages, create translation pairs, assign menus |
| Rank Math | No hardcoded SEO meta (already clean) | Install, run wizard, configure per-page SEO |
| Contact Form 7 | No conflicts | Install, create forms, place shortcodes |
| LiteSpeed Cache | No conflicts | Install, configure Vary by cookie for `pll_language` |
| UpdraftPlus | No conflicts | Install, configure backup schedule |
| Wordfence | No conflicts | Install, configure security rules |

---

## 18. HOMEPAGE SECTION → INDIVIDUAL PAGE MAPPING

### 18.1 Homepage Section Inventory

The homepage (`home.html` + language variants) contains 14 sections. Each section is fully implemented with content, pricing, CTAs, images, and translations in all 4 languages.

| # | Section | ID | EN Lines | Content Summary |
|---|---------|-----|----------|----------------|
| 1 | Hero | `#home` | 87–178 | Video background, headline, stats (est. 1998, 15+ activities, 4.9/5), weather widget, earlybird promo banner |
| 2 | Quick Services | `#services` | 188–328 | 5 category cards: Jet Ski (from €70), Yachts (from €250/h), Water Sports (from €20/pp), Excursions (from €170/h), Racing (from €299) |
| 3 | Why Choose Us | *(none)* | 330–365 | 6 USP cards: GPS tracking, premium experiences, maritime qualified team, pro photos (€25), family since 1998, ISO 9001/14001 |
| 4 | Jet Ski Hire | `#jetski` | 365–512 | Circuit rides (€70/20min, €90/30min) + Excursions (€170/1h, €330/2h), routes, features, booking CTAs |
| 5 | Water Sports | `#watersports` | 516–734 | 10 activities with pricing, category filters, capacity info, booking CTAs |
| 6 | Yacht Charters | `#boats` | 736–900 | 4 vessels (Rinker, Cranchi, Azimut, Catamaran) with full pricing tables, features, snacks disclaimer |
| 7 | Racing Lessons | `#racing-lessons` | 907–997 | 3 tiers: Basic €299/30min, Intermediate €499/1h, Masterclass €699/2h |
| 8 | About Us | `#about` | 999–1066 | Company history, racing credentials, ISO certs, team info |
| 9 | Videos | `#videos` | 1068–1108 | 4 promo videos with poster thumbnails |
| 10 | Photo Gallery | `#gallery` | 1110–1361 | 23 photos, lightbox viewer, category labels, social CTAs |
| 11 | Testimonials | *(none)* | 1370–1431 | 4.9/5 from 500+ reviews, 4 review cards (Google + TripAdvisor) |
| 12 | Booking CTA | `#booking` | 1433–1487 | Trust badges (insured, free cancellation, weather guarantee), phone CTA, hours |
| 13 | FAQ | `#faq` | 1489–1639 | 14 questions covering age, licence, cancellation, insurance, payment, weather, etc. |
| 14 | Contact | `#contact` | 1641–1736 | Phone, WhatsApp, email, hours, address, social links, Google Maps embed |

All 14 sections are identically structured in `es-home.html`, `fr-home.html`, `nl-home.html` — same section IDs, same CSS classes, fully translated content.

### 18.2 Section → Service Landing Page Mapping

The live site has individual landing pages for SEO. These map to content that currently lives inside homepage sections. The table below shows which homepage section provides the source content for each landing page.

| Homepage Section | Live Site Landing Page (EN) | Content in Theme? |
|-----------------|---------------------------|-------------------|
| `#jetski` — Circuit | `/closed-circuit-jet-ski/` | YES — pricing, features, CTA all in section |
| `#jetski` — Excursion | `/jet-ski-excursion-fuengirola/` | YES — excursion subsection |
| `#jetski` — Excursion | `/jet-ski-tour-marbella/` | YES — excursion subsection |
| `#jetski` — Excursion | `/jet-ski-tour-puerto-banus/` | YES — excursion subsection |
| `#watersports` — Wakeboarding | `/wakeboarding-experiencie/` | YES — card with price, duration, CTA |
| `#watersports` — Water Skiing | `/water-skiing-marbella/` | YES — card with price, duration, CTA |
| `#watersports` — Pedal Boat | `/pedal-boat/` | YES — card with price, duration, CTA |
| `#watersports` — Donut | `/donut-watersports/` | YES — card with price, duration, CTA |
| `#watersports` — Crazy Sofa | `/crazy-sofa-ride/` | YES — card with price, duration, CTA |
| `#watersports` — Banana Boat | `/banana-boat-ride/` | YES — card with price, duration, CTA |
| `#watersports` — Air Stream | `/air-stream-marbella/` | YES — card with price, duration, CTA |
| `#watersports` — Water Bull | `/water-bull-ride/` | YES — card with price, duration, CTA |
| `#watersports` — Paddleboarding | `/paddleboarding-marbella/` | YES — card with price, duration, CTA |
| `#boats` | `/yacht-charter-marbella/` | YES — 4 vessels with full pricing |
| `#boats` — Rinker/Sea Ray | `/sea-ray-240-sundeck/` | PARTIAL — Rinker 296 exists; Sea Ray 240 is a legacy boat not on homepage |
| `#boats` — Cranchi | `/cranchi-endurance-boat-charter/` | YES — Cranchi 39 card with pricing |

**Notes:**
- `/sea-ray-240-sundeck/` maps to a legacy vessel not displayed on the current homepage. Juan should verify with Daniel whether this page should redirect to Rinker 296 or be maintained as-is.
- Double Kayaks (€30/h) exist in `#watersports` but have no dedicated landing page on the live site.
- Racing Lessons exist in `#racing-lessons` but have no dedicated landing page on the live site.

### 18.3 Service Landing Page Content Requirements

Each individual service landing page must be a standalone WordPress page (using the `page-service-landing.php` template) containing:

| Element | Source | Notes |
|---------|--------|-------|
| Hero image | Theme `assets/media/photos/` | Service-specific photo |
| Service description | Unique per page | Must NOT be a copy-paste of homepage text — unique content for SEO |
| Pricing table | Must match homepage prices exactly | E.g., Circuit 20min = €70, 30min = €90 |
| Booking CTA | Link to `/booking/?activity={slug}` | Pre-selects the service in booking form |
| Features/inclusions | From homepage section | Safety briefing, life jacket, GPS, etc. |
| Photo gallery | 3-6 images per service | From `assets/media/photos/` |
| FAQ | 3-5 service-specific questions | Can draw from main FAQ but must be unique wording |
| Related services | Links to 2-3 related activities | Cross-linking for SEO |
| Trust badges | Insured, free cancellation, weather guarantee | From `#booking` section |
| Contact CTA | WhatsApp + phone | From `#contact` section |

---

## 19. FR/NL SERVICE LANDING PAGE URL MATRIX

### 19.1 Status

The live site currently has service landing pages in **EN and ES only**. FR and NL landing pages do not exist yet. They should be created to achieve full 4-language SEO coverage.

### 19.2 Proposed FR Service Landing Page URLs (16 pages)

| # | Service | Proposed URL | Translation Pair (EN) |
|---|---------|-------------|----------------------|
| 1 | Circuit fermé | `/fr/circuit-ferme-jet-ski/` | `closed-circuit-jet-ski` |
| 2 | Excursion Fuengirola | `/fr/excursion-jet-ski-fuengirola/` | `jet-ski-excursion-fuengirola` |
| 3 | Balade Marbella | `/fr/balade-jet-ski-marbella/` | `jet-ski-tour-marbella` |
| 4 | Balade Puerto Banús | `/fr/balade-jet-ski-puerto-banus/` | `jet-ski-tour-puerto-banus` |
| 5 | Wakeboard | `/fr/wakeboard-marbella/` | `wakeboarding-experiencie` |
| 6 | Ski nautique | `/fr/ski-nautique-marbella/` | `water-skiing-marbella` |
| 7 | Pédalo | `/fr/pedalo-marbella/` | `pedal-boat` |
| 8 | Bouée tractée | `/fr/bouee-tractee-marbella/` | `donut-watersports` |
| 9 | Canapé fou | `/fr/canape-fou-marbella/` | `crazy-sofa-ride` |
| 10 | Banana boat | `/fr/banana-boat-marbella/` | `banana-boat-ride` |
| 11 | Air stream | `/fr/air-stream-marbella/` | `air-stream-marbella` |
| 12 | Water bull | `/fr/water-bull-marbella/` | `water-bull-ride` |
| 13 | Paddle | `/fr/paddle-marbella/` | `paddleboarding-marbella` |
| 14 | Location yacht | `/fr/location-yacht-marbella/` | `yacht-charter-marbella` |
| 15 | Sea Ray 240 | `/fr/sea-ray-240-sundeck/` | `sea-ray-240-sundeck` |
| 16 | Cranchi Endurance | `/fr/cranchi-endurance-location/` | `cranchi-endurance-boat-charter` |

### 19.3 Proposed NL Service Landing Page URLs (16 pages)

| # | Service | Proposed URL | Translation Pair (EN) |
|---|---------|-------------|----------------------|
| 1 | Gesloten circuit | `/nl/gesloten-circuit-jetski/` | `closed-circuit-jet-ski` |
| 2 | Excursie Fuengirola | `/nl/jetski-excursie-fuengirola/` | `jet-ski-excursion-fuengirola` |
| 3 | Tour Marbella | `/nl/jetski-tour-marbella/` | `jet-ski-tour-marbella` |
| 4 | Tour Puerto Banús | `/nl/jetski-tour-puerto-banus/` | `jet-ski-tour-puerto-banus` |
| 5 | Wakeboarden | `/nl/wakeboarden-marbella/` | `wakeboarding-experiencie` |
| 6 | Waterskiën | `/nl/waterskien-marbella/` | `water-skiing-marbella` |
| 7 | Waterfiets | `/nl/waterfiets-marbella/` | `pedal-boat` |
| 8 | Donut rit | `/nl/donut-rit-marbella/` | `donut-watersports` |
| 9 | Gekke bank | `/nl/gekke-bank-marbella/` | `crazy-sofa-ride` |
| 10 | Bananenboot | `/nl/bananenboot-marbella/` | `banana-boat-ride` |
| 11 | Air stream | `/nl/air-stream-marbella/` | `air-stream-marbella` |
| 12 | Water bull | `/nl/water-bull-marbella/` | `water-bull-ride` |
| 13 | Suppen | `/nl/suppen-marbella/` | `paddleboarding-marbella` |
| 14 | Jachtcharter | `/nl/jachtcharter-marbella/` | `yacht-charter-marbella` |
| 15 | Sea Ray 240 | `/nl/sea-ray-240-sundeck/` | `sea-ray-240-sundeck` |
| 16 | Cranchi Endurance | `/nl/cranchi-endurance-charter/` | `cranchi-endurance-boat-charter` |

### 19.4 Full 4-Language Translation Pair Matrix

| EN Slug | ES Slug | FR Slug | NL Slug |
|---------|---------|---------|--------|
| `closed-circuit-jet-ski` | `circuito-cerrado-motos-acuaticas` | `circuit-ferme-jet-ski` | `gesloten-circuit-jetski` |
| `jet-ski-excursion-fuengirola` | `excursion-moto-agua-fuengirola` | `excursion-jet-ski-fuengirola` | `jetski-excursie-fuengirola` |
| `jet-ski-tour-marbella` | `excursion-moto-agua-marbella` | `balade-jet-ski-marbella` | `jetski-tour-marbella` |
| `jet-ski-tour-puerto-banus` | `tour-moto-agua-puerto-banus` | `balade-jet-ski-puerto-banus` | `jetski-tour-puerto-banus` |
| `wakeboarding-experiencie` | `wakeboard` | `wakeboard-marbella` | `wakeboarden-marbella` |
| `water-skiing-marbella` | `esqui-acuatico` | `ski-nautique-marbella` | `waterskien-marbella` |
| `pedal-boat` | `hidropedal-marbella` | `pedalo-marbella` | `waterfiets-marbella` |
| `donut-watersports` | `donut-acuatico` | `bouee-tractee-marbella` | `donut-rit-marbella` |
| `crazy-sofa-ride` | `sofa-loco` | `canape-fou-marbella` | `gekke-bank-marbella` |
| `banana-boat-ride` | `banana-boat` | `banana-boat-marbella` | `bananenboot-marbella` |
| `air-stream-marbella` | `air-stream-en-marbella` | `air-stream-marbella` | `air-stream-marbella` |
| `water-bull-ride` | `water-bull` | `water-bull-marbella` | `water-bull-marbella` |
| `paddleboarding-marbella` | `paddle-surf` | `paddle-marbella` | `suppen-marbella` |
| `yacht-charter-marbella` | `alquiler-yate-marbella` | `location-yacht-marbella` | `jachtcharter-marbella` |
| `sea-ray-240-sundeck` | `sea-ray-sundeck` | `sea-ray-240-sundeck` | `sea-ray-240-sundeck` |
| `cranchi-endurance-boat-charter` | `cranchi-endurance` | `cranchi-endurance-location` | `cranchi-endurance-charter` |

---

## 20. FUNCTIONAL CONTENT AUDIT

### 20.1 Fully Implemented Features (in theme, all 4 languages)

| Feature | Status | Files | Notes |
|---------|--------|-------|-------|
| Multi-step booking form | COMPLETE | `booking.html` + 3 lang variants (2,444-2,446 lines each) | 3-step flow: select activity → date/time → confirmation. Inline JS handles pricing, validation, summary. Sends via WhatsApp. |
| Jet ski circuit pricing | COMPLETE | `home.html` `#jetski` | 20min €70, 30min €90. Pre-selection URLs work. |
| Jet ski excursion pricing | COMPLETE | `home.html` `#jetski` | 1h €170, 2h €330. Route descriptions included. |
| Water sports pricing (10 activities) | COMPLETE | `home.html` `#watersports` | All 10 activities with prices, durations, capacities. Category filter works. |
| Yacht charter pricing (4 vessels) | COMPLETE | `home.html` `#boats` | Rinker, Cranchi, Azimut, Catamaran. 5 duration tiers each. Modal details for each vessel. |
| Yacht jet ski add-on | COMPLETE | `booking.html` | €200/hour, third-party disclaimer. Appears only when yacht is selected. |
| Racing lessons (3 tiers) | COMPLETE | `home.html` `#racing-lessons` | Basic €299, Intermediate €499, Masterclass €699. |
| Earlybird promo banner | COMPLETE | `header.php` + `home.html` | Code EARLYBIRD, 10% discount, configurable via Customizer. |
| Snacks disclaimer | COMPLETE | `home.html` `#boats` info bar | "Snacks are a complimentary selection and are limited, not unlimited." All 4 languages. |
| Third-party jet ski disclaimer | COMPLETE | `booking.html` yacht addon | "Jet ski add-ons are provided by a third-party operator, not by Marbella JetSki." All 4 languages. |
| Language switcher (4 languages) | COMPLETE | `header.php` | Desktop dropdown, mobile dropdown, mobile header dropdown. Flag icons via flagcdn.com. |
| Weather widget | COMPLETE | `home.html` `#home` hero | Live temp, wind, sea temp, humidity for Marbella. |
| Google Maps embed | COMPLETE | `home.html` `#contact` | Playa de las Dunas location. |
| Photo gallery (23 photos) | COMPLETE | `home.html` `#gallery` | Lightbox viewer, load more button. |
| Video gallery (4 videos) | COMPLETE | `home.html` `#videos` | Local MP4 files with poster thumbnails. |
| FAQ (14 questions) | COMPLETE | `home.html` `#faq` | Accordion with all common questions. |
| Testimonials (4 reviews) | COMPLETE | `home.html` testimonials | Swiper carousel, Google + TripAdvisor reviews. |
| Terms & conditions | COMPLETE | `terms.html` + 3 lang variants | Legal notice, T&C, privacy, cancellation, cookies. |
| Weather policy | COMPLETE | `weather-policy.html` + 3 lang variants | Full refund/reschedule policy. |
| About us | COMPLETE | `about-us.html` + 3 lang variants | Company history, team, certifications. |
| Lessons page | COMPLETE | `lessons.html` + 3 lang variants | Racing lesson details, requirements. |
| Boat detail modals | COMPLETE | `home-modal.html` + 3 lang variants | Specs, gallery, pricing for each of the 4 vessels. |
| WhatsApp float button | COMPLETE | `footer.php` | Fixed position, pre-populated message per language. |
| Back to top button | COMPLETE | `footer.php` | Appears on scroll. |
| Admin repair tool | COMPLETE | `functions.php` | Diagnoses 7 issues, 4 repair actions. |
| WordPress Customizer | COMPLETE | `functions.php` | Contact details, social media URLs, promo banner toggle. |
| 404 page | COMPLETE | `404.php` | Styled, links to home. |

### 20.2 NOT Yet Implemented (requires build)

| Feature | Status | Required For |
|---------|--------|-------------|
| WooCommerce theme support | NOT BUILT | Payment processing via Redsys |
| WooCommerce template wrapper | NOT BUILT | Styled checkout/cart pages |
| Blog templates (listing, single, archive, search) | NOT BUILT | SEO content strategy |
| Service landing page template | NOT BUILT | 32+ individual service pages |
| Polylang-compatible language functions | NOT BUILT | Polylang integration |
| WordPress nav menu registration | NOT BUILT | Dynamic menus via WP admin |
| Tracking codes in header.php | NOT INJECTED | Analytics (G-9ZJN1GSH08), GTM (GTM-PDCL6B6S), GSC verification |

---

## 21. TRACKING CODE VALIDATION

### 21.1 Current Status

| Code | ID | Required Location | Currently Injected? |
|------|----|-------------------|--------------------|
| Google Analytics (gtag.js) | `G-9ZJN1GSH08` | `<head>` before `wp_head()` | **NO** — Phase 1 task |
| Google Search Console | `bQHQysLM0ETkXltm7PYsDEcV2qA-manU24rrGVgOrDg` | `<head>` meta tag | **NO** — Phase 1 task |
| Google Tag Manager (script) | `GTM-PDCL6B6S` | `<head>` before `wp_head()` | **NO** — Phase 1 task |
| Google Tag Manager (noscript) | `GTM-PDCL6B6S` | Immediately after `<body>` tag | **NO** — Phase 1 task |

### 21.2 Exact Snippets

All snippets are documented in Section 2.2 of this spec and are confirmed correct (provided by Juan from current live site `header.php`).

### 21.3 Injection Plan

Phase 1, Task 1.1 will inject these codes into `header.php`:
1. Analytics + GSC + GTM script → inside `<head>`, before the existing `<?php wp_head(); ?>` call (line 10 of `header.php`)
2. GTM noscript → immediately after `<body <?php body_class(); ?>>` tag (line 46 of `header.php`), before `<?php wp_body_open(); ?>`

**Important:** If Rank Math also injects Google Analytics, disable Rank Math's Analytics module to prevent double-firing. Use only the hardcoded snippets OR GTM, not both approaches.

---

## 22. DELIVERY REPO STATUS

### 22.1 Repository Details

| Property | Value |
|----------|-------|
| Name | `marbellajetski-wordpress-theme-delivery` |
| URL | `https://github.com/munyanyo92/marbellajetski-wordpress-theme-delivery` |
| Visibility | **PRIVATE** |
| Branch | `main` |
| Source commit | `6449df4` (same as source repo) |
| LFS objects | 9 files, 746 MB (videos + PDFs) |
| Total size | ~84 MB git + 746 MB LFS |

### 22.2 Delivery Repo vs Source Repo

| Aspect | Source Repo | Delivery Repo |
|--------|-------------|---------------|
| URL | `munyanyo92/marbellajetski-wordpress-theme` | `munyanyo92/marbellajetski-wordpress-theme-delivery` |
| Visibility | Public | **Private** |
| Purpose | Development & preview (Cloudflare tunnel) | Clean handover to Juan |
| Modifications | Ongoing development | Delivery-ready only |
| Who has access | Public | Only `munyanyo92` (share with Juan when ready) |

The delivery repo is an exact copy of the source repo at commit `6449df4`. All migration work (Phase 1-6) should be performed on the delivery repo. The source repo remains untouched as a backup.

---

## 23. HANDOVER CHECKLIST FOR JUAN (1-PAGE QUICK REFERENCE)

### PRE-INSTALLATION

- [ ] Verify WordPress 6.0+ installed on target server
- [ ] Verify PHP 7.4+ on target server
- [ ] Confirm FTP access (theme is 1.3 GB — cannot upload via WP admin)
- [ ] Obtain Redsys credentials from Daniel: merchant code, terminal number, secret key

### THEME INSTALLATION

- [ ] Upload `marbellajetski/` folder to `/wp-content/themes/` via FTP
- [ ] Activate theme in Appearance → Themes
- [ ] Theme auto-creates 24 pages on activation (6 EN + 6×3 lang variants)
- [ ] Verify all 24 pages exist at correct URLs
- [ ] Verify homepage loads correctly at `/`
- [ ] Verify language pages load: `/es/`, `/fr/`, `/nl/`

### PLUGIN INSTALLATION (in this order)

- [ ] 1. **Polylang** → Configure: EN (default, no prefix), ES (`/es/`), FR (`/fr/`), NL (`/nl/`). URL format: directory name.
- [ ] 2. **Rank Math** → Run setup wizard. Disable Analytics module (tracking codes already in header.php).
- [ ] 3. **WooCommerce** → Configure: Currency EUR, country Spain. Create products per Section 5.3 product table.
- [ ] 4. **Redsys for WooCommerce** → Configure with Daniel's merchant credentials (test mode first).
- [ ] 5. **Contact Form 7** → Create contact form, place shortcode where needed.
- [ ] 6. **LiteSpeed Cache** → Enable. Set "Vary by cookie" → `pll_language`.
- [ ] 7. **UpdraftPlus** → Configure backup schedule (daily DB, weekly files).
- [ ] 8. **Wordfence** → Install, run initial scan.

### POLYLANG CONFIGURATION

- [ ] Link all 24 existing pages as translation groups (6 groups × 4 languages)
- [ ] Create 4 WordPress menus: Primary EN, Primary ES, Primary FR, Primary NL
- [ ] Assign menus to "Primary Navigation" location per language
- [ ] Verify language switcher dropdown works on all pages
- [ ] Verify hreflang tags appear in page source (Polylang generates them)

### SERVICE LANDING PAGES

- [ ] Create 16 EN service pages with slugs from Section 3.1
- [ ] Create 16 ES service pages with slugs from Section 3.2
- [ ] (Optional) Create 16 FR pages with slugs from Section 19.2
- [ ] (Optional) Create 16 NL pages with slugs from Section 19.3
- [ ] Link all as Polylang translation pairs per Section 19.4 matrix
- [ ] Use "Service Landing Page" template for all

### WOOCOMMERCE PRODUCTS

- [ ] Create products per Section 5.3 product table (24 products minimum)
- [ ] Assign to categories: Jet Ski, Water Sports, Yacht Charter, Racing, Add-on
- [ ] Configure Redsys gateway (test mode → production after verification)
- [ ] Test full checkout flow: add to cart → checkout → payment → confirmation

### BLOG

- [ ] Create "Blog" page (EN) + translated versions (ES/FR/NL)
- [ ] Set as Posts page in Settings → Reading
- [ ] Publish 1-2 test posts to verify blog templates

### TRACKING & SEO VERIFICATION

- [ ] Verify Google Analytics real-time report shows visits
- [ ] Verify GTM debug mode loads correctly
- [ ] Verify Search Console ownership via meta tag
- [ ] Verify Rank Math SEO titles/descriptions appear in page source
- [ ] Verify sitemap at `/sitemap_index.xml` (Rank Math)
- [ ] Verify hreflang tags in page source (Polylang)

### FINAL CHECKS

- [ ] Test all 4 languages load correctly
- [ ] Test booking form submission (WhatsApp redirect)
- [ ] Test WooCommerce checkout with Redsys (test mode)
- [ ] Test on mobile (responsive layout)
- [ ] Test 404 page
- [ ] Clear LiteSpeed Cache
- [ ] DNS switch when ready

---

*End of specification. Version 2.0 — updated with delivery repo, SEO preservation rules, homepage section mapping, FR/NL landing page URLs, functional content audit, tracking code validation, and handover checklist.*
