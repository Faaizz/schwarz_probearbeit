--
-- PostgreSQL database dump
--

-- Dumped from database version 13.6
-- Dumped by pg_dump version 13.6

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
-- Name: address; Type: TABLE; Schema: public; Owner: symfony
--

CREATE TABLE public.address (
    id integer NOT NULL,
    geo_id integer NOT NULL,
    street character varying(255) NOT NULL,
    suite character varying(255) NOT NULL,
    city character varying(255) NOT NULL,
    zipcode character varying(255) NOT NULL
);


ALTER TABLE public.address OWNER TO symfony;

--
-- Name: address_id_seq; Type: SEQUENCE; Schema: public; Owner: symfony
--

CREATE SEQUENCE public.address_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.address_id_seq OWNER TO symfony;

--
-- Name: company; Type: TABLE; Schema: public; Owner: symfony
--

CREATE TABLE public.company (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    catch_phrase character varying(255) NOT NULL,
    bs character varying(255) NOT NULL
);


ALTER TABLE public.company OWNER TO symfony;

--
-- Name: company_id_seq; Type: SEQUENCE; Schema: public; Owner: symfony
--

CREATE SEQUENCE public.company_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.company_id_seq OWNER TO symfony;

--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: symfony
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO symfony;

--
-- Name: geo; Type: TABLE; Schema: public; Owner: symfony
--

CREATE TABLE public.geo (
    id integer NOT NULL,
    lat character varying(255) NOT NULL,
    lng character varying(255) NOT NULL
);


ALTER TABLE public.geo OWNER TO symfony;

--
-- Name: geo_id_seq; Type: SEQUENCE; Schema: public; Owner: symfony
--

CREATE SEQUENCE public.geo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.geo_id_seq OWNER TO symfony;

--
-- Name: login; Type: TABLE; Schema: public; Owner: symfony
--

CREATE TABLE public.login (
    id integer NOT NULL,
    username character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL
);


ALTER TABLE public.login OWNER TO symfony;

--
-- Name: login_id_seq; Type: SEQUENCE; Schema: public; Owner: symfony
--

CREATE SEQUENCE public.login_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.login_id_seq OWNER TO symfony;

--
-- Name: messenger_messages; Type: TABLE; Schema: public; Owner: symfony
--

CREATE TABLE public.messenger_messages (
    id bigint NOT NULL,
    body text NOT NULL,
    headers text NOT NULL,
    queue_name character varying(190) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    available_at timestamp(0) without time zone NOT NULL,
    delivered_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.messenger_messages OWNER TO symfony;

--
-- Name: messenger_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: symfony
--

CREATE SEQUENCE public.messenger_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.messenger_messages_id_seq OWNER TO symfony;

--
-- Name: messenger_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: symfony
--

ALTER SEQUENCE public.messenger_messages_id_seq OWNED BY public.messenger_messages.id;


--
-- Name: portal_page; Type: TABLE; Schema: public; Owner: symfony
--

CREATE TABLE public.portal_page (
    id integer NOT NULL,
    country_code character varying(2) NOT NULL,
    page_path character varying(255) NOT NULL,
    content text NOT NULL
);


ALTER TABLE public.portal_page OWNER TO symfony;

--
-- Name: portal_page_id_seq; Type: SEQUENCE; Schema: public; Owner: symfony
--

CREATE SEQUENCE public.portal_page_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.portal_page_id_seq OWNER TO symfony;

--
-- Name: user; Type: TABLE; Schema: public; Owner: symfony
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    company_id integer NOT NULL,
    address_id integer NOT NULL,
    name character varying(255) NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(255) NOT NULL,
    website character varying(255) NOT NULL
);


ALTER TABLE public."user" OWNER TO symfony;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: symfony
--

CREATE SEQUENCE public.user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO symfony;

--
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Data for Name: address; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.address (id, geo_id, street, suite, city, zipcode) FROM stdin;
\.


--
-- Data for Name: company; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.company (id, name, catch_phrase, bs) FROM stdin;
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20220427085835	2022-04-27 08:58:55	181
\.


--
-- Data for Name: geo; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.geo (id, lat, lng) FROM stdin;
\.


--
-- Data for Name: login; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.login (id, username, roles, password) FROM stdin;
1	user	[]	$2y$13$VrjnR3CmLvkwura7RxRs2uDTV.aSui6e.n.fHKRB88c1oYPYjspKq
2	admin	["ROLE_USER", "ROLE_ADMIN"]	$2y$13$gz8b8F6R9UBT3Q6EqDtcIeBT5NhbnULa2B/ua.AytyFVlHtjUcdsC
\.


--
-- Data for Name: messenger_messages; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) FROM stdin;
\.


