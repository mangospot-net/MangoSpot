CREATE TABLE sysfacility (
  id        serial PRIMARY KEY,
  name      varchar(50),
  info      varchar(200)
);
COMMENT ON TABLE sysfacility IS 'Syslog';

CREATE TABLE sysseverity (
  id        serial PRIMARY KEY,
  name      varchar(50) NOT NULL,
  info      varchar(200),
  color     varchar(100)
);
COMMENT ON TABLE sysseverity IS 'Syslog';

CREATE TABLE systemevents (
  id                    serial PRIMARY KEY,
  customerid            bigint,
  receivedat            timestamp(6),
  devicereportedtime    timestamp(6),
  facility              smallint,
  priority              smallint,
  fromhost              varchar(60),
  message               text,
  ntseverity            integer,
  importance            integer,
  eventsource           varchar(60),
  eventuser             varchar(60),
  eventcategory         integer,
  eventid               integer,
  eventbinarydata       text,
  maxavailable          integer,
  currusage             integer,
  minusage              integer,
  maxusage              integer,
  infounitid            integer,
  syslogtag             varchar(60),
  eventlogtype          varchar(60),
  genericfilename       varchar(60),
  systemid              integer
);
COMMENT ON TABLE systemevents IS 'Syslog';

CREATE TABLE systemeventsproperties (
  id            serial PRIMARY KEY,
  systemeventid integer,
  paramname     varchar(255),
  paramvalue    text
);
COMMENT ON TABLE systemeventsproperties IS 'Syslog';

DROP VIEW IF EXISTS syslog;
CREATE VIEW syslog AS 
    SELECT 
        a.id,
        a.receivedat AS date,
        b.name AS facility,
        c.name AS priority,
        a.syslogtag AS syslog,
        a.message,
        c.color,
        a.facility AS sysfacility,
        a.priority AS syspriority
    FROM systemevents a
    LEFT JOIN sysfacility b ON a.facility = b.id
    LEFT JOIN sysseverity c ON a.priority = c.id;

INSERT INTO sysseverity VALUES (0, 'Emerg', 'system is unusable', 'secondary');
INSERT INTO sysseverity VALUES (1, 'Alert', 'action must be taken immediately', 'primary');
INSERT INTO sysseverity VALUES (2, 'Crit', 'critical conditions', 'dark');
INSERT INTO sysseverity VALUES (3, 'Error', 'error conditions', 'danger');
INSERT INTO sysseverity VALUES (4, 'Warning', 'warning conditions', 'warning');
INSERT INTO sysseverity VALUES (5, 'Notice', 'normal but significant condition', 'light');
INSERT INTO sysseverity VALUES (6, 'Info', 'informational messages', NULL);
INSERT INTO sysseverity VALUES (7, 'Debug', 'debug-level messages', 'info');

INSERT INTO sysfacility VALUES (0, 'Kern', 'kernel messages');
INSERT INTO sysfacility VALUES (1, 'User', 'user-level messages');
INSERT INTO sysfacility VALUES (2, 'Mail', 'mail system');
INSERT INTO sysfacility VALUES (3, 'Daemon', 'system daemons');
INSERT INTO sysfacility VALUES (4, 'Auth', 'security/authorization messages');
INSERT INTO sysfacility VALUES (5, 'Syslog', 'messages generated internally by syslogd');
INSERT INTO sysfacility VALUES (6, 'LPR', 'line printer subsystem');
INSERT INTO sysfacility VALUES (7, 'News', 'network news subsystem');
INSERT INTO sysfacility VALUES (8, 'UUCP', 'UUCP subsystem');
INSERT INTO sysfacility VALUES (9, 'Cron', 'clock daemon');
INSERT INTO sysfacility VALUES (10, 'Security', 'security/authorization messages');
INSERT INTO sysfacility VALUES (11, 'FTP', 'FTP daemon');
INSERT INTO sysfacility VALUES (12, 'NTP', 'NTP subsystem');
INSERT INTO sysfacility VALUES (13, 'Audit', 'log audit');
INSERT INTO sysfacility VALUES (14, 'Alert', 'log alert');
INSERT INTO sysfacility VALUES (15, 'Clock', 'clock daemon (note 2)');
INSERT INTO sysfacility VALUES (16, 'Local0', 'local use 0 (local0)');
INSERT INTO sysfacility VALUES (17, 'Local1', 'local use 1 (local1)');
INSERT INTO sysfacility VALUES (18, 'Local2', 'local use 2 (local2)');
INSERT INTO sysfacility VALUES (19, 'Local3', 'local use 3 (local3)');
INSERT INTO sysfacility VALUES (20, 'Local4', 'local use 4 (local4)');
INSERT INTO sysfacility VALUES (21, 'Local5', 'local use 5 (local5)');
INSERT INTO sysfacility VALUES (22, 'Local6', 'local use 6 (local6)');
INSERT INTO sysfacility VALUES (23, 'Local7', 'local use 7 (local7)');