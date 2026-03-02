<?php
/**
 * Marbella JetSki Theme Functions
 *
 * Self-contained theme: language helpers, asset URL builder,
 * navigation, translations, Customizer, page-content loader, auto-setup.
 *
 * @package MarbellaJetSki
 * @version 2.0.0
 */

/* ═══════════════════════════════════════════════════════════════
   1.  ASSET HELPER
   ═══════════════════════════════════════════════════════════════ */

/**
 * Return the full URL to an asset inside /assets/.
 */
function mjsk_asset( $path ) {
    return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
}

/* ═══════════════════════════════════════════════════════════════
   2.  LANGUAGE DETECTION
   ═══════════════════════════════════════════════════════════════ */

/**
 * Detect the current language from the URL path.
 * /es/…  → 'es'   /fr/…  → 'fr'   /nl/…  → 'nl'   otherwise → 'en'
 */
function mjsk_get_lang() {
    static $lang = null;
    if ( $lang !== null ) return $lang;

    // If Polylang is active, use it as the source of truth
    if ( function_exists( 'pll_current_language' ) ) {
        $pll_lang = pll_current_language( 'slug' );
        if ( $pll_lang ) {
            $lang = $pll_lang;
            return $lang;
        }
    }

    $uri = trim( $_SERVER['REQUEST_URI'] ?? '', '/' );
    $parts = explode( '/', $uri );

    // Check if first meaningful segment is a language code
    foreach ( $parts as $seg ) {
        $seg = strtolower( $seg );
        if ( in_array( $seg, [ 'es', 'fr', 'nl' ], true ) ) {
            $lang = $seg;
            return $lang;
        }
        // Stop at first non-empty segment that isn't a lang code
        if ( $seg !== '' && ! in_array( $seg, [ 'es', 'fr', 'nl' ], true ) ) {
            break;
        }
    }
    $lang = 'en';
    return $lang;
}

/* ═══════════════════════════════════════════════════════════════
   3.  PAGE / HOMEPAGE DETECTION
   ═══════════════════════════════════════════════════════════════ */

function mjsk_is_homepage() {
    if ( is_front_page() ) return true;

    // Language homepages: /es/, /fr/, /nl/
    $slug = get_post_field( 'post_name', get_the_ID() );
    if ( in_array( $slug, [ 'es', 'fr', 'nl' ], true ) ) return true;

    return false;
}

/* ═══════════════════════════════════════════════════════════════
   4.  URL BUILDERS
   ═══════════════════════════════════════════════════════════════ */

function mjsk_get_home_url( $lang = 'en' ) {
    return $lang === 'en' ? home_url( '/' ) : home_url( '/' . $lang . '/' );
}

function mjsk_get_booking_url( $lang = 'en' ) {
    return $lang === 'en'
        ? home_url( '/booking/' )
        : home_url( '/' . $lang . '/booking/' );
}

/**
 * Service slug mapping: EN slug => [ es => ..., fr => ..., nl => ... ]
 * Each language has its own localized slug for service pages.
 */
function mjsk_service_slug_map() {
    return [
        // Aggregate category pages
        'jet-ski'                        => [ 'es' => 'motos-de-agua',                    'fr' => 'jet-ski',                          'nl' => 'jetski' ],
        'water-activities'               => [ 'es' => 'actividades-acuaticas',            'fr' => 'activites-nautiques',              'nl' => 'wateractiviteiten' ],
        'boat-hire'                      => [ 'es' => 'alquiler-barcos',                  'fr' => 'location-bateaux',                 'nl' => 'boot-huren' ],
        // Individual service pages
        'closed-circuit-jet-ski'         => [ 'es' => 'circuito-cerrado-motos-acuaticas', 'fr' => 'circuit-ferme-jet-ski',            'nl' => 'gesloten-circuit-jetski' ],
        'jet-ski-excursion-fuengirola'   => [ 'es' => 'excursion-moto-agua-fuengirola',   'fr' => 'excursion-jet-ski-fuengirola',     'nl' => 'jetski-excursie-fuengirola' ],
        'jet-ski-tour-marbella'          => [ 'es' => 'excursion-moto-agua-marbella',     'fr' => 'tour-jet-ski-marbella',            'nl' => 'jetski-tour-marbella' ],
        'jet-ski-tour-puerto-banus'      => [ 'es' => 'tour-moto-agua-puerto-banus',      'fr' => 'tour-jet-ski-puerto-banus',        'nl' => 'jetski-tour-puerto-banus' ],
        'wakeboarding-experience'       => [ 'es' => 'wakeboard',                        'fr' => 'wakeboard-marbella',               'nl' => 'wakeboarden-marbella' ],
        'water-skiing-marbella'          => [ 'es' => 'esqui-acuatico',                   'fr' => 'ski-nautique-marbella',            'nl' => 'waterskien-marbella' ],
        'pedal-boat'                     => [ 'es' => 'hidropedal-marbella',              'fr' => 'pedalo-marbella',                  'nl' => 'waterfiets-marbella' ],
        'donut-watersports'              => [ 'es' => 'donut-acuatico',                   'fr' => 'bouee-donut-marbella',             'nl' => 'donut-ride-marbella' ],
        'crazy-sofa-ride'                => [ 'es' => 'sofa-loco',                        'fr' => 'crazy-sofa-marbella',              'nl' => 'crazy-sofa-marbella' ],
        'banana-boat-ride'               => [ 'es' => 'banana-boat',                      'fr' => 'banana-boat-marbella',             'nl' => 'bananenboot-marbella' ],
        'air-stream-marbella'            => [ 'es' => 'air-stream-en-marbella',           'fr' => 'air-stream-marbella',              'nl' => 'air-stream-marbella' ],
        'water-bull-ride'                => [ 'es' => 'water-bull',                       'fr' => 'water-bull-marbella',              'nl' => 'water-bull-marbella' ],
        'paddleboarding-marbella'        => [ 'es' => 'paddle-surf',                     'fr' => 'paddle-surf-marbella',             'nl' => 'suppen-marbella' ],
        'yacht-charter-marbella'         => [ 'es' => 'alquiler-yate-marbella',           'fr' => 'location-yacht-marbella',          'nl' => 'jacht-huren-marbella' ],
        'azimut-39-fly'                  => [ 'es' => 'azimut-39-fly',                    'fr' => 'azimut-39-fly',                    'nl' => 'azimut-39-fly' ],
        'sea-ray-240-sundeck'            => [ 'es' => 'sea-ray-sundeck',                  'fr' => 'sea-ray-240-sundeck-location',     'nl' => 'sea-ray-240-sundeck-huren' ],
        'cranchi-endurance-boat-charter' => [ 'es' => 'cranchi-endurance',                'fr' => 'cranchi-endurance-39-location',    'nl' => 'cranchi-endurance-39-huren' ],
        'catamaran-bali-charter'         => [ 'es' => 'catamaran-bali',                   'fr' => 'catamaran-bali-location',          'nl' => 'catamaran-bali-huren' ],
    ];
}

/**
 * Given any service slug (in any language), return the EN slug.
 */
function mjsk_resolve_en_service_slug( $slug ) {
    $map = mjsk_service_slug_map();
    // If it's already an EN slug, return it
    if ( isset( $map[ $slug ] ) ) return $slug;
    // Search through all mappings
    foreach ( $map as $en_slug => $translations ) {
        if ( in_array( $slug, $translations, true ) ) return $en_slug;
    }
    return null; // not a service page
}

/**
 * Build URL for the same page in a different language.
 */
function mjsk_get_page_in_lang( $target_lang ) {
    $slug = get_post_field( 'post_name', get_the_ID() );
    $current_lang = mjsk_get_lang();

    // Current page slug — resolve to page type
    $page_type = $slug;
    if ( in_array( $slug, [ 'es', 'fr', 'nl', 'home' ], true ) ) {
        $page_type = 'home';
    }

    // Check if this is a service page
    $en_service_slug = mjsk_resolve_en_service_slug( $slug );
    if ( $en_service_slug ) {
        $map = mjsk_service_slug_map();
        if ( $target_lang === 'en' ) {
            $target_slug = $en_service_slug;
            $base = home_url( '/' . $target_slug . '/' );
        } else {
            $target_slug = $map[ $en_service_slug ][ $target_lang ] ?? $en_service_slug;
            $base = home_url( '/' . $target_lang . '/' . $target_slug . '/' );
        }
        $qs = isset( $_SERVER['QUERY_STRING'] ) ? $_SERVER['QUERY_STRING'] : '';
        if ( ! empty( $qs ) ) $base .= '?' . $qs;
        return $base;
    }

    // Build target URL for regular pages
    if ( $page_type === 'home' ) {
        $base = $target_lang === 'en' ? home_url( '/' ) : home_url( '/' . $target_lang . '/' );
    } else {
        $base = $target_lang === 'en'
            ? home_url( '/' . $page_type . '/' )
            : home_url( '/' . $target_lang . '/' . $page_type . '/' );
    }

    // Preserve query string (e.g. ?promo=earlybird&yacht=rinker)
    $qs = isset( $_SERVER['QUERY_STRING'] ) ? $_SERVER['QUERY_STRING'] : '';
    if ( ! empty( $qs ) ) {
        $base .= '?' . $qs;
    }

    return $base;
}

/* ═══════════════════════════════════════════════════════════════
   5.  LANGUAGE SWITCHER DATA
   ═══════════════════════════════════════════════════════════════ */

/**
 * Returns array of [ code, flagCode, label, url, isActive ] for switcher.
 */
function mjsk_get_lang_switcher() {
    $current = mjsk_get_lang();

    // If Polylang is active, use its language switcher data
    if ( function_exists( 'pll_the_languages' ) ) {
        $pll_langs = pll_the_languages( [ 'raw' => 1, 'hide_if_no_translation' => 0 ] );
        if ( ! empty( $pll_langs ) ) {
            $flag_map = [ 'en' => 'gb', 'es' => 'es', 'fr' => 'fr', 'nl' => 'nl' ];
            $result   = [];
            foreach ( $pll_langs as $pll ) {
                $code = $pll['slug'];
                $result[] = [
                    $code,
                    $flag_map[ $code ] ?? $code,
                    $pll['name'],
                    $pll['url'],
                    $pll['current_lang'],
                ];
            }
            return $result;
        }
    }

    $langs   = [
        [ 'en', 'gb', 'English',    '', false ],
        [ 'es', 'es', 'Español',    '', false ],
        [ 'fr', 'fr', 'Français',   '', false ],
        [ 'nl', 'nl', 'Nederlands', '', false ],
    ];

    foreach ( $langs as &$l ) {
        $l[3] = mjsk_get_page_in_lang( $l[0] );
        $l[4] = ( $l[0] === $current );
    }
    unset( $l );
    return $langs;
}

/* ═══════════════════════════════════════════════════════════════
   6.  NAVIGATION ITEMS
   ═══════════════════════════════════════════════════════════════ */

