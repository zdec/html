--
-- Equivalente MySQL 8+ del esquema PostgreSQL (database/schema_init.sql)
-- Charset utf8mb4. Ejecutar sobre una base vacía (p. ej. CREATE DATABASE ecommerce ...).
--

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET sql_mode = 'NO_ENGINE_SUBSTITUTION';



CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` mediumtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `password_temp_created_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_last_activity_index` (`last_activity`),
  KEY `sessions_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT 0,
  `weight` varchar(255) DEFAULT NULL,
  `dimensions` varchar(255) DEFAULT NULL,
  `materials` varchar(255) DEFAULT NULL,
  `other_info` text DEFAULT NULL,
  `badges` json DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_tag` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_tag_product_id_tag_id_unique` (`product_id`,`tag_id`),
  CONSTRAINT `product_tag_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `email_guest` varchar(255) DEFAULT NULL,
  `phone_guest` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `document_number` varchar(50) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `qty` int NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inventory_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL,
  `type` varchar(50) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `inventory_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_movements_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sales_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'venta',
  `amount` decimal(12,2) NOT NULL,
  `date` date NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `sales_documents_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `wishlist_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) DEFAULT NULL,
  `guest_token` varchar(255) DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wishlist_items_session_id_index` (`session_id`),
  KEY `wishlist_items_guest_token_index` (`guest_token`),
  CONSTRAINT `wishlist_items_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `wishlist_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chat_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `guest_token` varchar(255) DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `channel` varchar(30) NOT NULL DEFAULT 'web',
  `status` varchar(50) NOT NULL DEFAULT 'idle',
  `metadata` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_sessions_session_id_index` (`session_id`),
  KEY `chat_sessions_guest_token_index` (`guest_token`),
  CONSTRAINT `chat_sessions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chat_session_id` bigint unsigned NOT NULL,
  `role` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `chat_messages_chat_session_id_foreign` FOREIGN KEY (`chat_session_id`) REFERENCES `chat_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_search_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `searchable_text` text NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `metadata` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_search_documents_product_id_unique` (`product_id`),
  KEY `product_search_documents_active_index` (`active`),
  CONSTRAINT `product_search_documents_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_embeddings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `model` varchar(100) NOT NULL,
  `embedding` json DEFAULT NULL,
  `updated_at_source` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_embeddings_product_model_unique` (`product_id`,`model`),
  CONSTRAINT `product_embeddings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Datos migrados desde PostgreSQL (COPY)

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
('1','Comunicacion','comunicacion','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('2','Seguridad','seguridad','2026-03-09 23:41:31','2026-03-09 23:41:31');

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
('1','satelital','satelital','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('2','comunicacion','comunicacion','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('3','internet','internet','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('4','maritimo','maritimo','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('5','telefono','telefono','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('6','emergencias','emergencias','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('7','portatil','portatil','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('8','router','router','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('9','wifi','wifi','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('10','seguridad','seguridad','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('11','gps','gps','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('12','marino','marino','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('13','navegacion','navegacion','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('14','escaner','escaner','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('15','rf','rf','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('16','bloqueador','bloqueador','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('17','radio','radio','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('18','icom','icom','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('19','aereo','aereo','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('20','frecuencias','frecuencias','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('21','iridium','iridium','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('22','tracking','tracking','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('23','bgan','bgan','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('24','inmarsat','inmarsat','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('25','datos','datos','2026-03-09 23:41:31','2026-03-09 23:41:31'),
('26','vhf','vhf','2026-03-09 23:41:31','2026-03-09 23:41:31');

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_admin`, `must_change_password`, `password_temp_created_at`, `created_at`, `updated_at`) VALUES
('1','Admin','admin@itsecursas.co','2026-03-09 23:41:31','$2y$10$1WWObiLaNYC2ZTsaHKLEPOLfj86bLjjMxwYR3ULRTSeKm90SoYEWm',NULL,1,0,NULL,'2026-03-09 23:41:31','2026-03-09 23:41:31');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
('1','0001_01_01_000000_create_users_table','1'),
('2','0001_01_01_000001_create_cache_table','1'),
('3','0001_01_01_000002_create_jobs_table','1'),
('4','0001_01_01_000003_create_categories_table','1'),
('5','0001_01_01_000004_create_products_table','1'),
('6','0001_01_01_000005_create_product_images_table','1'),
('7','0001_01_01_000006_create_tags_table','1'),
('8','0001_01_01_000007_create_product_tag_table','1');

