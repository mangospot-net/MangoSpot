<?php
if(isset($_POST['forgot'])){
	$postID = $Auth->decrypt($_POST['forgot'], Rahmad($_POST['token']));
	$userID = ($postID ? str_replace('BSK', '', $postID) : '-1');
	$checks = $Bsk->Show("users", "id", "id = '$userID' and md5(pswd) = '".Rahmad($_POST['key'])."' and identity = '$Identity[id]'");
	$forgot = ($_POST['npassword'] != $_POST['rpassword'] ? false :
		$Bsk->Update("users", array("pswd" => $Auth->encrypt(md5($_POST['rpassword']), 'BSK-RAHMAD')), "id = '$checks[id]'")
	);
	echo json_encode($forgot ?
		array("status" => true, "message" => "success", "data" => "Reset password successfully") : 
		array("status" => false, "message" => "error", "data" => "Reset password failed!"), true
	);
}