function mjsk_get_nav_items( $lang = 'en' ) {
    $home    = mjsk_get_home_url( $lang );
    $lessons = $lang === 'en' ? home_url( '/lessons/' ) : home_url( '/' . $lang . '/lessons/' );
    $about   = $lang === 'en' ? home_url( '/about-us/' ) : home_url( '/' . $lang . '/about-us/' );
    $blog    = $lang === 'en' ? home_url( '/blog/' ) : home_url( '/' . $lang . '/blog/' );
    $p       = $lang === 'en' ? '/' : '/' . $lang . '/'; // prefix for service page slugs

    // Each item: [ label, url ]  OR  [ label, landing_url, [ [sub_label, sub_url], ... ] ]
    $items = [
        'en' => [
            [ 'Home',    $home ],
            [ 'Jet Ski', home_url( $p . 'jet-ski/' ), [
                [ 'Circuit Jet Ski Experience',      home_url( $p . 'closed-circuit-jet-ski/' ) ],
                [ 'Jet Ski Adventure to Fuengirola', home_url( $p . 'jet-ski-excursion-fuengirola/' ) ],
                [ 'Jet Ski Tour Around Marbella',    home_url( $p . 'jet-ski-tour-marbella/' ) ],
                [ 'Jet Ski Excursion to Puerto Banús', home_url( $p . 'jet-ski-tour-puerto-banus/' ) ],
            ]],
            [ 'Water Activities', home_url( $p . 'water-activities/' ), [
                [ 'Wakeboarding',    home_url( $p . 'wakeboarding-experience/' ) ],
                [ 'Water Skiing',    home_url( $p . 'water-skiing-marbella/' ) ],
                [ 'Pedal Boat',      home_url( $p . 'pedal-boat/' ) ],
                [ 'Donut Rides',     home_url( $p . 'donut-watersports/' ) ],
                [ 'Crazy Sofa',      home_url( $p . 'crazy-sofa-ride/' ) ],
                [ 'Banana Boat',     home_url( $p . 'banana-boat-ride/' ) ],
                [ 'Air Stream',      home_url( $p . 'air-stream-marbella/' ) ],
                [ 'Water Bull',      home_url( $p . 'water-bull-ride/' ) ],
                [ 'Paddleboarding',  home_url( $p . 'paddleboarding-marbella/' ) ],
            ]],
            [ 'Boat Hire', home_url( $p . 'boat-hire/' ), [
                [ 'Yacht Charter',       home_url( $p . 'yacht-charter-marbella/' ) ],
                [ 'Rinker 296 Captiva',  home_url( $p . 'sea-ray-240-sundeck/' ) ],
                [ 'Cranchi Endurance',   home_url( $p . 'cranchi-endurance-boat-charter/' ) ],
                [ 'Azimut 39 Fly',       home_url( $p . 'azimut-39-fly/' ) ],
                [ 'Catamaran Bali 4.0',  home_url( $p . 'catamaran-bali-charter/' ) ],
            ]],
            [ 'Lessons',  $lessons ],
            [ 'About Us', $about ],
            [ 'Blog',     $blog ],
            [ 'Contact',  $home . '#contact' ],
        ],
        'es' => [
            [ 'Inicio',    $home ],
            [ 'Motos de Agua', home_url( $p . 'motos-de-agua/' ), [
                [ 'Circuito Cerrado',             home_url( $p . 'circuito-cerrado-motos-acuaticas/' ) ],
                [ 'Excursión Fuengirola',         home_url( $p . 'excursion-moto-agua-fuengirola/' ) ],
                [ 'Excursión Marbella',           home_url( $p . 'excursion-moto-agua-marbella/' ) ],
                [ 'Tour Puerto Banús',            home_url( $p . 'tour-moto-agua-puerto-banus/' ) ],
            ]],
            [ 'Actividades Acuáticas', home_url( $p . 'actividades-acuaticas/' ), [
                [ 'Wakeboard',           home_url( $p . 'wakeboard/' ) ],
                [ 'Esquí Acuático',      home_url( $p . 'esqui-acuatico/' ) ],
                [ 'Hidropedal',          home_url( $p . 'hidropedal-marbella/' ) ],
                [ 'Donut Acuático',      home_url( $p . 'donut-acuatico/' ) ],
                [ 'Sofá Loco',           home_url( $p . 'sofa-loco/' ) ],
                [ 'Banana Boat',         home_url( $p . 'banana-boat/' ) ],
                [ 'Air Stream',          home_url( $p . 'air-stream-en-marbella/' ) ],
                [ 'Water Bull',          home_url( $p . 'water-bull/' ) ],
                [ 'Paddle Surf',         home_url( $p . 'paddle-surf/' ) ],
            ]],
            [ 'Alquiler Barcos', home_url( $p . 'alquiler-barcos/' ), [
                [ 'Alquiler de Yates',   home_url( $p . 'alquiler-yate-marbella/' ) ],
                [ 'Rinker 296 Captiva',  home_url( $p . 'sea-ray-sundeck/' ) ],
                [ 'Cranchi Endurance',   home_url( $p . 'cranchi-endurance/' ) ],
                [ 'Azimut 39 Fly',       home_url( $p . 'azimut-39-fly/' ) ],
                [ 'Catamarán Bali 4.0',  home_url( $p . 'catamaran-bali/' ) ],
            ]],
            [ 'Clases',         $lessons ],
            [ 'Sobre Nosotros', $about ],
            [ 'Blog',           $blog ],
            [ 'Contacto',       $home . '#contact' ],
        ],
        'fr' => [
            [ 'Accueil',    $home ],
            [ 'Jet Ski', home_url( $p . 'jet-ski/' ), [
                [ 'Circuit Fermé',            home_url( $p . 'circuit-ferme-jet-ski/' ) ],
                [ 'Excursion Fuengirola',     home_url( $p . 'excursion-jet-ski-fuengirola/' ) ],
                [ 'Tour Marbella',            home_url( $p . 'tour-jet-ski-marbella/' ) ],
                [ 'Tour Puerto Banús',        home_url( $p . 'tour-jet-ski-puerto-banus/' ) ],
            ]],
            [ 'Activités Nautiques', home_url( $p . 'activites-nautiques/' ), [
                [ 'Wakeboard',       home_url( $p . 'wakeboard-marbella/' ) ],
                [ 'Ski Nautique',    home_url( $p . 'ski-nautique-marbella/' ) ],
                [ 'Pédalo',          home_url( $p . 'pedalo-marbella/' ) ],
                [ 'Donut',           home_url( $p . 'bouee-donut-marbella/' ) ],
                [ 'Crazy Sofa',      home_url( $p . 'crazy-sofa-marbella/' ) ],
                [ 'Banana Boat',     home_url( $p . 'banana-boat-marbella/' ) ],
                [ 'Air Stream',      home_url( $p . 'air-stream-marbella/' ) ],
                [ 'Water Bull',      home_url( $p . 'water-bull-marbella/' ) ],
                [ 'Paddle',          home_url( $p . 'paddle-surf-marbella/' ) ],
            ]],
            [ 'Location Bateaux', home_url( $p . 'location-bateaux/' ), [
                [ 'Location de Yacht',   home_url( $p . 'location-yacht-marbella/' ) ],
                [ 'Rinker 296 Captiva',  home_url( $p . 'sea-ray-240-sundeck-location/' ) ],
                [ 'Cranchi Endurance',   home_url( $p . 'cranchi-endurance-39-location/' ) ],
                [ 'Azimut 39 Fly',       home_url( $p . 'azimut-39-fly/' ) ],
                [ 'Catamaran Bali 4.0',  home_url( $p . 'catamaran-bali-location/' ) ],
            ]],
            [ 'Cours',     $lessons ],
            [ 'À Propos',  $about ],
            [ 'Blog',      $blog ],
            [ 'Contact',   $home . '#contact' ],
        ],
        'nl' => [
            [ 'Home',    $home ],
            [ 'Jetski', home_url( $p . 'jetski/' ), [
                [ 'Gesloten Circuit',         home_url( $p . 'gesloten-circuit-jetski/' ) ],
                [ 'Excursie Fuengirola',      home_url( $p . 'jetski-excursie-fuengirola/' ) ],
                [ 'Tour Marbella',            home_url( $p . 'jetski-tour-marbella/' ) ],
                [ 'Tour Puerto Banús',        home_url( $p . 'jetski-tour-puerto-banus/' ) ],
            ]],
            [ 'Wateractiviteiten', home_url( $p . 'wateractiviteiten/' ), [
                [ 'Wakeboarden',     home_url( $p . 'wakeboarden-marbella/' ) ],
                [ 'Waterskiën',      home_url( $p . 'waterskien-marbella/' ) ],
                [ 'Waterfiets',      home_url( $p . 'waterfiets-marbella/' ) ],
                [ 'Donut',           home_url( $p . 'donut-ride-marbella/' ) ],
                [ 'Crazy Sofa',      home_url( $p . 'crazy-sofa-marbella/' ) ],
                [ 'Bananenboot',     home_url( $p . 'bananenboot-marbella/' ) ],
                [ 'Air Stream',      home_url( $p . 'air-stream-marbella/' ) ],
                [ 'Water Bull',      home_url( $p . 'water-bull-marbella/' ) ],
                [ 'Suppen',          home_url( $p . 'suppen-marbella/' ) ],
            ]],
            [ 'Boot Huren', home_url( $p . 'boot-huren/' ), [
                [ 'Jacht Huren',         home_url( $p . 'jacht-huren-marbella/' ) ],
                [ 'Rinker 296 Captiva',  home_url( $p . 'sea-ray-240-sundeck-huren/' ) ],
                [ 'Cranchi Endurance',   home_url( $p . 'cranchi-endurance-39-huren/' ) ],
                [ 'Azimut 39 Fly',       home_url( $p . 'azimut-39-fly/' ) ],
                [ 'Catamaran Bali 4.0',  home_url( $p . 'catamaran-bali-huren/' ) ],
            ]],
            [ 'Lessen',   $lessons ],
            [ 'Over Ons', $about ],
            [ 'Blog',     $blog ],
            [ 'Contact',  $home . '#contact' ],
        ],
    ];

    return $items[ $lang ] ?? $items['en'];
}

/* ═══════════════════════════════════════════════════════════════
   7.  CTA BUTTON TEXT
   ═══════════════════════════════════════════════════════════════ */

function mjsk_get_cta_text( $lang = 'en' ) {
    $map = [
        'en' => 'Book Now',
        'es' => 'Reservar',
        'fr' => 'Réserver',
        'nl' => 'Boeken',
    ];
    return $map[ $lang ] ?? $map['en'];
}

/* ═══════════════════════════════════════════════════════════════
   8.  THEME OPTIONS  (Customizer with hardcoded defaults)
   ═══════════════════════════════════════════════════════════════ */

function mjsk_get( $key ) {
    $defaults = [
        'mjsk_phone'          => '+34 655 442 232',
        'mjsk_whatsapp'       => '34655442232',
        'mjsk_email'          => 'jetskimarbella@gmail.com',
        'mjsk_address'        => 'Playa de las Dunas, Urbanización Pinomar, Marbella',
        'mjsk_hours'          => 'Open daily 11:00 – 20:00',
        'mjsk_facebook'       => 'https://www.facebook.com/jetskimarbella/',
        'mjsk_instagram'      => 'https://www.instagram.com/jetskimarbella/',
        'mjsk_tiktok'         => 'https://www.tiktok.com/@jetskimarbella',
        'mjsk_youtube'        => 'https://www.youtube.com/@marbellajetski',
        'mjsk_tripadvisor'    => 'https://www.tripadvisor.es/Attraction_Review-g187439-d6949698-Reviews-Marbella_Jet_Ski-Marbella_Costa_del_Sol_Province_of_Malaga_Andalucia.html',
        'mjsk_promo_enabled'  => true,
        'mjsk_promo_title'    => 'Book Before Summer & Save 10%!',
        'mjsk_promo_text'     => 'Early bird discount on all jet ski & yacht bookings for June–September 2026',
    ];

    $mod_key = str_replace( 'mjsk_', '', $key );
    $value   = get_theme_mod( $mod_key, null );

    if ( $value !== null ) return $value;
    return $defaults[ $key ] ?? '';
}

/* ═══════════════════════════════════════════════════════════════
   9.  TRANSLATIONS
   ═══════════════════════════════════════════════════════════════ */

