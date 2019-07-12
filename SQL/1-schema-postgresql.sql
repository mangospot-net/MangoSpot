CREATE TABLE radacct (
	RadAcctId			bigserial PRIMARY KEY,
	AcctSessionId		text NOT NULL,
	AcctUniqueId		text NOT NULL UNIQUE,
	UserName			text,
	GroupName			text,
	Realm				text,
	NASIPAddress		inet NOT NULL,
	NASPortId			text,
	NASPortType			text,
	AcctStartTime		timestamp with time zone,
	AcctUpdateTime		timestamp with time zone,
	AcctStopTime		timestamp with time zone,
	AcctInterval		bigint,
	AcctSessionTime		bigint,
	AcctAuthentic		text,
	ConnectInfo_start	text,
	ConnectInfo_stop	text,
	AcctInputOctets		bigint,
	AcctOutputOctets	bigint,
	CalledStationId		text,
	CallingStationId	text,
	AcctTerminateCause	text,
	ServiceType			text,
	FramedProtocol		text,
	FramedIPAddress		inet
);
CREATE INDEX radacct_active_session_idx ON radacct (AcctUniqueId) WHERE AcctStopTime IS NULL;
CREATE INDEX radacct_bulk_close ON radacct (NASIPAddress, AcctStartTime) WHERE AcctStopTime IS NULL;
CREATE INDEX radacct_start_user_idx ON radacct (AcctStartTime, UserName);

CREATE TABLE radcheck (
	id				serial PRIMARY KEY,
	identity		integer,
    users   		integer,
	UserName		text NOT NULL DEFAULT '',
	Attribute		text NOT NULL DEFAULT '',
	op				VARCHAR(2) NOT NULL DEFAULT '==',
	Value			text NOT NULL DEFAULT '',
    description		text,
	created			timestamp without time zone
);
create index radcheck_UserName on radcheck (UserName,Attribute);
CREATE TABLE radgroupcheck (
	id				serial PRIMARY KEY,
	identity		integer,
    users   		integer,
	GroupName		text NOT NULL DEFAULT '',
	Attribute		text NOT NULL DEFAULT '',
	op				VARCHAR(2) NOT NULL DEFAULT '==',
	Value			text NOT NULL DEFAULT '',
    description		text
);
create index radgroupcheck_GroupName on radgroupcheck (GroupName,Attribute);

CREATE TABLE radgroupreply (
	id				serial PRIMARY KEY,
	identity		integer,
    users   		integer,
	GroupName		text NOT NULL DEFAULT '',
	Attribute		text NOT NULL DEFAULT '',
	op				VARCHAR(2) NOT NULL DEFAULT '=',
	Value			text NOT NULL DEFAULT '',
	description		text
);
create index radgroupreply_GroupName on radgroupreply (GroupName,Attribute);

CREATE TABLE radreply (
	id			serial PRIMARY KEY,
	identity	integer,
    users   	integer,
	UserName	text NOT NULL DEFAULT '',
	Attribute	text NOT NULL DEFAULT '',
	op			VARCHAR(2) NOT NULL DEFAULT '=',
	Value		text NOT NULL DEFAULT '',
	description	text,
	created		timestamp without time zone
);
create index radreply_UserName on radreply (UserName,Attribute);

CREATE TABLE radusergroup (
	id			serial PRIMARY KEY,
	identity	integer,
    users   	integer,
	UserName	text NOT NULL DEFAULT '',
	GroupName	text NOT NULL DEFAULT '',
	priority	integer NOT NULL DEFAULT 0
);
create index radusergroup_UserName on radusergroup (UserName);

CREATE TABLE radpostauth (
	id					bigserial PRIMARY KEY,
	username			text NOT NULL,
	pass				text,
	reply				text,
	CalledStationId		text,
	CallingStationId	text,
	authdate			timestamp with time zone NOT NULL default now()
);

CREATE TABLE nas (
	id				serial PRIMARY KEY,
	identity		integer,
    users   		integer,
	username		VARCHAR(100),
	password		VARCHAR(200),
	nasname			text NOT NULL,
	shortname		text NOT NULL,
	type			text NOT NULL DEFAULT 'other',
	ports			integer,
	secret			text NOT NULL,
	server			text,
	community		text,
	description		text,
    port            integer NULL DEFAULT '8728',
	status			boolean
);
create index nas_nasname on nas (nasname);

CREATE TABLE config (
    id		serial PRIMARY KEY,
    host	VARCHAR(100),
    name	VARCHAR(100),
    email	VARCHAR(100),
    pswd	VARCHAR(100),
    smtp	VARCHAR(10),
    port	smallint,
    api		VARCHAR(150),
    headers text,
    footers text,
    go_id	VARCHAR(150),
    go_ap	VARCHAR(150),
    go_sc	VARCHAR(150),
    fb_id	VARCHAR(150),
    fb_vs	VARCHAR(150),
    fb_sc	VARCHAR(150),
    on_api	VARCHAR(150),
    on_key	VARCHAR(150),
    on_dvc	VARCHAR(150),
    sosmed 	text
);

