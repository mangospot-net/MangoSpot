<?php
if(isset($_GET['type'])){
    $type = array();
    $query_type = $Bsk->Select("themes", "id, name", "identity = '$Menu[identity]' and (users = '$Menu[id]' or users = 0) and type = 'radius'", "id desc");
    foreach ($query_type as $show_type) {
        $type[] = $show_type;
    }
    echo json_encode($type ? 
		  array("status" => true, "message" => "success", "data" => $type) : 
		  array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['docs'])){
    $document = array();
    $docs = $Bsk->Select("type", "name, info", "type = 'radius' and status = 'true'", "id asc");
    foreach ($docs as $vals) {
        $document[] = $vals;
    }
    echo json_encode($document ? 
        array("status" => true, "message" => "success", "data" => $document) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $query_detail = $Bsk->Show("themes", "*", "id = '$id_detail' and identity = '$Menu[identity]' and (users = '$Menu[id]' or users = 0)");
    echo json_encode($query_detail ? 
		  array("status" => true, "message" => "success", "data" => $query_detail) : 
		  array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['name'])){
    $id_post = Rahmad($_POST['id']);
    unset($_POST['id']);
    $check_post = $Bsk->Show("themes", "id", "id = '$id_post' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $query_post = ($check_post ? 
        $Bsk->Change("themes", $_POST, "id = '$check_post[id]' ") : 
        $Bsk->Tambah("themes", array_merge($_POST, array("identity" => $Menu['identity'], "users" => $Menu['id'])))
    );
    echo json_encode($query_post ? 
		  array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		  array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['delete'])){
    $query_delete = $Bsk->Delete("themes", array("id" => Rahmad($_POST['delete']), "identity" => $Menu['identity'], "users" => $Menu['id']));
    echo json_encode($query_delete ? 
		  array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		  array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}