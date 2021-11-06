
CREATE TABLE radacct (
  radacctid           bigint(21) NOT NULL auto_increment,
  acctsessionid       varchar(64) NOT NULL default '',
  acctuniqueid        varchar(32) NOT NULL default '',
  username            varchar(64) NOT NULL default '',
  groupname           varchar(64) NOT NULL default '',
  realm               varchar(64) default '',
  nasipaddress        varchar(15) NOT NULL default '',
  nasportid           varchar(15) default NULL,
  nasporttype         varchar(32) default NULL,
  acctstarttime       datetime NULL default NULL,
  acctupdatetime      datetime NULL default NULL,
  acctstoptime        datetime NULL default NULL,
  acctinterval        int(12) default NULL,
  acctsessiontime     int(12) unsigned default NULL,
  acctauthentic       varchar(32) default NULL,
  connectinfo_start   varchar(50) default NULL,
  connectinfo_stop    varchar(50) default NULL,
  acctinputoctets     bigint(20) default NULL,
  acctoutputoctets    bigint(20) default NULL,
  calledstationid     varchar(50) NOT NULL default '',
  callingstationid    varchar(50) NOT NULL default '',
  acctterminatecause  varchar(32) NOT NULL default '',
  servicetype         varchar(32) default NULL,
  framedprotocol      varchar(32) default NULL,
  framedipaddress     varchar(15) NOT NULL default '',
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
) ENGINE = INNODB COMMENT='Radius';

CREATE TABLE radcheck (
  id          int(11) unsigned NOT NULL auto_increment,
  identity    int(32),
  users       int(32),
  username    varchar(64) NOT NULL default '',
  attribute   varchar(64)  NOT NULL default '',
  op          char(2) NOT NULL DEFAULT '==',
  value       varchar(253) NOT NULL default '',
  description text,
	created		  timestamp,
  PRIMARY KEY  (id),
  KEY username (username(32))
) ENGINE = INNODB COMMENT='Radius';

CREATE TABLE radgroupcheck (
  id          int(11) unsigned NOT NULL auto_increment,
  identity    int(32),
  users       int(32),
  groupname   varchar(64) NOT NULL default '',
  attribute   varchar(64)  NOT NULL default '',
  op          char(2) NOT NULL DEFAULT '==',
  value       varchar(253)  NOT NULL default '',
  description	text,
  PRIMARY KEY  (id),
  KEY groupname (groupname(32))
) ENGINE = INNODB COMMENT='Radius';

CREATE TABLE radgroupreply (
  id          int(11) unsigned NOT NULL auto_increment,
  identity    int(32),
  users       int(32),
  groupname   varchar(64) NOT NULL default '',
  attribute   varchar(64)  NOT NULL default '',
  op          char(2) NOT NULL DEFAULT '=',
  value       varchar(253)  NOT NULL default '',
  description	text,
  PRIMARY KEY  (id),
  KEY groupname (groupname(32))
) ENGINE = INNODB COMMENT='Radius';

CREATE TABLE radreply (
  id          int(11) unsigned NOT NULL auto_increment,
  identity    int(32),
  users       int(32),
  username    varchar(64) NOT NULL default '',
  attribute   varchar(64) NOT NULL default '',
  op          char(2) NOT NULL DEFAULT '=',
  value       varchar(253) NOT NULL default '',
	description	text,
	created		  timestamp,
  PRIMARY KEY  (id),
  KEY username (username(32))
) ENGINE = INNODB COMMENT='Radius';

CREATE TABLE radusergroup (
  identity  int(32),
  users     int(32),
  username  varchar(64) NOT NULL default '',
  groupname varchar(64) NOT NULL default '',
  priority  int(11) NOT NULL default '1',
  KEY username (username(32))
) ENGINE = INNODB COMMENT='Radius';

CREATE TABLE radpostauth (
  id        int(11) NOT NULL auto_increment,
  username  varchar(64) NOT NULL default '',
  pass      varchar(64) NOT NULL default '',
  reply     varchar(32) NOT NULL default '',
  authdate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (id)
) ENGINE = INNODB COMMENT='Radius';

CREATE TABLE nas (
  id          int(10) NOT NULL auto_increment,
  identity    int(11),
  users       int(32),
  username		varchar(100),
	password		varchar(200),
  nasname     varchar(128) NOT NULL,
  shortname   varchar(32),
  type        varchar(30) DEFAULT 'other',
  ports       int(5),
  secret      varchar(60) DEFAULT 'secret' NOT NULL,
  server      varchar(64),
  community   varchar(50),
  description varchar(200) DEFAULT 'RADIUS Client',
  port        int(16) DEFAULT '8728',
	status      enum('true','false') DEFAULT NULL,
  PRIMARY KEY (id),
  KEY nasname (nasname)
) ENGINE = INNODB COMMENT='Radius';

