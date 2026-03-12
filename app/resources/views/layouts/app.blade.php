<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IT Secur')</title>
    <meta name="robots" content="index, follow" />
    <meta name="description" content="@yield('description', 'IT Secur - Tienda en línea de articulos de seguridad')">
    <link rel="shortcut icon" href="/assets/images/logo/icono.png" type="image/png">
    <link rel="icon" href="/assets/images/logo/icono.png" type="image/png">

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/font.awesome.css" />
    <link rel="stylesheet" href="/assets/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="/assets/css/animate.min.css">
    <link rel="stylesheet" href="/assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/css/venobox.css">
    <link rel="stylesheet" href="/assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Header: min-height solo en desktop; en tablets/móvil se adapta al contenido */
        .header-container-adaptive { min-height: 0; }
        @media (min-width: 769px) {
            .header-container-adaptive { min-height: 195px; }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div id="header-container" class="header-container-adaptive"></div>
        @yield('content')
        <div id="footer-container"></div>
    </div>

    <script>
        var SiteConfig = {
            contact: {
                phone: @json(config('site.contact.phone')),
                phoneFormatted: @json(config('site.contact.phone_formatted')),
                email: @json(config('site.contact.email')),
                address: @json(config('site.contact.address')),
                city: @json(config('site.contact.city')),
                website: @json(config('site.contact.website')),
            },
            images: @json(config('site.images')),
            texts: {
                welcomeMessage: @json(config('site.texts.welcome_message')),
                accountText: @json(config('site.texts.account_text')),
                cartText: @json(config('site.texts.cart_text')),
                wishlistText: @json(config('site.texts.wishlist_text')),
            },
            header: { showCart: {{ config('site.header.show_cart') ? 'true' : 'false' }} },
            social: @json(config('site.social')),
            menu: @json(config('site.menu')),
            footer: {
                services: @json(config('site.footer.services')),
                copyright: {
                    year: @json(config('site.footer.copyright.year')),
                    company: @json(config('site.footer.copyright.company')),
                    creator: @json(config('site.footer.copyright.creator')),
                    creatorUrl: @json(config('site.footer.copyright.creator_url')),
                },
            },
            products: { items: @json($productsForSearch ?? []) },
            auth: {
                check: @json(auth()->check()),
                logoutUrl: @json(route('logout')),
                adminUrl: @json(route('admin.orders.index')),
                csrfToken: document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
            },
        };
    </script>
    <script src="/assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <script src="/assets/js/vendor/modernizr-3.11.2.min.js"></script>
    <script src="/assets/js/plugins/jquery.countdown.min.js"></script>
    <script src="/assets/js/plugins/swiper-bundle.min.js"></script>
    <script src="/assets/js/plugins/scrollUp.js"></script>
    <script src="/assets/js/plugins/venobox.min.js"></script>
    <script src="/assets/js/plugins/jquery-ui.min.js"></script>
    <script src="/assets/js/plugins/mailchimp-ajax.js"></script>

    <script src="/assets/js/components/whatsapp-button/whatsapp-button.js"></script>
    <script src="/assets/js/components/header/header.js"></script>
    <script src="/assets/js/components/offcanvas/offcanvas.js"></script>
    <script src="/assets/js/components/footer/footer.js"></script>
    <script src="/assets/js/components/modals/modals.js"></script>
    @stack('scripts')
    <script src="/assets/js/components/components.js"></script>
    <script src="/assets/js/components/sliders-init.js"></script>
    <script src="/assets/js/components/modal-accessibility-fix.js"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>
