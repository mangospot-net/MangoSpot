CREATE TABLE sysfacility (
  id        int(16) NOT NULL,
  name      varchar(50),
  info      varchar(200)
) ENGINE = INNODB COMMENT='Syslog';

CREATE TABLE sysseverity (
  id        int(16) NOT NULL,
  name      varchar(50) NOT NULL,
  info      varchar(200),
  color     varchar(100)
) ENGINE = INNODB COMMENT='Syslog';

CREATE TABLE SystemEvents (
  ID int unsigned not null auto_increment primary key,
  CustomerID bigint,
  ReceivedAt datetime NULL,
  DeviceReportedTime datetime NULL,
  Facility smallint NULL,
  Priority smallint NULL,
  FromHost varchar(60) NULL,
  Message text,
  NTSeverity int NULL,
  Importance int NULL,
  EventSource varchar(60),
  EventUser varchar(60) NULL,
  EventCategory int NULL,
  EventID int NULL,
  EventBinaryData text NULL,
  MaxAvailable int NULL,
  CurrUsage int NULL,
  MinUsage int NULL,
  MaxUsage int NULL,
  InfoUnitID int NULL ,
  SysLogTag varchar(60),
  EventLogType varchar(60),
  GenericFileName VarChar(60),
  SystemID int NULL
) ENGINE = INNODB COMMENT='Syslog';

CREATE TABLE SystemEventsProperties (
  ID int unsigned not null auto_increment primary key,
  SystemEventID int NULL ,
  ParamName varchar(255) NULL ,
  ParamValue text NULL
) ENGINE = INNODB COMMENT='Syslog';

CREATE VIEW syslog AS  SELECT a.id,
    a.ReceivedAt AS date,
    b.name AS facility,
    c.name AS priority,
    a.SysLogTag AS syslog,
    a.Message AS message,
    c.color,
    a.Facility AS sysfacility,
    a.Priority AS syspriority
   FROM ((SystemEvents a
     LEFT JOIN sysfacility b ON ((a.Facility = b.id)))
     LEFT JOIN sysseverity c ON ((a.Priority = c.id)));

INSERT INTO sysseverity (id, name, info, color) VALUES (0, 'Emerg', 'system is unusable', 'secondary');
INSERT INTO sysseverity (id, name, info, color) VALUES (1, 'Alert', 'action must be taken immediately', 'primary');
INSERT INTO sysseverity (id, name, info, color) VALUES (2, 'Crit', 'critical conditions', 'dark');
INSERT INTO sysseverity (id, name, info, color) VALUES (3, 'Error', 'error conditions', 'danger');
INSERT INTO sysseverity (id, name, info, color) VALUES (4, 'Warning', 'warning conditions', 'warning');
INSERT INTO sysseverity (id, name, info, color) VALUES (5, 'Notice', 'normal but significant condition', 'light');
INSERT INTO sysseverity (id, name, info, color) VALUES (6, 'Info', 'informational messages', NULL);
INSERT INTO sysseverity (id, name, info, color) VALUES (7, 'Debug', 'debug-level messages', 'info');

INSERT INTO sysfacility (id, name, info) VALUES (0, 'Kern', 'kernel messages');
INSERT INTO sysfacility (id, name, info) VALUES (1, 'User', 'user-level messages');
INSERT INTO sysfacility (id, name, info) VALUES (2, 'Mail', 'mail system');
INSERT INTO sysfacility (id, name, info) VALUES (3, 'Daemon', 'system daemons');
INSERT INTO sysfacility (id, name, info) VALUES (4, 'Auth', 'security/authorization messages');
INSERT INTO sysfacility (id, name, info) VALUES (5, 'Syslog', 'messages generated internally by syslogd');
INSERT INTO sysfacility (id, name, info) VALUES (6, 'LPR', 'line printer subsystem');
INSERT INTO sysfacility (id, name, info) VALUES (7, 'News', 'network news subsystem');
INSERT INTO sysfacility (id, name, info) VALUES (8, 'UUCP', 'UUCP subsystem');
INSERT INTO sysfacility (id, name, info) VALUES (9, 'Cron', 'clock daemon');
INSERT INTO sysfacility (id, name, info) VALUES (10, 'Security', 'security/authorization messages');
INSERT INTO sysfacility (id, name, info) VALUES (11, 'FTP', 'FTP daemon');
INSERT INTO sysfacility (id, name, info) VALUES (12, 'NTP', 'NTP subsystem');
INSERT INTO sysfacility (id, name, info) VALUES (13, 'Audit', 'log audit');
INSERT INTO sysfacility (id, name, info) VALUES (14, 'Alert', 'log alert');
INSERT INTO sysfacility (id, name, info) VALUES (15, 'Clock', 'clock daemon (note 2)');
INSERT INTO sysfacility (id, name, info) VALUES (16, 'Local0', 'local use 0 (local0)');
INSERT INTO sysfacility (id, name, info) VALUES (17, 'Local1', 'local use 1 (local1)');
INSERT INTO sysfacility (id, name, info) VALUES (18, 'Local2', 'local use 2 (local2)');
INSERT INTO sysfacility (id, name, info) VALUES (19, 'Local3', 'local use 3 (local3)');
INSERT INTO sysfacility (id, name, info) VALUES (20, 'Local4', 'local use 4 (local4)');
INSERT INTO sysfacility (id, name, info) VALUES (21, 'Local5', 'local use 5 (local5)');
INSERT INTO sysfacility (id, name, info) VALUES (22, 'Local6', 'local use 6 (local6)');
INSERT INTO sysfacility (id, name, info) VALUES (23, 'Local7', 'local use 7 (local7)');
