/**
 * Configuración del sitio IT Secur
 * Centraliza todos los datos dinámicos del sitio
 */
const SiteConfig = {
    // Información de contacto
    contact: {
        phone: "+57 310 6707901",
        phoneFormatted: "+57 310 6707901",
        email: "ventas@itsecursas.co",
        address: "Cra 32 # 77 S 371",
        city: "Medellín, Colombia",
        website: "https://www.itsecursas.co"
    },
    
    // Rutas de imágenes
    images: {
        logo: "assets/images/logo/logo.png",
        footerLogo: "assets/images/logo/footer-logo.png",
        icon: "assets/images/logo/icono.png"
    },
    
    // Textos comunes
    texts: {
        welcomeMessage: "Devoluciones y envíos completamente gratis a todo el mundo",
        accountText: "Mi Cuenta",
        cartText: "Carrito",
        wishlistText: "Me gusta"
    },
    
    // Configuración de elementos del header
    header: {
        showCart: false  // Mostrar/ocultar botón del carrito
    },
    
    // Redes sociales
    social: {
        facebook: "#",
        twitter: "#",
        instagram: "#",
        youtube: "#",
        tumblr: "#"
    },
    
    // Menú de navegación
    menu: [
        { text: "Inicio", href: "index.html" },
        { text: "Catalogo", href: "catalog.html" },
        { text: "Contacto", href: "contact.html" }
    ],
    
    // Footer links
    footer: {
        services: [
            { text: "Mi Cuenta", href: "#", realhref: "my-account.html", enabled: false },
            { text: "Contacto", href: "contact.html", realhref: "contact.html", enabled: true },
            { text: "Carrito de Compras", href: "#", realhref: "cart.html", enabled: false },
            { text: "Catalogo", href: "catalog.html", realhref: "catalog.html", enabled: true }
        ],
        copyright: {
            year: "2026",
            company: "IT Secur",
            creator: "IT Secur SAS",
            creatorUrl: "https://itsecursas.co/"
        }
    },
    
    // Productos para el área de productos en index.html
    // Cada producto tiene un orden diferente para cada tab
    products: {
        items: [
            {
                id: 1,
                image: "assets/images/product-image/1.webp",
                alt: "Antena Satelital",
                category: "Comunicacion",
                title: "Antena Satelital",
                price: "$9.800",
                oldPrice: null,
                badges: ["new"],
                orderNewArrivals: 1,  // Orden en tab "Novedades"
                orderTopRated: 3      // Orden en tab "Mas Vendidos"
            },
            {
                id: 2,
                image: "assets/images/product-image/2.webp",
                alt: "Telefono Satelital",
                category: "Comunicacion",
                title: "Telefono Satelital",
                price: "$4.365",
                oldPrice: "$4.850",
                badges: ["sale", "new"],
                orderNewArrivals: 2,
                orderTopRated: 1
            },
            {
                id: 3,
                image: "assets/images/product-image/7.webp",
                alt: "Enrutadores Portatiles",
                category: "Seguridad",
                title: "Enrutadores Portatiles",
                price: "$1.850",
                oldPrice: null,
                badges: ["new"],
                orderNewArrivals: 3,
                orderTopRated: 7
            },
            {
                id: 4,
                image: "assets/images/product-image/4.webp",
                alt: "GPS Marino",
                category: "Seguridad",
                title: "GPS Marino",
                price: "$2.950",
                oldPrice: null,
                badges: ["new"],
                orderNewArrivals: 4,
                orderTopRated: 4
            },
            {
                id: 5,
                image: "assets/images/product-image/5.webp",
                alt: "Escaner Profecional",
                category: "Seguridad",
                title: "Escaner Profecional",
                price: "$9.850",
                oldPrice: null,
                badges: [],
                orderNewArrivals: 5,
                orderTopRated: 5
            },
            {
                id: 6,
                image: "assets/images/product-image/6.webp",
                alt: "Bloqueador de Señal",
                category: "Seguridad",
                title: "Bloqueador de Señal",
                price: "$12.742",
                oldPrice: "$13.850",
                badges: ["sale", { type: "new", text: "Oferta" }], // Badge "new" con texto personalizado "Oferta"
                orderNewArrivals: 6,
                orderTopRated: 6
            },
            {
                id: 7,
                image: "assets/images/product-image/3.webp",
                alt: "Radio Satelital",
                category: "Comunicacion",
                title: "Radio Satelital",
                price: "$3.850",
                oldPrice: null,
                badges: [],
                orderNewArrivals: 7,
                orderTopRated: 2
            },
            {
                id: 8,
                image: "assets/images/product-image/8.webp",
                alt: "Escaner Aereo",
                category: "Seguridad",
                title: "Escaner Aereo",
                price: "$5.940",
                oldPrice: "$6.600",
                badges: ["sale"],
                orderNewArrivals: 8,
                orderTopRated: 8
            }
        ]
    }
};
