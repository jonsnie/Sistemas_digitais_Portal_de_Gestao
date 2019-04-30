/*
 Navicat Premium Data Transfer

 Source Server         : SEPUD-Neogrid
 Source Server Type    : PostgreSQL
 Source Server Version : 100004
 Source Host           : 177.67.89.21:5432
 Source Catalog        : waze_data
 Source Schema         : sepud

 Target Server Type    : PostgreSQL
 Target Server Version : 100004
 File Encoding         : 65001

 Date: 25/04/2019 14:40:09
*/


-- ----------------------------
-- Sequence structure for company_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."company_id_seq";
CREATE SEQUENCE "sepud"."company_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for eri_parking_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."eri_parking_id_seq";
CREATE SEQUENCE "sepud"."eri_parking_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for eri_parking_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."eri_parking_type_id_seq";
CREATE SEQUENCE "sepud"."eri_parking_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for eri_schedule_parking_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."eri_schedule_parking_id_seq";
CREATE SEQUENCE "sepud"."eri_schedule_parking_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for hospital_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."hospital_id_seq";
CREATE SEQUENCE "sepud"."hospital_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_event_conditions_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_event_conditions_id_seq";
CREATE SEQUENCE "sepud"."oct_event_conditions_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_event_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_event_type_id_seq";
CREATE SEQUENCE "sepud"."oct_event_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_events_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_events_id_seq";
CREATE SEQUENCE "sepud"."oct_events_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_fleet_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_fleet_id_seq";
CREATE SEQUENCE "sepud"."oct_fleet_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_providence_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_providence_id_seq";
CREATE SEQUENCE "sepud"."oct_providence_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_events_images_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_rel_events_images_id_seq";
CREATE SEQUENCE "sepud"."oct_rel_events_images_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_rel_events_providence_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_rel_events_providence_id_seq";
CREATE SEQUENCE "sepud"."oct_rel_events_providence_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_vehicle_type_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_vehicle_type_id_seq";
CREATE SEQUENCE "sepud"."oct_vehicle_type_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_vehicles_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_vehicles_id_seq";
CREATE SEQUENCE "sepud"."oct_vehicles_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_victim_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_victim_id_seq";
CREATE SEQUENCE "sepud"."oct_victim_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for oct_workshift_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."oct_workshift_id_seq";
CREATE SEQUENCE "sepud"."oct_workshift_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for rot_data_sensors_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."rot_data_sensors_id_seq";
CREATE SEQUENCE "sepud"."rot_data_sensors_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for rot_equipments_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."rot_equipments_id_seq";
CREATE SEQUENCE "sepud"."rot_equipments_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for streets_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."streets_id_seq";
CREATE SEQUENCE "sepud"."streets_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 32767
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for users_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "sepud"."users_id_seq";
CREATE SEQUENCE "sepud"."users_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."company";
CREATE TABLE "sepud"."company" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".company_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "acron" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."company" OWNER TO "postgres";

-- ----------------------------
-- Table structure for eri_parking
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."eri_parking";
CREATE TABLE "sepud"."eri_parking" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".eri_parking_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "active" bool,
  "description" text COLLATE "pg_catalog"."default",
  "id_street" int4,
  "id_parking_type" int4
)
;
ALTER TABLE "sepud"."eri_parking" OWNER TO "postgres";

-- ----------------------------
-- Table structure for eri_parking_type
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."eri_parking_type";
CREATE TABLE "sepud"."eri_parking_type" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".eri_parking_type_id_seq'::regclass),
  "type" text COLLATE "pg_catalog"."default",
  "time" int4,
  "time_warning" int4,
  "observation" text COLLATE "pg_catalog"."default",
  "multi_parking" bool DEFAULT false
)
;
ALTER TABLE "sepud"."eri_parking_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for eri_schedule_parking
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."eri_schedule_parking";
CREATE TABLE "sepud"."eri_schedule_parking" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".eri_schedule_parking_id_seq'::regclass),
  "id_vehicle" int4,
  "id_parking" int4,
  "timestamp" timestamp(0) DEFAULT now(),
  "notified" bool DEFAULT false,
  "notified_timestamp" timestamp(0),
  "closed" bool DEFAULT false,
  "closed_timestamp" timestamp(0),
  "id_user" int4,
  "licence_plate" varchar(255) COLLATE "pg_catalog"."default",
  "obs" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."eri_schedule_parking" OWNER TO "postgres";

