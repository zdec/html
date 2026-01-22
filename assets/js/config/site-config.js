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
        // NOTA: Los campos orderNewArrivals y orderTopRated son OPCIONALES
        // Si no se especifican, se calculan automáticamente:
        // - orderNewArrivals: Productos con badge "new" primero, luego por id descendente
        // - orderTopRated: Ordenados por precio (más caro primero), luego por id
        // Si se especifican, se respetan los valores manuales
        items: [
            {
                id: 1,
                image: "assets/images/product-image/1/1.webp",
                alt: "Antena Starlink Maritime",
                category: "Comunicacion",
                title: "Antena Starlink Maritime",
                price: "$3,100",
                oldPrice: null,
                badges: ["new"],
                index: 1,
                description: "Starlink Maritime es una solución de internet satelital de alta velocidad diseñada para embarcaciones y operaciones en mar abierto. Utiliza la constelación de satélites en órbita baja (LEO) de SpaceX para ofrecer conexión estable, baja latencia y cobertura global, ideal para barcos comerciales, yates, pesca industrial y operaciones offshore.",
                sku: "STL-MAR-ANT-001",
                availability: 18,
                tags: ["satelital", "comunicacion", "internet", "maritimo"],
                information: {
                    weight: "2.9 kg",
                    dimensions: "50 x 30 x 12 cm",
                    materials: "Aluminio, acero inoxidable, polímeros reforzados",
                    other_info: "Antena plana de autoalineación, resistente a ambiente marino, incluye fuente de poder y kit de montaje"
                }
            },

            {
                id: 2,
                image: "assets/images/product-image/2/1.webp",
                alt: "Telefono Satelital IsatPhone 2",
                category: "Comunicacion",
                title: "Telefono Satelital IsatPhone 2",
                price: "$1,150",
                oldPrice: "$1,280",
                badges: ["sale", "new"],
                index: 2,
                description: "El IsatPhone 2 de Inmarsat es un teléfono satelital robusto y confiable, diseñado para comunicación de voz y SMS en cualquier parte del mundo. Ideal para expediciones, minería, petróleo y gas, zonas rurales y emergencias.",
                sku: "INM-ISP2-TEL-002",
                availability: 10,
                tags: ["telefono", "satelital", "emergencias", "portatil"],
                information: {
                    weight: "0.32 kg",
                    dimensions: "17 x 5.4 x 2.9 cm",
                    materials: "Polímero reforzado, pantalla transflectiva",
                    other_info: "Autonomía hasta 8h en llamada y 160h en espera, GPS integrado, certificación IP65"
                }
            },

            {
                id: 3,
                image: "assets/images/product-image/7/1.webp",
                alt: "Enrutador Portátil 4G/5G",
                category: "Seguridad",
                title: "Enrutador Portátil 4G/5G",
                price: "$390",
                oldPrice: null,
                badges: ["new"],
                index: 7,
                description: "Enrutador portátil de alta seguridad diseñado para crear redes privadas móviles seguras. Ideal para viajes, trabajo remoto, periodistas y equipos técnicos que requieren conectividad confiable con cifrado avanzado.",
                sku: "SEC-ROU-PT-003",
                availability: 20,
                tags: ["router", "portatil", "wifi", "seguridad"],
                information: {
                    weight: "0.25 kg",
                    dimensions: "12 x 8 x 2 cm",
                    materials: "Plástico ABS de alta resistencia",
                    other_info: "Soporte VPN, WiFi 802.11ac, hasta 20 dispositivos, batería integrada"
                }
            },

            {
                id: 4,
                image: "assets/images/product-image/4/1.webp",
                alt: "GPS Marino Garmin GPSMAP",
                category: "Seguridad",
                title: "GPS Marino Garmin GPSMAP",
                price: "$480",
                oldPrice: null,
                badges: ["new"],
                index: 4,
                description: "GPS portátil marino Garmin 79s, diseñado para navegación marítima profesional y recreativa. Flota en el agua, ofrece alta sensibilidad GNSS y cartografía básica integrada.",
                sku: "GAR-GPS-79S-004",
                availability: 7,
                tags: ["gps", "marino", "navegacion"],
                information: {
                    weight: "0.28 kg",
                    dimensions: "15.2 x 6.6 x 3.0 cm",
                    materials: "Carcasa sellada IPX7",
                    other_info: "Soporte GPS, GLONASS y Galileo, batería hasta 19 horas"
                }
            },

            {
                id: 5,
                image: "assets/images/product-image/5/1.webp",
                alt: "Escáner Profesional RF",
                category: "Seguridad",
                title: "Escáner Profesional RF",
                price: "$1,600",
                oldPrice: null,
                badges: [],
                index: 5,
                description: "Escáner profesional de radiofrecuencia diseñado para detección de señales inalámbricas, espionaje electrónico y análisis de espectro en entornos corporativos y de seguridad.",
                sku: "RF-SCAN-PRO-005",
                availability: 15,
                tags: ["escaner", "rf", "seguridad"],
                information: {
                    weight: "0.55 kg",
                    dimensions: "22 x 14 x 5 cm",
                    materials: "Plástico técnico, pantalla OLED",
                    other_info: "Cobertura de amplio espectro RF, almacenamiento interno, alertas programables"
                }
            },

            {
                id: 6,
                image: "assets/images/product-image/6/1.webp",
                alt: "Bloqueador de Señal",
                category: "Seguridad",
                title: "Bloqueador de Señal",
                price: "$2,500",
                oldPrice: "$2,900",
                badges: ["sale", { type: "new", text: "Oferta" }],
                index: 6,
                description: "Bloqueador de señal multibanda diseñado para entornos controlados donde se requiere inhibición de comunicaciones inalámbricas. Uso exclusivo para entidades autorizadas.",
                sku: "JAM-MULTI-006",
                availability: 20,
                tags: ["bloqueador", "rf", "seguridad"],
                information: {
                    weight: "1.7 kg",
                    dimensions: "30 x 25 x 10 cm",
                    materials: "Carcasa metálica con disipación térmica",
                    other_info: "Bloqueo GSM, 3G, 4G, GPS y WiFi, alcance variable según entorno"
                }
            },

            {
                id: 7,
                image: "assets/images/product-image/3/1.webp",
                alt: "Radio Satelital ICOM IC-SAT100",
                category: "Comunicacion",
                title: "Radio Satelital ICOM IC-SAT100",
                price: "$1,330",
                oldPrice: null,
                badges: [],
                index: 3,
                description: "Radio satelital profesional ICOM IC-SAT100 que opera sobre la red Iridium, permitiendo comunicación grupal global sin infraestructura terrestre.",
                sku: "ICOM-SAT100-007",
                availability: 8,
                tags: ["radio", "satelital", "icom"],
                information: {
                    weight: "0.5 kg",
                    dimensions: "15 x 6 x 4 cm",
                    materials: "Carcasa reforzada IP67",
                    other_info: "Cobertura global, botón PTT, comunicación grupal y privada"
                }
            },

            {
                id: 8,
                image: "assets/images/product-image/8/1.webp",
                alt: "Escáner Aéreo RF-30",
                category: "Seguridad",
                title: "Escáner Aéreo RF-30",
                price: "$1,150",
                oldPrice: "$1,400",
                badges: ["sale"],
                index: 8,
                description: "Escáner de frecuencias aéreas diseñado para monitoreo de comunicaciones aeronáuticas y análisis de tráfico aéreo.",
                sku: "AIR-RF30-008",
                availability: 25,
                tags: ["escaner", "aereo", "frecuencias"],
                information: {
                    weight: "0.9 kg",
                    dimensions: "28 x 18 x 6 cm",
                    materials: "Aluminio aeronáutico",
                    other_info: "Base de datos actualizable, grabación de audio, pantalla a color"
                }
            },
            
            {
                id: 9,
                image: "assets/images/product-image/9/1.webp",
                alt: "Telefono Satelital Iridium Extreme 9555",
                category: "Comunicacion",
                title: "Telefono Satelital Iridium",
                price: "$1,300",
                oldPrice: "$1,550",
                badges: ["sale", "new"],
                index: 9,
                description: "El Iridium Extreme 9555 es un teléfono satelital de grado militar diseñado para operar en los entornos más hostiles del planeta. Ofrece comunicación de voz y SMS con cobertura global real gracias a la red Iridium, siendo ideal para misiones críticas, exploración, defensa, minería y respuesta a emergencias.",
                sku: "IRD-EXT-9555-009",
                availability: 12,
                tags: ["telefono", "satelital", "iridium", "emergencias"],
                information: {
                  weight: "0.27 kg",
                  dimensions: "14.3 x 5.7 x 3.2 cm",
                  materials: "Polímero reforzado, carcasa rugerizada",
                  other_info: "Certificación militar MIL-STD 810F, IP65, botón SOS programable, GPS integrado"
                }
              },
              
              {
                id: 10,
                image: "assets/images/product-image/10/1.webp",
                alt: "GPS Satelital Garmin inReach Explorer+",
                category: "Seguridad",
                title: "GPS Satelital Garmin inReach",
                price: "$600",
                oldPrice: null,
                badges: ["new"],
                index: 10,
                description: "El Garmin inReach Explorer+ es un GPS satelital con mensajería bidireccional que permite comunicación y rastreo global a través de la red Iridium. Diseñado para aventureros, expediciones, operaciones rurales y seguridad personal.",
                sku: "GAR-INR-EXP-010",
                availability: 9,
                tags: ["gps", "satelital", "emergencias", "tracking"],
                information: {
                  weight: "0.21 kg",
                  dimensions: "16.4 x 6.8 x 3.5 cm",
                  materials: "Carcasa resistente IPX7",
                  other_info: "Mensajería satelital, botón SOS 24/7, batería hasta 100h en modo expedición"
                }
              },
              
              {
                id: 11,
                image: "assets/images/product-image/11/1.webp",
                alt: "Antena Satelital Cobham BGAN Explorer 710",
                category: "Comunicacion",
                title: "Antena Satelital Cobham BGAN",
                price: "$5,600",
                oldPrice: null,
                badges: ["sale"],
                index: 11,
                description: "La Cobham BGAN Explorer 710 es una antena satelital portátil de alto rendimiento diseñada para transmisión de datos de misión crítica. Permite conectividad de banda ancha IP casi en cualquier parte del mundo mediante la red Inmarsat.",
                sku: "COB-BGAN-710-011",
                availability: 20,
                tags: ["satelital", "bgan", "inmarsat", "datos"],
                information: {
                  weight: "3.9 kg",
                  dimensions: "38 x 38 x 5.6 cm",
                  materials: "Aluminio, polímeros de alta resistencia",
                  other_info: "Velocidades hasta 650 kbps, WiFi integrado, interfaz Ethernet"
                }
              },
              
              {
                id: 12,
                image: "assets/images/product-image/12/1.webp",
                alt: "Radio Marino ICOM IC-M35",
                category: "Comunicacion",
                title: "Radio Marino ICOM IC-M35",
                price: "$350",
                oldPrice: null,
                badges: ["sale"],
                index: 12,
                description: "El ICOM IC-M35 es un radio marino VHF portátil, compacto y flotante, diseñado para comunicación confiable en entornos marítimos. Ideal para embarcaciones recreativas, pesca y seguridad costera.",
                sku: "ICOM-M35-012",
                availability: 6,
                tags: ["radio", "vhf", "marino", "icom"],
                information: {
                  weight: "0.30 kg",
                  dimensions: "14.5 x 6.3 x 3.0 cm",
                  materials: "Carcasa sellada IPX7",
                  other_info: "Flotante, audio potente, batería hasta 8 horas, canales marinos internacionales"
                }
              }              
        ]

    }
};