function mjsk_t( $key ) {
    $lang = mjsk_get_lang();

    $strings = [
        'quick_links'   => [ 'en' => 'Quick Links',    'es' => 'Enlaces Rápidos',  'fr' => 'Liens Rapides',   'nl' => 'Snelle Links' ],
        'services'      => [ 'en' => 'Services',       'es' => 'Servicios',        'fr' => 'Services',        'nl' => 'Diensten' ],
        'jetski'        => [ 'en' => 'Jet Ski',        'es' => 'Motos de Agua',    'fr' => 'Jet Ski',         'nl' => 'Jetski' ],
        'watersports'   => [ 'en' => 'Water Sports',   'es' => 'Deportes Acuáticos','fr' => 'Sports Nautiques','nl' => 'Watersporten' ],
        'yachts'        => [ 'en' => 'Yachts',         'es' => 'Yates',            'fr' => 'Yachts',          'nl' => 'Jachten' ],
        'about'         => [ 'en' => 'About Us',       'es' => 'Sobre Nosotros',   'fr' => 'À Propos',        'nl' => 'Over Ons' ],
        'racing'        => [ 'en' => 'Racing',         'es' => 'Competición',       'fr' => 'Course',          'nl' => 'Racen' ],
        'book_now'      => [ 'en' => 'Book Now',       'es' => 'Reservar',         'fr' => 'Réserver',        'nl' => 'Boeken' ],
        'information'   => [ 'en' => 'Information',    'es' => 'Información',      'fr' => 'Informations',    'nl' => 'Informatie' ],
        'faq'           => [ 'en' => 'FAQ',            'es' => 'Preguntas Frecuentes', 'fr' => 'FAQ',         'nl' => 'Veelgestelde Vragen' ],
        'book_online'   => [ 'en' => 'Book Online',    'es' => 'Reservar Online',  'fr' => 'Réserver en Ligne','nl' => 'Online Boeken' ],
        'legal'         => [ 'en' => 'Legal Notice',   'es' => 'Aviso Legal',      'fr' => 'Mentions Légales','nl' => 'Juridische Kennisgeving' ],
        'terms_cond'    => [ 'en' => 'Terms & Conditions', 'es' => 'Términos y Condiciones', 'fr' => 'Conditions Générales', 'nl' => 'Algemene Voorwaarden' ],
        'privacy'       => [ 'en' => 'Privacy Policy', 'es' => 'Política de Privacidad', 'fr' => 'Politique de Confidentialité', 'nl' => 'Privacybeleid' ],
        'cancel'        => [ 'en' => 'Cancellation',   'es' => 'Cancelación',      'fr' => 'Annulation',      'nl' => 'Annulering' ],
        'weather'       => [ 'en' => 'Weather Policy', 'es' => 'Política Meteorológica', 'fr' => 'Politique Météo', 'nl' => 'Weerbeleid' ],
        'cookies'       => [ 'en' => 'Cookies',        'es' => 'Cookies',          'fr' => 'Cookies',         'nl' => 'Cookies' ],
        'contact_us'    => [ 'en' => 'Contact Us',     'es' => 'Contáctanos',      'fr' => 'Contactez-nous',  'nl' => 'Neem Contact Op' ],
        'designed'      => [
            'en' => 'Designed with 💙 for Summer 2026',
            'es' => 'Diseñado con 💙 para Verano 2026',
            'fr' => 'Conçu avec 💙 pour l\'Été 2026',
            'nl' => 'Ontworpen met 💙 voor Zomer 2026',
        ],
        'chat_msg' => [
            'en' => 'Hi! I\'d like to book a water sports experience!',
            'es' => '¡Hola! Me gustaría reservar una experiencia de deportes acuáticos.',
            'fr' => 'Bonjour ! Je souhaite réserver une activité nautique !',
            'nl' => 'Hallo! Ik wil graag een watersportervaring boeken!',
        ],
        'chat_tooltip' => [
            'en' => 'Chat with us!',
            'es' => '¡Escríbenos!',
            'fr' => 'Écrivez-nous !',
            'nl' => 'Chat met ons!',
        ],
        'promo_title' => [
            'en' => 'Book Before May 15th & Save 10%!',
            'es' => '¡Reserva Antes del 15 de Mayo y Ahorra un 10%!',
            'fr' => 'Réservez Avant le 15 Mai et Économisez 10% !',
            'nl' => 'Boek Vóór 15 Mei en Bespaar 10%!',
        ],
        'promo_text' => [
            'en' => 'Use code EARLYBIRD at checkout — offer ends May 15th',
            'es' => 'Usa el código EARLYBIRD al reservar — la oferta termina el 15 de mayo',
            'fr' => 'Utilisez le code EARLYBIRD — offre valable jusqu\'au 15 mai',
            'nl' => 'Gebruik code EARLYBIRD bij het boeken — aanbieding geldig tot 15 mei',
        ],
        'brand_desc' => [
            'en' => 'Costa del Sol\'s premier water sports destination. Family-owned since 1998, led by championship-winning pilots.',
            'es' => 'El destino número uno de deportes acuáticos en la Costa del Sol. Empresa familiar desde 1998, dirigida por pilotos campeones.',
            'fr' => 'La destination nautique de référence sur la Costa del Sol. Entreprise familiale depuis 1998, dirigée par des pilotes champions.',
            'nl' => 'Dé bestemming voor watersporten aan de Costa del Sol. Familiebedrijf sinds 1998, geleid door kampioenpiloten.',
        ],
        'all_rights' => [
            'en' => 'All rights reserved.',
            'es' => 'Todos los derechos reservados.',
            'fr' => 'Tous droits réservés.',
            'nl' => 'Alle rechten voorbehouden.',
        ],
        'hours' => [
            'en' => 'Open daily 11:00 – 20:00',
            'es' => 'Abierto todos los días 11:00 – 20:00',
            'fr' => 'Ouvert tous les jours 11:00 – 20:00',
            'nl' => 'Dagelijks geopend 11:00 – 20:00',
        ],
        'back_to_top' => [
            'en' => 'Back to top',
            'es' => 'Volver arriba',
            'fr' => 'Retour en haut',
            'nl' => 'Terug naar boven',
        ],
        'lessons' => [
            'en' => 'Lessons',
            'es' => 'Clases',
            'fr' => 'Cours',
            'nl' => 'Lessen',
        ],
        'skip_content' => [
            'en' => 'Skip to content',
            'es' => 'Saltar al contenido',
            'fr' => 'Aller au contenu',
            'nl' => 'Naar inhoud',
        ],
        'your_booking' => [
            'en' => 'Your booking',
            'es' => 'Tu reserva',
            'fr' => 'Votre réservation',
            'nl' => 'Uw boeking',
        ],
        'toggle_nav' => [
            'en' => 'Toggle navigation',
            'es' => 'Abrir menú',
            'fr' => 'Menu',
            'nl' => 'Menu',
        ],
        'language' => [
            'en' => 'Language',
            'es' => 'Idioma',
            'fr' => 'Langue',
            'nl' => 'Taal',
        ],
        'blog' => [
            'en' => 'Blog',
            'es' => 'Blog',
            'fr' => 'Blog',
            'nl' => 'Blog',
        ],
        'blog_desc' => [
            'en' => 'News, tips & guides about watersports in Marbella',
            'es' => 'Noticias, consejos y guías sobre deportes acuáticos en Marbella',
            'fr' => 'Actualités, conseils et guides sur les sports nautiques à Marbella',
            'nl' => 'Nieuws, tips en gidsen over watersporten in Marbella',
        ],
        'blog_no_posts_title' => [
            'en' => 'No posts found',
            'es' => 'No se encontraron artículos',
            'fr' => 'Aucun article trouvé',
            'nl' => 'Geen artikelen gevonden',
        ],
        'blog_no_posts_text' => [
            'en' => 'Check back soon — we\'re preparing amazing content.',
            'es' => 'Vuelve pronto, estamos preparando contenido increíble.',
            'fr' => 'Revenez bientôt — nous préparons du contenu incroyable.',
            'nl' => 'Kom snel terug — we bereiden geweldige content voor.',
        ],
        'blog_back_home' => [
            'en' => 'Back to Home',
            'es' => 'Volver al Inicio',
            'fr' => 'Retour à l\'Accueil',
            'nl' => 'Terug naar Home',
        ],
        'blog_categories' => [
            'en' => 'Categories',
            'es' => 'Categorías',
            'fr' => 'Catégories',
            'nl' => 'Categorieën',
        ],
        'blog_cta_title' => [
            'en' => 'Ready for Adventure?',
            'es' => '¿Listo para la Aventura?',
            'fr' => 'Prêt pour l\'Aventure ?',
            'nl' => 'Klaar voor Avontuur?',
        ],
        'blog_cta_text' => [
            'en' => 'Book your water experience today.',
            'es' => 'Reserva tu experiencia acuática hoy.',
            'fr' => 'Réservez votre expérience nautique aujourd\'hui.',
            'nl' => 'Boek vandaag nog je waterervaring.',
        ],
        'blog_pagination' => [
            'en' => 'Pagination',
            'es' => 'Paginación',
            'fr' => 'Pagination',
            'nl' => 'Paginering',
        ],
        'blog_prev' => [
            'en' => 'Previous',
            'es' => 'Anterior',
            'fr' => 'Précédent',
            'nl' => 'Vorige',
        ],
        'blog_next' => [
            'en' => 'Next',
            'es' => 'Siguiente',
            'fr' => 'Suivant',
            'nl' => 'Volgende',
        ],
        'blog_category' => [
            'en' => 'Category',
            'es' => 'Categoría',
            'fr' => 'Catégorie',
            'nl' => 'Categorie',
        ],
        'blog_tag' => [
            'en' => 'Tag',
            'es' => 'Etiqueta',
            'fr' => 'Étiquette',
            'nl' => 'Tag',
        ],
        'blog_author' => [
            'en' => 'Author',
            'es' => 'Autor',
            'fr' => 'Auteur',
            'nl' => 'Auteur',
        ],
        'blog_archive' => [
            'en' => 'Archive',
            'es' => 'Archivo',
            'fr' => 'Archives',
            'nl' => 'Archief',
        ],
        'blog_written_by' => [
            'en' => 'Written by',
            'es' => 'Escrito por',
            'fr' => 'Écrit par',
            'nl' => 'Geschreven door',
        ],
        'blog_recent' => [
            'en' => 'Recent Posts',
            'es' => 'Artículos Recientes',
            'fr' => 'Articles Récents',
            'nl' => 'Recente Artikelen',
        ],
        'home' => [
            'en' => 'Home',
            'es' => 'Inicio',
            'fr' => 'Accueil',
            'nl' => 'Home',
        ],
    ];

    if ( isset( $strings[ $key ][ $lang ] ) ) {
        return $strings[ $key ][ $lang ];
    }
    // Fallback to English
    return $strings[ $key ]['en'] ?? $key;
}

/* ═══════════════════════════════════════════════════════════════
   10. PAGE CONTENT LOADER
   ═══════════════════════════════════════════════════════════════ */

/**
 * Load an HTML content file from page-content/ directory.
 * Returns the HTML string with template directory URLs resolved.
 */
function mjsk_load_page_content( $filename ) {
    $filepath = get_template_directory() . '/page-content/' . $filename;
    if ( ! file_exists( $filepath ) ) return '';

    ob_start();
    include $filepath;
    return ob_get_clean();
}

/**
 * Map the current page to its content file.
 */
function mjsk_get_content_file_for_page() {
    $slug = get_post_field( 'post_name', get_the_ID() );
    $lang = mjsk_get_lang();

    // Language homepages: slug = 'es', 'fr', 'nl'
    if ( in_array( $slug, [ 'es', 'fr', 'nl' ], true ) ) {
        return $slug . '-home.html';
    }

    // Check for service landing page content file first
    $prefix = ( $lang !== 'en' ) ? $lang . '-' : '';
    $service_file = $prefix . 'service-' . $slug . '.html';
    $service_path = get_template_directory() . '/page-content/' . $service_file;
    if ( file_exists( $service_path ) ) {
        return $service_file;
    }

    // English pages: booking, terms, weather-policy, about-us, lessons
    if ( $lang === 'en' ) {
        return $slug . '.html';
    }

    // Non-English subpages: es-booking.html, fr-terms.html, etc.
    return $lang . '-' . $slug . '.html';
}

/* ═══════════════════════════════════════════════════════════════
   11. ENQUEUE STYLES & SCRIPTS
   ═══════════════════════════════════════════════════════════════ */

