---
---		TO IMPORT
---		psql vip_ci_app vip_app_user
---		\i **FULL PATH**/_trunk/.migrations/sql_assessments.sql
---



--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: exp_proj_assessment_id_seq; Type: SEQUENCE; Schema: public; Owner: vip_app_user
--

CREATE SEQUENCE exp_proj_assessment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

--
-- Name: exp_proj_assessment; Type: TABLE; Schema: public; Owner: vip_app_user; Tablespace: 
--

CREATE TABLE exp_proj_assessment (
    id bigint DEFAULT nextval('exp_proj_assessment_id_seq'::regclass) NOT NULL,
    pid bigint,
    slug text,
    uid bigint,
    competitors text,
    drivers text,
    analysis text
);


ALTER TABLE public.exp_proj_assessment OWNER TO vip_app_user;

--
-- PostgreSQL database dump complete
--
