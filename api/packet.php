<?php
if(isset($_GET['data'])){
    $nas = $Bsk->Table(
        "packet a left join users b on a.client = b.id", 
        "a.id, b.name as client, a.groupname, a.price, a.total, a.voucher, a.defaults, a.status", 
        "a.identity = '$Menu[identity]' and a.users = '$Menu[id]'", 
        array("b.name", "a.groupname", "a.price", "a.total", "a.voucher", "a.id")
	);
	echo json_encode($nas, true);
}
if(isset($_GET['client'])){
    $client = array();
    $query_client = $Bsk->Select(
        "users a inner join level b on a.level = b.id", 
        "a.id, a.name", 
        "a.identity = '$Menu[identity]' and (b.slug = '$Menu[level]' or b.id = '$Menu[level]') and a.status = 'true'"
    );
    foreach ($query_client as $show_client) {
        $client[] = $show_client;
    }
    echo json_encode($client ? 
		array("status" => true, "message" => "success", "data" => $client) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['groupname'])){
    $group = array();
    $query_group = $Bsk->Select(
        "radgroupcheck a left join radgroupreply b on a.groupname = b.groupname", 
        "a.groupname as id, a.groupname as name", 
        "a.identity = '$Menu[identity]' GROUP BY a.groupname", "a.groupname asc"
    );
    foreach ($query_group as $show_group) {
        $group[] = $show_group;
    }
    echo json_encode($group ? 
		array("status" => true, "message" => "success", "data" => $group) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $query_detail = $Bsk->Show("packet", "*", "id = '$id_detail' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    echo json_encode($query_detail ? 
		array("status" => true, "message" => "success", "data" => $query_detail) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['client'])){
    $id_post = Rahmad($_POST['id']);
    unset($_POST['id']);
    $check_nast = $Bsk->Show("packet", "id", "client = '".Rahmad($_POST['client'])."' and groupname = '".Rahmad($_POST['groupname'])."' ");
    $check_post = $Bsk->Show("packet", "id", "id = '$id_post' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $data_post = array_replace(
        $_POST, 
        array("status" => (isset($_POST['status']) ? 'true' : 'false'))
    );
    $query_post = ($check_post ? 
        $Bsk->Update("packet", $data_post, "id = '$check_post[id]'") : ($check_nast ? false :
        $Bsk->Insert("packet", array_merge($data_post, array("identity" => $Menu['identity'], "users" => $Menu['id']))))
    );
    echo json_encode($query_post ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['active'])){
    $id_active = Rahmad($_POST['active']);
    $check_active = $Bsk->Show("packet", "id, status", "id = '$id_active' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $array_active = ($check_active['status'] == 'true' ? 'false' : 'true');
    $query_active = $Bsk->Update(
        "packet", array("status" => $array_active),
        "id = '$check_active[id]' and identity = '$Menu[identity]' and users = '$Menu[id]'"
    );
    echo json_encode($query_active ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => $array_active) : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Active data failed!"), true
	);
}
if(isset($_POST['default'])){
    $id_default = Rahmad($_POST['default']);
    $check_default = $Bsk->Show("packet", "id, client", "id = '$id_default' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $query_default = $Bsk->Update(
        "packet", array("defaults" => "true"),
        "id = '$id_default' and identity = '$Menu[identity]' and users = '$Menu[id]'"
    );
    $Bsk->Update(
        "packet", array("defaults" => "false"),
        "id != '$id_default' and identity = '$Menu[identity]' and users = '$Menu[id]' and client = '$check_default[client]'"
    );
    echo json_encode($query_default ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Default data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Default data failed!"), true
	);
}
if(isset($_POST['delete'])){
    $query_delete = $Bsk->Delete("packet", array("id" => Rahmad($_POST['delete']), "users" => $Menu['id']));
    echo json_encode($query_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}
