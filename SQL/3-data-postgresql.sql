INSERT INTO identity VALUES (1, 0, '', 'MangoSpot', 'Networking', 'mangospot.net', 'mangospot.net', 'admin@mangospot.net', '085642311781', '085642311781', 'Dsn. Pelem Pulokulon 04/05', 57771, 'dist/img/favicon.png', 'dist/img/logo-dark.png', '[{"title":"","info":"","image":"dist\/img\/bg\/bg1.png"},{"title":"MangoSpot","info":"Network & Software House Development","image":"dist\/img\/bg\/bg2.jpg"}]', '-7.561122387074532', '110.86817470262451', '2019-10-10 00:00:00', '2020-10-10 00:00:00', '', 'false', 'true');
INSERT INTO config (id, name) VALUES (1, 'MangoSpot');
INSERT INTO level VALUES (1, 1, 0, 'Admin', '6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,22,23,24,25,26,27,28,29,30,31', NULL, 'true');
INSERT INTO users VALUES (1, 1, 1, NULL, '12345', 'admin', '+DykVVGTPrsYdKbxoWh+ype3L6pBuRaQeoIa35Vhz3M=', 'MangoSpot', 'admin@mangospot.net', '085642311781', 'Male', '', NULL, NULL, 'Dsn. Pelem Pulokulon 04/05', NULL, '', NULL, NULL, '', '2019-06-25 02:22:05', 'true');

