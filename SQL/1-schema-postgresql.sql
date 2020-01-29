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
COMMENT ON TABLE radacct IS 'Radius';

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
COMMENT ON TABLE radcheck IS 'Radius';

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
COMMENT ON TABLE radgroupcheck IS 'Radius';

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
COMMENT ON TABLE radgroupreply IS 'Radius';

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
COMMENT ON TABLE radreply IS 'Radius';

CREATE TABLE radusergroup (
	id			serial PRIMARY KEY,
	identity	integer,
    users   	integer,
	UserName	text NOT NULL DEFAULT '',
	GroupName	text NOT NULL DEFAULT '',
	priority	integer NOT NULL DEFAULT 0
);
create index radusergroup_UserName on radusergroup (UserName);
COMMENT ON TABLE radusergroup IS 'Radius';

CREATE TABLE radpostauth (
	id					bigserial PRIMARY KEY,
	username			text NOT NULL,
	pass				text,
	reply				text,
	CalledStationId		text,
	CallingStationId	text,
	authdate			timestamp with time zone NOT NULL default now()
);
COMMENT ON TABLE radpostauth IS 'Radius';

CREATE TABLE nas (
	id				serial PRIMARY KEY,
	identity		integer,
    users   		integer,
	username		VARCHAR(100),
	password		VARCHAR(200),
	nasname			text NOT NULL,
	shortname		text NOT NULL,
	type			text NOT NULL DEFAULT 'other',
	ports			integer NOT NULL DEFAULT 0,
	secret			text NOT NULL,
	server			text,
	community		text,
	description		text,
    port            integer NULL DEFAULT '8728',
	status			boolean
);
create index nas_nasname on nas (nasname);
COMMENT ON TABLE nas IS 'Radius';

CREATE TABLE config (
    id		    serial PRIMARY KEY,
    host	    VARCHAR(100),
    name	    VARCHAR(100),
    email	    VARCHAR(100),
    pswd	    VARCHAR(100),
    smtp	    VARCHAR(10),
    port        smallint,
    api         VARCHAR(150),
    headers     text,
    footers     text,
    currency    VARCHAR(10),
    go_id       VARCHAR(150),
    go_ap       VARCHAR(150),
    go_sc       VARCHAR(150),
    fb_id       VARCHAR(150),
    fb_vs       VARCHAR(150),
    fb_sc       VARCHAR(150),
    on_api      VARCHAR(150),
    on_key      VARCHAR(150),
    on_dvc      VARCHAR(150),
    sosmed      text,
    wifi        text
);
COMMENT ON TABLE config IS 'MangoSpot';

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
COMMENT ON TABLE identity IS 'MangoSpot';

CREATE TABLE level (
    id			serial PRIMARY KEY,
    identity	integer NOT NULL,
    slug		integer,
    name		VARCHAR(100) NOT NULL,
    value		text,
    data		text,
    status		boolean
);
COMMENT ON TABLE level IS 'MangoSpot';

CREATE TABLE menu (
    id		serial PRIMARY KEY,
    slug	integer,
    name	VARCHAR(50) NOT NULL,
    value	VARCHAR(50),
    icon	VARCHAR(50),
    number	integer,
    status	boolean
);
COMMENT ON TABLE menu IS 'MangoSpot';

CREATE TABLE radprice (
    id			serial PRIMARY KEY,
    identity	integer NOT NULL,
    users   	integer,
    groupname	VARCHAR(100) NOT NULL,
    price		integer,
    discount    integer
);
COMMENT ON TABLE radprice IS 'Radius';

CREATE TABLE income (
  id        serial PRIMARY KEY,
  identity  INTEGER NOT NULL,
  users   	INTEGER,
  total     INTEGER,
  price     INTEGER,
  discount  INTEGER,
  income    INTEGER,
  upload    bigint,
  download  bigint,
  data      text,
  date      timestamp(6) DEFAULT now()
);
COMMENT ON TABLE income IS 'MangoSpot';

CREATE TABLE packet (
  id        serial PRIMARY KEY,
  identity  int4 NOT NULL,
  users     int4 NOT NULL,
  client    int4 NOT NULL,
  groupname varchar(100) NOT NULL,
  price     int4,
  total     int4,
  voucher   int4,
  defaults  bool,
  status    bool
);
COMMENT ON TABLE packet IS 'MangoSpot';

CREATE TABLE payment (
  id        serial PRIMARY KEY,
  identity  int4 NOT NULL,
  users     int4 NOT NULL,
  client    int4 NOT NULL,
  packet    int4 NOT NULL,
  price     int4 NOT NULL,
  total     int2,
  info      text,
  date      timestamp(6),
  approve   timestamp(6),
  status    bool
);
COMMENT ON TABLE payment IS 'MangoSpot';

