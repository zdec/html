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
        { text: "Catalogo", href: "#" },
        { text: "Contacto", href: "contact.html" }
    ],
    
    // Footer links
    footer: {
        services: [
            { text: "Mi Cuenta", href: "#", realhref: "my-account.html", enabled: false },
            { text: "Contacto", href: "contact.html", realhref: "contact.html", enabled: true },
            { text: "Carrito de Compras", href: "#", realhref: "cart.html", enabled: false },
            { text: "Catalogo", href: "#", realhref: "shop-left-sidebar.html", enabled: true }
        ],
        copyright: {
            year: "2026",
            company: "IT Secur",
            creator: "IT Secur SAS",
            creatorUrl: "https://itsecursas.co/"
        }
    }
};
