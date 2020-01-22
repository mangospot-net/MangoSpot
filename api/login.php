<?php
if(isset($_POST['login'])){
	$user	= Rahmad($_POST['username']);
	$pswd	= $Auth->encrypt(md5($_POST['password']), 'BSK-RAHMAD');
	$expi	= (isset($_POST['remember'])? 7 : 1);
	$login	= $Bsk->Show("users", "*", "(phone = '$user' or email = '$user' or username = '$user') and pswd = '$pswd' and status = 'true'");
	echo json_encode($login ? 
		array(
			"status"	=> true, 
			"message"	=> "success", 
			"color"		=> "green",
			"data"		=> array(
				"token" => md5($Identity['url']), 
				"api"	=> $Auth->encrypt($login['id'], $Host), 
				"key"	=> $Auth->encrypt(md5($login['pswd']), $Host),
				"auth"	=> md5($login['id']),
				"signal"=> $Config['on_api'],
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
	$qr_logn = $Bsk->Show("users", "*", "(phone = '$qr_xpld[1]' or email = '$qr_xpld[1]' or username = '$qr_xpld[1]') and id = '$qr_xpld[0]' and status = 'true'");
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
	$themes = $Bsk->Show("themes", "content", "identity = '$Identity[id]' and type = 'forgot'");
	$usrMail = $Bsk->Show(
        "users", "id, pswd, name as user_name, 
        email as user_email, phone as user_phone, 
        gender as user_gender, religion as user_religion, 
        place as user_place, birth as user_birth, zip as user_zip, address user_address, 
        concat('$Http', '/', (CASE WHEN image != '' THEN image ELSE 'dist/img/users/no_foto.jpg' END)) as user_image", 
        "identity = '$Identity[id]' and email = '$email' and status = 'true'"
    );
    $webMail = $Bsk->Show(
        "identity", "data as web_data, title as web_title, 
        email as web_email, phone as web_phone, fax as web_fax, zip as web_zip, 
        address as web_address, concat('$Http', icon) as web_icon, concat('$Http', logo) as web_logo", 
        "id = '$Identity[id]'"
    );
	$getLink = $Http.'/forgot.html?id='.$Auth->encrypt($usrMail['id'].'BSK', md5(date('Yhimsd'))).'&key='.md5($usrMail['pswd']).'&token='.md5(date('Yhimsd'));
	$theme = HTMLReplace($themes['content'], 
		array_merge(
			$webMail,
			$usrMail,
			array(
				"code"	=> '',
				"link"	=> $getLink,
				"time"  => date('H:i:s'),
				"date"  => date('Y-m-d'),
				"year"  => date('Y'),
				"url"   => $Http,
			)
		)
    );
	$sends = ($usrMail ? 
		$Mail->sendMail($Config, array(
			'email'     => $usrMail['user_email'], 
			'subject'   => 'Reset Password | '.$Identity['data'], 
			'message'   => $theme)
		) : false
	);
	echo json_encode($usrMail ? 
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