CREATE TABLE config (
    id		    int(11) NOT NULL auto_increment,
    host	    varchar(100),
    name	    varchar(100),
    email	    varchar(100),
    pswd	    varchar(100),
    smtp	    varchar(10),
    port	    int(16),
    api		    varchar(150),
    headers   text,
    footers   text,
    currency  varchar(10),
    go_id	    varchar(150),
    go_ap	    varchar(150),
    go_sc	    varchar(150),
    fb_id	    varchar(150),
    fb_vs	    varchar(150),
    fb_sc	    varchar(150),
    on_api    varchar(150),
    on_key    varchar(150),
    on_dvc    varchar(150),
    sosmed    text,
    wifi      text,
    PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

CREATE TABLE identity (
    id			  int(11) NOT NULL auto_increment,
    district	int(16),
    theme		  varchar(50),
    data		  varchar(100) NOT NULL,
    title		  varchar(200),
    web			  varchar(50),
    url			  varchar(50) NOT NULL,
    email		  varchar(100),
    phone		  varchar(25),
    fax			  varchar(25),
    address		text,
    zip			  int(16),
    icon		  varchar(200),
    logo		  varchar(200),
    cover		  text,
    lat			  varchar(50),
    lng			  varchar(50),
    register	timestamp,
    expayed		timestamp,
    toeken		varchar(100),
    ads			  enum('true','false') DEFAULT NULL,
    status		enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

CREATE TABLE level (
    id			  int(11) NOT NULL auto_increment,
    identity	int(11) NOT NULL,
    slug		  int(11),
    name		  varchar(100) NOT NULL,
    value		  text,
    data		  text,
    status		enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

CREATE TABLE menu (
    id		  int(11) NOT NULL auto_increment,
    slug	  int(11),
    name	  varchar(50) NOT NULL,
    value	  varchar(50),
    icon	  varchar(50),
    number	int(16),
    status	enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

CREATE TABLE radprice (
    id			  int(11) NOT NULL auto_increment,
    identity	int(11) NOT NULL,
    users   	int(32),
    groupname	varchar(100) NOT NULL,
    price		  int(11),
    discount  int(11),
    PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='Radius';

CREATE TABLE income (
  id        int(11) NOT NULL auto_increment,
  identity  int(11) NOT NULL,
  users   	int(32),
  total     int(16),
  price     int(16),
  discount  int(16),
  income    int(16),
  upload    bigint,
  download  bigint,
  data      text,
  date      timestamp,
  PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

CREATE TABLE packet (
  id        int(16) NOT NULL auto_increment,
  identity  int(16) NOT NULL,
  users     int(32) NOT NULL,
  client    int(32) NOT NULL,
  groupname varchar(100) NOT NULL,
  price     int(16),
  total     int(16),
  voucher   int(16),
  defaults  enum('true','false') DEFAULT NULL,
  status    enum('true','false') DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

CREATE TABLE payment (
  id        int(32) NOT NULL auto_increment,
  identity  int(16) NOT NULL,
  users     int(32) NOT NULL,
  client    int(32) NOT NULL,
  packet    int(16) NOT NULL,
  price     int(16) NOT NULL,
  total     int(16),
  info      text,
  date      timestamp(6),
  approve   timestamp(6),
  status    enum('true','false') DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

CREATE TABLE themes (
    id			  int(11) NOT NULL auto_increment,
    identity	int(11) NOT NULL,
    users   	int(32),
    name		  varchar(100) NOT NULL,
    type      varchar(10),
    content		text,
    PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

CREATE TABLE type (
    id		  int(11) NOT NULL auto_increment,
    name	  varchar(50) NOT NULL,
    type	  varchar(25),
    info	  text,
    status	enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

CREATE TABLE users (
    id			  int(32) NOT NULL auto_increment,
    identity	int(11) NOT NULL,
    level		  int(11) NOT NULL,
    district	int(16),
    number    varchar(50),
    username 	varchar(100),
    pswd		  varchar(100) NOT NULL,
    name		  varchar(100) NOT NULL,
    email		  varchar(100),
    phone		  varchar(25),
    gender		varchar(12),
    religion	varchar(25),
    place		  varchar(100),
    birth		  varchar(12),
    address		text,
    zip			  smallint,
    image		  varchar(100),
    google		bigint,
    facebook	bigint,
    online		varchar(20),
    date		  timestamp,
    status		enum('true','false') DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB COMMENT='MangoSpot';

DELIMITER //
CREATE FUNCTION formatbytes(byte BIGINT) RETURNS VARCHAR(200)
BEGIN
  DECLARE bytes VARCHAR(200);
	CASE
		WHEN (byte >= 1099511627776) THEN SET bytes = CONCAT(FORMAT((byte / 1099511627776), 2), 'TB');
		WHEN (byte >= 1073741824) THEN SET bytes = CONCAT(FORMAT((byte / 1073741824), 2), 'GB');
 		WHEN (byte >= 1048576) THEN SET bytes = CONCAT(FORMAT((byte / 1048576), 2), 'MB');
 		WHEN (byte >= 1024) THEN SET bytes = CONCAT(FORMAT((byte / 1024), 2), 'KB');
 		WHEN (byte > 1) THEN SET bytes = CONCAT(FORMAT((byte), 2), 'B');
		ELSE SET bytes = byte;
	END CASE;
	RETURN bytes;
END; //

DELIMITER ;

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

CREATE VIEW replay AS  
  SELECT 
    a.id,
    b.identity,
    b.users,
    a.username,
    a.reply AS info,
    a.authdate AS date,
    DATE_FORMAT(a.authdate, '%H:%i:%s') AS time
  FROM radpostauth a
  JOIN radcheck b ON a.username = b.username;

CREATE VIEW lost AS  
  SELECT 
    a.radacctid AS id,
    b.identity,
    b.users,
    a.username,
    a.acctterminatecause AS info,
    a.acctstoptime AS date,
    DATE_FORMAT(a.acctstoptime, '%H:%i:%s') AS time
  FROM radacct a
  JOIN radcheck b ON a.username = b.username
  WHERE a.acctstoptime IS NOT NULL
  GROUP BY a.radacctid, b.identity, b.users, a.username;

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
      ELSE d.price - d.discount 
    END AS total,
    date_format(min(b.acctstarttime),'%Y-%m-%d %H:%i') AS time,
    CHAR(date_format(from_unixtime(unix_timestamp(b.acctstarttime) + a.value),'%Y-%m-%d %H:%i')) AS expired 
  FROM expiredcheck a JOIN radacct b ON a.username = b.username 
  LEFT JOIN radusergroup c ON a.username = c.username AND a.identity = c.identity
  LEFT JOIN radprice d ON c.groupname = d.groupname AND a.identity = d.identity 
  WHERE a.attribute = 'Access-Period' AND unix_timestamp(b.acctstarttime) + a.value < unix_timestamp() 
  GROUP BY a.identity,a.users,a.username,c.groupname,a.attribute,a.value,d.price,d.discount 
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
      ELSE d.price - d.discount 
    END AS total,
    date_format(min(b.acctstarttime),'%Y-%m-%d %H:%i') AS time,
    sum(b.acctsessiontime) AS expired 
  FROM expiredcheck a JOIN radacct b ON a.username = b.username 
  LEFT JOIN radusergroup c ON a.username = c.username AND a.identity = c.identity 
  LEFT JOIN radprice d ON c.groupname = d.groupname AND a.identity = d.identity 
  WHERE a.attribute = 'Max-All-Session' 
  GROUP BY a.identity,a.users,a.username,c.groupname,a.attribute,a.value,d.price,d.discount 
  HAVING a.value <= sum(b.acctsessiontime)
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
      ELSE d.price - d.discount 
    END AS total,
    date_format(min(b.acctstarttime),'%Y-%m-%d %H:%i') AS time,
    CHAR(formatbytes(sum(b.acctinputoctets) + sum(b.acctoutputoctets))) AS expired 
  FROM expiredcheck a JOIN radacct b ON a.username = b.username 
  LEFT JOIN radusergroup c ON a.username = c.username AND a.identity = c.identity 
  LEFT JOIN radprice d ON c.groupname = d.groupname AND a.identity = d.identity 
  WHERE a.attribute = 'Max-Data' 
  GROUP BY a.identity,a.users,a.username,c.groupname,a.attribute,a.value,d.price,d.discount 
  HAVING a.value - (sum(b.acctinputoctets) + sum(b.acctoutputoctets)) <= 0;

CREATE VIEW expired AS
  SELECT 
    a.identity, 
    a.users, 
    a.username, 
    a.profile, 
    a.price, 
    a.discount, 
    a.total, 
    a.time, 
    concat(formatbytes(sum(b.acctinputoctets)), ' / ', formatbytes(sum(b.acctoutputoctets))) as usages,
    sum(b.acctinputoctets) AS upload,
    sum(b.acctoutputoctets) AS download,
    (sum(b.acctinputoctets) + sum(b.acctoutputoctets)) AS quota
  FROM expiredgroup a LEFT JOIN radacct b on a.username = b.username
  WHERE a.expired IS NOT NULL
  GROUP BY a.identity, a.users, a.username, a.profile, a.price, a.discount, a.total, a.time;

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
    DATE_FORMAT(a.acctstarttime, '%Y-%m-%d %H:%i:%s') AS time
  FROM radacct a
  JOIN radcheck b ON a.username = b.username
  LEFT JOIN radusergroup c ON a.username = c.username
  WHERE a.acctstoptime IS NULL
  GROUP BY a.radacctid, b.identity, b.users, a.username, c.groupname;

CREATE VIEW resume AS 
  SELECT 
    id,
    identity,
    users,
    total,
    price,
    discount,
    income,
    DATE_FORMAT(date, '%Y-%m-%d') AS date,
    date AS time,
    DATE_FORMAT(date, '%D') AS week,
    upload,
    download,
    (upload + download) AS usages
  FROM income;

CREATE VIEW profiles AS  
  SELECT 
    a.id,
    a.identity,
    a.users,
    a.groupname,
    b.value AS shared,
    c.value AS period,
    d.value AS rate,
    formatbytes(e.value) AS quota,
    formatbytes(f.value) AS volume,
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
    formatbytes(i.value) AS quota,
    formatbytes(g.value) AS volume,
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

CREATE VIEW print AS 
  SELECT 
    a.id,
    a.id AS no,
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
