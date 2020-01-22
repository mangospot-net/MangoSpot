<?php
if(isset($_GET['data'])){
    $details = array();
    $levels = $Bsk->Table(
        "levels", "id, name, menu as value, radius as data, status", 
        "identity = '$Menu[identity]' and slug = '$Menu[level]'", 
        array("name", "value", "data", "status", "id")
    );
    echo json_encode($levels, true);
}
if(isset($_GET['type'])){
    $type = array();
    $query_type = $Bsk->Select("menu", "id, name", "id in ($Menu[value]) and slug != 0 and status = 'true'", "name asc");
    foreach ($query_type as $show_type) {
        $type[] = $show_type;
    }
    echo json_encode($type ? 
		array("status" => true, "message" => "success", "data" => $type) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['radius'])){
    $radius = array();
    $nasnam = $Bsk->Select("nas", "id, description as name", "identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true'");
    foreach ($nasnam as $rad) {
        $radius[] = $rad;
    }
    echo json_encode($radius ? 
		array("status" => true, "message" => "success", "data" => $radius) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['detail'])){
    $id_detail     = Rahmad($_GET['detail']);
    $query_detail  = $Bsk->Show("level", "id, name, value, data, status", "id = '$id_detail' and identity = '$Menu[identity]' and slug = '$Menu[level]'");
    $query_replace = array_replace(
        $query_detail, 
        array(
            "value" => ($query_detail['value'] ? explode(',', $query_detail['value']) : array()),
            "data"  => ($query_detail['data'] ? explode(',', $query_detail['data']) : array())
        )
    );
    echo json_encode($query_detail ? 
		array("status" => true, "message" => "success", "data" => $query_replace) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['name'])){
    $id_post = Rahmad($_POST['id']);
    unset($_POST['id']);
    $check_post = $Bsk->Show("level", "id", "id = '$id_post' and identity = '$Menu[identity]' and slug = '$Menu[level]' ");
    $data_post = array_replace(
        $_POST, 
        array(
            "value"  => (isset($_POST['value']) ? implode(',', $_POST['value']) : NULL), 
            "data"   => (isset($_POST['data']) ? implode(',', $_POST['data']) : NULL),
            "status" => (isset($_POST['status']) ? 'true' : 'false')
        )
    );
    $query_post = ($check_post ? 
        $Bsk->Change("level", $data_post, "id = '$check_post[id]'") : 
        $Bsk->Insert("level", array_merge($data_post, array("identity" => $Menu['identity'], "slug" => $Menu['level'])))
    );
    echo json_encode($query_post ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['active'])){
    $id_active = Rahmad($_POST['active']);
    $check_active = $Bsk->Show("level", "id, status", "id = '$id_active' and identity = '$Menu[identity]' and slug = '$Menu[level]'");
    $query_active = $Bsk->Update(
        "level", array("status" => ($check_active['status'] == 'true' ? 'false' : 'true')),
        "id = '$check_active[id]' "
    );
    echo json_encode($query_active ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Active data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Active data failed!"), true
	);
}
if(isset($_POST['delete'])){
    $query_delete = $Bsk->Delete("level", 
        array(
            "id"        => Rahmad($_POST['delete']),
            "identity"  => $Menu['identity'],
            "slug"      => $Menu['level']
        )
    );
    echo json_encode($query_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}
