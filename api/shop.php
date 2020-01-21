<?php
$search = $Bsk->Show("users a inner join level b on a.level = b.slug", "a.id", "a.identity = '$Menu[identity]' and b.id = '$Menu[level]'");
$admins = ($search['id'] ? $search['id'] : $Menu['id']);
if(isset($_GET['data'])){
    $nas = $Bsk->Table(
        "payment a left join packet b on a.packet = b.id", 
        "a.id, b.groupname as packet, a.price, a.total, a.date, a.status", 
        "a.identity = '$Menu[identity]' and a.users = '$admins' and a.client = '$Menu[id]'", 
        array("b.groupname", "a.total", "a.price", "a.date", "a.id")
	);
	echo json_encode($nas, true);
}
if(isset($_GET['packet'])){
    $group = array();
    $query_group = $Bsk->Select(
        "packet", 
        "id, groupname as name, price as value", 
        "identity = '$Menu[identity]' and users = '$admins' and client = '$Menu[id]' and status = 'true' ", "id asc"
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
    $price = $Bsk->Show("packet", "price", "identity = '$Menu[identity]' and users = '$admins' and client = '$Menu[id]' and id = '$idprc' and status = 'true'");
    $array = array("total" => 1, "price" => $price['price'], "count" => $price['price']);
    echo json_encode($price ? 
		array("status" => true, "message" => "success", "data" => $array) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $query_detail = $Bsk->Show("payment", "*", "id = '$id_detail' and identity = '$Menu[identity]' and users = '$admins' and client = '$Menu[id]'");
    $merge_detail = array_merge($query_detail, array("value" => ($query_detail['price'] / $query_detail['total'])));
    echo json_encode($query_detail ? 
		array("status" => true, "message" => "success", "data" => $merge_detail) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['packet'])){
    $id_post = Rahmad($_POST['id']);
    unset($_POST['id']);
    $check_post = $Bsk->Show("payment", "id", "id = '$id_post' and identity = '$Menu[identity]' and users = '$admins' and client = '$Menu[id]'");
    $query_post = ($check_post ? 
        $Bsk->Update("payment", $_POST, "id = '$check_post[id]' and (status = 'false' or status is null)") : 
        $Bsk->Insert("payment", 
            array_merge(
                $_POST, 
                array(
                    "identity"  => $Menu['identity'], 
                    "client"    => $Menu['id'],
                    "users"     => $admins,
                    "date"      => date('Y-m-d H:i:s')
                )
            )
        )
    );
    $packets = $Bsk->Show("packet", "groupname, price", "client = '$Menu[id]' and id = '".$_POST['packet']."'");
    $setBody = "Order Packet ".$packets['groupname']."/".$packets['price']." ".$_POST['total']." Items ".$_POST['info'];
    if(!$check_post && $query_post){
        $Signal->notification 
        -> setBody($setBody) 
        -> setTitle($Users['name'])
        -> setIcon($Users['image'] ? $Http."/".$Users['image'] : $Http."/dist/img/users/no_foto.jpg")
        -> setSmall('shop')
        -> addTag('key', md5($admins))
        -> send();
    }
    echo json_encode($query_post ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['delete'])){
    $query_delete = $Bsk->Delete("payment", array("id" => Rahmad($_POST['delete']), "client" => $Menu['id'], "users" => $admins));
    echo json_encode($query_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}
