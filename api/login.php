<?php
if(isset($_POST['login'])){
	$user	= Rahmad($_POST['username']);
	$pswd	= $Auth->encrypt(md5($_POST['password']), 'BSK-RAHMAD');
	$expi	= (isset($_POST['remember'])? 7 : 1);
	$login	= $Bsk->Tampil("users", "*", "(phone = '$user' or email = '$user' or username = '$user') and pswd = '$pswd' and status = 'true'");
	echo json_encode($login ? 
		array(
			"status"	=> true, 
			"message"	=> "success", 
			"color"		=> "green",
			"data"		=> array(
				"token" => md5($Identity['url']), 
				"api"	=> $Auth->encrypt($login['id'], $Host), 
				"key"	=> $Auth->encrypt(md5($login['pswd']), $Host), 
				"exp"	=> $expi
			)
		) : 
		array(
			"status"	=> false, 
			"message"	=> "error", 
			"color"		=> "red",	
			"data"		=> "Login failed!"
		), true
	);
}
if(isset($_POST['qr_code'])){
	$qr_code = $Auth->decrypt(Rahmad($_POST['qr_code']), md5($Identity['url']));
	$qr_xpld = explode(',', $qr_code);
	$qr_logn = $Bsk->Tampil("users", "*", "(phone = '$qr_xpld[1]' or email = '$qr_xpld[1]' or username = '$qr_xpld[1]') and id = '$qr_xpld[0]' and status = 'true'");
	echo json_encode($qr_logn ? 
		array(
			"status"	=> true, 
			"message"	=> "success",
			"color"		=> "green", 
			"data"		=> array(
				"token"	=> md5($Identity['url']), 
				"api"	=> $Auth->encrypt($lg['id'], md5($Identity['url'])), 
				"key"	=> $Auth->encrypt(md5($lg['pswd']), md5($Identity['url']))
			)
		) : 
		array(
			"status"	=> false, 
			"message"	=> "error", 
			"color"		=> "red",
			"data"		=> "Login failed!"
		), true
	);
}
if(isset($_POST['forgot'])){
	$email = Rahmad($_POST['email']);
	$forgot = $Bsk->Tampil("users", "id, email, name, pswd", "email = '$email' and identity = '$Identity[id]' and status = 'true'");
	$theme = file_get_contents('../email/themes/forgot.html');
	$theme = str_replace('[data]', $Identity['data'], $theme);
	$theme = str_replace('[address]', $Identity['address'], $theme);
	$theme = str_replace('[phone]', $Identity['phone'], $theme);
	$theme = str_replace('[logo]', $Http.$Identity['logo'], $theme);
	$theme = str_replace('[name]', $forgot['name'], $theme);
	$theme = str_replace('[link]', $Http.'/forgot?id='.$Auth->encrypt($forgot['id'].'BSK', md5($Identity['url'])).'&key='.md5($forgot['pswd']), $theme);
	$theme = str_replace('[email]', $forgot['email'], $theme);
	$theme = str_replace('[year]', date('Y'), $theme);
	$theme = str_replace('[url]', $Http, $theme);
	$sends = ($forgot ? SendMail($Config, array("data" => 'Reset Password | '.$Identity['data'], "email" => $forgot['email']), $theme) : false);
	echo json_encode($forgot ? 
		array(
			"status"	=> true, 
			"message"	=> "success", 
			"color"		=> "green", 
			"data"		=> "Has been delivered to your email"
		) : 
		array(
			"status"	=> false, 
			"message"	=> "error", 
			"color"		=> "red", 
			"data"		=> "Forgot failed!"
		), true
	);
}
