--
-- PostgreSQL database dump
--

-- Dumped from database version 16.13
-- Dumped by pg_dump version 16.13

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: product_images; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.product_images (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    path character varying(255) NOT NULL,
    "order" integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: product_images_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.product_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: product_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.product_images_id_seq OWNED BY public.product_images.id;


--
-- Name: product_tag; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.product_tag (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    tag_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: product_tag_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.product_tag_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: product_tag_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.product_tag_id_seq OWNED BY public.product_tag.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.products (
    id bigint NOT NULL,
    category_id bigint NOT NULL,
    sku character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    price numeric(12,2) NOT NULL,
    old_price numeric(12,2),
    stock integer DEFAULT 0 NOT NULL,
    weight character varying(255),
    dimensions character varying(255),
    materials character varying(255),
    other_info text,
    badges json,
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: tags; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tags (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: tags_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tags_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tags_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tags_id_seq OWNED BY public.tags.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    is_admin boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: customers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.customers (
    id bigint NOT NULL,
    user_id bigint,
    email character varying(255) NOT NULL,
    name character varying(255),
    phone character varying(50),
    address text,
    city character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: customers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.customers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.customers_id_seq OWNED BY public.customers.id;


--
-- Name: orders; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.orders (
    id bigint NOT NULL,
    customer_id bigint,
    email_guest character varying(255),
    phone_guest character varying(50),
    status character varying(50) NOT NULL DEFAULT 'draft',
    document_number character varying(50),
    total numeric(12,2) DEFAULT 0,
    notes text,
    source character varying(50),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: orders_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.orders_id_seq OWNED BY public.orders.id;


--
-- Name: order_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.order_items (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    product_id bigint NOT NULL,
    qty integer NOT NULL,
    unit_price numeric(12,2) NOT NULL,
    subtotal numeric(12,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: order_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.order_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.order_items_id_seq OWNED BY public.order_items.id;


--
-- Name: inventory_movements; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.inventory_movements (
    id bigint NOT NULL,
    product_id bigint NOT NULL,
    order_id bigint,
    quantity integer NOT NULL,
    type character varying(50) NOT NULL,
    reference character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: inventory_movements_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.inventory_movements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.inventory_movements_id_seq OWNED BY public.inventory_movements.id;


--
-- Name: sales_documents; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sales_documents (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    type character varying(50) NOT NULL DEFAULT 'venta',
    amount numeric(12,2) NOT NULL,
    date date NOT NULL,
    reference character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: sales_documents_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.sales_documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sales_documents_id_seq OWNED BY public.sales_documents.id;


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: product_images id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_images ALTER COLUMN id SET DEFAULT nextval('public.product_images_id_seq'::regclass);


--
-- Name: product_tag id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_tag ALTER COLUMN id SET DEFAULT nextval('public.product_tag_id_seq'::regclass);


--
-- Name: products id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- Name: tags id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tags ALTER COLUMN id SET DEFAULT nextval('public.tags_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: customers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers ALTER COLUMN id SET DEFAULT nextval('public.customers_id_seq'::regclass);


--
-- Name: orders id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders ALTER COLUMN id SET DEFAULT nextval('public.orders_id_seq'::regclass);


--
-- Name: order_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_items ALTER COLUMN id SET DEFAULT nextval('public.order_items_id_seq'::regclass);


--
-- Name: inventory_movements id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_movements ALTER COLUMN id SET DEFAULT nextval('public.inventory_movements_id_seq'::regclass);


--
-- Name: sales_documents id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sales_documents ALTER COLUMN id SET DEFAULT nextval('public.sales_documents_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.categories (id, name, slug, created_at, updated_at) FROM stdin;
1	Comunicacion	comunicacion	2026-03-09 23:41:31	2026-03-09 23:41:31
2	Seguridad	seguridad	2026-03-09 23:41:31	2026-03-09 23:41:31
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	0001_01_01_000003_create_categories_table	1
5	0001_01_01_000004_create_products_table	1
6	0001_01_01_000005_create_product_images_table	1
7	0001_01_01_000006_create_tags_table	1
8	0001_01_01_000007_create_product_tag_table	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: product_images; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.product_images (id, product_id, path, "order", created_at, updated_at) FROM stdin;
61	1	assets/images/products/1/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
62	1	assets/images/products/1/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
63	1	assets/images/products/1/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
64	1	assets/images/products/1/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
65	1	assets/images/products/1/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
66	2	assets/images/products/2/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
67	2	assets/images/products/2/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
68	2	assets/images/products/2/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
69	2	assets/images/products/2/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
70	2	assets/images/products/2/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
71	3	assets/images/products/3/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
72	3	assets/images/products/3/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
73	3	assets/images/products/3/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
74	3	assets/images/products/3/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
75	3	assets/images/products/3/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
76	4	assets/images/products/4/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
77	4	assets/images/products/4/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
78	4	assets/images/products/4/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
79	4	assets/images/products/4/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
80	4	assets/images/products/4/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
81	5	assets/images/products/5/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
82	5	assets/images/products/5/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
83	5	assets/images/products/5/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
84	5	assets/images/products/5/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
85	5	assets/images/products/5/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
86	6	assets/images/products/6/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
87	6	assets/images/products/6/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
88	6	assets/images/products/6/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
89	6	assets/images/products/6/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
90	6	assets/images/products/6/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
91	7	assets/images/products/7/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
92	7	assets/images/products/7/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
93	7	assets/images/products/7/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
94	7	assets/images/products/7/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
95	7	assets/images/products/7/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
96	8	assets/images/products/8/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
97	8	assets/images/products/8/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
98	8	assets/images/products/8/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
99	8	assets/images/products/8/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
100	8	assets/images/products/8/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
101	9	assets/images/products/9/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
102	9	assets/images/products/9/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
103	9	assets/images/products/9/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
104	9	assets/images/products/9/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
105	9	assets/images/products/9/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
106	10	assets/images/products/10/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
107	10	assets/images/products/10/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
108	10	assets/images/products/10/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
109	10	assets/images/products/10/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
110	10	assets/images/products/10/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
111	11	assets/images/products/11/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
112	11	assets/images/products/11/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
113	11	assets/images/products/11/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
114	11	assets/images/products/11/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
115	11	assets/images/products/11/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
116	12	assets/images/products/12/1.webp	0	2026-03-09 23:52:44	2026-03-09 23:52:44
117	12	assets/images/products/12/2.webp	1	2026-03-09 23:52:44	2026-03-09 23:52:44
118	12	assets/images/products/12/3.webp	2	2026-03-09 23:52:44	2026-03-09 23:52:44
119	12	assets/images/products/12/4.webp	3	2026-03-09 23:52:44	2026-03-09 23:52:44
120	12	assets/images/products/12/5.webp	4	2026-03-09 23:52:44	2026-03-09 23:52:44
\.


--
-- Data for Name: product_tag; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.product_tag (id, product_id, tag_id, created_at, updated_at) FROM stdin;
1	1	1	\N	\N
2	1	2	\N	\N
3	1	3	\N	\N
4	1	4	\N	\N
5	2	1	\N	\N
6	2	5	\N	\N
7	2	6	\N	\N
8	2	7	\N	\N
9	3	7	\N	\N
10	3	8	\N	\N
11	3	9	\N	\N
12	3	10	\N	\N
13	4	11	\N	\N
14	4	12	\N	\N
15	4	13	\N	\N
16	5	10	\N	\N
17	5	14	\N	\N
18	5	15	\N	\N
19	6	10	\N	\N
20	6	15	\N	\N
21	6	16	\N	\N
22	7	1	\N	\N
23	7	17	\N	\N
24	7	18	\N	\N
25	8	14	\N	\N
26	8	19	\N	\N
27	8	20	\N	\N
28	9	1	\N	\N
29	9	5	\N	\N
30	9	6	\N	\N
31	9	21	\N	\N
32	10	1	\N	\N
33	10	6	\N	\N
34	10	11	\N	\N
35	10	22	\N	\N
36	11	1	\N	\N
37	11	23	\N	\N
38	11	24	\N	\N
39	11	25	\N	\N
40	12	12	\N	\N
41	12	17	\N	\N
42	12	18	\N	\N
43	12	26	\N	\N
\.


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.products (id, category_id, sku, slug, name, description, price, old_price, stock, weight, dimensions, materials, other_info, badges, active, created_at, updated_at) FROM stdin;
1	1	STL-MAR-ANT-001	antena-starlink-maritime-1	Antena Starlink Maritime	Starlink Maritime es una solución de internet satelital de alta velocidad diseñada para embarcaciones y operaciones en mar abierto. Utiliza la constelación de satélites en órbita baja (LEO) de SpaceX para ofrecer conexión estable, baja latencia y cobertura global, ideal para barcos comerciales, yates, pesca industrial y operaciones offshore.	3100.00	\N	18	2.9 kg	50 x 30 x 12 cm	Aluminio, acero inoxidable, polímeros reforzados	Antena plana de autoalineación, resistente a ambiente marino, incluye fuente de poder y kit de montaje	["new"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
2	1	INM-ISP2-TEL-002	telefono-satelital-isatphone-2-1	Telefono Satelital IsatPhone 2	El IsatPhone 2 de Inmarsat es un teléfono satelital robusto y confiable, diseñado para comunicación de voz y SMS en cualquier parte del mundo. Ideal para expediciones, minería, petróleo y gas, zonas rurales y emergencias.	1150.00	1280.00	10	0.32 kg	17 x 5.4 x 2.9 cm	Polímero reforzado, pantalla transflectiva	Autonomía hasta 8h en llamada y 160h en espera, GPS integrado, certificación IP65	["sale","new"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
3	2	SEC-ROU-PT-003	enrutador-portatil-4g5g-1	Enrutador Portátil 4G/5G	Enrutador portátil de alta seguridad diseñado para crear redes privadas móviles seguras. Ideal para viajes, trabajo remoto, periodistas y equipos técnicos que requieren conectividad confiable con cifrado avanzado.	390.00	\N	20	0.25 kg	12 x 8 x 2 cm	Plástico ABS de alta resistencia	Soporte VPN, WiFi 802.11ac, hasta 20 dispositivos, batería integrada	["new"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
4	2	GAR-GPS-79S-004	gps-marino-garmin-gpsmap-1	GPS Marino Garmin GPSMAP	GPS portátil marino Garmin 79s, diseñado para navegación marítima profesional y recreativa. Flota en el agua, ofrece alta sensibilidad GNSS y cartografía básica integrada.	480.00	\N	7	0.28 kg	15.2 x 6.6 x 3.0 cm	Carcasa sellada IPX7	Soporte GPS, GLONASS y Galileo, batería hasta 19 horas	["new"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
5	2	RF-SCAN-PRO-005	escaner-profesional-rf-1	Escáner Profesional RF	Escáner profesional de radiofrecuencia diseñado para detección de señales inalámbricas, espionaje electrónico y análisis de espectro en entornos corporativos y de seguridad.	1900.00	\N	15	0.55 kg	22 x 14 x 5 cm	Plástico técnico, pantalla OLED	Cobertura de amplio espectro RF, almacenamiento interno, alertas programables	[]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
6	2	JAM-MULTI-006	bloqueador-de-senal-1	Bloqueador de Señal	Bloqueador de señal multibanda diseñado para entornos controlados donde se requiere inhibición de comunicaciones inalámbricas. Uso exclusivo para entidades autorizadas.	2500.00	2900.00	20	1.7 kg	30 x 25 x 10 cm	Carcasa metálica con disipación térmica	Bloqueo GSM, 3G, 4G, GPS y WiFi, alcance variable según entorno	["sale","new"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
7	1	ICOM-SAT100-007	radio-satelital-icom-ic-sat100-1	Radio Satelital ICOM IC-SAT100	Radio satelital profesional ICOM IC-SAT100 que opera sobre la red Iridium, permitiendo comunicación grupal global sin infraestructura terrestre.	1330.00	\N	8	0.5 kg	15 x 6 x 4 cm	Carcasa reforzada IP67	Cobertura global, botón PTT, comunicación grupal y privada	[]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
8	2	AIR-RF30-008	escaner-aereo-rf-30-1	Escáner Aéreo RF-30	Escáner de frecuencias aéreas diseñado para monitoreo de comunicaciones aeronáuticas y análisis de tráfico aéreo.	1650.00	1900.00	25	0.9 kg	28 x 18 x 6 cm	Aluminio aeronáutico	Base de datos actualizable, grabación de audio, pantalla a color	["sale","new"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
9	1	IRD-EXT-9555-009	telefono-satelital-iridium-1	Telefono Satelital Iridium	El Iridium Extreme 9555 es un teléfono satelital de grado militar diseñado para operar en los entornos más hostiles del planeta. Ofrece comunicación de voz y SMS con cobertura global real gracias a la red Iridium.	1300.00	1550.00	12	0.27 kg	14.3 x 5.7 x 3.2 cm	Polímero reforzado, carcasa rugerizada	Certificación militar MIL-STD 810F, IP65, botón SOS programable, GPS integrado	["sale","new"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
10	2	GAR-INR-EXP-010	gps-satelital-garmin-inreach-1	GPS Satelital Garmin inReach	El Garmin inReach Explorer+ es un GPS satelital con mensajería bidireccional que permite comunicación y rastreo global a través de la red Iridium. Diseñado para aventureros, expediciones, operaciones rurales y seguridad personal.	600.00	\N	9	0.21 kg	16.4 x 6.8 x 3.5 cm	Carcasa resistente IPX7	Mensajería satelital, botón SOS 24/7, batería hasta 100h en modo expedición	["new"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
11	1	COB-BGAN-710-011	antena-satelital-cobham-bgan-1	Antena Satelital Cobham BGAN	La Cobham BGAN Explorer 710 es una antena satelital portátil de alto rendimiento diseñada para transmisión de datos de misión crítica. Permite conectividad de banda ancha IP casi en cualquier parte del mundo mediante la red Inmarsat.	5600.00	\N	20	3.9 kg	38 x 38 x 5.6 cm	Aluminio, polímeros de alta resistencia	Velocidades hasta 650 kbps, WiFi integrado, interfaz Ethernet	["sale"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
12	1	ICOM-M35-012	radio-marino-icom-ic-m35-1	Radio Marino ICOM IC-M35	El ICOM IC-M35 es un radio marino VHF portátil, compacto y flotante, diseñado para comunicación confiable en entornos marítimos. Ideal para embarcaciones recreativas, pesca y seguridad costera.	450.00	\N	6	0.30 kg	14.5 x 6.3 x 3.0 cm	Carcasa sellada IPX7	Flotante, audio potente, batería hasta 8 horas, canales marinos internacionales	["sale"]	t	2026-03-09 23:41:31	2026-03-09 23:52:44
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
WZyzcbnrMTUr9kLQa895y96VvBV9l7wIsg3KYLvv	\N	192.168.65.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0VBUEJENFAyeEFBUDdEYzJlRE1SUjlVdmt0dHFpYXV3OFBoQVFNbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1773100321
\.


--
-- Data for Name: tags; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.tags (id, name, slug, created_at, updated_at) FROM stdin;
1	satelital	satelital	2026-03-09 23:41:31	2026-03-09 23:41:31
2	comunicacion	comunicacion	2026-03-09 23:41:31	2026-03-09 23:41:31
3	internet	internet	2026-03-09 23:41:31	2026-03-09 23:41:31
4	maritimo	maritimo	2026-03-09 23:41:31	2026-03-09 23:41:31
5	telefono	telefono	2026-03-09 23:41:31	2026-03-09 23:41:31
6	emergencias	emergencias	2026-03-09 23:41:31	2026-03-09 23:41:31
7	portatil	portatil	2026-03-09 23:41:31	2026-03-09 23:41:31
8	router	router	2026-03-09 23:41:31	2026-03-09 23:41:31
9	wifi	wifi	2026-03-09 23:41:31	2026-03-09 23:41:31
10	seguridad	seguridad	2026-03-09 23:41:31	2026-03-09 23:41:31
11	gps	gps	2026-03-09 23:41:31	2026-03-09 23:41:31
12	marino	marino	2026-03-09 23:41:31	2026-03-09 23:41:31
13	navegacion	navegacion	2026-03-09 23:41:31	2026-03-09 23:41:31
14	escaner	escaner	2026-03-09 23:41:31	2026-03-09 23:41:31
15	rf	rf	2026-03-09 23:41:31	2026-03-09 23:41:31
16	bloqueador	bloqueador	2026-03-09 23:41:31	2026-03-09 23:41:31
17	radio	radio	2026-03-09 23:41:31	2026-03-09 23:41:31
18	icom	icom	2026-03-09 23:41:31	2026-03-09 23:41:31
19	aereo	aereo	2026-03-09 23:41:31	2026-03-09 23:41:31
20	frecuencias	frecuencias	2026-03-09 23:41:31	2026-03-09 23:41:31
21	iridium	iridium	2026-03-09 23:41:31	2026-03-09 23:41:31
22	tracking	tracking	2026-03-09 23:41:31	2026-03-09 23:41:31
23	bgan	bgan	2026-03-09 23:41:31	2026-03-09 23:41:31
24	inmarsat	inmarsat	2026-03-09 23:41:31	2026-03-09 23:41:31
25	datos	datos	2026-03-09 23:41:31	2026-03-09 23:41:31
26	vhf	vhf	2026-03-09 23:41:31	2026-03-09 23:41:31
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, is_admin, created_at, updated_at) FROM stdin;
1	Admin	admin@itsecursas.co	2026-03-09 23:41:31	$2y$10$1WWObiLaNYC2ZTsaHKLEPOLfj86bLjjMxwYR3ULRTSeKm90SoYEWm	\N	t	2026-03-09 23:41:31	2026-03-09 23:41:31
\.


--
-- Data for Name: customers; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.customers (id, user_id, email, name, phone, address, city, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.orders (id, customer_id, email_guest, phone_guest, status, document_number, total, notes, source, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.order_items (id, order_id, product_id, qty, unit_price, subtotal, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: inventory_movements; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.inventory_movements (id, product_id, order_id, quantity, type, reference, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: sales_documents; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sales_documents (id, order_id, type, amount, date, reference, created_at, updated_at) FROM stdin;
\.


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.categories_id_seq', 2, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 8, true);


--
-- Name: product_images_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.product_images_id_seq', 120, true);


--
-- Name: product_tag_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.product_tag_id_seq', 43, true);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.products_id_seq', 12, true);


--
-- Name: tags_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tags_id_seq', 26, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- Name: customers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.customers_id_seq', 1, false);


--
-- Name: orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.orders_id_seq', 1, false);


--
-- Name: order_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.order_items_id_seq', 1, false);


--
-- Name: inventory_movements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.inventory_movements_id_seq', 1, false);


--
-- Name: sales_documents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.sales_documents_id_seq', 1, false);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: categories categories_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_slug_unique UNIQUE (slug);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: product_images product_images_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_images
    ADD CONSTRAINT product_images_pkey PRIMARY KEY (id);


--
-- Name: product_tag product_tag_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_tag
    ADD CONSTRAINT product_tag_pkey PRIMARY KEY (id);


--
-- Name: product_tag product_tag_product_id_tag_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_tag
    ADD CONSTRAINT product_tag_product_id_tag_id_unique UNIQUE (product_id, tag_id);


--
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: products products_sku_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_sku_unique UNIQUE (sku);


--
-- Name: products products_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_slug_unique UNIQUE (slug);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: tags tags_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tags
    ADD CONSTRAINT tags_pkey PRIMARY KEY (id);


--
-- Name: tags tags_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tags
    ADD CONSTRAINT tags_slug_unique UNIQUE (slug);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: customers customers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_pkey PRIMARY KEY (id);


--
-- Name: orders orders_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);


--
-- Name: order_items order_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_pkey PRIMARY KEY (id);


--
-- Name: inventory_movements inventory_movements_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_movements
    ADD CONSTRAINT inventory_movements_pkey PRIMARY KEY (id);


--
-- Name: sales_documents sales_documents_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sales_documents
    ADD CONSTRAINT sales_documents_pkey PRIMARY KEY (id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: product_images product_images_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_images
    ADD CONSTRAINT product_images_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: product_tag product_tag_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_tag
    ADD CONSTRAINT product_tag_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: product_tag product_tag_tag_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.product_tag
    ADD CONSTRAINT product_tag_tag_id_foreign FOREIGN KEY (tag_id) REFERENCES public.tags(id) ON DELETE CASCADE;


--
-- Name: products products_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: customers customers_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: orders orders_customer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES public.customers(id) ON DELETE SET NULL;


--
-- Name: order_items order_items_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_order_id_foreign FOREIGN KEY (order_id) REFERENCES public.orders(id) ON DELETE CASCADE;


--
-- Name: order_items order_items_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: inventory_movements inventory_movements_product_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_movements
    ADD CONSTRAINT inventory_movements_product_id_foreign FOREIGN KEY (product_id) REFERENCES public.products(id) ON DELETE CASCADE;


--
-- Name: inventory_movements inventory_movements_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.inventory_movements
    ADD CONSTRAINT inventory_movements_order_id_foreign FOREIGN KEY (order_id) REFERENCES public.orders(id) ON DELETE SET NULL;


--
-- Name: sales_documents sales_documents_order_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sales_documents
    ADD CONSTRAINT sales_documents_order_id_foreign FOREIGN KEY (order_id) REFERENCES public.orders(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--
