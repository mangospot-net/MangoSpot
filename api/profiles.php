<?php
if(isset($_GET['data'])){
    $table = $Bsk->Table(
        "profiles",  "groupname as id, groupname, concat(shared, ' ', ppp) as shared, rate, price, discount, quota, period as expired, description", 
        "identity = '$Menu[identity]' and users = '$Menu[id]'", 
        array("groupname", "concat(shared, ' ', ppp)", "rate", "price", "discount", "id")
	);
	echo json_encode($table, true);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $show_detail = $Bsk->Show("profiles", "*", "groupname = '$id_detail' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $data_quota  = ($show_detail['quota'] ? preg_split('#(?<=\d)(?=[a-z])#i', $show_detail['quota']) : array('', 'B'));
    $replace_data = array_replace(
        $show_detail, 
        array(
            "quota_numb"=> $data_quota[0],
            "quota_code"=> $data_quota[1]
        )
    );
    echo json_encode($show_detail ? 
		array("status" => true, "message" => "success", "data" => $replace_data) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['groupname'])){
    $post_profile= Rahmad($_POST['id']);
    $groupname   = Rahmad($_POST['groupname']);
    $count_check = count($_POST['radgroupcheck']);
    $count_reply = count($_POST['radgroupreply']);
    for($i=0; $i<$count_check; $i++){
        $attribute = $_POST['attribute'][$i];
        $check_check = $Bsk->Show(
            "radgroupcheck", "groupname", 
            "groupname = '$post_profile' and attribute = '$attribute' and identity = '$Menu[identity]'"
        );
        if(!empty($_POST['radgroupcheck'][$i])){
            $query_check = array(
                "identity"   => $Menu['identity'],
                "users"      => $Menu['id'],
                "groupname"  => $groupname, 
                "attribute"  => $_POST['attribute'][$i],
                "op"         => ":=",
                "value"      => ($_POST['attribute'][$i] == 'Simultaneous-Use' ? $_POST['radgroupcheck'][$i] : ($_POST['attribute'][$i] == 'Max-Data' ? ByteConvert($_POST['radgroupcheck'][$i]) : DateTime($_POST['radgroupcheck'][$i]))),
                "description"=> $_POST['description']
            );
            if($check_check){
                $Bsk->Update("radgroupcheck", $query_check, "groupname = '$check_check[groupname]' and attribute = '$attribute' and identity = '$Menu[identity]' and users = '$Menu[id]'");
            } else {
                $Bsk->Insert("radgroupcheck", $query_check);
            }
        } else {
            $Bsk->Delete("radgroupcheck", array(
                "groupname" => $check_check['groupname'],
                "attribute" => $attribute,
                "identity"  => $Menu['identity'],
                "users"     => $Menu['id']
            ));
        }
    }
    for($e=0; $e<$count_reply; $e++){
        $attribut = $_POST['attribut'][$e];
        $check_reply = $Bsk->Show(
            "radgroupreply", "groupname", 
            "groupname = '$post_profile' and attribute = '$attribut' and identity = '$Menu[identity]' and users = '$Menu[id]'"
        );
        if(!empty($_POST['radgroupreply'][$e])){
            $query_reply = array(
                "identity"   => $Menu['identity'],
                "users"      => $Menu['id'],
                "groupname"  => $groupname,
                "attribute"  => $_POST['attribut'][$e], 
                "op"         => ":=",
                "value"      => ($_POST['attribut'][$e] == 'Mikrotik-Total-Limit' ? ByteConvert($_POST['radgroupreply'][$e]) : $_POST['radgroupreply'][$e]),
                "description"=> $_POST['description']
            );
            if($check_reply){
                $Bsk->Update("radgroupreply", $query_reply, "groupname = '$check_reply[groupname]' and attribute = '$attribut' and identity = '$Menu[identity]' and users = '$Menu[id]'");
            } else {
                $Bsk->Insert("radgroupreply", $query_reply);
            }
        } else {
            $Bsk->Delete("radgroupreply", array(
                "groupname" => $check_reply['groupname'],
                "attribute" => $attribut,
                "identity"  => $Menu['identity'],
                "users"     => $Menu['id']
            ));
        }
    }
    $check_price = $Bsk->Show("radprice", "groupname", "groupname = '$post_profile' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    if(!empty($_POST['price'])){
        ($check_price ? 
            $Bsk->Update("radprice", 
                array(
                    "groupname" => $groupname,
                    "price"     => Rahmad($_POST['price']),
                    "discount"  => Rahmad($_POST['discount'])
                ),
                "groupname = '$check_price[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]'"
            ) :
            $Bsk->Insert("radprice", 
                array(
                    "groupname" => $groupname,
                    "price"     => Rahmad($_POST['price']),
                    "discount"  => Rahmad($_POST['discount']),
                    "identity"  => $Menu['identity'],
                    "users"     => $Menu['id']
                )
            )
        );
    } else {
        $Bsk->Delete("radprice", array("groupname" => $post_profile, "identity" => $Menu['identity'], "users" => $Menu['id']));
    }
    echo json_encode($groupname ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
    );
}
if(isset($_POST['delete'])){
    $id_delete = Rahmad($_POST['delete']);
    $check_delete = $Bsk->Delete("radgroupcheck", array("groupname" => $id_delete, "identity" => $Menu['identity'], "users" => $Menu['id']));
    $Bsk->Delete("radgroupreply", array("groupname" => $id_delete, "identity" => $Menu['identity'], "users" => $Menu['id']));
    $Bsk->Delete("radusergroup", array("groupname" => $id_delete, "identity" => $Menu['identity'], "users" => $Menu['id']));
    $Bsk->Delete("radprice", array("groupname" => $id_delete, "identity" => $Menu['identity'], "users" => $Menu['id']));
    echo json_encode($check_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}