function mjsk_enqueue_assets() {
    // Main stylesheet
    wp_enqueue_style(
        'mjsk-main',
        get_template_directory_uri() . '/assets/css/main.min.css',
        [],
        filemtime( get_template_directory() . '/assets/css/main.min.css' )
    );

    // Font Awesome 6
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        [],
        '6.5.1'
    );

    // Google Fonts — Montserrat (primary) + Space Grotesk (accent) + Playfair Display (serif)
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@500;600;700&family=Playfair+Display:wght@700;800;900&display=swap',
        [],
        null
    );

    // Swiper carousel
    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        '11'
    );
    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        '11',
        true
    );

    // AOS (Animate On Scroll)
    wp_enqueue_style(
        'aos-css',
        'https://unpkg.com/aos@2.3.1/dist/aos.css',
        [],
        '2.3.1'
    );
    wp_enqueue_script(
        'aos-js',
        'https://unpkg.com/aos@2.3.1/dist/aos.js',
        [],
        '2.3.1',
        true
    );

    // Main theme script
    wp_enqueue_script(
        'mjsk-script',
        get_template_directory_uri() . '/assets/js/script.min.js',
        [],
        filemtime( get_template_directory() . '/assets/js/script.min.js' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'mjsk_enqueue_assets' );

/**
 * Defer non-critical CSS (AOS, Swiper, Font Awesome) to eliminate render-blocking.
 * Google Fonts already uses &display=swap; AOS/Swiper/FA are not needed for initial paint.
 */
function mjsk_defer_noncritical_css( $html, $handle ) {
    $defer_handles = [ 'aos-css', 'swiper-css', 'font-awesome', 'google-fonts' ];

    if ( in_array( $handle, $defer_handles, true ) && ! is_admin() ) {
        // Replace media="all" with media="print" + onload swap
        $html = str_replace(
            "media='all'",
            "media='print' onload=\"this.media='all'\"",
            $html
        );
        // Add noscript fallback
        $noscript = '<noscript>' . str_replace(
            [ "media='print'", " onload=\"this.media='all'\"" ],
            [ "media='all'", '' ],
            $html
        ) . '</noscript>';
        $html .= $noscript;
    }

    return $html;
}
add_filter( 'style_loader_tag', 'mjsk_defer_noncritical_css', 10, 2 );

/**
 * Add defer attribute to non-critical JavaScript loaded in footer.
 */
function mjsk_defer_scripts( $tag, $handle ) {
    $defer_handles = [ 'swiper-js', 'aos-js', 'mjsk-script' ];
    if ( in_array( $handle, $defer_handles, true ) && ! is_admin() ) {
        $tag = str_replace( ' src=', ' defer src=', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'mjsk_defer_scripts', 10, 2 );

/**
 * Preload LCP image on homepage for faster paint.
 */
function mjsk_preload_lcp() {
    if ( is_front_page() || mjsk_is_homepage() ) {
        echo '<link rel="preload" as="image" href="/wp-content/themes/marbellajetski/assets/media/photos/wp-uploads/18328-mar-scaled.webp" type="image/webp" fetchpriority="high" />' . "\n";
    }
}
add_action( 'wp_head', 'mjsk_preload_lcp', 3 );

/* ═══════════════════════════════════════════════════════════════
   12. THEME SETUP
   ═══════════════════════════════════════════════════════════════ */

function mjsk_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );

    // WooCommerce support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Register navigation menus
    register_nav_menus( [
        'primary-en' => 'Primary Navigation (English)',
        'primary-es' => 'Primary Navigation (Español)',
        'primary-fr' => 'Primary Navigation (Français)',
        'primary-nl' => 'Primary Navigation (Nederlands)',
        'footer-en'  => 'Footer Navigation (English)',
        'footer-es'  => 'Footer Navigation (Español)',
        'footer-fr'  => 'Footer Navigation (Français)',
        'footer-nl'  => 'Footer Navigation (Nederlands)',
    ] );
}
add_action( 'after_setup_theme', 'mjsk_theme_setup' );

/* ═══════════════════════════════════════════════════════════════
   13. CUSTOMIZER SECTIONS
   ═══════════════════════════════════════════════════════════════ */

function mjsk_customizer( $wp_customize ) {
    // Contact Details section
    $wp_customize->add_section( 'mjsk_contact', [
        'title'    => 'Contact Details',
        'priority' => 30,
    ] );

    $fields = [
        'phone'     => 'Phone Number',
        'whatsapp'  => 'WhatsApp Number',
        'email'     => 'Email Address',
        'address'   => 'Address',
        'hours'     => 'Opening Hours',
    ];

    foreach ( $fields as $id => $label ) {
        $wp_customize->add_setting( $id, [ 'default' => '' ] );
        $wp_customize->add_control( $id, [
            'label'   => $label,
            'section' => 'mjsk_contact',
            'type'    => 'text',
        ] );
    }

    // Social Media section
    $wp_customize->add_section( 'mjsk_social', [
        'title'    => 'Social Media',
        'priority' => 31,
    ] );

    $socials = [ 'facebook', 'instagram', 'tiktok', 'youtube', 'tripadvisor' ];
    foreach ( $socials as $s ) {
        $wp_customize->add_setting( $s, [ 'default' => '' ] );
        $wp_customize->add_control( $s, [
            'label'   => ucfirst( $s ) . ' URL',
            'section' => 'mjsk_social',
            'type'    => 'url',
        ] );
    }

    // Promo Banner section
    $wp_customize->add_section( 'mjsk_promo', [
        'title'    => 'Promo Banner',
        'priority' => 32,
    ] );

    $wp_customize->add_setting( 'promo_enabled', [ 'default' => true ] );
    $wp_customize->add_control( 'promo_enabled', [
        'label'   => 'Enable Promo Banner',
        'section' => 'mjsk_promo',
        'type'    => 'checkbox',
    ] );

    $wp_customize->add_setting( 'promo_title', [ 'default' => '' ] );
    $wp_customize->add_control( 'promo_title', [
        'label'   => 'Promo Title',
        'section' => 'mjsk_promo',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'promo_text', [ 'default' => '' ] );
    $wp_customize->add_control( 'promo_text', [
        'label'   => 'Promo Description',
        'section' => 'mjsk_promo',
        'type'    => 'textarea',
    ] );
}
add_action( 'customize_register', 'mjsk_customizer' );

/* ═══════════════════════════════════════════════════════════════
   14. AUTO-SETUP  (create pages on first activation)
   ═══════════════════════════════════════════════════════════════ */

function mjsk_auto_setup() {
    if ( get_option( 'mjsk_setup_done' ) ) return;

    // Page definitions: slug => [ title, parent_slug ]
    $pages = [
        'home'             => [ 'Home', '' ],
        'booking'          => [ 'Book Now', '' ],
        'terms'            => [ 'Terms', '' ],
        'weather-policy'   => [ 'Weather Policy', '' ],
        'about-us'         => [ 'About Us', '' ],
        'lessons'          => [ 'Lessons', '' ],
        'blog'             => [ 'Blog', '' ],
        'es'               => [ 'Inicio', '' ],
        'fr'               => [ 'Accueil', '' ],
        'nl'               => [ 'Home NL', '' ],
    ];

    // Language sub-pages
    $lang_pages = [
        'es' => [
            'booking'        => 'Reservar',
            'terms'          => 'Términos',
            'weather-policy' => 'Política Clima',
            'about-us'       => 'Sobre Nosotros',
            'lessons'        => 'Clases',
            'blog'           => 'Blog',
        ],
        'fr' => [
            'booking'        => 'Réserver',
            'terms'          => 'Conditions',
            'weather-policy' => 'Politique Météo',
            'about-us'       => 'À Propos',
            'lessons'        => 'Cours',
            'blog'           => 'Blog',
        ],
        'nl' => [
            'booking'        => 'Boeken',
            'terms'          => 'Voorwaarden',
            'weather-policy' => 'Weerbeleid',
            'about-us'       => 'Over Ons',
            'lessons'        => 'Lessen',
            'blog'           => 'Blog',
        ],
    ];

    $created = [];

    // Create top-level pages
    foreach ( $pages as $slug => $info ) {
        $existing = get_page_by_path( $slug );
        if ( $existing ) {
            $created[ $slug ] = $existing->ID;
            continue;
        }

        $id = wp_insert_post( [
            'post_title'  => $info[0],
            'post_name'   => $slug,
            'post_type'   => 'page',
            'post_status' => 'publish',
        ] );

        if ( ! is_wp_error( $id ) ) $created[ $slug ] = $id;
    }

    // Create language sub-pages
    foreach ( $lang_pages as $lang => $subpages ) {
        $parent_id = $created[ $lang ] ?? 0;
        foreach ( $subpages as $slug => $title ) {
            $full_path = $lang . '/' . $slug;
            $existing  = get_page_by_path( $full_path );
            if ( $existing ) continue;

            wp_insert_post( [
                'post_title'  => $title,
                'post_name'   => $slug,
                'post_parent' => $parent_id,
                'post_type'   => 'page',
                'post_status' => 'publish',
            ] );
        }
    }

    // Set homepage
    if ( isset( $created['home'] ) ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $created['home'] );
    }

    // Assign blog template (do NOT set page_for_posts — our custom template handles the query)
    if ( isset( $created['blog'] ) ) {
        update_post_meta( $created['blog'], '_wp_page_template', 'page-blog.php' );
    }
    // Assign blog template to language sub-pages
    foreach ( ['es', 'fr', 'nl'] as $blang ) {
        $blog_page = get_page_by_path( $blang . '/blog' );
        if ( $blog_page ) {
            update_post_meta( $blog_page->ID, '_wp_page_template', 'page-blog.php' );
        }
    }

    // Set permalinks
    update_option( 'permalink_structure', '/%postname%/' );

    // ── Service Landing Pages ──────────────────────────────────────
    // EN services (top-level): slug => title
    $en_services = [
        'jet-ski'                        => 'Jet Ski Adventures',
        'water-activities'               => 'Water Activities',
        'boat-hire'                      => 'Boat Hire',
        'closed-circuit-jet-ski'         => 'Closed Circuit Jet Ski',
        'jet-ski-excursion-fuengirola'   => 'Jet Ski Excursion to Fuengirola',
        'jet-ski-tour-marbella'          => 'Jet Ski Tour Marbella',
        'jet-ski-tour-puerto-banus'      => 'Jet Ski Tour Puerto Banús',
        'wakeboarding-experience'       => 'Wakeboarding Experience',
        'water-skiing-marbella'          => 'Water Skiing in Marbella',
        'pedal-boat'                     => 'Pedal Boat Rental',
        'donut-watersports'              => 'Donut Ride',
        'crazy-sofa-ride'                => 'Crazy Sofa Ride',
        'banana-boat-ride'               => 'Banana Boat Ride',
        'air-stream-marbella'            => 'Air Stream Fly Board',
        'water-bull-ride'                => 'Water Bull Ride',
        'paddleboarding-marbella'        => 'Paddle Surf (SUP) in Marbella',
        'yacht-charter-marbella'         => 'Yacht Charter Marbella',
        'azimut-39-fly'                  => 'Azimut 39 Fly Charter',
        'sea-ray-240-sundeck'            => 'Sea Ray 240 Sundeck Charter',
        'cranchi-endurance-boat-charter' => 'Cranchi Endurance 39 Charter',
        'catamaran-bali-charter'         => 'Catamaran Bali 4.0 Charter',
    ];

    // ES services (under /es/ parent): slug => title
    $es_services = [
        'motos-de-agua'                    => 'Motos de Agua',
        'actividades-acuaticas'            => 'Actividades Acuáticas',
        'alquiler-barcos'                  => 'Alquiler de Barcos',
        'circuito-cerrado-motos-acuaticas' => 'Circuito Cerrado Motos Acuáticas',
        'excursion-moto-agua-fuengirola'   => 'Excursión Moto de Agua Fuengirola',
        'excursion-moto-agua-marbella'     => 'Excursión Moto de Agua Marbella',
        'tour-moto-agua-puerto-banus'      => 'Tour Moto de Agua Puerto Banús',
        'wakeboard'                        => 'Wakeboard',
        'esqui-acuatico'                   => 'Esquí Acuático',
        'hidropedal-marbella'              => 'Hidropedal en Marbella',
        'donut-acuatico'                   => 'Donut Acuático',
        'sofa-loco'                        => 'Sofá Loco',
        'banana-boat'                      => 'Banana Boat',
        'air-stream-en-marbella'           => 'Air Stream en Marbella',
        'water-bull'                       => 'Water Bull',
        'paddle-surf'                      => 'Paddle Surf',
        'alquiler-yate-marbella'           => 'Alquiler de Yate en Marbella',
        'azimut-39-fly'                    => 'Azimut 39 Fly',
        'sea-ray-sundeck'                  => 'Sea Ray 240 Sundeck',
        'cranchi-endurance'                => 'Cranchi Endurance 39',
        'catamaran-bali'                   => 'Catamarán Bali 4.0',
    ];

    $template_file = 'page-service-landing.php';

    // Create EN service pages (top-level)
    foreach ( $en_services as $slug => $title ) {
        $existing = get_page_by_path( $slug );
        if ( $existing ) {
            // Ensure template is set
            update_post_meta( $existing->ID, '_wp_page_template', $template_file );
            continue;
        }
        $id = wp_insert_post( [
            'post_title'  => $title,
            'post_name'   => $slug,
            'post_type'   => 'page',
            'post_status' => 'publish',
        ] );
        if ( ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', $template_file );
        }
    }

    // Create ES service pages (under /es/ parent)
    $es_parent_id = $created['es'] ?? 0;
    foreach ( $es_services as $slug => $title ) {
        $full_path = 'es/' . $slug;
        $existing  = get_page_by_path( $full_path );
        if ( $existing ) {
            update_post_meta( $existing->ID, '_wp_page_template', $template_file );
            continue;
        }
        $id = wp_insert_post( [
            'post_title'  => $title,
            'post_name'   => $slug,
            'post_parent' => $es_parent_id,
            'post_type'   => 'page',
            'post_status' => 'publish',
        ] );
        if ( ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', $template_file );
        }
    }

    // FR services (under /fr/ parent): slug => title
    $fr_services = [
        'jet-ski'                        => 'Jet Ski à Marbella',
        'activites-nautiques'            => 'Activités Nautiques',
        'location-bateaux'               => 'Location de Bateaux',
        'circuit-ferme-jet-ski'          => 'Circuit Fermé Jet Ski',
        'excursion-jet-ski-fuengirola'   => 'Excursion Jet Ski Fuengirola',
        'tour-jet-ski-marbella'          => 'Tour Jet Ski Marbella',
        'tour-jet-ski-puerto-banus'      => 'Tour Jet Ski Puerto Banús',
        'wakeboard-marbella'             => 'Wakeboard à Marbella',
        'ski-nautique-marbella'          => 'Ski Nautique à Marbella',
        'pedalo-marbella'                => 'Pédalo à Marbella',
        'bouee-donut-marbella'           => 'Bouée Donut à Marbella',
        'crazy-sofa-marbella'            => 'Crazy Sofa à Marbella',
        'banana-boat-marbella'           => 'Banana Boat à Marbella',
        'air-stream-marbella'            => 'Air Stream à Marbella',
        'water-bull-marbella'            => 'Water Bull à Marbella',
        'paddle-surf-marbella'           => 'Paddle Surf à Marbella',
        'location-yacht-marbella'        => 'Location de Yacht à Marbella',
        'sea-ray-240-sundeck-location'   => 'Sea Ray 240 Sundeck Location',
        'cranchi-endurance-39-location'  => 'Cranchi Endurance 39 Location',
        'azimut-39-fly'                  => 'Azimut 39 Fly',
        'catamaran-bali-location'        => 'Catamaran Bali 4.0 Location',
    ];

    $fr_parent_id = $created['fr'] ?? 0;
    foreach ( $fr_services as $slug => $title ) {
        $full_path = 'fr/' . $slug;
        $existing  = get_page_by_path( $full_path );
        if ( $existing ) {
            update_post_meta( $existing->ID, '_wp_page_template', $template_file );
            continue;
        }
        $id = wp_insert_post( [
            'post_title'  => $title,
            'post_name'   => $slug,
            'post_parent' => $fr_parent_id,
            'post_type'   => 'page',
            'post_status' => 'publish',
        ] );
        if ( ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', $template_file );
        }
    }

    // NL services (under /nl/ parent): slug => title
    $nl_services = [
        'jetski'                         => 'Jetski Avonturen',
        'wateractiviteiten'              => 'Wateractiviteiten',
        'boot-huren'                     => 'Boot Huren',
        'gesloten-circuit-jetski'        => 'Gesloten Circuit Jetski',
        'jetski-excursie-fuengirola'     => 'Jetski Excursie Fuengirola',
        'jetski-tour-marbella'           => 'Jetski Tour Marbella',
        'jetski-tour-puerto-banus'       => 'Jetski Tour Puerto Banús',
        'wakeboarden-marbella'           => 'Wakeboarden in Marbella',
        'waterskien-marbella'            => 'Waterskiën in Marbella',
        'waterfiets-marbella'            => 'Waterfiets in Marbella',
        'donut-ride-marbella'            => 'Donut Ride in Marbella',
        'crazy-sofa-marbella'            => 'Crazy Sofa in Marbella',
        'bananenboot-marbella'           => 'Bananenboot in Marbella',
        'air-stream-marbella'            => 'Air Stream in Marbella',
        'water-bull-marbella'            => 'Water Bull in Marbella',
        'suppen-marbella'                => 'Suppen (SUP) in Marbella',
        'jacht-huren-marbella'           => 'Jacht Huren in Marbella',
        'sea-ray-240-sundeck-huren'      => 'Sea Ray 240 Sundeck Huren',
        'cranchi-endurance-39-huren'     => 'Cranchi Endurance 39 Huren',
        'azimut-39-fly'                  => 'Azimut 39 Fly',
        'catamaran-bali-huren'           => 'Catamaran Bali 4.0 Huren',
    ];

    $nl_parent_id = $created['nl'] ?? 0;
    foreach ( $nl_services as $slug => $title ) {
        $full_path = 'nl/' . $slug;
        $existing  = get_page_by_path( $full_path );
        if ( $existing ) {
            update_post_meta( $existing->ID, '_wp_page_template', $template_file );
            continue;
        }
        $id = wp_insert_post( [
            'post_title'  => $title,
            'post_name'   => $slug,
            'post_parent' => $nl_parent_id,
            'post_type'   => 'page',
            'post_status' => 'publish',
        ] );
        if ( ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', $template_file );
        }
    }

    update_option( 'mjsk_setup_done', 1 );
}
add_action( 'after_switch_theme', 'mjsk_auto_setup' );
add_action( 'init', function() {
    if ( ! get_option( 'mjsk_setup_done' ) ) mjsk_auto_setup();
} );

/* ═══════════════════════════════════════════════════════════════
   15. FLUSH REWRITE RULES (once after setup)
   ═══════════════════════════════════════════════════════════════ */

function mjsk_maybe_flush() {
    if ( get_option( 'mjsk_flush_done' ) ) return;
    flush_rewrite_rules();
    update_option( 'mjsk_flush_done', 1 );
}
add_action( 'init', 'mjsk_maybe_flush', 99 );

/* ═══════════════════════════════════════════════════════════════
   16. CLEANUP <head>
   ═══════════════════════════════════════════════════════════════ */

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

/* Remove WordPress emoji scripts & styles — saves ~15KB of render-blocking inline JS */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
add_filter( 'emoji_svg_url', '__return_false' );

/* Remove WordPress block library CSS (not using Gutenberg blocks on frontend) */
function mjsk_remove_wp_block_css() {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' );
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'mjsk_remove_wp_block_css', 100 );

/* Remove global styles inline CSS */
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

/**
 * Security headers — improves Lighthouse Best Practices score.
 */
function mjsk_security_headers() {
    if ( is_admin() ) return;
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
}
add_action( 'send_headers', 'mjsk_security_headers' );

/* ═══════════════════════════════════════════════════════════════
   16b. WOOCOMMERCE INTEGRATION
   ═══════════════════════════════════════════════════════════════ */

/**
 * WooCommerce content wrappers — match our theme's markup.
 */
function mjsk_wc_wrapper_start() {
    echo '<main id="main-content"><div class="container" style="padding-top:120px;padding-bottom:60px;">';
}
function mjsk_wc_wrapper_end() {
    echo '</div></main>';
}

if ( class_exists( 'WooCommerce' ) ) {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
    add_action( 'woocommerce_before_main_content', 'mjsk_wc_wrapper_start', 10 );
    add_action( 'woocommerce_after_main_content', 'mjsk_wc_wrapper_end', 10 );

    // Disable WooCommerce default styles (we use our own)
    add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

    // Add our WooCommerce CSS
    add_action( 'wp_enqueue_scripts', function() {
        if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
            wp_enqueue_style(
                'mjsk-woocommerce',
                get_template_directory_uri() . '/assets/css/woocommerce.css',
                [ 'mjsk-main' ],
                filemtime( get_template_directory() . '/assets/css/woocommerce.css' )
            );
        }
    } );
}

