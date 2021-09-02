# MangoSpot Radius Server v1.0.1
1. Install Webserver Apache, PHP, PostgreSQL or MariaDB (MySQL)
2. Install FreeRadius
3. Enable sqlcounter (accessperiod & quotalimit) [MySQL](https://github.com/mangospot-net/MangoSpot/tree/master/SQL/sqlcounter-mysql) / [PostgreSQL](https://github.com/mangospot-net/MangoSpot/tree/master/SQL/sqlcounter-postgresql)
4. Upload to Webserver
5. Import Schema.sql & Data.sql [SQL](https://github.com/mangospot-net/MangoSpot/tree/master/SQL)

## Install 
```
- install git or composer
```
```
cd /var/www/html
git clone https://github.com/mangospot-net/MangoSpot.git
```
or
```
cd /var/www/html
wget https://github.com/mangospot-net/MangoSpot/archive/master.zip
unzip *.zip
```
or
```
cd /var/www/html
composer require mangospot/mangospot
```
### Note:
```
Enable: htaccess, php-mcrypt, php-ssh2, php-gd, php-curl, php-xml, php-xsl, php-zip
```
Edit Username & Password ssh on [api/config.php](https://github.com/mangospot-net/MangoSpot/blob/master/api/config.php)

### Demo & Tutorial
[Video Tutorial](https://www.youtube.com/watch?v=Df3jDXt7n3Y&list=PLBwbrrj11losuLh2W9t36YQmB9h0NG4Fc)
```
- Username: admin
- Password: admin
```
[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8CRUEDLPLCFSQ)
```
Paypal: mangospot.net@gmail.com
Phone: +6285642311781
```
