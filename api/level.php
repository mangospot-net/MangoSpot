<?php
if(isset($_GET['data'])){
    $details = array();
    $levels = DataTable("level", "*", "identity = '$Menu[identity]' and slug = '$Menu[level]'", 
        array("name", "value", "status", "id")
    );
    foreach($levels['data'] as $list){
		$vim[$list['id']] = array();
		foreach(explode(',', $list['value']) as $mnv){
			$mno = $Bsk->Tampil("menu","name","id = '$mnv' and status = 'true'");
			$vim[$list['id']][] = $mno['name'];
		}
		$details[] = array_replace($list, array("value" => implode(', ', $vim[$list['id']])));
	}
    echo json_encode(array_replace($levels, array("data" => $details)), true);
}
if(isset($_GET['type'])){
    $type = array();
    $query_type = $Bsk->View("menu", "id, name", "id in ($Menu[value]) and slug != 0 and status = 'true'", "name asc");
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
    $query_detail = $Bsk->Tampil("level", "id, name, value, status", "id = '$id_detail' and identity = '$Menu[identity]' and slug = '$Menu[id]'");
    echo json_encode($query_detail ? 
		array("status" => true, "message" => "success", "data" => $query_detail) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['name'])){
    $id_post = Rahmad($_POST['id']);
    unset($_POST['id']);
    $check_post = $Bsk->Tampil("level", "id", "id = '$id_post' and identity = '$Menu[identity]' and slug = '$Menu[level]' ");
    $data_post = array_replace(
        $_POST, 
        array("value" => implode(',', $_POST['value']), "status" => (isset($_POST['status']) ? 'true' : 'false'))
    );
    $query_post = ($check_post ? 
        $Bsk->Ubah("level", $data_post, "id = '$check_post[id]'") : 
        $Bsk->Tambah("level", array_merge($data_post, array("identity" => $Menu['identity'], "slug" => $Menu['level'])))
    );
    echo json_encode($query_post ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['active'])){
    $id_active = Rahmad($_POST['active']);
    $check_active = $Bsk->Tampil("level", "id, status", "id = '$id_active' and identity = '$Menu[identity]' and slug = '$Menu[level]'");
    $query_active = $Bsk->Ubah(
        "level", array("status" => ($check_active['status'] == 'true' ? 'false' : 'true')),
        "id = '$check_active[id]' "
    );
    echo json_encode($query_active ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Active data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Active data failed!"), true
	);
}
if(isset($_POST['delete'])){
    $query_delete = $Bsk->Hapus("level", 
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
