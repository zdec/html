<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rutas de imágenes de productos
    |--------------------------------------------------------------------------
    | Base path para imágenes: assets/images/products/{product_id}/
    | Referencia por product_id para estabilidad y organización.
    */
    'product_images_path' => 'assets/images/products',


    'contact' => [
        'phone' => env('SITE_PHONE', '+57 320 5523491'),
        'phone_formatted' => env('SITE_PHONE_FORMATTED', '+57 320 5523491'),
        'email' => env('SITE_EMAIL', 'ventas@itsecursas.co'),
        'address' => env('SITE_ADDRESS', 'Cra 32 # 77 S 371'),
        'city' => env('SITE_CITY', 'Medellín, Colombia'),
        'website' => env('SITE_WEBSITE', 'https://www.itsecursas.co'),
    ],

    'images' => [
        'logo' => '/assets/images/logo/logo.png',
        'footerLogo' => '/assets/images/logo/footer-logo.png',
        'icon' => '/assets/images/logo/icono.png',
    ],

    'texts' => [
        'welcome_message' => 'Devoluciones y envíos completamente gratis a todo el mundo',
        'account_text' => 'Mi Cuenta',
        'cart_text' => 'Carrito',
        'wishlist_text' => 'Me gusta',
    ],

    'header' => [
        'show_cart' => false,
    ],

    'social' => [
        'facebook' => env('SITE_FACEBOOK', '#'),
        'twitter' => env('SITE_TWITTER', '#'),
        'instagram' => env('SITE_INSTAGRAM', '#'),
        'youtube' => env('SITE_YOUTUBE', '#'),
        'tumblr' => env('SITE_TUMBLR', '#'),
    ],

    'menu' => [
        ['text' => 'Inicio', 'href' => '/'],
        ['text' => 'Catalogo', 'href' => '/catalogo'],
        ['text' => 'Contacto', 'href' => '/contacto'],
    ],

    'footer' => [
        'services' => [
            ['text' => 'Mi Cuenta', 'href' => '#', 'realhref' => '/mi-cuenta', 'enabled' => false],
            ['text' => 'Contacto', 'href' => '/contacto', 'realhref' => '/contacto', 'enabled' => true],
            ['text' => 'Carrito de Compras', 'href' => '#', 'realhref' => '/carrito', 'enabled' => false],
            ['text' => 'Catalogo', 'href' => '/catalogo', 'realhref' => '/catalogo', 'enabled' => true],
        ],
        'copyright' => [
            'year' => date('Y'),
            'company' => env('SITE_COMPANY', 'IT Secur'),
            'creator' => env('SITE_CREATOR', 'IT Secur SAS'),
            'creator_url' => env('SITE_CREATOR_URL', 'https://itsecursas.co/'),
        ],
    ],

];