-- ----------------------------
-- Table structure for hospital
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."hospital";
CREATE TABLE "sepud"."hospital" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".hospital_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "observation" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."hospital" OWNER TO "postgres";

-- ----------------------------
-- Table structure for iq_pesquisa
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."iq_pesquisa";
CREATE TABLE "sepud"."iq_pesquisa" (
  "iq" text COLLATE "pg_catalog"."default",
  "status" text COLLATE "pg_catalog"."default",
  "ano" int4
)
;
ALTER TABLE "sepud"."iq_pesquisa" OWNER TO "postgres";

-- ----------------------------
-- Table structure for logs
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."logs";
CREATE TABLE "sepud"."logs" (
  "ip" varchar(255) COLLATE "pg_catalog"."default",
  "id_user" int4,
  "module" varchar(255) COLLATE "pg_catalog"."default",
  "action" text COLLATE "pg_catalog"."default",
  "timestamp" timestamp(0),
  "obs" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."logs" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_event_conditions
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_event_conditions";
CREATE TABLE "sepud"."oct_event_conditions" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".oct_event_conditions_id_seq'::regclass),
  "type" text COLLATE "pg_catalog"."default",
  "subtype" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_event_conditions" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_event_type
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_event_type";
CREATE TABLE "sepud"."oct_event_type" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".oct_event_type_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "name_acron" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "type" text COLLATE "pg_catalog"."default",
  "active" bool DEFAULT true
)
;
ALTER TABLE "sepud"."oct_event_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_events
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_events";
CREATE TABLE "sepud"."oct_events" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_events_id_seq'::regclass),
  "date" timestamp(0),
  "description" text COLLATE "pg_catalog"."default",
  "address_reference" varchar(255) COLLATE "pg_catalog"."default",
  "geoposition" text COLLATE "pg_catalog"."default",
  "id_event_type" int2,
  "status" text COLLATE "pg_catalog"."default",
  "victim_inform" int2,
  "victim_found" int2,
  "active" bool DEFAULT true,
  "arrival" timestamp(0),
  "closure" timestamp(0),
  "id_user" int4,
  "address_complement" text COLLATE "pg_catalog"."default",
  "id_street" int4,
  "street_number" int4
)
;
ALTER TABLE "sepud"."oct_events" OWNER TO "postgres";
COMMENT ON COLUMN "sepud"."oct_events"."date" IS 'Data/hora do registro inicial do sistema';
COMMENT ON COLUMN "sepud"."oct_events"."arrival" IS 'Data/hora de chegada no local do atendimento';
COMMENT ON COLUMN "sepud"."oct_events"."closure" IS 'Data/hora do encerramento da ocorrencia';

