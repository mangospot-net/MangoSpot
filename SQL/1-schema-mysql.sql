
CREATE TABLE radacct (
  radacctid bigint(21) NOT NULL auto_increment,
  acctsessionid varchar(64) NOT NULL default '',
  acctuniqueid varchar(32) NOT NULL default '',
  username varchar(64) NOT NULL default '',
  groupname varchar(64) NOT NULL default '',
  realm varchar(64) default '',
  nasipaddress varchar(15) NOT NULL default '',
  nasportid varchar(15) default NULL,
  nasporttype varchar(32) default NULL,
  acctstarttime datetime NULL default NULL,
  acctupdatetime datetime NULL default NULL,
  acctstoptime datetime NULL default NULL,
  acctinterval int(12) default NULL,
  acctsessiontime int(12) unsigned default NULL,
  acctauthentic varchar(32) default NULL,
  connectinfo_start varchar(50) default NULL,
  connectinfo_stop varchar(50) default NULL,
  acctinputoctets bigint(20) default NULL,
  acctoutputoctets bigint(20) default NULL,
  calledstationid varchar(50) NOT NULL default '',
  callingstationid varchar(50) NOT NULL default '',
  acctterminatecause varchar(32) NOT NULL default '',
  servicetype varchar(32) default NULL,
  framedprotocol varchar(32) default NULL,
  framedipaddress varchar(15) NOT NULL default '',
  PRIMARY KEY (radacctid),
  UNIQUE KEY acctuniqueid (acctuniqueid),
  KEY username (username),
  KEY framedipaddress (framedipaddress),
  KEY acctsessionid (acctsessionid),
  KEY acctsessiontime (acctsessiontime),
  KEY acctstarttime (acctstarttime),
  KEY acctinterval (acctinterval),
  KEY acctstoptime (acctstoptime),
  KEY nasipaddress (nasipaddress)
) ENGINE = INNODB;

CREATE TABLE radcheck (
  id int(11) unsigned NOT NULL auto_increment,
  identity  int(32),
  users int(32),
  username varchar(64) NOT NULL default '',
  attribute varchar(64)  NOT NULL default '',
  op char(2) NOT NULL DEFAULT '==',
  value varchar(253) NOT NULL default '',
  description	text,
	created		timestamp,
  PRIMARY KEY  (id),
  KEY username (username(32))
);

CREATE TABLE radgroupcheck (
  id int(11) unsigned NOT NULL auto_increment,
  identity  int(32),
  users int(32),
  groupname varchar(64) NOT NULL default '',
  attribute varchar(64)  NOT NULL default '',
  op char(2) NOT NULL DEFAULT '==',
  value varchar(253)  NOT NULL default '',
  description	text,
  PRIMARY KEY  (id),
  KEY groupname (groupname(32))
);

CREATE TABLE radgroupreply (
  id int(11) unsigned NOT NULL auto_increment,
  identity  int(32),
  users int(32),
  groupname varchar(64) NOT NULL default '',
  attribute varchar(64)  NOT NULL default '',
  op char(2) NOT NULL DEFAULT '=',
  value varchar(253)  NOT NULL default '',
  description	text,
  PRIMARY KEY  (id),
  KEY groupname (groupname(32))
);

CREATE TABLE radreply (
  id int(11) unsigned NOT NULL auto_increment,
  identity  int(32),
  users int(32),
  username varchar(64) NOT NULL default '',
  attribute varchar(64) NOT NULL default '',
  op char(2) NOT NULL DEFAULT '=',
  value varchar(253) NOT NULL default '',
	description	text,
	created		timestamp,
  PRIMARY KEY  (id),
  KEY username (username(32))
);


CREATE TABLE radusergroup (
  identity  int(32),
  users int(32),
  username varchar(64) NOT NULL default '',
  groupname varchar(64) NOT NULL default '',
  priority int(11) NOT NULL default '1',
  KEY username (username(32))
);

CREATE TABLE radpostauth (
  id int(11) NOT NULL auto_increment,
  username varchar(64) NOT NULL default '',
  pass varchar(64) NOT NULL default '',
  reply varchar(32) NOT NULL default '',
  authdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (id)
);