/* ═══════════════════════════════════════════════════════════════
   16c. SERVICE LANDING PAGE DETECTION
   ═══════════════════════════════════════════════════════════════ */

/**
 * Check if current page uses the service landing page template.
 */
function mjsk_is_service_page() {
    $slug = get_post_field( 'post_name', get_the_ID() );
    $lang  = mjsk_get_lang();
    $prefix = ( $lang !== 'en' ) ? $lang . '-' : '';
    $file   = $prefix . 'service-' . $slug . '.html';
    $path   = get_template_directory() . '/page-content/' . $file;
    return file_exists( $path );
}

/* ═══════════════════════════════════════════════════════════════
   16d. BLOG SUPPORT
   ═══════════════════════════════════════════════════════════════ */

/**
 * Estimated reading time for blog posts.
 */
function mjsk_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $words   = str_word_count( strip_tags( $content ) );
    $minutes = max( 1, ceil( $words / 200 ) );
    $lang    = mjsk_get_lang();
    return $minutes . ' min ' . ( $lang === 'es' ? 'de lectura' : 'read' );
}

/**
 * Register blog sidebar widget area.
 */
function mjsk_register_sidebars() {
    register_sidebar( [
        'name'          => 'Blog Sidebar',
        'id'            => 'blog-sidebar',
        'description'   => 'Widgets shown on blog archive and single post pages.',
        'before_widget' => '<div id="%1$s" class="blog-sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ] );
}
add_action( 'widgets_init', 'mjsk_register_sidebars' );

/**
 * Enqueue blog CSS on blog pages.
 */
function mjsk_blog_assets() {
    if ( is_single() || is_archive() || is_search() || is_home()
         || is_page_template( 'page-blog.php' ) ) {
        wp_enqueue_style(
            'mjsk-blog',
            get_template_directory_uri() . '/assets/css/blog.css',
            [ 'mjsk-main' ],
            filemtime( get_template_directory() . '/assets/css/blog.css' )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'mjsk_blog_assets' );

/* ═══════════════════════════════════════════════════════════════
   17. HREFLANG TAGS
   ═══════════════════════════════════════════════════════════════ */

function mjsk_hreflang_tags() {
    // Skip if Polylang is active — it handles hreflang
    if ( function_exists( 'pll_the_languages' ) ) {
        return;
    }

    $langs = [
        'en' => 'en',
        'es' => 'es',
        'fr' => 'fr',
        'nl' => 'nl',
    ];

    echo "\n";
    foreach ( $langs as $code => $hreflang ) {
        $url = mjsk_get_page_in_lang( $code );
        echo '<link rel="alternate" hreflang="' . esc_attr( $hreflang ) . '" href="' . esc_url( $url ) . '" />' . "\n";
    }
    // x-default → English
    $en_url = mjsk_get_page_in_lang( 'en' );
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $en_url ) . '" />' . "\n";
}
add_action( 'wp_head', 'mjsk_hreflang_tags', 1 );

/* ═══════════════════════════════════════════════════════════════
   17b. META DESCRIPTION TAG (SEO)
   ═══════════════════════════════════════════════════════════════ */

function mjsk_meta_description() {
    // Skip if an SEO plugin handles this
    if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) ) {
        return;
    }

    $lang = mjsk_get_lang();
    $desc = '';

    if ( is_front_page() || mjsk_is_homepage() ) {
        $descriptions = [
            'en' => 'Jet ski tours, water activities, boat hire and yacht charters in Marbella, Costa del Sol. Book your adventure online — best prices guaranteed.',
            'es' => 'Excursiones en moto de agua, actividades acuáticas, alquiler de barcos y yates en Marbella, Costa del Sol. Reserva tu aventura online.',
            'fr' => 'Excursions en jet ski, activités nautiques, location de bateaux et yachts à Marbella, Costa del Sol. Réservez votre aventure en ligne.',
            'nl' => 'Jetski-tochten, wateractiviteiten, bootje huren en jachtverhuur in Marbella, Costa del Sol. Boek je avontuur online.',
        ];
        $desc = $descriptions[ $lang ] ?? $descriptions['en'];
    } elseif ( is_archive() || is_home() ) {
        $descriptions_blog = [
            'en' => 'Tips, guides and news about jet skiing, water sports and boating in Marbella and the Costa del Sol.',
            'es' => 'Consejos, guías y noticias sobre motos de agua, deportes acuáticos y navegación en Marbella y la Costa del Sol.',
            'fr' => 'Conseils, guides et actualités sur le jet ski, les sports nautiques et la navigation à Marbella et sur la Costa del Sol.',
            'nl' => 'Tips, gidsen en nieuws over jetskiën, watersporten en varen in Marbella en aan de Costa del Sol.',
        ];
        $desc = $descriptions_blog[ $lang ] ?? $descriptions_blog['en'];
    } elseif ( is_singular() ) {
        $post = get_queried_object();
        // Try excerpt or content first
        if ( $post && ! empty( $post->post_excerpt ) ) {
            $desc = wp_strip_all_tags( $post->post_excerpt );
        } elseif ( $post && ! empty( $post->post_content ) ) {
            $desc = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 25, '…' );
        }

        // Fallback: generate description from page slug + language for template-based pages
        if ( empty( $desc ) && $post ) {
            $desc = mjsk_slug_description( $post->post_name, $lang );
        }
    }

    // Ultimate fallback for any page
    if ( empty( $desc ) ) {
        $fallbacks = [
            'en' => 'Marbella JetSki — jet ski tours, water sports, boat hire and yacht charters on the Costa del Sol, Spain.',
            'es' => 'Marbella JetSki — excursiones en moto de agua, deportes acuáticos, alquiler de barcos y yates en la Costa del Sol.',
            'fr' => 'Marbella JetSki — excursions en jet ski, sports nautiques, location de bateaux et yachts sur la Costa del Sol.',
            'nl' => 'Marbella JetSki — jetski-tochten, watersporten, bootverhuur en jachtverhuur aan de Costa del Sol.',
        ];
        $desc = $fallbacks[ $lang ] ?? $fallbacks['en'];
    }

    $desc = mb_substr( $desc, 0, 160 );
    echo '<meta name="description" content="' . esc_attr( $desc ) . '" />' . "\n";
}
add_action( 'wp_head', 'mjsk_meta_description', 2 );