-- ----------------------------
-- Table structure for oct_fleet
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_fleet";
CREATE TABLE "sepud"."oct_fleet" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_fleet_id_seq'::regclass),
  "id_company" int4,
  "plate" varchar(255) COLLATE "pg_catalog"."default",
  "type" varchar(255) COLLATE "pg_catalog"."default",
  "model" varchar(255) COLLATE "pg_catalog"."default",
  "brand" varchar(255) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_fleet" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_providence
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_providence";
CREATE TABLE "sepud"."oct_providence" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_providence_id_seq'::regclass),
  "table_orgim" varchar(255) COLLATE "pg_catalog"."default",
  "title" varchar(255) COLLATE "pg_catalog"."default",
  "area" varchar(255) COLLATE "pg_catalog"."default",
  "providence" varchar(255) COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_providence" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_event_type_company
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_event_type_company";
CREATE TABLE "sepud"."oct_rel_event_type_company" (
  "id_company" int4,
  "id_event_type" int4
)
;
ALTER TABLE "sepud"."oct_rel_event_type_company" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_event_conditions
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_events_event_conditions";
CREATE TABLE "sepud"."oct_rel_events_event_conditions" (
  "id_events" int4,
  "id_event_conditions" int2
)
;
ALTER TABLE "sepud"."oct_rel_events_event_conditions" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_images
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_events_images";
CREATE TABLE "sepud"."oct_rel_events_images" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_rel_events_images_id_seq'::regclass),
  "id_events" int4,
  "image" varchar(255) COLLATE "pg_catalog"."default",
  "path" varchar(255) COLLATE "pg_catalog"."default",
  "timestamp" timestamp(0)
)
;
ALTER TABLE "sepud"."oct_rel_events_images" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_observations
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_events_observations";
CREATE TABLE "sepud"."oct_rel_events_observations" (
  "id_user" int2,
  "id_event" int8,
  "id_type" int2,
  "text" text COLLATE "pg_catalog"."default",
  "timestamp" timestamp(0) DEFAULT now()
)
;
ALTER TABLE "sepud"."oct_rel_events_observations" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_events_providence
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_events_providence";
CREATE TABLE "sepud"."oct_rel_events_providence" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_rel_events_providence_id_seq'::regclass),
  "opened_date" timestamp(0) DEFAULT now(),
  "closed_date" timestamp(0),
  "id_owner" int4,
  "id_vehicle" int4,
  "id_victim" int4,
  "id_hospital" int4,
  "id_company_requested" int4,
  "observation" text COLLATE "pg_catalog"."default",
  "id_event" int4,
  "id_providence" int4
)
;
ALTER TABLE "sepud"."oct_rel_events_providence" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_rel_workshift_persona
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_rel_workshift_persona";
CREATE TABLE "sepud"."oct_rel_workshift_persona" (
  "id_shift" int4,
  "id_person" int4,
  "id_fleet" int4,
  "opened" timestamp(0),
  "closed" timestamp(0),
  "type" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_rel_workshift_persona" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_vehicle_type
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_vehicle_type";
CREATE TABLE "sepud"."oct_vehicle_type" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".oct_vehicle_type_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "desc" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_vehicle_type" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_vehicles
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_vehicles";
CREATE TABLE "sepud"."oct_vehicles" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_vehicles_id_seq'::regclass),
  "description" text COLLATE "pg_catalog"."default",
  "id_events" int4,
  "observation" text COLLATE "pg_catalog"."default",
  "licence_plate" text COLLATE "pg_catalog"."default",
  "color" text COLLATE "pg_catalog"."default",
  "renavam" text COLLATE "pg_catalog"."default",
  "chassi" text COLLATE "pg_catalog"."default",
  "id_vehicle_type" int2
)
;
ALTER TABLE "sepud"."oct_vehicles" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_victim
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_victim";
CREATE TABLE "sepud"."oct_victim" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_victim_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "age" int2,
  "genre" text COLLATE "pg_catalog"."default",
  "id_vehicle" int4,
  "id_events" int4,
  "position_in_vehicle" text COLLATE "pg_catalog"."default",
  "state" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "forwarded_to" text COLLATE "pg_catalog"."default",
  "refuse_help" bool DEFAULT true
)
;
ALTER TABLE "sepud"."oct_victim" OWNER TO "postgres";

-- ----------------------------
-- Table structure for oct_workshift
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."oct_workshift";
CREATE TABLE "sepud"."oct_workshift" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".oct_workshift_id_seq'::regclass),
  "opened" timestamp(0),
  "closed" timestamp(0),
  "id_company" int4,
  "observation" text COLLATE "pg_catalog"."default",
  "period" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."oct_workshift" OWNER TO "postgres";

-- ----------------------------
-- Table structure for resume
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."resume";
CREATE TABLE "sepud"."resume" (
  "field" varchar COLLATE "pg_catalog"."default",
  "int_value" int8,
  "str_value" varchar COLLATE "pg_catalog"."default",
  "type" varchar COLLATE "pg_catalog"."default",
  "module" varchar COLLATE "pg_catalog"."default",
  "ref_month" int2,
  "ref_year" int2
)
;
ALTER TABLE "sepud"."resume" OWNER TO "postgres";

