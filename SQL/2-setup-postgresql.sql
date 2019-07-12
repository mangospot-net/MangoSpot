CREATE USER radius WITH PASSWORD 'radius1234';
GRANT SELECT ON radcheck TO radius;
GRANT SELECT ON radreply TO radius;
GRANT SELECT ON radgroupcheck TO radius;
GRANT SELECT ON radgroupreply TO radius;
GRANT SELECT ON radusergroup TO radius;
GRANT SELECT ON nas TO radius;

GRANT SELECT, INSERT, UPDATE on radacct TO radius;
GRANT SELECT, INSERT, UPDATE on radpostauth TO radius;

GRANT USAGE, SELECT ON SEQUENCE nas_id_seq TO radius;
GRANT USAGE, SELECT ON SEQUENCE radacct_radacctid_seq TO radius;
GRANT USAGE, SELECT ON SEQUENCE radcheck_id_seq TO radius;
GRANT USAGE, SELECT ON SEQUENCE radgroupcheck_id_seq TO radius;
GRANT USAGE, SELECT ON SEQUENCE radgroupreply_id_seq TO radius;
GRANT USAGE, SELECT ON SEQUENCE radpostauth_id_seq TO radius;
GRANT USAGE, SELECT ON SEQUENCE radreply_id_seq TO radius;
GRANT USAGE, SELECT ON SEQUENCE radusergroup_id_seq TO radius;
