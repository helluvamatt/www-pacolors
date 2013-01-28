﻿-- ----------------------------- --
-- DATABASE SCHEMA [POSTGRESQL]  --
-- PA Colors Application         --
-- ----------------------------- --

drop table if exists votes;
drop table if exists colors;
drop table if exists applications;
drop table if exists ci_sessions;
drop table if exists permissions_map;
drop table if exists permissions;
drop table if exists users;

-- User table
create sequence users_id_seq;
create table users (
	id integer primary key default nextval('users_id_seq'),
	username character varying(64) not null unique,
	email character varying(255) not null,
	realname_first character varying(255) default null,
	realname_last character varying(255) default null,
	password character varying(128) not null,
	created timestamp not null default now(),
	enabled boolean default true
);
alter sequence users_id_seq owned by users.id;

-- Permissions table
create sequence permissions_id_seq;
create table permissions (
	id integer primary key default nextval('permissions_id_seq'),
	permissionname character varying(64) unique not null
);
alter sequence permissions_id_seq owned by permissions.id;

-- System permissions
insert into permissions
	(permissionname)
values
	('sys.users.manage'),		-- Create, update, delete user accounts
	('sys.colors.add'),		-- Create new color settings, disable color settings owned by self
	('sys.colors.moderate'),	-- Disable color settings by other users (including anonymous users)
	('sys.colors.manage'),		-- Disable and re-enable color settings by other users (including anonymous users)
	('sys.applications.add'),	-- Create new application records
	('sys.applications.moderate'),	-- Disable applications records (any user)
	('sys.applications.manage'); 	-- Disable and re-enable application records (any user)

-- Role map
create table permissions_map (
	permissionid integer references permissions (id),
	userid integer references users (id),
	primary key (permissionid, userid)
);

-- CodeIgniter Session table
create table ci_sessions (
	session_id character varying(40) primary key default '0',
	ip_address character varying(45) default '0' not null,
	user_agent character varying(120) not null,
	last_activity numeric(10) default 0 not null,
	user_data text not null
);
create index last_activity_idx on ci_sessions (last_activity);

-- Application table
create sequence applications_id_seq;
create table applications (
	id integer primary key default nextval('applications_id_seq'),
	package_name character varying(255) not null unique,
	display_name character varying(255) not null,
	enabled boolean not null default true
);
alter sequence applications_id_seq owned by applications.id;

-- Colors table
create sequence colors_id_seq;
create table colors (
	id integer primary key default nextval('colors_id_seq'),
	userid integer references users,
	appid integer not null references applications,
	color_navbar_bg integer,
	color_navbar_fg integer,
	color_navbar_gl integer,
	color_status_bg integer,
	color_status_fg integer,
	enabled boolean not null default true
);
alter sequence colors_id_seq owned by colors.id;

-- Votes table
create sequence votes_id_seq;
create table votes (
	id integer primary key default nextval('votes_id_seq'),
	userid integer not null references users,
	colorid integer not null references colors,
	enabled boolean not null default true
);
alter sequence votes_id_seq owned by votes.id;

-- Example applications
insert into applications
	(package_name, display_name)
values
	('com.facebook.katana', 'Facebook'),
	('com.spotify.mobile.android.ui', 'Spotify Mobile'),
	('com.dropbox.android', 'Dropbox'),
	('com.skype.raider', 'Skype'),
	('com.android.launcher2', 'Launcher');

-- Example color settings
insert into colors
	(appid, color_navbar_bg, color_navbar_fg, color_navbar_gl, color_status_bg, color_status_fg)
values
	(1, x'FF3B5998'::int, x'FFFAFAFA'::int, x'FFFFFFFF'::int, x'FF3B5998'::int, x'FFFAFAFA'::int),
	(1, x'FF2C4988'::int, x'B2E7E7E7'::int, x'FFEDEFF7'::int, x'FF2C4988'::int, x'FFE7E7E7'::int),
	(2, x'FF292929'::int, x'FF86B410'::int, x'FFDAE1C3'::int, x'FF292929'::int, x'FF86B410'::int),
	(3, x'FF0063B2'::int, x'B2FFFFFF'::int, x'FFFFFFFF'::int, x'FF007DE3'::int, x'FFFFFFFF'::int),
	(4, x'FFFFFFFF'::int, x'FF00C1FF'::int, x'FFFFD400'::int, x'FF000000'::int, x'FF33B5E5'::int),
	(5, x'80000000'::int, NULL, NULL, x'FF000000'::int, NULL),
	(5, x'10000000'::int, NULL, NULL, x'FF000000'::int, NULL);

-- Ensure tables are owned by the application user
alter table votes owner to "app_pacolors";
alter table colors owner to "app_pacolors";
alter table applications owner to "app_pacolors";
alter table users owner to "app_pacolors";




























