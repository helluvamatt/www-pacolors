-- ----------------------------- --
-- DATABASE SCHEMA [POSTGRESQL]  --
-- PA Colors Application         --
-- ----------------------------- --

drop table if exists votes;
drop table if exists colors;
drop table if exists color_types;
drop table if exists color_settings;
drop table if exists applications;
drop table if exists users;

drop sequence if exists votes_id_seq;
drop sequence if exists colors_id_seq;
drop sequence if exists color_types_id_seq;
drop sequence if exists color_settings_id_seq;
drop sequence if exists applications_id_seq;
drop sequence if exists users_id_seq;

-- User table
create sequence users_id_seq;
create table users (
	id integer not null primary key default nextval('users_id_seq'),
	username character varying(64) unique not null,
	email character varying(255) not null,
	realname_first character varying(255) default null,
	realname_last character varying(255) default null,
	password character varying(128) not null,
	created timestamp not null default now(),
	enabled boolean default true
);
alter sequence users_id_seq owned by users.id;

-- Application table
create sequence applications_id_seq;
create table applications (
	id integer not null primary key default nextval('applications_id_seq'),
	package_name character varying(255) not null unique,
	display_name character varying(255) not null,
	enabled boolean not null default true
);
alter sequence applications_id_seq owned by applications.id;

-- Color settings
create sequence color_settings_id_seq;
create table color_settings (
	id integer not null primary key default nextval('color_settings_id_seq'),
	userid integer not null references users,
	appid integer not null references applications,
	enabled boolean not null default true
);
alter sequence color_settings_id_seq owned by color_settings.id;

-- Color types table
create sequence color_types_id_seq;
create table color_types (
	id integer not null primary key default nextval('color_types_id_seq'),
	props_order integer not null unique,
	display_name character varying(255),
	enabled boolean default true
);
alter sequence color_types_id_seq owned by color_types.id;

-- Colors table
create sequence colors_id_seq;
create table colors (
	id integer not null primary key default nextval('colors_id_seq'),
	settingid integer not null references color_settings,
	color_type integer not null references color_types,
	color integer not null
);
alter sequence colors_id_seq owned by colors.id;

-- Votes table
create sequence votes_id_seq;
create table votes (
	id integer not null primary key default nextval('votes_id_seq'),
	userid integer not null references users,
	settingid integer not null references color_settings,
	enabled boolean not null default true
);
alter sequence votes_id_seq owned by votes.id;