CREATE TABLE nas (
  id int(10) NOT NULL auto_increment,
  identity  int(11),
  users int(32),
  username		varchar(100),
	password		varchar(200),
  nasname varchar(128) NOT NULL,
  shortname varchar(32),
  type varchar(30) DEFAULT 'other',
  ports int(5),
  secret varchar(60) DEFAULT 'secret' NOT NULL,
  server varchar(64),
  community varchar(50),
  description varchar(200) DEFAULT 'RADIUS Client',
  port  int(16) DEFAULT '8728',
	status  enum('true','false') DEFAULT NULL,
  PRIMARY KEY (id),
  KEY nasname (nasname)
);

CREATE TABLE config (
    id		int(11) NOT NULL auto_increment,
    host	varchar(100),
    name	varchar(100),
    email	varchar(100),
    pswd	varchar(100),
    smtp	varchar(10),
    port	int(16),
    api		varchar(150),
    headers text,
    footers text,
    go_id	varchar(150),
    go_ap	varchar(150),
    go_sc	varchar(150),
    fb_id	varchar(150),
    fb_vs	varchar(150),
    fb_sc	varchar(150),
    on_api	varchar(150),
    on_key	varchar(150),
    on_dvc	varchar(150),
    sosmed 	text,
    PRIMARY KEY (id)
);

CREATE TABLE dictionary (
    id					int(32) NOT NULL auto_increment,
    type				varchar(30),
    attribute			varchar(64),
    value				varchar(64),
    format				varchar(20),
    vendor				varchar(32),
    recommendedop		varchar(32),
    recommendedtable	varchar(32),
    recommendedhelper	varchar(32),
    recommendedtooltip	varchar(512),
    PRIMARY KEY (id)
);

