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
   FROM income