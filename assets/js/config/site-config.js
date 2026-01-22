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
                image: "assets/images/product-image/1/1.webp",
                alt: "Antena Satelital",
                category: "Comunicacion",
                title: "Antena Satelital",
                price: "$9.800",
                oldPrice: null,
                badges: ["new"],
                index: 1,
                orderNewArrivals: 1,  // Orden en tab "Novedades"
                orderTopRated: 3,    // Orden en tab "Mas Vendidos"
                description: "Starlink Maritime es la solución de internet satelital diseñada especialmente para embarcaciones y operaciones marítimas que requieren conectividad confiable, rápida y global sin importar dónde se encuentren en los océanos del mundo. Aprovechando la más grande constelación de satélites en órbita terrestre baja, Starlink ofrece acceso continuo a internet incluso en aguas internacionales, ideal para barcos comerciales, pesca, investigación y yates privados.",
                sku: "SKU-ANT-001",
                availability: 15,  // Disponibilidad en días
                tags: ["satelital", "comunicacion", "antena", "exterior"],
                information: {
                    weight: "2.5 kg",
                    dimensions: "45 x 35 x 12 cm",
                    materials: "Aluminio, acero inoxidable, componentes electrónicos",
                    other_info: "Resistente a condiciones climáticas extremas, incluye kit de montaje"
                }
            },
            {
                id: 2,
                image: "assets/images/product-image/2/1.webp",
                alt: "Telefono Satelital",
                category: "Comunicacion",
                title: "Telefono Satelital",
                price: "$4.365",
                oldPrice: "$4.850",
                badges: ["sale", "new"],
                index: 2,
                orderNewArrivals: 2,
                orderTopRated: 1,
                description: "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
                sku: "SKU-TEL-002",
                availability: 8,  // Disponibilidad en días
                tags: ["telefono", "satelital", "comunicacion", "portatil"],
                information: {
                    weight: "0.8 kg",
                    dimensions: "18 x 7 x 3 cm",
                    materials: "Plástico reforzado, pantalla LCD, batería de litio",
                    other_info: "Batería de larga duración, resistente al agua IP67, GPS integrado"
                }
            },
            {
                id: 3,
                image: "assets/images/product-image/7/1.webp",
                alt: "Enrutadores Portatiles",
                category: "Seguridad",
                title: "Enrutadores Portatiles",
                price: "$1.850",
                oldPrice: null,
                badges: ["new"],
                index: 7,
                orderNewArrivals: 3,
                orderTopRated: 7,
                description: "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.",
                sku: "SKU-ROU-003",
                availability: 22,  // Disponibilidad en días
                tags: ["enrutador", "portatil", "wifi", "red", "seguridad"],
                information: {
                    weight: "0.3 kg",
                    dimensions: "12 x 8 x 2 cm",
                    materials: "Plástico ABS, circuitos integrados, antena interna",
                    other_info: "WiFi 802.11ac, soporte para hasta 32 dispositivos simultáneos, batería recargable"
                }
            },
            {
                id: 4,
                image: "assets/images/product-image/4/1.webp",
                alt: "GPS Marino",
                category: "Seguridad",
                title: "GPS Marino",
                price: "$2.950",
                oldPrice: null,
                badges: ["new"],
                index: 4,
                orderNewArrivals: 4,
                orderTopRated: 4,
                description: "Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.",
                sku: "SKU-GPS-004",
                availability: 5,  // Disponibilidad en días
                tags: ["gps", "marino", "navegacion", "localizacion"],
                information: {
                    weight: "1.2 kg",
                    dimensions: "25 x 20 x 8 cm",
                    materials: "Carcasa marina IP68, pantalla táctil, antena GPS externa",
                    other_info: "Resistente al agua y sal, cartografía marina incluida, batería de 12 horas"
                }
            },
            {
                id: 5,
                image: "assets/images/product-image/5/1.webp",
                alt: "Escaner Profecional",
                category: "Seguridad",
                title: "Escaner Profecional",
                price: "$9.850",
                oldPrice: null,
                badges: [],
                index: 5,
                orderNewArrivals: 5,
                orderTopRated: 5,
                description: "At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa.",
                sku: "SKU-SCA-005",
                availability: 12,  // Disponibilidad en días
                tags: ["escaner", "profesional", "seguridad", "detection"],
                information: {
                    weight: "0.6 kg",
                    dimensions: "22 x 15 x 5 cm",
                    materials: "Plástico de alta resistencia, pantalla OLED, antena direccional",
                    other_info: "Rango de frecuencia amplio, detección de múltiples señales, memoria interna 8GB"
                }
            },
            {
                id: 6,
                image: "assets/images/product-image/6/1.webp",
                alt: "Bloqueador de Señal",
                category: "Seguridad",
                title: "Bloqueador de Señal",
                price: "$12.742",
                oldPrice: "$13.850",
                badges: ["sale", { type: "new", text: "Oferta" }], // Badge "new" con texto personalizado "Oferta"
                index: 6,
                orderNewArrivals: 6,
                orderTopRated: 6,
                description: "Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est.",
                sku: "SKU-BLO-006",
                availability: 18,  // Disponibilidad en días
                tags: ["bloqueador", "senal", "jamming", "proteccion"],
                information: {
                    weight: "1.8 kg",
                    dimensions: "30 x 25 x 10 cm",
                    materials: "Carcasa metálica, circuitos de alta potencia, disipador de calor",
                    other_info: "Bloqueo de múltiples frecuencias, alcance de 50 metros, control remoto incluido"
                }
            },
            {
                id: 7,
                image: "assets/images/product-image/3/1.webp",
                alt: "Radio Satelital",
                category: "Comunicacion",
                title: "Radio Satelital",
                price: "$3.850",
                oldPrice: null,
                badges: [],
                index: 3,
                orderNewArrivals: 7,
                orderTopRated: 2,
                description: "Omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus.",
                sku: "SKU-RAD-007",
                availability: 3,  // Disponibilidad en días
                tags: ["radio", "satelital", "comunicacion", "portatil"],
                information: {
                    weight: "0.5 kg",
                    dimensions: "15 x 6 x 4 cm",
                    materials: "Carcasa resistente a golpes, antena retráctil, batería de litio",
                    other_info: "Comunicación bidireccional, alcance de 50 km, 128 canales programables"
                }
            },
            {
                id: 8,
                image: "assets/images/product-image/8/1.webp",
                alt: "Escaner Aereo",
                category: "Seguridad",
                title: "Escaner Aereo",
                price: "$5.940",
                oldPrice: "$6.600",
                badges: ["sale"],
                index: 8,
                orderNewArrivals: 8,
                orderTopRated: 8,
                description: "Ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.",
                sku: "SKU-AER-008",
                availability: 25,  // Disponibilidad en días
                tags: ["escaner", "aereo", "avionica", "frecuencia"],
                information: {
                    weight: "0.9 kg",
                    dimensions: "28 x 18 x 6 cm",
                    materials: "Aluminio aeronáutico, pantalla táctil a color, antena direccional",
                    other_info: "Detección de frecuencias aéreas, base de datos actualizable, grabación de audio"
                }
            }
        ]
    }
};