CREATE TABLE dictionary (
    id					serial PRIMARY KEY,
    type				VARCHAR(30),
    attribute			VARCHAR(64),
    value				VARCHAR(64),
    format				VARCHAR(20),
    vendor				VARCHAR(32),
    recommendedop		VARCHAR(32),
    recommendedtable	VARCHAR(32),
    recommendedhelper	VARCHAR(32),
    recommendedtooltip	VARCHAR(512)
);

CREATE TABLE identity (
    id			serial PRIMARY KEY,
    district	integer,
    theme		VARCHAR(50),
    data		VARCHAR(100) NOT NULL,
    title		VARCHAR(200),
    web			VARCHAR(50),
    url			VARCHAR(50) NOT NULL,
    email		VARCHAR(100),
    phone		VARCHAR(25),
    fax			VARCHAR(25),
    address		text,
    zip			integer,
    icon		VARCHAR(200),
    logo		VARCHAR(200),
    cover		text,
    lat			VARCHAR(50),
    lng			VARCHAR(50),
    register	timestamp(6) without time zone,
    expayed		timestamp without time zone,
    toeken		VARCHAR(100),
    ads			boolean,
    status		boolean
);

CREATE TABLE level (
    id			serial PRIMARY KEY,
    identity	integer NOT NULL,
    slug		integer,
    name		VARCHAR(100) NOT NULL,
    value		text,
    status		boolean
);

CREATE TABLE menu (
    id		serial PRIMARY KEY,
    slug	integer,
    name	VARCHAR(50) NOT NULL,
    value	VARCHAR(50),
    icon	VARCHAR(50),
    number	integer,
    status	boolean
);

CREATE TABLE price (
    id			serial PRIMARY KEY,
    identity	integer NOT NULL,
    users   	integer,
    groupname	VARCHAR(100) NOT NULL,
    value		VARCHAR(100)
);

CREATE TABLE income (
  id        serial PRIMARY KEY,
  identity  integer NOT NULL,
  users   	integer,
  total     integer,
  value     integer,
  date      timestamp(6)
);

CREATE TABLE themes (
    id			serial PRIMARY KEY,
    identity	integer NOT NULL,
    users   	integer,
    name		VARCHAR NOT NULL,
    content		text
);

CREATE TABLE type (
    id		serial PRIMARY KEY,
    name	VARCHAR(50) NOT NULL,
    type	VARCHAR(25),
    info	text,
    status	boolean
);

CREATE TABLE users (
    id			bigserial PRIMARY KEY,
    identity	integer NOT NULL,
    level		integer NOT NULL,
    district	integer,
    username 	VARCHAR(100),
    pswd		VARCHAR(100) NOT NULL,
    name		VARCHAR(100) NOT NULL,
    email		VARCHAR(100),
    phone		VARCHAR(25),
    gender		VARCHAR(12),
    religion	VARCHAR(25),
    place		VARCHAR(100),
    birth		VARCHAR(12),
    address		text,
    zip			smallint,
    image		VARCHAR(100),
    google		bigint,
    facebook	bigint,
    online		VARCHAR(20),
    date		timestamp(6) without time zone,
    status		boolean
);
CREATE OR REPLACE FUNCTION greater(integer, integer) RETURNS integer AS '
    DECLARE
        res INTEGER;
        one INTEGER := 0;
        two INTEGER := 0;
    BEGIN
        one = $1;
        two = $2;
        IF one IS NULL THEN
            one = 0;
        END IF;
        IF two IS NULL THEN
            two = 0;
        END IF;
        IF one > two THEN
            res := one;
        ELSE
            res := two;
        END IF;
        RETURN res;
    END;
' LANGUAGE 'plpgsql';

DROP VIEW IF EXISTS replay;
CREATE VIEW replay AS  SELECT a.id,
    b.identity,
    b.users,
    a.username,
    a.reply AS info,
    a.authdate AS date,
    to_char(a.authdate, 'HH24:MI:SS'::text) AS time
    FROM (radpostauth a
    JOIN radcheck b ON ((a.username = b.username)));

DROP VIEW IF EXISTS lost;
CREATE VIEW lost AS  SELECT a.radacctid AS id,
    b.identity,
    b.users,
    a.username,
    a.acctterminatecause AS info,
    a.acctstoptime AS date,
    to_char(a.acctstoptime, 'HH24:MI:SS'::text) AS time
    FROM (radacct a
    JOIN radcheck b ON ((a.username = b.username)))
    WHERE (a.acctstoptime IS NOT NULL)
    GROUP BY a.radacctid, b.identity, b.users, a.username;