-- ----------------------------
-- Table structure for rot_data_sensors
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."rot_data_sensors";
CREATE TABLE "sepud"."rot_data_sensors" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".rot_data_sensors_id_seq'::regclass),
  "event_id" int4,
  "equipment_id" int4,
  "datetime" timestamp(0),
  "geo_position" text COLLATE "pg_catalog"."default",
  "description" text COLLATE "pg_catalog"."default",
  "value" text COLLATE "pg_catalog"."default"
)
;
ALTER TABLE "sepud"."rot_data_sensors" OWNER TO "postgres";

-- ----------------------------
-- Table structure for rot_equipments
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."rot_equipments";
CREATE TABLE "sepud"."rot_equipments" (
  "name" text COLLATE "pg_catalog"."default",
  "obs" text COLLATE "pg_catalog"."default",
  "last_activity" timestamp(6),
  "id" int4 NOT NULL DEFAULT nextval('"sepud".rot_equipments_id_seq'::regclass),
  "active" bool DEFAULT true
)
;
ALTER TABLE "sepud"."rot_equipments" OWNER TO "postgres";

-- ----------------------------
-- Table structure for sepud_cadastro_imoveis
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."sepud_cadastro_imoveis";
CREATE TABLE "sepud"."sepud_cadastro_imoveis" (
  "iq" varchar(255) COLLATE "pg_catalog"."default",
  "uso" varchar(255) COLLATE "pg_catalog"."default",
  "ano" int2
)
;
ALTER TABLE "sepud"."sepud_cadastro_imoveis" OWNER TO "postgres";