CREATE TABLE identity (
    id			int(11) NOT NULL auto_increment,
    district	int(16),
    theme		varchar(50),
    data		varchar(100) NOT NULL,
    title		varchar(200),
    web			varchar(50),
    url			varchar(50) NOT NULL,
    email		varchar(100),
    phone		varchar(25),
    fax			varchar(25),
    address		text,
    zip			int(16),
    icon		varchar(200),
    logo		varchar(200),
    cover		text,
    lat			varchar(50),
    lng			varchar(50),
    register	timestamp,
    expayed		timestamp,
    toeken		varchar(100),
    ads			enum('true','false') DEFAULT NULL,
    status		enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE level (
    id			int(11) NOT NULL auto_increment,
    identity	int(11) NOT NULL,
    slug		int(11),
    name		varchar(100) NOT NULL,
    value		text,
    status		enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE menu (
    id		int(11) NOT NULL auto_increment,
    slug	int(11),
    name	varchar(50) NOT NULL,
    value	varchar(50),
    icon	varchar(50),
    number	int(16),
    status	enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE price (
    id			int(11) NOT NULL auto_increment,
    identity	int(11) NOT NULL,
    users   	int(32),
    groupname	varchar(100) NOT NULL,
    value		varchar(100),
    PRIMARY KEY (id)
);

CREATE TABLE income (
  id        int(11) NOT NULL auto_increment,
  identity  int(11) NOT NULL,
  users   	int(32),
  total     int(16),
  value     int(16),
  date      timestamp,
  PRIMARY KEY (id)
);

CREATE TABLE themes (
    id			int(11) NOT NULL auto_increment,
    identity	int(11) NOT NULL,
    users   	int(32),
    name		varchar(100) NOT NULL,
    content		text,
    PRIMARY KEY (id)
);

CREATE TABLE type (
    id		int(11) NOT NULL auto_increment,
    name	varchar(50) NOT NULL,
    type	varchar(25),
    info	text,
    status	enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE users (
    id			int(32) NOT NULL auto_increment,
    identity	int(11) NOT NULL,
    level		int(11) NOT NULL,
    district	int(16),
    username 	varchar(100),
    pswd		varchar(100) NOT NULL,
    name		varchar(100) NOT NULL,
    email		varchar(100),
    phone		varchar(25),
    gender		varchar(12),
    religion	varchar(25),
    place		varchar(100),
    birth		varchar(12),
    address		text,
    zip			smallint,
    image		varchar(100),
    google		bigint,
    facebook	bigint,
    online		varchar(20),
    date		timestamp,
    status		enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB;

DROP VIEW IF EXISTS replay;
CREATE VIEW replay AS  SELECT a.id,
  b.identity,
  b.users,
  a.username,
  a.reply AS info,
  a.authdate AS date,
  DATE_FORMAT(a.authdate, '%H:%i:%s') AS time
  FROM (radpostauth a
  JOIN radcheck b ON ((a.username = b.username)));

DROP VIEW IF EXISTS lost;
CREATE VIEW lost AS  SELECT a.radacctid AS id,
  b.identity,
  b.users,
  a.username,
  a.acctterminatecause AS info,
  a.acctstoptime AS date,
  DATE_FORMAT(a.acctstoptime, '%H:%i:%s') AS time
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
  WHERE (a.attribute = 'Access-Period')
  GROUP BY a.identity, a.users, a.username, a.attribute, a.value
UNION
SELECT radcheck.identity,
  radcheck.users,
  radcheck.username,
  radcheck.attribute,
  radcheck.value
  FROM radcheck
  WHERE (radcheck.attribute = 'Max-Data')
UNION
SELECT a.identity,
  a.users,
  a.username,
  c.attribute,
  c.value
  FROM ((radcheck a
  JOIN radusergroup b ON (((a.username = b.username) AND (a.identity = b.identity) AND (a.users = b.users))))
  JOIN radgroupcheck c ON (((b.groupname = c.groupname) AND (a.identity = c.identity) AND (a.users = c.users))))
  WHERE (c.attribute = 'Access-Period')
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
  WHERE (c.attribute = 'Max-Data');

DROP VIEW IF EXISTS expired;
CREATE VIEW expired AS  SELECT a.identity,
  a.users,
  a.username,
  c.groupname AS profile,
  a.attribute,
  a.value,
  d.value AS price,
  DATE_FORMAT(min(b.acctstarttime), '%d-%m-%Y %H:%i') AS time,
  DATE_FORMAT(UNIX_TIMESTAMP(min(b.acctstarttime)) + a.value, '%Y-%m-%d %H:%i') AS expired
  FROM (((expiredcheck a
  JOIN radacct b ON ((a.username = b.username)))
  LEFT JOIN radusergroup c ON (((a.username = c.username) AND (a.identity = c.identity) AND (a.users = c.users))))
  LEFT JOIN price d ON (((c.groupname = (d.groupname)) AND (a.identity = d.identity) AND (a.users = d.users))))
  WHERE ((UNIX_TIMESTAMP(b.acctstarttime) + ((a.value))) < now())
  GROUP BY a.identity, a.users, a.username, c.groupname, a.attribute, a.value, d.value
UNION
SELECT a.identity,
  a.users,
  a.username,
  c.groupname AS profile,
  a.attribute,
  a.value,
  d.value AS price,
  DATE_FORMAT(min(b.acctstarttime), '%d-%m-%Y %H:%i') AS time,
  a.value AS expired
  FROM (((expiredcheck a
  JOIN radacct b ON ((a.username = b.username)))
  LEFT JOIN radusergroup c ON (((a.username = c.username) AND (a.identity = c.identity) AND (a.users = c.users))))
  LEFT JOIN price d ON (((c.groupname = (d.groupname)) AND (a.identity = d.identity) AND (a.users = d.users))))
  WHERE (a.attribute = 'Max-Data')
  GROUP BY a.identity, a.users, a.username, c.groupname, a.attribute, a.value, d.value
  HAVING ((((a.value)) - (sum(b.acctinputoctets) + sum(b.acctoutputoctets))) <= (0));

DROP VIEW IF EXISTS active;
CREATE VIEW active AS  SELECT a.radacctid AS id,
  b.identity,
  b.users,
  a.nasipaddress AS nasname,
  a.calledstationid AS server,
  a.username,
  a.framedipaddress AS address,
  c.groupname AS profile,
  DATE_FORMAT(a.acctstarttime, '%d-%m-%Y %H:%i:%s') AS time
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
    DATE_FORMAT(income.date, '%Y-%m-%d') AS date,
    income.date AS time,
    DATE_FORMAT(income.date, '%D') AS week
   FROM income;