/**
 * Generate a meta description based on page slug and language.
 * Covers all template-based pages that have no WP post_content.
 */
function mjsk_slug_description( $slug, $lang ) {
    // Slug → description keyword mapping (patterns checked via strpos/match)
    $map = [
        // Booking
        'booking' => [
            'en' => 'Book your jet ski tour, water activity or boat hire in Marbella online. Instant confirmation and best price guarantee.',
            'es' => 'Reserva tu excursión en moto de agua, actividad acuática o alquiler de barco en Marbella online. Confirmación instantánea.',
            'fr' => 'Réservez votre excursion en jet ski, activité nautique ou location de bateau à Marbella en ligne. Confirmation instantanée.',
            'nl' => 'Boek je jetski-tour, wateractiviteit of bootverhuur in Marbella online. Directe bevestiging en beste prijs.',
        ],
        // Lessons
        'lessons' => [
            'en' => 'Professional jet ski racing lessons in Marbella with a 4x National Champion. Circuit, offshore and competition techniques.',
            'es' => 'Clases profesionales de motos de agua en Marbella con un 4 veces Campeón Nacional. Técnicas de circuito, offshore y competición.',
            'fr' => 'Cours de jet ski professionnels à Marbella avec un 4x Champion National. Techniques de circuit, offshore et compétition.',
            'nl' => 'Professionele jetski-lessen in Marbella met een 4x Nationaal Kampioen. Circuit-, offshore- en wedstrijdtechnieken.',
        ],
        // About
        'about' => [
            'en' => 'About Marbella JetSki — meet our team of water sports professionals on the Costa del Sol. Safety, fun and unforgettable experiences.',
            'es' => 'Sobre Marbella JetSki — conoce a nuestro equipo de profesionales de deportes acuáticos en la Costa del Sol.',
            'fr' => 'À propos de Marbella JetSki — découvrez notre équipe de professionnels des sports nautiques sur la Costa del Sol.',
            'nl' => 'Over Marbella JetSki — maak kennis met ons team van watersportprofessionals aan de Costa del Sol.',
        ],
        // Weather policy / terms
        'weather' => [
            'en' => 'Marbella JetSki weather and cancellation policy. Free rescheduling when conditions are not suitable for water activities.',
            'es' => 'Política de meteorología y cancelación de Marbella JetSki. Reprogramación gratuita cuando las condiciones no son adecuadas.',
            'fr' => 'Politique météo et annulation de Marbella JetSki. Report gratuit en cas de mauvaises conditions météorologiques.',
            'nl' => 'Weer- en annuleringsbeleid van Marbella JetSki. Gratis omboeken bij ongeschikte weersomstandigheden.',
        ],
        'terms' => [
            'en' => 'Terms and conditions for Marbella JetSki bookings, water activities and boat hire services on the Costa del Sol.',
            'es' => 'Términos y condiciones para reservas, actividades acuáticas y alquiler de barcos en Marbella JetSki.',
            'fr' => 'Conditions générales pour les réservations, activités nautiques et locations de bateaux chez Marbella JetSki.',
            'nl' => 'Algemene voorwaarden voor boekingen, wateractiviteiten en bootverhuur bij Marbella JetSki.',
        ],
        // Jet ski tours
        'tour-marbella' => [
            'en' => 'Guided jet ski tour along the Marbella coastline. Explore stunning beaches and coves with expert instructors.',
            'es' => 'Excursión guiada en moto de agua por la costa de Marbella. Explora playas y calas con instructores expertos.',
            'fr' => 'Excursion guidée en jet ski le long de la côte de Marbella. Explorez plages et criques avec des moniteurs experts.',
            'nl' => 'Begeleide jetski-tour langs de kust van Marbella. Ontdek stranden en baaien met ervaren instructeurs.',
        ],
        'puerto-banus' => [
            'en' => 'Jet ski tour to Puerto Banús — ride past luxury yachts and the famous marina. An unforgettable Marbella experience.',
            'es' => 'Excursión en moto de agua a Puerto Banús — navega junto a yates de lujo y el famoso puerto deportivo.',
            'fr' => 'Tour en jet ski à Puerto Banús — naviguez devant les yachts de luxe et le célèbre port de plaisance.',
            'nl' => 'Jetski-tour naar Puerto Banús — vaar langs luxe jachten en de beroemde jachthaven.',
        ],
        'fuengirola' => [
            'en' => 'Jet ski excursion from Fuengirola along the Costa del Sol coast. Thrilling ride with stunning Mediterranean views.',
            'es' => 'Excursión en moto de agua desde Fuengirola por la costa de la Costa del Sol. Emocionante paseo con vistas al Mediterráneo.',
            'fr' => 'Excursion en jet ski depuis Fuengirola le long de la Costa del Sol. Balade palpitante avec vue sur la Méditerranée.',
            'nl' => 'Jetski-excursie vanuit Fuengirola langs de Costa del Sol. Spannende rit met prachtig uitzicht op de Middellandse Zee.',
        ],
        'closed-circuit' => [
            'en' => 'Closed circuit jet ski experience in Marbella. Perfect for beginners and families — safe, fun and supervised.',
            'es' => 'Circuito cerrado de motos de agua en Marbella. Perfecto para principiantes y familias — seguro y divertido.',
            'fr' => 'Circuit fermé de jet ski à Marbella. Parfait pour débutants et familles — sûr, amusant et encadré.',
            'nl' => 'Gesloten circuit jetski-ervaring in Marbella. Perfect voor beginners en gezinnen — veilig en leuk.',
        ],
        'circuito-cerrado' => [
            'en' => 'Closed circuit jet ski experience in Marbella. Perfect for beginners and families.',
            'es' => 'Circuito cerrado de motos de agua en Marbella. Perfecto para principiantes y familias — seguro y divertido.',
            'fr' => 'Circuit fermé de jet ski à Marbella. Parfait pour débutants et familles.',
            'nl' => 'Gesloten circuit jetski-ervaring in Marbella. Perfect voor beginners en gezinnen.',
        ],
        'circuit-ferme' => [
            'en' => 'Closed circuit jet ski in Marbella. Perfect for beginners and families.',
            'es' => 'Circuito cerrado de motos de agua en Marbella. Perfecto para principiantes y familias.',
            'fr' => 'Circuit fermé de jet ski à Marbella. Parfait pour les débutants et les familles — sûr, amusant et encadré.',
            'nl' => 'Gesloten circuit jetski in Marbella. Perfect voor beginners en gezinnen.',
        ],
        'gesloten-circuit' => [
            'en' => 'Closed circuit jet ski in Marbella. Perfect for beginners and families.',
            'es' => 'Circuito cerrado de motos de agua en Marbella.',
            'fr' => 'Circuit fermé de jet ski à Marbella.',
            'nl' => 'Gesloten circuit jetski-ervaring in Marbella. Perfect voor beginners en gezinnen — veilig, leuk en begeleid.',
        ],
        // Water activities
        'wakeboard' => [
            'en' => 'Wakeboarding in Marbella — ride the waves with professional equipment and instructors. All levels welcome.',
            'es' => 'Wakeboard en Marbella — surfea las olas con equipo profesional e instructores. Todos los niveles.',
            'fr' => 'Wakeboard à Marbella — surfez les vagues avec équipement professionnel et moniteurs. Tous niveaux.',
            'nl' => 'Wakeboarden in Marbella — surf de golven met professionele uitrusting en instructeurs. Alle niveaus.',
        ],
        'water-ski' => [
            'en' => 'Water skiing in Marbella, Costa del Sol. Professional equipment, expert instructors and perfect Mediterranean conditions.',
            'es' => 'Esquí acuático en Marbella, Costa del Sol. Equipo profesional, instructores expertos y condiciones perfectas.',
            'fr' => 'Ski nautique à Marbella, Costa del Sol. Équipement professionnel, moniteurs experts et conditions parfaites.',
            'nl' => 'Waterskiën in Marbella, Costa del Sol. Professionele uitrusting, ervaren instructeurs en perfecte omstandigheden.',
        ],
        'esqui-acuatico' => [
            'en' => 'Water skiing in Marbella.',
            'es' => 'Esquí acuático en Marbella, Costa del Sol. Equipo profesional, instructores expertos y condiciones perfectas del Mediterráneo.',
            'fr' => 'Ski nautique à Marbella.',
            'nl' => 'Waterskiën in Marbella.',
        ],
        'ski-nautique' => [
            'en' => 'Water skiing in Marbella.',
            'es' => 'Esquí acuático en Marbella.',
            'fr' => 'Ski nautique à Marbella, Costa del Sol. Équipement professionnel, moniteurs experts et conditions méditerranéennes parfaites.',
            'nl' => 'Waterskiën in Marbella.',
        ],
        'waterskien' => [
            'en' => 'Water skiing in Marbella.',
            'es' => 'Esquí acuático en Marbella.',
            'fr' => 'Ski nautique à Marbella.',
            'nl' => 'Waterskiën in Marbella, Costa del Sol. Professionele uitrusting, ervaren instructeurs en perfecte Middellandse Zee-omstandigheden.',
        ],
        'banana' => [
            'en' => 'Banana boat ride in Marbella — fun group water activity on the Costa del Sol. Perfect for families and friends.',
            'es' => 'Banana boat en Marbella — actividad acuática grupal en la Costa del Sol. Perfecta para familias y amigos.',
            'fr' => 'Banana boat à Marbella — activité nautique de groupe sur la Costa del Sol. Parfait pour familles et amis.',
            'nl' => 'Bananenboot in Marbella — leuke groepswateractiviteit aan de Costa del Sol. Perfect voor gezinnen en vrienden.',
        ],
        'sofa' => [
            'en' => 'Crazy sofa ride in Marbella — thrilling group water activity for all ages. Hold on tight and enjoy the fun!',
            'es' => 'Sofá loco en Marbella — emocionante actividad acuática grupal para todas las edades. ¡Agárrate bien y disfruta!',
            'fr' => 'Crazy sofa à Marbella — activité nautique de groupe palpitante pour tous les âges. Accrochez-vous et amusez-vous!',
            'nl' => 'Crazy sofa in Marbella — spannende groepswateractiviteit voor alle leeftijden. Hou je vast en geniet!',
        ],
        'donut' => [
            'en' => 'Donut ride in Marbella — exciting inflatable water activity for groups and families on the Costa del Sol.',
            'es' => 'Donut acuático en Marbella — emocionante actividad inflable para grupos y familias en la Costa del Sol.',
            'fr' => 'Bouée donut à Marbella — activité nautique gonflable pour groupes et familles sur la Costa del Sol.',
            'nl' => 'Donut ride in Marbella — opwindende opblaasbare wateractiviteit voor groepen en gezinnen aan de Costa del Sol.',
        ],
        'air-stream' => [
            'en' => 'Air Stream experience in Marbella — unique water activity combining speed and fun. Available for all skill levels.',
            'es' => 'Air Stream en Marbella — actividad acuática única que combina velocidad y diversión. Disponible para todos los niveles.',
            'fr' => 'Air Stream à Marbella — activité nautique unique combinant vitesse et plaisir. Disponible pour tous les niveaux.',
            'nl' => 'Air Stream in Marbella — unieke wateractiviteit met snelheid en plezier. Beschikbaar voor alle niveaus.',
        ],
        'water-bull' => [
            'en' => 'Water bull ride in Marbella — hold on to this inflatable bull on the sea! Fun challenge for groups.',
            'es' => 'Water bull en Marbella — ¡agárrate a este toro inflable sobre el mar! Reto divertido para grupos.',
            'fr' => 'Water bull à Marbella — accrochez-vous à ce taureau gonflable en mer! Défi amusant pour groupes.',
            'nl' => 'Water bull in Marbella — hou je vast op deze opblaasbare stier op zee! Leuke uitdaging voor groepen.',
        ],
        'paddle' => [
            'en' => 'Stand-up paddleboarding (SUP) in Marbella — explore the Costa del Sol coastline at your own pace.',
            'es' => 'Paddle surf (SUP) en Marbella — explora la costa de la Costa del Sol a tu ritmo.',
            'fr' => 'Paddle surf (SUP) à Marbella — explorez la côte de la Costa del Sol à votre rythme.',
            'nl' => 'Stand-up paddleboarding (SUP) in Marbella — verken de kustlijn van de Costa del Sol op je eigen tempo.',
        ],
        'pedal' => [
            'en' => 'Pedal boat hire in Marbella — relax on the Mediterranean with a pedalo. Family-friendly activity on the Costa del Sol.',
            'es' => 'Alquiler de hidropedal en Marbella — relájate en el Mediterráneo. Actividad familiar en la Costa del Sol.',
            'fr' => 'Location de pédalo à Marbella — détendez-vous sur la Méditerranée. Activité familiale sur la Costa del Sol.',
            'nl' => 'Waterfiets huren in Marbella — ontspan op de Middellandse Zee. Gezinsvriendelijke activiteit aan de Costa del Sol.',
        ],
        'hidropedal' => [
            'en' => 'Pedal boat in Marbella.',
            'es' => 'Alquiler de hidropedal en Marbella — relájate en el Mediterráneo con un hidropedal. Actividad familiar en la Costa del Sol.',
            'fr' => 'Location de pédalo à Marbella.',
            'nl' => 'Waterfiets huren in Marbella.',
        ],
        'waterfiets' => [
            'en' => 'Pedal boat in Marbella.',
            'es' => 'Alquiler de hidropedal en Marbella.',
            'fr' => 'Location de pédalo à Marbella.',
            'nl' => 'Waterfiets huren in Marbella — ontspan op de Middellandse Zee met een waterfiets. Gezinsvriendelijk aan de Costa del Sol.',
        ],
        // Boat hire / Yacht
        'boat-hire' => [
            'en' => 'Boat hire in Marbella — rent a boat for the day on the Costa del Sol. Motor boats, yachts and catamarans available.',
            'es' => 'Alquiler de barcos en Marbella — alquila un barco para el día en la Costa del Sol. Lanchas, yates y catamaranes.',
            'fr' => 'Location de bateaux à Marbella — louez un bateau à la journée sur la Costa del Sol. Bateaux à moteur, yachts et catamarans.',
            'nl' => 'Bootverhuur in Marbella — huur een boot voor de dag aan de Costa del Sol. Motorboten, jachten en catamarans.',
        ],
        'alquiler-barcos' => [
            'en' => 'Boat hire in Marbella.',
            'es' => 'Alquiler de barcos en Marbella — alquila un barco para el día en la Costa del Sol. Lanchas, yates y catamaranes disponibles.',
            'fr' => 'Location de bateaux à Marbella.',
            'nl' => 'Bootverhuur in Marbella.',
        ],
        'location-bateaux' => [
            'en' => 'Boat hire in Marbella.',
            'es' => 'Alquiler de barcos en Marbella.',
            'fr' => 'Location de bateaux à Marbella — louez un bateau à la journée sur la Costa del Sol. Bateaux à moteur, yachts et catamarans.',
            'nl' => 'Bootverhuur in Marbella.',
        ],
        'boot-huren' => [
            'en' => 'Boat hire in Marbella.',
            'es' => 'Alquiler de barcos en Marbella.',
            'fr' => 'Location de bateaux à Marbella.',
            'nl' => 'Boot huren in Marbella — huur een boot voor de dag aan de Costa del Sol. Motorboten, jachten en catamarans beschikbaar.',
        ],
        'yacht' => [
            'en' => 'Luxury yacht charter in Marbella — private yacht hire with skipper on the Costa del Sol. Azimut, Sea Ray and more.',
            'es' => 'Alquiler de yate de lujo en Marbella — yate privado con patrón en la Costa del Sol. Azimut, Sea Ray y más.',
            'fr' => 'Location de yacht de luxe à Marbella — yacht privé avec skipper sur la Costa del Sol. Azimut, Sea Ray et plus.',
            'nl' => 'Luxe jachtverhuur in Marbella — privéjacht met schipper aan de Costa del Sol. Azimut, Sea Ray en meer.',
        ],
        'azimut' => [
            'en' => 'Azimut 39 Fly yacht charter in Marbella — luxury motor yacht with flybridge for up to 11 guests.',
            'es' => 'Alquiler de yate Azimut 39 Fly en Marbella — yate de lujo con flybridge para hasta 11 personas.',
            'fr' => 'Location yacht Azimut 39 Fly à Marbella — yacht à moteur de luxe avec flybridge pour 11 personnes.',
            'nl' => 'Azimut 39 Fly jachtverhuur in Marbella — luxe motorjacht met flybridge voor maximaal 11 gasten.',
        ],
        'catamaran' => [
            'en' => 'Catamaran Bali charter in Marbella — spacious sailing catamaran for groups, events and celebrations.',
            'es' => 'Alquiler de catamarán Bali en Marbella — catamarán espacioso para grupos, eventos y celebraciones.',
            'fr' => 'Location catamaran Bali à Marbella — catamaran spacieux pour groupes, événements et célébrations.',
            'nl' => 'Catamaran Bali huren in Marbella — ruime zeilcatamaran voor groepen, evenementen en feesten.',
        ],
        'cranchi' => [
            'en' => 'Cranchi Endurance 39 boat charter in Marbella — sporty day cruiser for up to 12 guests on the Costa del Sol.',
            'es' => 'Alquiler de Cranchi Endurance 39 en Marbella — crucero deportivo para hasta 12 personas en la Costa del Sol.',
            'fr' => 'Location Cranchi Endurance 39 à Marbella — day-cruiser sportif pour 12 personnes sur la Costa del Sol.',
            'nl' => 'Cranchi Endurance 39 huren in Marbella — sportieve dagcruiser voor maximaal 12 gasten aan de Costa del Sol.',
        ],
        'sea-ray' => [
            'en' => 'Sea Ray 240 Sundeck boat hire in Marbella — perfect day boat for families and small groups on the Costa del Sol.',
            'es' => 'Alquiler de Sea Ray 240 Sundeck en Marbella — barco perfecto para familias y grupos pequeños en la Costa del Sol.',
            'fr' => 'Location Sea Ray 240 Sundeck à Marbella — bateau idéal pour familles et petits groupes sur la Costa del Sol.',
            'nl' => 'Sea Ray 240 Sundeck huren in Marbella — perfecte dagboot voor gezinnen en kleine groepen aan de Costa del Sol.',
        ],
        // Aggregate pages
        'jet-ski' => [
            'en' => 'Jet ski experiences in Marbella — tours, excursions and closed circuit rides. Choose your adventure on the Costa del Sol.',
            'es' => 'Experiencias en moto de agua en Marbella — excursiones, tours y circuitos cerrados. Elige tu aventura en la Costa del Sol.',
            'fr' => 'Expériences jet ski à Marbella — tours, excursions et circuits fermés. Choisissez votre aventure sur la Costa del Sol.',
            'nl' => 'Jetski-ervaringen in Marbella — tours, excursies en gesloten circuits. Kies je avontuur aan de Costa del Sol.',
        ],
        'motos-de-agua' => [
            'en' => 'Jet ski in Marbella.',
            'es' => 'Experiencias en moto de agua en Marbella — excursiones, tours y circuitos cerrados en la Costa del Sol. Reserva online.',
            'fr' => 'Jet ski à Marbella.',
            'nl' => 'Jetski in Marbella.',
        ],
        'water-activities' => [
            'en' => 'Water activities in Marbella — banana boat, wakeboard, water ski, paddle surf and more on the Costa del Sol.',
            'es' => 'Actividades acuáticas en Marbella — banana boat, wakeboard, esquí acuático, paddle surf y más en la Costa del Sol.',
            'fr' => 'Activités nautiques à Marbella — banana boat, wakeboard, ski nautique, paddle surf et plus sur la Costa del Sol.',
            'nl' => 'Wateractiviteiten in Marbella — bananenboot, wakeboard, waterski, suppen en meer aan de Costa del Sol.',
        ],
        'actividades-acuaticas' => [
            'en' => 'Water activities in Marbella.',
            'es' => 'Actividades acuáticas en Marbella — banana boat, wakeboard, esquí acuático, paddle surf y más en la Costa del Sol.',
            'fr' => 'Activités nautiques à Marbella.',
            'nl' => 'Wateractiviteiten in Marbella.',
        ],
        'activites-nautiques' => [
            'en' => 'Water activities in Marbella.',
            'es' => 'Actividades acuáticas en Marbella.',
            'fr' => 'Activités nautiques à Marbella — banana boat, wakeboard, ski nautique, paddle surf et plus sur la Costa del Sol.',
            'nl' => 'Wateractiviteiten in Marbella.',
        ],
        'wateractiviteiten' => [
            'en' => 'Water activities in Marbella.',
            'es' => 'Actividades acuáticas en Marbella.',
            'fr' => 'Activités nautiques à Marbella.',
            'nl' => 'Wateractiviteiten in Marbella — bananenboot, wakeboard, waterski, suppen en meer aan de Costa del Sol.',
        ],
        'suppen' => [
            'en' => 'Stand-up paddleboarding in Marbella.',
            'es' => 'Paddle surf en Marbella.',
            'fr' => 'Paddle surf à Marbella.',
            'nl' => 'Stand-up paddleboarding (SUP) in Marbella — verken de kust van de Costa del Sol op je eigen tempo.',
        ],
    ];

    // Exact match first
    if ( isset( $map[ $slug ] ) ) {
        return $map[ $slug ][ $lang ] ?? $map[ $slug ]['en'];
    }

    // Partial match (longer keys first for specificity)
    $keys = array_keys( $map );
    usort( $keys, function( $a, $b ) { return strlen( $b ) - strlen( $a ); } );
    foreach ( $keys as $key ) {
        if ( strpos( $slug, $key ) !== false ) {
            return $map[ $key ][ $lang ] ?? $map[ $key ]['en'];
        }
    }

    return '';
}