INSERT INTO menu (slug, name, value, icon, number, status) VALUES (0, 'Master', '', 'tune', 1, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (0, 'Radius', '', 'wifi_tethering', 2, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (0, 'MikroTik', '', 'router', 3, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (0, 'Shop', '', 'shopping_cart', 4, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (0, 'Report', '', 'list_alt', 5, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (1, 'Identity', 'identity', NULL, 1, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (1, 'Config', 'config', NULL, 2, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (1, 'Level', 'level', NULL, 3, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (1, 'Client', 'client', NULL, 4, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (1, 'Themes', 'themes', NULL, 5, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (2, 'Radius', 'radius', 'ion-ios-radio', 1, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (2, 'Active', 'active', 'ion-logo-rss', 2, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (2, 'Profiles', 'profiles', 'ion-ios-paper', 3, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (2, 'Users', 'users', 'ion-ios-person-add', 4, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (2, 'Voucher', 'voucher', 'ion-ios-barcode', 5, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (2, 'Expired', 'expired', 'ion-ios-timer', 6, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (3, 'Router', 'router', 'ion-ios-easel', 1, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (3, 'Interface', 'interface', NULL, 2, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (3, 'Address', 'address', NULL, 3, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (3, 'Pool', 'pool', NULL, 4, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (3, 'Route', 'route', NULL, 5, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (3, 'DHCP', 'dhcp', NULL, 6, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (3, 'Online', 'online', 'ion-ios-cellular', 7, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (3, 'Wireless', 'wireless', 'ion-ios-wifi', 8, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (4, 'Packet', 'packet', NULL, 1, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (4, 'Shop', 'shop', NULL, 2, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (4, 'Transaction', 'transaction', NULL, 3, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (5, 'Logs', 'logs', 'ion-ios-information-circle-outline', 1, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (5, 'History', 'history', 'ion-md-time', 2, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (5, 'Usages', 'usages', NULL, 3, 'true');
INSERT INTO menu (slug, name, value, icon, number, status) VALUES (5, 'Payment', 'payment', 'ion-ios-cash', 4, 'true');

INSERT INTO themes (identity, users, name, type, content) VALUES (1, 1, 'Standar', 'radius', '<table class="voucher" style="display:inline-block; border: 2px solid black; margin:3px;">
<tbody>
<tr>
<td style="padding-left:3px;font-size: 14px;font-weight:bold;border-bottom: 1px black solid;">[profile] <small style="float:right;margin:2px">[data] [[no]]</small></td>
</tr>
<tr>
<td>
<table style=" text-align: center; width: 155px; margin:2px;">
<tbody>
<tr style="color: black; font-size: 11px;">
<td>
<table style="width:100%;">
<tbody><tr>
<td style="width: 50%">Username</td>
<td>Password</td>
</tr>
<tr style="color: black; font-size: 14px;">
<td style="text-align: center; border: 1px solid black; font-weight:bold;">[username]</td>
<td style="text-align: center; border: 1px solid black; font-weight:bold;">[password]</td>
</tr>
<tr>
<td colspan="2" style="text-align: center; border: 1px solid black; font-weight:bold;">[price]</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>');
INSERT INTO themes (identity, users, name, type, content) VALUES (1, 0, 'Standar', 'forgot', '<!DOCTYPE html>
<html>
	<head>
	<title>Reset Password | [web_data]</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<style type="text/css">
	body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
	table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
	img { -ms-interpolation-mode: bicubic; }
	img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
	table { border-collapse: collapse !important; }
	body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

	a[x-apple-data-detectors] {
		color: inherit !important;
		text-decoration: none !important;
		font-size: inherit !important;
		font-family: inherit !important;
		font-weight: inherit !important;
		line-height: inherit !important;
	}

	@media screen and (max-width: 480px) {
		.mobile-hide {
			display: none !important;
		}
		.mobile-center {
			text-align: center !important;
		}
	}

	div[style*="margin: 16px 0;"] { margin: 0 !important; }
	</style>
	<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
					<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
						<tr>
							<td align="center" valign="top" style="padding: 20px; background-color: #00a2e9" bgcolor="#00a2e9">
								<div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
									<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;" class="mobile-center">
												<h3 style="margin: 0; color: #ffffff;">Reset Password</h3>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
						<tr>
							<td align="center" style="padding: 20px; background-color: #ffffff;" bgcolor="#ffffff">
								<img src="[web_logo]" style="display: block; border: 0px; margin-bottom:8px;"/>
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
									<tr>
										<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px; border-bottom: 3px solid #eeeeee;">
											<img src="[user_image]" style="display: block; border: 0px; margin-bottom:8px;"/>
                                          	<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">
												Hello [user_name],
											</p>
											<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
												Kami baru saja mendapat sebuah permintaan untuk mengatur ulang password Anda. Silahkan klick tombol dibawah ini:
											</p>
										 </td>
									</tr>
									<tr>
										<td align="center" style="padding: 25px 0 5px 0;">
											<table border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td align="center" style="border-radius: 5px;" bgcolor="#ed8e20">
													  <a href="[link]" target="_blank" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #ed8e20; padding: 15px 30px; border: 1px solid #ed8e20; display: block;">Ganti Password</a>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 24px;">
											<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Informasi</p>
											<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">Jika Anda mengabaikan pesan ini, password Anda tidak akan diubah.</p>
										</td>
									</tr>
								</table>
							</td>
						  </tr>
						<tr>
							<td align="center" style="padding:25px 10px 25px 10px; background-color: #00a2e9; border-bottom: 20px solid #10b2f9;" bgcolor="#00a2e9">
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:500px; color:#ffffff">
									<tr>
										<td align="center">
											<strong>&#169; [year] [web_data]</strong>
											<br/>
											[web_address] | [web_phone]
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="background-color: #ffffff; padding:10px" bgcolor="#ffffff">
								Sent to [user_email] by <a href="[url]" target="_blank">[web_data]</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>');
INSERT INTO themes (identity, users, name, type, content) VALUES (1, 0, 'Standar', 'register', '<!DOCTYPE html>
<html>
	<head>
	<title>Registrasi Akun | [web_data]</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<style type="text/css">
	body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
	table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
	img { -ms-interpolation-mode: bicubic; }
	img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
	table { border-collapse: collapse !important; }
	body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
	a[x-apple-data-detectors] {
		color: inherit !important;
		text-decoration: none !important;
		font-size: inherit !important;
		font-family: inherit !important;
		font-weight: inherit !important;
		line-height: inherit !important;
	}
	@media screen and (max-width: 480px) {
		.mobile-hide {
			display: none !important;
		}
		.mobile-center {
			text-align: center !important;
		}
	}
	div[style*="margin: 16px 0;"] { margin: 0 !important; }
	</style>
	<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
					<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
						<tr>
							<td align="center" valign="top" style="padding: 20px; background-color: #00a2e9" bgcolor="#00a2e9">
								<div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
									<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif;" class="mobile-center">
												<h3 style="margin: 0; color: #ffffff;">Registrasi Akun</h3>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
						<tr>
							<td align="center" style="padding: 20px; background-color: #ffffff;" bgcolor="#ffffff">
								<img src="[web_logo]" style="display: block; border: 0px; margin-bottom:8px;"/>
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
									<tr>
										<td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-bottom: 15px; border-bottom: 3px solid #eeeeee;">
											<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">
												Hello [user_name],
											</p>
											<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
												Terima kasih telah bergabung bersama kami. Sebelum dapat menggunakan akun Anda, harap lakukan aktivasi dengan klick tombol dibawah ini:
											</p>
										 </td>
									</tr>
									<tr>
										<td align="center" style="padding: 25px 0 5px 0;">
											<table border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td align="center" style="border-radius: 5px;" bgcolor="#ed8e20">
													  <a href="[link]" target="_blank" style="font-size: 18px; font-family: Open Sans, Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 5px; background-color: #ed8e20; padding: 15px 30px; border: 1px solid #ed8e20; display: block;">Konfirmasi Akun</a>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 24px;">
											<p style="font-size: 18px; font-weight: 800; line-height: 24px; color: #333333;">Informasi</p>
											<p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">Jika Anda mengabaikan pesan ini, Akun Anda tidak dapat digunakan di aplikasi ini.</p>
										</td>
									</tr>
								</table>
							</td>
						  </tr>
						<tr>
							<td align="center" style="padding:25px 10px 25px 10px; background-color: #00a2e9; border-bottom: 20px solid #10b2f9;" bgcolor="#00a2e9">
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:500px; color:#ffffff">
									<tr>
										<td align="center">
											<strong>&#169; [year] [web_data]</strong>
											<br/>
											[web_address] | [web_phone]
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="background-color: #ffffff; padding:10px" bgcolor="#ffffff">
								Sent to [user_email] by <a href="[url]" target="_blank">[web_data]</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>');

INSERT INTO type (name, type, info, status) VALUES ('cisco', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('computone', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('livingston', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('max40xx', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('multitech', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('netserver', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('pathras', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('patton', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('portslave', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('tc', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('usrhiper', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('other', 'nas', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('Cleartext-Password', 'radcheck', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('User-Password', 'radcheck', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('Crypt-Password', 'radcheck', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('MD5-Password', 'radcheck', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('SHA1-Password', 'radcheck', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('CHAP-Password', 'radcheck', NULL, 'true');
INSERT INTO type (name, type, info, status) VALUES ('Access-Period', 'mode', 'A users can login for the specified period', 'true');
INSERT INTO type (name, type, info, status) VALUES ('Max-Daily-Session', 'mode', 'A user with User-Name test123 has online time limit of 3 hours per day', 'true');
INSERT INTO type (name, type, info, status) VALUES ('Max-All-Session', 'mode', 'A user can login as many times as needed but can be online for the specified time', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[web_data]', 'mail', 'Website Name', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[web_title]', 'mail', 'Website Title', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[web_email]', 'mail', 'Website Email', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[web_phone]', 'mail', 'Website Phone', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[web_fax]', 'mail', 'Website Fax', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[web_zip]', 'mail', 'Website ZIP Code', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[web_address]', 'mail', 'Website Address', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[web_icon]', 'mail', 'Website Url Icon', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[web_logo]', 'mail', 'Website Url Logo', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_name]', 'mail', 'Users Name', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_email]', 'mail', 'Users Email', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_phone]', 'mail', 'Users Phone', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_gender]', 'mail', 'Users Gender', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_religion]', 'mail', 'Users Religion', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_place]', 'mail', 'Users Place of Birth', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_birth]', 'mail', 'Users Date of Birth', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_zip]', 'mail', 'Users ZIP Code', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_address]', 'mail', 'Users Address', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[user_image]', 'mail', 'Users Url Image', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[code]', 'mail', 'Confirmation Code', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[link]', 'mail', 'Confirmation Link', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[url]', 'mail', 'Url Website', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[time]', 'mail', 'Current Time', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[date]', 'mail', 'Current Date', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[year]', 'mail', 'Current Year', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[no]', 'radius', 'Number Voucher', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[data]', 'radius', 'Website Identity', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[profile]', 'radius', 'Profile Voucher', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[username]', 'radius', 'Username Voucher', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[password]', 'radius', 'Password Voucher', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[price]', 'radius', 'Price Voucher', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[shared]', 'radius', 'Shared User', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[rate]', 'radius', 'Rate Limit', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[quota]', 'radius', 'Quota Limit', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[times]', 'radius', 'Online Every Time', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[daily]', 'radius', 'Online Per Day', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[url]', 'radius', 'Url Login', 'true');
INSERT INTO type (name, type, info, status) VALUES ('[qr_code]', 'radius', 'QR-Code', 'true');
INSERT INTO type (name, type, info, status) VALUES ('MikroTik', 'cron', ':global cronuser "username"
:global cronpass "password"
:global cronmode "http"
:global cronlink "192.168.1.254"
:global cronpost "/api/\?autoremove"
:global cronpath "autoremove.txt"
:log info "Cron Job: Autoremove Users MangoSpot"
:log info [ :put [/tool fetch address=$cronlink src-path=$cronpost mode=$cronmode user=$cronuser password=$cronpass dst-path=$cronpath] ]', 'true');
INSERT INTO type (name, type, info, status) VALUES ('Python', 'cron', 'import http.client
conn = http.client.HTTPSConnection("192.168.1.254")
headers = { "user": "your-username", "password": "your-password" }
conn.request("GET/POST", "/api/?autoremove", headers=headers)
res = conn.getresponse()
data = res.read()', 'true');
INSERT INTO type (name, type, info, status) VALUES ('jQuery', 'cron', '$.ajax({
	url: "http://192.168.1.254/api/",
	method: "GET",	// POST or GET
	headers: {
 		"user": "your_username",
		"password": "your_password",
		"Accept": "application/json"
    },
	data: "autoremove",
  	success: function(result){
 		alert(result);
    }
});', 'true');
INSERT INTO type (name, type, info, status) VALUES ('PHP', 'cron', '$curl = curl_init();
curl_setopt_array($curl, 
    array(
        CURLOPT_URL => "http://192.168.1.254/api/?autoremove",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET", //POST or GET
        CURLOPT_HTTPHEADER => array(
            "user: your_username"
            "password: your_password" 
        ),
    )
);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);', 'true');