--
-- Data for Name: portal_page; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public.portal_page (id, country_code, page_path, content) FROM stdin;
1	DE	impressum	<p><strong>Diese Internetseite wird betrieben von:</strong></p><p>Schwarz Unternehmenskommunikation GmbH &amp; Co. KG<br>Stiftsbergstra&szlig;e 1<br>74172 Neckarsulm<br>Sitz: Neckarsulm<br>Amtsgericht Stuttgart: HRA 735837<br>USt-IdNr.: DE325553499</p><p>Die Schwarz Unternehmenskommunikation GmbH &amp; Co. KG wird vertreten durch die Schwarz Unternehmenskommunikation Beteiligungs-GmbH mit Sitz in Neckarsulm, Registergericht Stuttgart, HRB 769866, die ihrerseits gemeinsam durch zwei gesamtvertretungsberechtigte Gesch&auml;ftsf&uuml;hrer, Gerd Wolf und Leonie Knorpp, vertreten wird.</p><p>Telefon: + 49 (0)7132 &ndash; 30788600<br>E-Mail: <a href="">kontakt</a><a href="mailto:kontakt@mail.schwarz" target="_blank" title="kontakt@mail.schwarz">@mail.schwarz</a></p>
2	US	legal-notice	 <p><strong>This website is operated by:</strong></p><p>Schwarz Unternehmenskommunikation GmbH &amp; Co. KG<br>Stiftsbergstra&szlig;e 1<br>74172 Neckarsulm, Germany<br>Location: Neckarsulm<br>Amtsgericht Stuttgart (Local Court): HRA 735837<br>VAT ID no.: DE325553499</p><p>Schwarz Unternehmenskommunikation GmbH &amp; Co. KG is represented by the Schwarz Unternehmenskommunikation Beteiligungs-GmbH based in Neckarsulm, registered with registration court Stuttgart HRB 769866, which in turn is represented by two managing directors with authority of joint representation, Gerd Wolf and Leonie Knorpp.</p><p>Phone: + 49 (0)7132 &ndash; 30788600<br>E-mail: <a href="">kontakt</a><a href="mailto:kontakt@mail.schwarz" target="_blank" title="kontakt@mail.schwarz">@mail.schwarz</a></p><h2>&nbsp;</h2>\r\n
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: symfony
--

COPY public."user" (id, company_id, address_id, name, username, email, phone, website) FROM stdin;
\.


--
-- Name: address_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.address_id_seq', 1, false);


--
-- Name: company_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.company_id_seq', 1, false);


--
-- Name: geo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.geo_id_seq', 1, false);


--
-- Name: login_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.login_id_seq', 2, true);


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.messenger_messages_id_seq', 1, false);


--
-- Name: portal_page_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.portal_page_id_seq', 2, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: symfony
--

SELECT pg_catalog.setval('public.user_id_seq', 1, false);


--
-- Name: address address_pkey; Type: CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public.address
    ADD CONSTRAINT address_pkey PRIMARY KEY (id);


--
-- Name: company company_pkey; Type: CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public.company
    ADD CONSTRAINT company_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: geo geo_pkey; Type: CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public.geo
    ADD CONSTRAINT geo_pkey PRIMARY KEY (id);


--
-- Name: login login_pkey; Type: CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public.login
    ADD CONSTRAINT login_pkey PRIMARY KEY (id);


--
-- Name: messenger_messages messenger_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public.messenger_messages
    ADD CONSTRAINT messenger_messages_pkey PRIMARY KEY (id);


--
-- Name: portal_page portal_page_pkey; Type: CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public.portal_page
    ADD CONSTRAINT portal_page_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: idx_75ea56e016ba31db; Type: INDEX; Schema: public; Owner: symfony
--

CREATE INDEX idx_75ea56e016ba31db ON public.messenger_messages USING btree (delivered_at);


--
-- Name: idx_75ea56e0e3bd61ce; Type: INDEX; Schema: public; Owner: symfony
--

CREATE INDEX idx_75ea56e0e3bd61ce ON public.messenger_messages USING btree (available_at);


--
-- Name: idx_75ea56e0fb7336f0; Type: INDEX; Schema: public; Owner: symfony
--

CREATE INDEX idx_75ea56e0fb7336f0 ON public.messenger_messages USING btree (queue_name);


--
-- Name: uniq_8d93d649979b1ad6; Type: INDEX; Schema: public; Owner: symfony
--

CREATE UNIQUE INDEX uniq_8d93d649979b1ad6 ON public."user" USING btree (company_id);


--
-- Name: uniq_8d93d649f5b7af75; Type: INDEX; Schema: public; Owner: symfony
--

CREATE UNIQUE INDEX uniq_8d93d649f5b7af75 ON public."user" USING btree (address_id);


--
-- Name: uniq_aa08cb10f85e0677; Type: INDEX; Schema: public; Owner: symfony
--

CREATE UNIQUE INDEX uniq_aa08cb10f85e0677 ON public.login USING btree (username);


--
-- Name: uniq_d4e6f81fa49d0b; Type: INDEX; Schema: public; Owner: symfony
--

CREATE UNIQUE INDEX uniq_d4e6f81fa49d0b ON public.address USING btree (geo_id);


--
-- Name: user fk_8d93d649979b1ad6; Type: FK CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT fk_8d93d649979b1ad6 FOREIGN KEY (company_id) REFERENCES public.company(id);


--
-- Name: user fk_8d93d649f5b7af75; Type: FK CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT fk_8d93d649f5b7af75 FOREIGN KEY (address_id) REFERENCES public.address(id);


--
-- Name: address fk_d4e6f81fa49d0b; Type: FK CONSTRAINT; Schema: public; Owner: symfony
--

ALTER TABLE ONLY public.address
    ADD CONSTRAINT fk_d4e6f81fa49d0b FOREIGN KEY (geo_id) REFERENCES public.geo(id);


--
-- PostgreSQL database dump complete
--