CREATE TABLE themes (
    id			serial PRIMARY KEY,
    identity	integer NOT NULL,
    users   	integer,
    name		VARCHAR(100) NOT NULL,
    type        VARCHAR(25),
    content		text
);
COMMENT ON TABLE themes IS 'MangoSpot';

CREATE TABLE type (
    id		serial PRIMARY KEY,
    name	VARCHAR(50) NOT NULL,
    type	VARCHAR(25),
    info	text,
    status	boolean
);
COMMENT ON TABLE type IS 'MangoSpot';

CREATE TABLE users (
    id			bigserial PRIMARY KEY,
    identity	integer NOT NULL,
    level		integer NOT NULL,
    district	integer,
    number      VARCHAR(50),
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
COMMENT ON TABLE users IS 'MangoSpot';

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

CREATE OR REPLACE FUNCTION formatbytes(byte int8) 
    RETURNS VARCHAR AS $BODY$
        DECLARE
            format NUMERIC;
            bytes VARCHAR;
        BEGIN
            format = $1;
            CASE
                WHEN (format >= 1099511627776) THEN bytes = CONCAT(TRUNC((format / 1099511627776), 2), 'TB');
                WHEN (format >= 1073741824) THEN bytes = CONCAT(TRUNC((format / 1073741824), 2), 'GB');
                WHEN (format >= 1048576) THEN bytes = CONCAT(TRUNC((format / 1048576), 2), 'MB');
                WHEN (format >= 1024) THEN bytes = CONCAT(TRUNC((format / 1024), 2), 'KB');
                WHEN (format > 1) THEN bytes = CONCAT(TRUNC((format), 2), 'B');
                ELSE bytes = format;
            END CASE;
            RETURN bytes;
        END;
    $BODY$ 
LANGUAGE plpgsql VOLATILE;

DROP VIEW IF EXISTS replay;
CREATE VIEW replay AS  
    SELECT 
        a.id,
        b.identity,
        b.users,
        a.username,
        a.reply AS info,
        a.authdate AS date,
        to_char(a.authdate, 'HH24:MI:SS') AS time
    FROM radpostauth a
    JOIN radcheck b ON a.username = b.username;

DROP VIEW IF EXISTS lost;
CREATE VIEW lost AS  
    SELECT
        a.radacctid AS id,
        b.identity,
        b.users,
        a.username,
        a.acctterminatecause AS info,
        a.acctstoptime AS date,
        to_char(a.acctstoptime, 'HH24:MI:SS') AS time
    FROM radacct a
    JOIN radcheck b ON a.username = b.username
    WHERE a.acctstoptime IS NOT NULL
    GROUP BY a.radacctid, b.identity, b.users, a.username;

DROP VIEW IF EXISTS active;
CREATE VIEW active AS  
    SELECT 
        a.radacctid AS id,
        b.identity,
        b.users,
        a.nasipaddress AS nasname,
        a.calledstationid AS server,
        a.username,
        a.framedipaddress AS address,
        c.groupname AS profile,
        to_char(a.acctstarttime, 'YYYY-MM-DD HH24:MI:SS') AS time
    FROM radacct a
    JOIN radcheck b ON a.username = b.username
    LEFT JOIN radusergroup c ON a.username = c.username
    WHERE a.acctstoptime IS NULL
    GROUP BY a.radacctid, b.identity, b.users, a.username, c.groupname;

DROP VIEW IF EXISTS levels;
CREATE VIEW levels AS
    SELECT 
        id,
        identity,
        slug,
        name,
        value,
        data,
        ((length(value) - length(replace(value, ',', ''))) + 1) AS menu,
        ((length(data) - length(replace(data, ',', ''))) + 1) AS radius,
        status
   FROM level;

DROP VIEW IF EXISTS access;
CREATE VIEW access AS  
    SELECT 
        a.id,
        a.identity,
        a.level,
        a.username,
        a.pswd,
        a.email,
        a.phone,
        a.name,
        a.image,
        b.value,
        b.data
    FROM users a
    LEFT JOIN level b ON a.level = b.id
    WHERE a.status = true AND b.status = true;

DROP VIEW IF EXISTS expiredcheck;
CREATE VIEW expiredcheck AS  
    SELECT 
        a.identity,
        a.users,
        a.username,
        a.attribute,
        a.value
    FROM radcheck a
    JOIN radacct b ON a.username = b.username
    WHERE a.attribute = 'Access-Period'
    GROUP BY a.identity, a.users, a.username, a.attribute, a.value
    UNION
    SELECT 
        radcheck.identity,
        radcheck.users,
        radcheck.username,
        radcheck.attribute,
        radcheck.value
    FROM radcheck
    WHERE radcheck.attribute = 'Max-Data'
    UNION
    SELECT 
        a.identity,
        a.users,
        a.username,
        c.attribute,
        c.value
    FROM radcheck a
    JOIN radusergroup b ON a.username = b.username AND a.identity = b.identity
    JOIN radgroupcheck c ON b.groupname = c.groupname AND a.identity = c.identity
    WHERE c.attribute = 'Access-Period'
    GROUP BY a.identity, a.users, a.username, c.attribute, c.value
    UNION
    SELECT 
        a.identity,
        a.users,
        a.username,
        c.attribute,
        c.value
    FROM radcheck a
    JOIN radusergroup b ON a.username = b.username AND a.identity = b.identity
    JOIN radgroupcheck c ON b.groupname = c.groupname AND a.identity = c.identity
    WHERE c.attribute = 'Max-Data';

DROP VIEW IF EXISTS expiredgroup;
CREATE VIEW expiredgroup AS 
    SELECT 
        a.identity,
        a.users,
        a.username,
        c.groupname AS profile,
        a.attribute,
        a.value,
        d.price,
        d.discount,
        CASE
            WHEN d.discount IS NULL THEN d.price
            ELSE (d.price - d.discount)
        END AS total,
        to_char(min(b.acctstarttime), 'YYYY-MM-DD HH24:MI') AS time,
        to_char(to_timestamp(date_part('epoch', min(b.acctstarttime)) + a.value::integer), 'YYYY-MM-DD HH24:MI') AS expired
    FROM expiredcheck a
    JOIN radacct b ON a.username = b.username
    LEFT JOIN radusergroup c ON a.username = c.username AND a.identity = c.identity
    LEFT JOIN radprice d ON c.groupname = d.groupname AND a.identity = d.identity
    WHERE a.attribute = 'Access-Period' AND to_timestamp(date_part('epoch', b.acctstarttime) + a.value::integer) < now()
    GROUP BY a.identity, a.users, a.username, c.groupname, a.attribute, a.value, d.price, d.discount
UNION
    SELECT 
        a.identity,
        a.users,
        a.username,
        c.groupname AS profile,
        a.attribute,
        a.value,
        d.price,
        d.discount,
        CASE
            WHEN d.discount IS NULL THEN d.price
            ELSE (d.price - d.discount)
        END AS total,
        to_char(min(b.acctstarttime), 'YYYY-MM-DD HH24:MI') AS time,
        formatbytes(sum(b.acctinputoctets)::bigint + sum(b.acctoutputoctets)::bigint) AS expired
    FROM expiredcheck a
    JOIN radacct b ON a.username = b.username
    LEFT JOIN radusergroup c ON a.username = c.username AND a.identity = c.identity
    LEFT JOIN radprice d ON c.groupname = d.groupname AND a.identity = d.identity
    WHERE a.attribute = 'Max-Data'::text
    GROUP BY a.identity, a.users, a.username, c.groupname, a.attribute, a.value, d.price, d.discount
    HAVING (a.value::bigint - (sum(b.acctinputoctets) + sum(b.acctoutputoctets))) <= 0;

DROP VIEW IF EXISTS expired;
CREATE VIEW expired AS 
    SELECT a.identity,
    a.users,
    a.username,
    a.profile,
    a.price,
    a.discount,
    a.total,
    a."time",
    concat(formatbytes(sum(b.acctinputoctets)::bigint), ' / ', formatbytes(sum(b.acctoutputoctets)::bigint)) AS usages,
    sum(b.acctinputoctets) AS upload,
    sum(b.acctoutputoctets) AS download,
    (sum(b.acctinputoctets) + sum(b.acctoutputoctets)) AS quota
    FROM expiredgroup a
    LEFT JOIN radacct b ON a.username = b.username
    WHERE a.expired IS NOT NULL
    GROUP BY a.identity, a.users, a.username, a.profile, a.price, a.discount, a.total, a."time";

DROP VIEW IF EXISTS resume;
CREATE VIEW resume AS  
    SELECT 
        id,
        identity,
        users,
        total,
        price,
        discount,
        income,
        to_char(date, 'YYYY-MM-DD') AS date,
        date AS time,
        to_char(date, 'Dy') AS week,
        upload,
        download,
        (upload + download) AS usages
   FROM income;

DROP VIEW IF EXISTS profiles;
CREATE VIEW profiles AS  
    SELECT 
        row_number() OVER (ORDER BY a.groupname) AS id,
        a.identity,
        a.users,
        a.groupname,
        b.value AS shared,
        c.value AS period,
        d.value AS rate,
        formatbytes(e.value::bigint) AS quota,
        formatbytes(f.value::bigint) AS volume,
        g.value AS times,
        h.value AS daily,
        i.value AS ppp,
        j.price,
        j.discount,
        a.description
    FROM radgroupcheck a
    LEFT JOIN radgroupcheck b ON a.groupname = b.groupname AND b.attribute = 'Simultaneous-Use'
    LEFT JOIN radgroupcheck c ON a.groupname = c.groupname AND c.attribute = 'Access-Period'
    LEFT JOIN radgroupreply d ON a.groupname = d.groupname AND d.attribute = 'Mikrotik-Rate-Limit'
    LEFT JOIN radgroupreply e ON a.groupname = e.groupname AND e.attribute = 'Mikrotik-Total-Limit'
    LEFT JOIN radgroupcheck f ON a.groupname = f.groupname AND f.attribute = 'Max-Data'
    LEFT JOIN radgroupcheck g ON a.groupname = g.groupname AND g.attribute = 'Max-All-Session'
    LEFT JOIN radgroupcheck h ON a.groupname = h.groupname AND h.attribute = 'Max-Daily-Session'
    LEFT JOIN radgroupreply i ON a.groupname = i.groupname AND i.attribute = 'Framed-Protocol'
    LEFT JOIN radprice j ON a.groupname = j.groupname
    GROUP BY a.identity, a.users, a.groupname, b.value, c.value, d.value, e.value, f.value, g.value, h.value, i.value, j.price, j.discount, a.description;

DROP VIEW IF EXISTS voucher;
CREATE VIEW voucher AS  
    SELECT 
        a.id,
        a.identity,
        a.users,
        a.username,
        a.value AS passwd,
        b.groupname,
        b.groupname AS profiles,
        c.value AS shared,
        d.value AS period,
        e.value AS times,
        f.value AS daily,
        h.value AS rate,
        formatbytes(i.value::bigint) AS quota,
        formatbytes(g.value::bigint) AS volume,
        j.value AS ppp,
        a.description,
        a.created
    FROM radcheck a
    LEFT JOIN radusergroup b ON a.username = b.username
    LEFT JOIN radcheck c ON a.username = c.username AND c.attribute = 'Simultaneous-Use'
    LEFT JOIN radcheck d ON a.username = d.username AND d.attribute = 'Access-Period'
    LEFT JOIN radcheck e ON a.username = e.username AND e.attribute = 'Max-All-Session'
    LEFT JOIN radcheck f ON a.username = f.username AND f.attribute = 'Max-Daily-Session'
    LEFT JOIN radcheck g ON a.username = g.username AND g.attribute = 'Max-Data'
    LEFT JOIN radreply h ON a.username = h.username AND h.attribute = 'Mikrotik-Rate-Limit'
    LEFT JOIN radreply i ON a.username = i.username AND i.attribute = 'Mikrotik-Total-Limit'
    LEFT JOIN radreply j ON a.username = j.username AND j.attribute = 'Framed-Protocol'
    WHERE a.attribute = 'Cleartext-Password'
    ORDER BY a.id DESC;

DROP VIEW IF EXISTS print;
CREATE VIEW print AS  
    SELECT 
        a.id,
        row_number() OVER (ORDER BY a.id) AS no,
        a.identity,
        a.users,
        d.data,
        a.username,
        a.passwd AS password,
        a.profiles AS profile,
        b.period,
        b.shared,
        CASE
            WHEN (a.ppp IS NOT NULL) THEN a.ppp
            ELSE b.ppp
        END AS ppp,
        CASE
            WHEN (a.rate IS NOT NULL) THEN a.rate
            ELSE b.rate
        END AS rate,
        CASE
            WHEN (a.quota IS NOT NULL) THEN a.quota
            ELSE b.quota
        END AS quota,
        CASE
            WHEN (a.times IS NOT NULL) THEN a.times
            ELSE b.times
        END AS times,
        CASE
            WHEN (a.daily IS NOT NULL) THEN a.daily
            ELSE b.daily
        END AS daily,
        b.price,
        b.discount,
        c.sosmed AS url,
        concat(c.sosmed, '/login?username=', a.username, '&password=', a.passwd) AS qr_code,
        a.created
    FROM voucher a
    LEFT JOIN profiles b ON a.profiles = b.groupname AND a.identity = b.identity
    LEFT JOIN config c ON a.identity = c.id
    LEFT JOIN identity d ON a.identity = d.id;