DROP VIEW IF EXISTS expiredcheck;
CREATE VIEW expiredcheck AS  SELECT a.identity,
    a.users,
    a.username,
    a.attribute,
    a.value
    FROM (radcheck a
    JOIN radacct b ON ((a.username = b.username)))
    WHERE (a.attribute = 'Access-Period'::text)
    GROUP BY a.identity, a.users, a.username, a.attribute, a.value
    UNION
    SELECT radcheck.identity,
    radcheck.users,
    radcheck.username,
    radcheck.attribute,
    radcheck.value
    FROM radcheck
    WHERE (radcheck.attribute = 'Max-Data'::text)
UNION
SELECT a.identity,
    a.users,
    a.username,
    c.attribute,
    c.value
    FROM ((radcheck a
    JOIN radusergroup b ON (((a.username = b.username) AND (a.identity = b.identity) AND (a.users = b.users))))
    JOIN radgroupcheck c ON (((b.groupname = c.groupname) AND (a.identity = c.identity) AND (a.users = c.users))))
    WHERE (c.attribute = 'Access-Period'::text)
    GROUP BY a.identity, a.users, a.username, c.attribute, c.value
UNION
SELECT a.identity,
    a.users,
    a.username,
    c.attribute,
    c.value
    FROM ((radcheck a
    JOIN radusergroup b ON (((a.username = b.username) AND (a.identity = b.identity) AND (a.users = b.users))))
    JOIN radgroupcheck c ON (((b.groupname = c.groupname) AND (a.identity = c.identity) AND (a.users = c.users))))
    WHERE (c.attribute = 'Max-Data'::text);

DROP VIEW IF EXISTS expired;
CREATE VIEW expired AS  SELECT a.identity,
    a.users,
    a.username,
    c.groupname AS profile,
    a.attribute,
    a.value,
    d.value AS price,
    to_char(min(b.acctstarttime), 'DD-MM-YYYY HH24:MI'::text) AS time,
    to_char(to_timestamp((date_part('epoch'::text, min(b.acctstarttime)) + ((a.value)::integer)::double precision)), 'YYYY-MM-DD HH24:MI'::text) AS expired
    FROM (((expiredcheck a
    JOIN radacct b ON ((a.username = b.username)))
    LEFT JOIN radusergroup c ON (((a.username = c.username) AND (a.identity = c.identity) AND (a.users = c.users))))
    LEFT JOIN price d ON (((c.groupname = (d.groupname)::text) AND (a.identity = d.identity) AND (a.users = d.users))))
    WHERE (to_timestamp((date_part('epoch'::text, b.acctstarttime) + ((a.value)::integer)::double precision)) < now())
    GROUP BY a.identity, a.users, a.username, c.groupname, a.attribute, a.value, d.value
UNION
SELECT a.identity,
    a.users,
    a.username,
    c.groupname AS profile,
    a.attribute,
    a.value,
    d.value AS price,
    to_char(min(b.acctstarttime), 'DD-MM-YYYY HH24:MI'::text) AS time,
    a.value AS expired
    FROM (((expiredcheck a
    JOIN radacct b ON ((a.username = b.username)))
    LEFT JOIN radusergroup c ON (((a.username = c.username) AND (a.identity = c.identity) AND (a.users = c.users))))
    LEFT JOIN price d ON (((c.groupname = (d.groupname)::text) AND (a.identity = d.identity) AND (a.users = d.users))))
    WHERE (a.attribute = 'Max-Data'::text)
    GROUP BY a.identity, a.users, a.username, c.groupname, a.attribute, a.value, d.value
    HAVING ((((a.value)::integer)::numeric - (sum(b.acctinputoctets) + sum(b.acctoutputoctets))) <= (0)::numeric);

DROP VIEW IF EXISTS active;
CREATE VIEW active AS  SELECT a.radacctid AS id,
    b.identity,
    b.users,
    a.nasipaddress AS nasname,
    a.calledstationid AS server,
    a.username,
    a.framedipaddress AS address,
    c.groupname AS profile,
    to_char(a.acctstarttime, 'DD-MM-YYYY HH24:MI:SS'::text) AS time
    FROM ((radacct a
    JOIN radcheck b ON ((a.username = b.username)))
    LEFT JOIN radusergroup c ON ((a.username = c.username)))
    WHERE (a.acctstoptime IS NULL)
    GROUP BY a.radacctid, b.identity, b.users, a.username, c.groupname;

DROP VIEW IF EXISTS resume;
CREATE VIEW resume AS SELECT income.id,
    income.identity,
    income.users,
    income.total,
    income.value,
    to_char(income.date, 'YYYY-MM-DD'::text) AS date,
    income.date AS time,
    to_char(income.date, 'Dy'::text) AS week
   FROM income;
