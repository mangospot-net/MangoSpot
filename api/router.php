<?php
$rad = ($Menu['data'] ? "and id in($Menu[data])" : "and users = '$Menu[id]'");
if(isset($_GET['data'])){
    $nas = $Bsk->Table("nas", 
        "id, description, nasname, username, port", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true'", 
        array("description", "nasname", "username", "port", "id")
	);
	echo json_encode($nas, true);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $query_detail = $Bsk->Show("nas", "*", "id = '$id_detail' and identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true'");
    $array_detail = ($query_detail['password'] ? 
        array_replace($query_detail, array("password" => $Auth->decrypt($query_detail['password'], 'BSK-RAHMAD'))) :
        $query_detail
    );
    echo json_encode($query_detail ? 
		array("status" => true, "message" => "success", "data" => $array_detail) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['username'])){
    $id_post = Rahmad($_POST['id']);
    unset($_POST['id']);
    $check_post = $Bsk->Show("nas", "id", "id = '$id_post' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $data_post = array_replace(
        $_POST, 
        array("password" => $Auth->encrypt($_POST['password'], 'BSK-RAHMAD'))
    );
    $query_post = $Bsk->Update("nas", $data_post, "id = '$check_post[id]' and users = '$Menu[id]'");
    echo json_encode($query_post ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data successfully.") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['test'])){
    $test = $Bsk->Show(
        "nas", "nasname", 
        "id = '".Rahmad($_POST['test'])."' and identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true' "
    );
    if ($Router->connect($test['nasname'].":".$_POST['port'], $_POST['user'], $_POST['pswd'])) {
        $testing = true;
    } else {
        $testing = false;
    }
    $Router->disconnect();
    echo json_encode($test ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => $testing) : 
		array("status" => false, "message" => "error", "color" => "red", "data" => $testing), true
    );
}
if(isset($_POST['reboot'])){
    $getIde = Rahmad($_POST['reboot']);
    $getNas = $Bsk->Show(
        "nas", "nasname, username, password, port", 
        "id = '$getIde' and identity = '$Menu[identity]' and status = 'true' ".$rad
    );
    if ($Router->connect($getNas['nasname'].":".$getNas['port'], $getNas['username'], $getNas['password'])) {
        $return = $Router->comm("/system/reboot");
    } else {
        $return = false;
    }
    $Router->disconnect();
    echo json_encode($return ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Reboot router successfully.") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Reboot router failed!"), true
    );
}