/* ═══════════════════════════════════════════════════════════════
   18. ADMIN REPAIR / DIAGNOSTIC TOOL
   ═══════════════════════════════════════════════════════════════ */

/**
 * Add a "Site Repair" page under Appearance menu.
 * Non-coders can fix common issues with one click.
 */
function mjsk_admin_menu() {
    add_theme_page(
        'MJS Site Repair',
        '🔧 MJS Repair Tool',
        'manage_options',
        'mjsk-repair',
        'mjsk_repair_page'
    );
}
add_action( 'admin_menu', 'mjsk_admin_menu' );

function mjsk_repair_page() {
    // Handle form actions
    $message = '';
    $message_type = '';

    if ( isset( $_POST['mjsk_action'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'mjsk_repair' ) ) {
        $action = sanitize_text_field( $_POST['mjsk_action'] );

        switch ( $action ) {
            case 'recreate_pages':
                delete_option( 'mjsk_setup_done' );
                mjsk_auto_setup();
                $message = '✅ All 24 pages have been recreated. Missing pages were restored.';
                $message_type = 'success';
                break;

            case 'fix_permalinks':
                update_option( 'permalink_structure', '/%postname%/' );
                delete_option( 'mjsk_flush_done' );
                flush_rewrite_rules();
                update_option( 'mjsk_flush_done', 1 );
                $message = '✅ Permalinks fixed. Pretty URLs (like /booking/) should work now.';
                $message_type = 'success';
                break;

            case 'fix_homepage':
                $home_page = get_page_by_path( 'home' );
                if ( $home_page ) {
                    update_option( 'show_on_front', 'page' );
                    update_option( 'page_on_front', $home_page->ID );
                    $message = '✅ Homepage has been set to the "Home" page.';
                } else {
                    $message = '⚠️ "Home" page not found. Click "Recreate All Pages" first.';
                    $message_type = 'warning';
                }
                $message_type = $message_type ?: 'success';
                break;

            case 'full_reset':
                delete_option( 'mjsk_setup_done' );
                delete_option( 'mjsk_flush_done' );
                mjsk_auto_setup();
                flush_rewrite_rules();
                update_option( 'mjsk_flush_done', 1 );
                $home_page = get_page_by_path( 'home' );
                if ( $home_page ) {
                    update_option( 'show_on_front', 'page' );
                    update_option( 'page_on_front', $home_page->ID );
                }
                $message = '✅ Full reset complete. Pages recreated, permalinks fixed, homepage set.';
                $message_type = 'success';
                break;
        }
    }

    // Diagnostic checks
    $diagnostics = mjsk_run_diagnostics();

    ?>
    <div class="wrap">
        <h1>🔧 Marbella JetSki — Site Repair Tool</h1>
        <p style="font-size:14px;color:#666;">If something looks broken on the site, use these buttons to fix it. No coding needed.</p>

        <?php if ( $message ) : ?>
            <div class="notice notice-<?php echo esc_attr($message_type); ?> is-dismissible">
                <p style="font-size:14px;"><?php echo wp_kses_post($message); ?></p>
            </div>
        <?php endif; ?>

        <!-- Diagnostic Report -->
        <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:20px;margin:20px 0;max-width:700px;">
            <h2 style="margin-top:0;">📋 Site Health Check</h2>
            <table class="widefat striped" style="max-width:650px;">
                <tbody>
                    <?php foreach ( $diagnostics as $check ) : ?>
                        <tr>
                            <td style="width:30px;font-size:18px;"><?php echo $check['icon']; ?></td>
                            <td><strong><?php echo esc_html($check['label']); ?></strong></td>
                            <td><?php echo wp_kses_post($check['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Repair Actions -->
        <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:20px;margin:20px 0;max-width:700px;">
            <h2 style="margin-top:0;">🛠️ Repair Actions</h2>

            <div style="display:grid;gap:15px;max-width:600px;">

                <!-- Fix 1: Recreate Pages -->
                <div style="border:1px solid #ddd;border-radius:6px;padding:15px;">
                    <h3 style="margin:0 0 5px;">Recreate All Pages</h3>
                    <p style="color:#666;margin:0 0 10px;">Use if: Pages are missing, deleted, or showing 404 errors.</p>
                    <form method="post">
                        <?php wp_nonce_field( 'mjsk_repair' ); ?>
                        <input type="hidden" name="mjsk_action" value="recreate_pages">
                        <button type="submit" class="button button-primary">Recreate 24 Pages</button>
                    </form>
                </div>

                <!-- Fix 2: Permalinks -->
                <div style="border:1px solid #ddd;border-radius:6px;padding:15px;">
                    <h3 style="margin:0 0 5px;">Fix Permalinks (404 Errors)</h3>
                    <p style="color:#666;margin:0 0 10px;">Use if: Pages exist but show "Page Not Found" when you visit them.</p>
                    <form method="post">
                        <?php wp_nonce_field( 'mjsk_repair' ); ?>
                        <input type="hidden" name="mjsk_action" value="fix_permalinks">
                        <button type="submit" class="button button-primary">Fix Permalinks</button>
                    </form>
                </div>

                <!-- Fix 3: Homepage -->
                <div style="border:1px solid #ddd;border-radius:6px;padding:15px;">
                    <h3 style="margin:0 0 5px;">Fix Homepage</h3>
                    <p style="color:#666;margin:0 0 10px;">Use if: The homepage shows a blog/posts list instead of the real homepage.</p>
                    <form method="post">
                        <?php wp_nonce_field( 'mjsk_repair' ); ?>
                        <input type="hidden" name="mjsk_action" value="fix_homepage">
                        <button type="submit" class="button button-primary">Set Homepage</button>
                    </form>
                </div>

                <!-- Fix 4: Full Reset -->
                <div style="border:2px solid #0073aa;border-radius:6px;padding:15px;background:#f0f6fc;">
                    <h3 style="margin:0 0 5px;">⭐ Full Reset (Fixes Everything)</h3>
                    <p style="color:#666;margin:0 0 10px;">Use if: Nothing else worked, or you just want to make sure everything is correct. This recreates all pages, fixes permalinks, and sets the homepage. <strong>Safe to run — won't delete anything.</strong></p>
                    <form method="post">
                        <?php wp_nonce_field( 'mjsk_repair' ); ?>
                        <input type="hidden" name="mjsk_action" value="full_reset">
                        <button type="submit" class="button button-primary" style="background:#0073aa;font-size:14px;padding:5px 20px;">🔄 Full Reset</button>
                    </form>
                </div>

            </div>
        </div>

        <!-- Help Section -->
        <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:20px;margin:20px 0;max-width:700px;">
            <h2 style="margin-top:0;">❓ Still Broken?</h2>
            <ol style="font-size:14px;line-height:1.8;">
                <li><strong>Try "Full Reset"</strong> above — it fixes 90% of issues</li>
                <li><strong>Clear your browser cache</strong> (Ctrl+Shift+Delete on PC, Cmd+Shift+Delete on Mac)</li>
                <li><strong>Try a different browser</strong> or incognito/private window</li>
                <li><strong>Re-upload the theme</strong> — download the theme zip, go to Appearance → Themes, delete the current theme, then upload and activate again</li>
                <li><strong>Check the GitHub repo</strong> for the latest files: <a href="https://github.com/munyanyo92/marbellajetski-wordpress-theme" target="_blank">github.com/munyanyo92/marbellajetski-wordpress-theme</a></li>
            </ol>
        </div>
    </div>
    <?php
}

/**
 * Run diagnostic checks and return results.
 */
function mjsk_run_diagnostics() {
    $checks = [];

    // Check 1: Are all 24 pages present?
    $expected_pages = [
        'home', 'booking', 'terms', 'weather-policy', 'about-us', 'lessons',
        'es', 'fr', 'nl',
    ];
    $lang_subpages = [
        'es' => ['booking','terms','weather-policy','about-us','lessons'],
        'fr' => ['booking','terms','weather-policy','about-us','lessons'],
        'nl' => ['booking','terms','weather-policy','about-us','lessons'],
    ];

    $missing = [];
    foreach ( $expected_pages as $slug ) {
        if ( ! get_page_by_path( $slug ) ) $missing[] = '/' . $slug . '/';
    }
    foreach ( $lang_subpages as $lang => $subs ) {
        foreach ( $subs as $sub ) {
            if ( ! get_page_by_path( $lang . '/' . $sub ) ) $missing[] = '/' . $lang . '/' . $sub . '/';
        }
    }

    $page_count = 24 - count($missing);
    if ( count($missing) === 0 ) {
        $checks[] = ['icon' => '✅', 'label' => 'Pages (24 total)', 'status' => 'All 24 pages present'];
    } else {
        $checks[] = ['icon' => '❌', 'label' => 'Pages', 'status' => $page_count . '/24 found. Missing: ' . implode(', ', array_slice($missing, 0, 5)) . (count($missing) > 5 ? '...' : '') . ' — <em>Click "Recreate All Pages" to fix</em>'];
    }

    // Check 2: Permalink structure
    $permalink = get_option( 'permalink_structure' );
    if ( $permalink === '/%postname%/' ) {
        $checks[] = ['icon' => '✅', 'label' => 'Permalinks', 'status' => 'Correct (/%postname%/)'];
    } else {
        $checks[] = ['icon' => '❌', 'label' => 'Permalinks', 'status' => 'Wrong: "' . esc_html($permalink) . '" — <em>Click "Fix Permalinks"</em>'];
    }

    // Check 3: Homepage setting
    $show_on_front = get_option( 'show_on_front' );
    $front_page_id = get_option( 'page_on_front' );
    $home_page = get_page_by_path( 'home' );
    if ( $show_on_front === 'page' && $home_page && intval($front_page_id) === $home_page->ID ) {
        $checks[] = ['icon' => '✅', 'label' => 'Homepage', 'status' => 'Set to "Home" page'];
    } else {
        $checks[] = ['icon' => '❌', 'label' => 'Homepage', 'status' => 'Not set correctly — <em>Click "Fix Homepage"</em>'];
    }

    // Check 4: Theme files integrity
    $required_files = ['functions.php', 'header.php', 'footer.php', 'front-page.php', 'page.php', 'style.css', 'assets/css/main.css', 'assets/js/script.js'];
    $missing_files = [];
    foreach ( $required_files as $f ) {
        if ( ! file_exists( get_template_directory() . '/' . $f ) ) $missing_files[] = $f;
    }
    if ( empty($missing_files) ) {
        $checks[] = ['icon' => '✅', 'label' => 'Theme Files', 'status' => 'All core files present'];
    } else {
        $checks[] = ['icon' => '❌', 'label' => 'Theme Files', 'status' => 'Missing: ' . implode(', ', $missing_files) . ' — <em>Re-upload the theme</em>'];
    }

    // Check 5: Content files
    $content_files = ['home.html','booking.html','es-home.html','es-booking.html','fr-home.html','fr-booking.html','nl-home.html','nl-booking.html'];
    $missing_content = [];
    foreach ( $content_files as $cf ) {
        if ( ! file_exists( get_template_directory() . '/page-content/' . $cf ) ) $missing_content[] = $cf;
    }
    if ( empty($missing_content) ) {
        $checks[] = ['icon' => '✅', 'label' => 'Content Files', 'status' => 'All content files present'];
    } else {
        $checks[] = ['icon' => '❌', 'label' => 'Content Files', 'status' => 'Missing: ' . implode(', ', $missing_content) . ' — <em>Re-upload the theme</em>'];
    }

    // Check 6: PHP version
    $php_version = phpversion();
    if ( version_compare( $php_version, '7.4', '>=' ) ) {
        $checks[] = ['icon' => '✅', 'label' => 'PHP Version', 'status' => $php_version];
    } else {
        $checks[] = ['icon' => '⚠️', 'label' => 'PHP Version', 'status' => $php_version . ' (7.4+ recommended) — <em>Contact your hosting provider</em>'];
    }

    // Check 7: WordPress version
    global $wp_version;
    if ( version_compare( $wp_version, '6.0', '>=' ) ) {
        $checks[] = ['icon' => '✅', 'label' => 'WordPress', 'status' => $wp_version];
    } else {
        $checks[] = ['icon' => '⚠️', 'label' => 'WordPress', 'status' => $wp_version . ' (6.0+ recommended) — <em>Update WordPress</em>'];
    }

    return $checks;
}

