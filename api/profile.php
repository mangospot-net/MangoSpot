<?php
if(isset($_GET['data'])){
	$data = $Bsk->Tampil("users", "*", "id = '$Api' and md5(pswd) = '$Key' and status = 'true'");
	$user = ($data ? array_merge($data, array("users" => Nama($data['name'], 2))) : array());
	echo json_encode($data ? 
		array("status" => true, "message" => "success", "data" => $user) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['name'])){
	$post = $Bsk->Ubah("users", $_POST, "id = '$Api' and md5(pswd) = '$Key' and status = 'true' ");
	echo json_encode($post ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['password'])){
	$check = $Bsk->Tampil("users", "id", "id = '$Api' and pswd = '".$Auth->encrypt(md5($_POST['cpassword']), 'BSK-RAHMAD')."' and status = 'true'");
	$password = $Bsk->Ubah("users", array("pswd" => $Auth->encrypt(md5($_POST['rpassword']), 'BSK-RAHMAD')), "id = '$check[id]' and status = 'true'");
	echo json_encode($password ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_FILES['images']['tmp_name'])){
	$folder = "../dist/img/users/";
	$basename = $folder . basename($_FILES["images"]["name"]);
	$format = array("jpg");
	$upload = false;
	$extention = pathinfo($basename, PATHINFO_EXTENSION);
	$images = $Bsk->Tampil("users", "id, image", "id = '$Api' and md5(pswd) = '$Key' and status = 'true'");
	if(in_array($extention, $format)){
		$file_name = ($images['image'] ? str_replace('dist/img/users/', '', $images['image']) : date('idmsYhi').".".$extention);
		if(strlen($file_name)>0){
			$image = new SimpleImage();
			$image->load($_FILES['images']['tmp_name']);
			$image->resize(200,200);
			$image->save($folder.$file_name);
		}
		$upload = $Bsk->Ubah("users", array("image"	=> "dist/img/users/".$file_name), "id = '$images[id]' and status = 'true' ");
	}
	echo json_encode($upload ?
		array("status" => true, "message" => "success", "color" => "green", "data" => "Upload data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Upload data failed!"), true
	);
}
