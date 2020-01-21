<?php
if(isset($_GET['data'])){
    $client = $Bsk->Table(
        "users a inner join level b on a.level = b.id", 
        "a.id, a.username, a.name, b.name as level, a.phone, a.status", 
        "a.identity = '$Menu[identity]' and b.slug = '$Menu[level]'", 
        array("a.username", "a.name", "b.name", "a.phone", "a.id")
	);
	echo json_encode($client, true);
}
if(isset($_GET['level'])){
    $type = array();
    $query_type = $Bsk->Select("level", "id, name", "slug = '$Menu[level]' and status = 'true'");
    foreach ($query_type as $show_type) {
        $type[] = $show_type;
    }
    echo json_encode($type ? 
		array("status" => true, "message" => "success", "data" => $type) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $query_detail = $Bsk->Show("users", "id, level, username, name, email, phone, status", "id = '$id_detail' and identity = '$Menu[identity]'");
    echo json_encode($query_detail ? 
		array("status" => true, "message" => "success", "data" => $query_detail) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['username'])){
    $id_post = Rahmad($_POST['id']);
    unset($_POST['id']);
    $check_post = $Bsk->Show("users", "id", "id = '$id_post' and identity = '$Menu[identity]' ");
    $check_user = $Bsk->Show("users", "id", "(username = '".Rahmad($_POST['username'])."' or email = '".Rahmad($_POST['email'])."') and identity = '$Menu[identity]' ");
    $data_post = array_replace(
        $_POST, 
        array(
            "status" => (isset($_POST['status']) ? 'true' : 'false'),
            "pswd"   => $Auth->encrypt(md5($_POST['pswd']), 'BSK-RAHMAD')
        )
    );
    $query_post = ($check_post ? 
        $Bsk->Update("users", $data_post, "id = '$check_post[id]'") : ($check_user ? false :
        $Bsk->Insert("users", array_merge($data_post, array("identity" => $Menu['identity'], "date" => date('Y-m-d H:i:s')))))
    );
    echo json_encode($query_post ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['active'])){
    $id_active = Rahmad($_POST['active']);
    $check_active = $Bsk->Show("users", "id, status", "id = '$id_active' and identity = '$Menu[identity]'");
    $query_active = $Bsk->Update(
        "users", 
        array("status" => ($check_active['status'] == 'true' ? 'false' : 'true')),
        "id = '$check_active[id]' "
    );
    echo json_encode($query_active ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Active data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Active data failed!"), true
	);
}
if(isset($_POST['delete'])){
    $query_delete = $Bsk->Delete("users", array("id" => Rahmad($_POST['delete'])));
    echo json_encode($query_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}
