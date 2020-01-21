<?php
if(isset($_GET['data'])){
    $nas = $Bsk->Table(
        "payment a left join users b on a.client = b.id left join packet c on a.packet = c.id", 
        "a.id, a.client, a.packet, b.name, c.groupname, a.price, a.total, a.info, a.date, a.status", 
        "a.identity = '$Menu[identity]' and a.users = '$Menu[id]'", 
        array("b.name", "c.groupname", "a.total", "a.price", "a.info", "a.date", "a.id")
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
if(isset($_GET['packet'])){
    $group = array();
    $usersid = Rahmad($_GET['packet']);
    $query_group = $Bsk->Select(
        "packet", 
        "id, groupname as name, price as value", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' and client = '$usersid' and status = 'true' ", "id asc"
    );
    foreach ($query_group as $show_group) {
        $group[] = $show_group;
    }
    echo json_encode($group ? 
		array("status" => true, "message" => "success", "data" => $group) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['price'])){
    $idprc = Rahmad($_GET['price']);
    $price = $Bsk->Show("packet", "price", "identity = '$Menu[identity]' and users = '$Menu[id]' and id = '$idprc' and status = 'true'");
    $array = array("total" => 1, "price" => $price['price'], "count" => $price['price']);
    echo json_encode($price ? 
		array("status" => true, "message" => "success", "data" => $array) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $query_detail = $Bsk->Show("payment", "*", "id = '$id_detail' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $merge_detail = array_merge($query_detail, array("value" => ($query_detail['price'] / $query_detail['total'])));
    echo json_encode($query_detail ? 
		array("status" => true, "message" => "success", "data" => $merge_detail) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['packet'])){
    $id_post = Rahmad($_POST['id']);
    unset($_POST['id']);
    $check_post = $Bsk->Show("payment", "id", "id = '$id_post' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $query_post = ($check_post ? 
        $Bsk->Update("payment", $_POST, "id = '$check_post[id]'") : 
        $Bsk->Insert("payment", 
            array_merge(
                $_POST, 
                array(
                    "identity"  => $Menu['identity'], 
                    "users"     => $Menu['id'],
                    "date"      => date('Y-m-d H:i:s')
                )
            )
        )
    );
    echo json_encode($query_post ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['active'])){
    $id_active = Rahmad($_POST['active']);
    $check_active = $Bsk->Show("payment", "id, packet, client, total, status", "id = '$id_active' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $check_packet = $Bsk->Show("packet", "id, groupname, price, total, voucher", "id = '$check_active[packet]' and identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true'");
    $array_active = ($check_active['status'] == 'true' ? 'false' : 'true');
    $query_active = $Bsk->Update(
        "payment", array("status" => $array_active),
        "id = '$check_active[id]' and identity = '$Menu[identity]' and users = '$Menu[id]'"
    );
    $voucher = ($check_active['status'] == 'true' ? 
        ($check_packet['voucher'] - ($check_packet['total'] * $check_active['total'])) : 
        ($check_packet['voucher'] + ($check_packet['total'] * $check_active['total']))
    );
    if($query_active){
        $Bsk->Update("payment", array("approve" => date('Y-m-d H:i:s')), "id = '$check_active[id]'");
        $Bsk->Update("packet", array("voucher" => $voucher), "id = '$check_packet[id]' ");
    }
    if($array_active == 'true' && $query_active){
        $setBody = "Packet ".$check_packet['groupname']."/".$check_packet['price'].", ".$check_active['total']." items has been approved.";
        $Signal->notification 
        -> setBody($setBody) 
        -> setTitle($Users['name'])
        -> setIcon($Users['image'] ? $Http."/".$Users['image'] : $Http."/dist/img/users/no_foto.jpg")
        -> setSmall('shop')
        -> addTag('key', md5($check_active['client']))
        -> send();
    }
    echo json_encode($query_active ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => $array_active) : 
		array("status" => false, "message" => "error", "color" => "red", "data" => false), true
	);
}
if(isset($_POST['delete'])){
    $id_delete = Rahmad($_POST['delete']);
    $check_delete = $Bsk->Show("payment", "packet, client, total", "id = '$id_delete' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $shows_packet = $Bsk->Show("packet", "groupname, price", "id = '$check_delete[packet]' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $query_delete = $Bsk->Delete("payment", array("id" => $id_delete, "users" => $Menu['id']));
    if($query_delete){
        $setBodi = "Packet ".$shows_packet['groupname']."/".$shows_packet['price'].", ".$check_delete['total']." items has been rejected!";
        $Signal->notification 
        -> setBody($setBodi) 
        -> setTitle($Users['name'])
        -> setIcon($Users['image'] ? $Http."/".$Users['image'] : $Http."/dist/img/users/no_foto.jpg")
        -> setSmall('shop')
        -> addTag('key', md5($check_delete['client']))
        -> send();
    }
    echo json_encode($query_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}