INSERT INTO `products` (`id`, `category_id`, `sku`, `slug`, `name`, `description`, `price`, `old_price`, `stock`, `weight`, `dimensions`, `materials`, `other_info`, `badges`, `active`, `created_at`, `updated_at`) VALUES
('1','1','STL-MAR-ANT-001','antena-starlink-maritime-1','Antena Starlink Maritime','Starlink Maritime es una solución de internet satelital de alta velocidad diseñada para embarcaciones y operaciones en mar abierto. Utiliza la constelación de satélites en órbita baja (LEO) de SpaceX para ofrecer conexión estable, baja latencia y cobertura global, ideal para barcos comerciales, yates, pesca industrial y operaciones offshore.','3100.00',NULL,'18','2.9 kg','50 x 30 x 12 cm','Aluminio, acero inoxidable, polímeros reforzados','Antena plana de autoalineación, resistente a ambiente marino, incluye fuente de poder y kit de montaje','["new"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('2','1','INM-ISP2-TEL-002','telefono-satelital-isatphone-2-1','Telefono Satelital IsatPhone 2','El IsatPhone 2 de Inmarsat es un teléfono satelital robusto y confiable, diseñado para comunicación de voz y SMS en cualquier parte del mundo. Ideal para expediciones, minería, petróleo y gas, zonas rurales y emergencias.','1150.00','1280.00','10','0.32 kg','17 x 5.4 x 2.9 cm','Polímero reforzado, pantalla transflectiva','Autonomía hasta 8h en llamada y 160h en espera, GPS integrado, certificación IP65','["sale","new"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('3','2','SEC-ROU-PT-003','enrutador-portatil-4g5g-1','Enrutador Portátil 4G/5G','Enrutador portátil de alta seguridad diseñado para crear redes privadas móviles seguras. Ideal para viajes, trabajo remoto, periodistas y equipos técnicos que requieren conectividad confiable con cifrado avanzado.','390.00',NULL,'20','0.25 kg','12 x 8 x 2 cm','Plástico ABS de alta resistencia','Soporte VPN, WiFi 802.11ac, hasta 20 dispositivos, batería integrada','["new"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('4','2','GAR-GPS-79S-004','gps-marino-garmin-gpsmap-1','GPS Marino Garmin GPSMAP','GPS portátil marino Garmin 79s, diseñado para navegación marítima profesional y recreativa. Flota en el agua, ofrece alta sensibilidad GNSS y cartografía básica integrada.','480.00',NULL,'7','0.28 kg','15.2 x 6.6 x 3.0 cm','Carcasa sellada IPX7','Soporte GPS, GLONASS y Galileo, batería hasta 19 horas','["new"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('5','2','RF-SCAN-PRO-005','escaner-profesional-rf-1','Escáner Profesional RF','Escáner profesional de radiofrecuencia diseñado para detección de señales inalámbricas, espionaje electrónico y análisis de espectro en entornos corporativos y de seguridad.','1900.00',NULL,'15','0.55 kg','22 x 14 x 5 cm','Plástico técnico, pantalla OLED','Cobertura de amplio espectro RF, almacenamiento interno, alertas programables','[]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('6','2','JAM-MULTI-006','bloqueador-de-senal-1','Bloqueador de Señal','Bloqueador de señal multibanda diseñado para entornos controlados donde se requiere inhibición de comunicaciones inalámbricas. Uso exclusivo para entidades autorizadas.','2500.00','2900.00','20','1.7 kg','30 x 25 x 10 cm','Carcasa metálica con disipación térmica','Bloqueo GSM, 3G, 4G, GPS y WiFi, alcance variable según entorno','["sale","new"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('7','1','ICOM-SAT100-007','radio-satelital-icom-ic-sat100-1','Radio Satelital ICOM IC-SAT100','Radio satelital profesional ICOM IC-SAT100 que opera sobre la red Iridium, permitiendo comunicación grupal global sin infraestructura terrestre.','1330.00',NULL,'8','0.5 kg','15 x 6 x 4 cm','Carcasa reforzada IP67','Cobertura global, botón PTT, comunicación grupal y privada','[]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('8','2','AIR-RF30-008','escaner-aereo-rf-30-1','Escáner Aéreo RF-30','Escáner de frecuencias aéreas diseñado para monitoreo de comunicaciones aeronáuticas y análisis de tráfico aéreo.','1650.00','1900.00','25','0.9 kg','28 x 18 x 6 cm','Aluminio aeronáutico','Base de datos actualizable, grabación de audio, pantalla a color','["sale","new"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('9','1','IRD-EXT-9555-009','telefono-satelital-iridium-1','Telefono Satelital Iridium','El Iridium Extreme 9555 es un teléfono satelital de grado militar diseñado para operar en los entornos más hostiles del planeta. Ofrece comunicación de voz y SMS con cobertura global real gracias a la red Iridium.','1300.00','1550.00','12','0.27 kg','14.3 x 5.7 x 3.2 cm','Polímero reforzado, carcasa rugerizada','Certificación militar MIL-STD 810F, IP65, botón SOS programable, GPS integrado','["sale","new"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('10','2','GAR-INR-EXP-010','gps-satelital-garmin-inreach-1','GPS Satelital Garmin inReach','El Garmin inReach Explorer+ es un GPS satelital con mensajería bidireccional que permite comunicación y rastreo global a través de la red Iridium. Diseñado para aventureros, expediciones, operaciones rurales y seguridad personal.','600.00',NULL,'9','0.21 kg','16.4 x 6.8 x 3.5 cm','Carcasa resistente IPX7','Mensajería satelital, botón SOS 24/7, batería hasta 100h en modo expedición','["new"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('11','1','COB-BGAN-710-011','antena-satelital-cobham-bgan-1','Antena Satelital Cobham BGAN','La Cobham BGAN Explorer 710 es una antena satelital portátil de alto rendimiento diseñada para transmisión de datos de misión crítica. Permite conectividad de banda ancha IP casi en cualquier parte del mundo mediante la red Inmarsat.','5600.00',NULL,'20','3.9 kg','38 x 38 x 5.6 cm','Aluminio, polímeros de alta resistencia','Velocidades hasta 650 kbps, WiFi integrado, interfaz Ethernet','["sale"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44'),
('12','1','ICOM-M35-012','radio-marino-icom-ic-m35-1','Radio Marino ICOM IC-M35','El ICOM IC-M35 es un radio marino VHF portátil, compacto y flotante, diseñado para comunicación confiable en entornos marítimos. Ideal para embarcaciones recreativas, pesca y seguridad costera.','450.00',NULL,'6','0.30 kg','14.5 x 6.3 x 3.0 cm','Carcasa sellada IPX7','Flotante, audio potente, batería hasta 8 horas, canales marinos internacionales','["sale"]',1,'2026-03-09 23:41:31','2026-03-09 23:52:44');

INSERT INTO `product_images` (`id`, `product_id`, `path`, `order`, `created_at`, `updated_at`) VALUES
('61','1','assets/images/products/1/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('62','1','assets/images/products/1/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('63','1','assets/images/products/1/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('64','1','assets/images/products/1/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('65','1','assets/images/products/1/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('66','2','assets/images/products/2/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('67','2','assets/images/products/2/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('68','2','assets/images/products/2/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('69','2','assets/images/products/2/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('70','2','assets/images/products/2/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('71','3','assets/images/products/3/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('72','3','assets/images/products/3/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('73','3','assets/images/products/3/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('74','3','assets/images/products/3/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('75','3','assets/images/products/3/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('76','4','assets/images/products/4/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('77','4','assets/images/products/4/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('78','4','assets/images/products/4/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('79','4','assets/images/products/4/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('80','4','assets/images/products/4/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('81','5','assets/images/products/5/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('82','5','assets/images/products/5/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('83','5','assets/images/products/5/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('84','5','assets/images/products/5/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('85','5','assets/images/products/5/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('86','6','assets/images/products/6/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('87','6','assets/images/products/6/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('88','6','assets/images/products/6/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('89','6','assets/images/products/6/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('90','6','assets/images/products/6/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('91','7','assets/images/products/7/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('92','7','assets/images/products/7/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('93','7','assets/images/products/7/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('94','7','assets/images/products/7/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('95','7','assets/images/products/7/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('96','8','assets/images/products/8/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('97','8','assets/images/products/8/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('98','8','assets/images/products/8/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('99','8','assets/images/products/8/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('100','8','assets/images/products/8/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('101','9','assets/images/products/9/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('102','9','assets/images/products/9/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('103','9','assets/images/products/9/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('104','9','assets/images/products/9/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('105','9','assets/images/products/9/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('106','10','assets/images/products/10/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('107','10','assets/images/products/10/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('108','10','assets/images/products/10/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('109','10','assets/images/products/10/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('110','10','assets/images/products/10/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('111','11','assets/images/products/11/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('112','11','assets/images/products/11/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('113','11','assets/images/products/11/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('114','11','assets/images/products/11/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('115','11','assets/images/products/11/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('116','12','assets/images/products/12/1.webp','0','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('117','12','assets/images/products/12/2.webp','1','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('118','12','assets/images/products/12/3.webp','2','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('119','12','assets/images/products/12/4.webp','3','2026-03-09 23:52:44','2026-03-09 23:52:44'),
('120','12','assets/images/products/12/5.webp','4','2026-03-09 23:52:44','2026-03-09 23:52:44');

INSERT INTO `product_tag` (`id`, `product_id`, `tag_id`, `created_at`, `updated_at`) VALUES
('1','1','1',NULL,NULL),
('2','1','2',NULL,NULL),
('3','1','3',NULL,NULL),
('4','1','4',NULL,NULL),
('5','2','1',NULL,NULL),
('6','2','5',NULL,NULL),
('7','2','6',NULL,NULL),
('8','2','7',NULL,NULL),
('9','3','7',NULL,NULL),
('10','3','8',NULL,NULL),
('11','3','9',NULL,NULL),
('12','3','10',NULL,NULL),
('13','4','11',NULL,NULL),
('14','4','12',NULL,NULL),
('15','4','13',NULL,NULL),
('16','5','10',NULL,NULL),
('17','5','14',NULL,NULL),
('18','5','15',NULL,NULL),
('19','6','10',NULL,NULL),
('20','6','15',NULL,NULL),
('21','6','16',NULL,NULL),
('22','7','1',NULL,NULL),
('23','7','17',NULL,NULL),
('24','7','18',NULL,NULL),
('25','8','14',NULL,NULL),
('26','8','19',NULL,NULL),
('27','8','20',NULL,NULL),
('28','9','1',NULL,NULL),
('29','9','5',NULL,NULL),
('30','9','6',NULL,NULL),
('31','9','21',NULL,NULL),
('32','10','1',NULL,NULL),
('33','10','6',NULL,NULL),
('34','10','11',NULL,NULL),
('35','10','22',NULL,NULL),
('36','11','1',NULL,NULL),
('37','11','23',NULL,NULL),
('38','11','24',NULL,NULL),
('39','11','25',NULL,NULL),
('40','12','12',NULL,NULL),
('41','12','17',NULL,NULL),
('42','12','18',NULL,NULL),
('43','12','26',NULL,NULL);

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('WZyzcbnrMTUr9kLQa895y96VvBV9l7wIsg3KYLvv',NULL,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0VBUEJENFAyeEFBUDdEYzJlRE1SUjlVdmt0dHFpYXV3OFBoQVFNbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==','1773100321');


SET FOREIGN_KEY_CHECKS = 1;