-- ----------------------------
-- Table structure for streets
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."streets";
CREATE TABLE "sepud"."streets" (
  "id" int2 NOT NULL DEFAULT nextval('"sepud".streets_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "is_rotate_parking" bool DEFAULT false
)
;
ALTER TABLE "sepud"."streets" OWNER TO "postgres";

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS "sepud"."users";
CREATE TABLE "sepud"."users" (
  "id" int4 NOT NULL DEFAULT nextval('"sepud".users_id_seq'::regclass),
  "name" text COLLATE "pg_catalog"."default",
  "email" text COLLATE "pg_catalog"."default",
  "password" text COLLATE "pg_catalog"."default",
  "company" text COLLATE "pg_catalog"."default",
  "area" text COLLATE "pg_catalog"."default",
  "job" text COLLATE "pg_catalog"."default",
  "active" bool,
  "in_ativaction" bool,
  "company_acron" text COLLATE "pg_catalog"."default",
  "id_company" int4,
  "phone" text COLLATE "pg_catalog"."default",
  "observation" text COLLATE "pg_catalog"."default",
  "cpf" text COLLATE "pg_catalog"."default",
  "date_of_birth" date
)
;
ALTER TABLE "sepud"."users" OWNER TO "postgres";
COMMENT ON COLUMN "sepud"."users"."id" IS 'unique index';
COMMENT ON COLUMN "sepud"."users"."name" IS 'Fullname';
COMMENT ON COLUMN "sepud"."users"."email" IS 'E-mail address, use to auth';
COMMENT ON COLUMN "sepud"."users"."password" IS 'MD5 password';

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "sepud"."company_id_seq"
OWNED BY "sepud"."company"."id";
SELECT setval('"sepud"."company_id_seq"', 11, true);
ALTER SEQUENCE "sepud"."eri_parking_id_seq"
OWNED BY "sepud"."eri_parking"."id";
SELECT setval('"sepud"."eri_parking_id_seq"', 1002, true);
ALTER SEQUENCE "sepud"."eri_parking_type_id_seq"
OWNED BY "sepud"."eri_parking_type"."id";
SELECT setval('"sepud"."eri_parking_type_id_seq"', 17, true);
ALTER SEQUENCE "sepud"."eri_schedule_parking_id_seq"
OWNED BY "sepud"."eri_schedule_parking"."id";
SELECT setval('"sepud"."eri_schedule_parking_id_seq"', 21634, true);
ALTER SEQUENCE "sepud"."hospital_id_seq"
OWNED BY "sepud"."hospital"."id";
SELECT setval('"sepud"."hospital_id_seq"', 4, true);
ALTER SEQUENCE "sepud"."oct_event_conditions_id_seq"
OWNED BY "sepud"."oct_event_conditions"."id";
SELECT setval('"sepud"."oct_event_conditions_id_seq"', 15, true);
ALTER SEQUENCE "sepud"."oct_event_type_id_seq"
OWNED BY "sepud"."oct_event_type"."id";
SELECT setval('"sepud"."oct_event_type_id_seq"', 502, true);
ALTER SEQUENCE "sepud"."oct_events_id_seq"
OWNED BY "sepud"."oct_events"."id";
SELECT setval('"sepud"."oct_events_id_seq"', 12056, true);
ALTER SEQUENCE "sepud"."oct_fleet_id_seq"
OWNED BY "sepud"."oct_fleet"."id";
SELECT setval('"sepud"."oct_fleet_id_seq"', 7, true);
ALTER SEQUENCE "sepud"."oct_providence_id_seq"
OWNED BY "sepud"."oct_providence"."id";
SELECT setval('"sepud"."oct_providence_id_seq"', 22, true);
ALTER SEQUENCE "sepud"."oct_rel_events_images_id_seq"
OWNED BY "sepud"."oct_rel_events_images"."id";
SELECT setval('"sepud"."oct_rel_events_images_id_seq"', 1047, true);
ALTER SEQUENCE "sepud"."oct_rel_events_providence_id_seq"
OWNED BY "sepud"."oct_rel_events_providence"."id";
SELECT setval('"sepud"."oct_rel_events_providence_id_seq"', 2194, true);
ALTER SEQUENCE "sepud"."oct_vehicle_type_id_seq"
OWNED BY "sepud"."oct_vehicle_type"."id";
SELECT setval('"sepud"."oct_vehicle_type_id_seq"', 18, true);
ALTER SEQUENCE "sepud"."oct_vehicles_id_seq"
OWNED BY "sepud"."oct_vehicles"."id";
SELECT setval('"sepud"."oct_vehicles_id_seq"', 352, true);
ALTER SEQUENCE "sepud"."oct_victim_id_seq"
OWNED BY "sepud"."oct_victim"."id";
SELECT setval('"sepud"."oct_victim_id_seq"', 82, true);
ALTER SEQUENCE "sepud"."oct_workshift_id_seq"
OWNED BY "sepud"."oct_workshift"."id";
SELECT setval('"sepud"."oct_workshift_id_seq"', 19, true);
ALTER SEQUENCE "sepud"."rot_data_sensors_id_seq"
OWNED BY "sepud"."rot_data_sensors"."id";
SELECT setval('"sepud"."rot_data_sensors_id_seq"', 52072, true);
ALTER SEQUENCE "sepud"."rot_equipments_id_seq"
OWNED BY "sepud"."rot_equipments"."id";
SELECT setval('"sepud"."rot_equipments_id_seq"', 7, true);
ALTER SEQUENCE "sepud"."streets_id_seq"
OWNED BY "sepud"."streets"."id";
SELECT setval('"sepud"."streets_id_seq"', 4150, true);
ALTER SEQUENCE "sepud"."users_id_seq"
OWNED BY "sepud"."users"."id";
SELECT setval('"sepud"."users_id_seq"', 105, true);

-- ----------------------------
-- Primary Key structure for table company
-- ----------------------------
ALTER TABLE "sepud"."company" ADD CONSTRAINT "company_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table eri_parking_type
-- ----------------------------
ALTER TABLE "sepud"."eri_parking_type" ADD CONSTRAINT "eri_parking_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table eri_schedule_parking
-- ----------------------------
ALTER TABLE "sepud"."eri_schedule_parking" ADD CONSTRAINT "eri_schedule_parking_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table hospital
-- ----------------------------
ALTER TABLE "sepud"."hospital" ADD CONSTRAINT "hospital_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_event_conditions
-- ----------------------------
ALTER TABLE "sepud"."oct_event_conditions" ADD CONSTRAINT "oct_event_conditions_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_event_type
-- ----------------------------
ALTER TABLE "sepud"."oct_event_type" ADD CONSTRAINT "oct_event_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_events
-- ----------------------------
ALTER TABLE "sepud"."oct_events" ADD CONSTRAINT "oct_events_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_fleet
-- ----------------------------
ALTER TABLE "sepud"."oct_fleet" ADD CONSTRAINT "oct_fleet_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_providence
-- ----------------------------
ALTER TABLE "sepud"."oct_providence" ADD CONSTRAINT "oct_providence_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_rel_events_images
-- ----------------------------
ALTER TABLE "sepud"."oct_rel_events_images" ADD CONSTRAINT "oct_rel_events_images_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_rel_events_providence
-- ----------------------------
ALTER TABLE "sepud"."oct_rel_events_providence" ADD CONSTRAINT "oct_rel_events_providence_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_vehicle_type
-- ----------------------------
ALTER TABLE "sepud"."oct_vehicle_type" ADD CONSTRAINT "oct_vehicle_type_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_vehicles
-- ----------------------------
ALTER TABLE "sepud"."oct_vehicles" ADD CONSTRAINT "oct_vehicles_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_victim
-- ----------------------------
ALTER TABLE "sepud"."oct_victim" ADD CONSTRAINT "oct_victim_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table oct_workshift
-- ----------------------------
ALTER TABLE "sepud"."oct_workshift" ADD CONSTRAINT "oct_workshift_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rot_data_sensors
-- ----------------------------
ALTER TABLE "sepud"."rot_data_sensors" ADD CONSTRAINT "rot_data_sensors_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table rot_equipments
-- ----------------------------
ALTER TABLE "sepud"."rot_equipments" ADD CONSTRAINT "rot_equipments_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table streets
-- ----------------------------
ALTER TABLE "sepud"."streets" ADD CONSTRAINT "streets_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table users
-- ----------------------------
ALTER TABLE "sepud"."users" ADD CONSTRAINT "users_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Foreign Keys structure for table oct_events
-- ----------------------------
ALTER TABLE "sepud"."oct_events" ADD CONSTRAINT "fk_id_event_type" FOREIGN KEY ("id_event_type") REFERENCES "sepud"."oct_event_type" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_rel_events_event_conditions
-- ----------------------------
ALTER TABLE "sepud"."oct_rel_events_event_conditions" ADD CONSTRAINT "fk_event_conditions" FOREIGN KEY ("id_event_conditions") REFERENCES "sepud"."oct_event_conditions" ("id") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "sepud"."oct_rel_events_event_conditions" ADD CONSTRAINT "fk_events" FOREIGN KEY ("id_events") REFERENCES "sepud"."oct_events" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_rel_events_observations
-- ----------------------------
ALTER TABLE "sepud"."oct_rel_events_observations" ADD CONSTRAINT "fk01" FOREIGN KEY ("id_user") REFERENCES "sepud"."users" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE "sepud"."oct_rel_events_observations" ADD CONSTRAINT "fk02" FOREIGN KEY ("id_event") REFERENCES "sepud"."oct_events" ("id") ON DELETE RESTRICT ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_vehicles
-- ----------------------------
ALTER TABLE "sepud"."oct_vehicles" ADD CONSTRAINT "fk_id_events" FOREIGN KEY ("id_events") REFERENCES "sepud"."oct_events" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table oct_victim
-- ----------------------------
ALTER TABLE "sepud"."oct_victim" ADD CONSTRAINT "fk_events" FOREIGN KEY ("id_events") REFERENCES "sepud"."oct_events" ("id") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "sepud"."oct_victim" ADD CONSTRAINT "fk_vehicle" FOREIGN KEY ("id_vehicle") REFERENCES "sepud"."oct_vehicles" ("id") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Keys structure for table rot_data_sensors
-- ----------------------------
ALTER TABLE "sepud"."rot_data_sensors" ADD CONSTRAINT "fk_rot_equipments" FOREIGN KEY ("equipment_id") REFERENCES "sepud"."rot_equipments" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;
