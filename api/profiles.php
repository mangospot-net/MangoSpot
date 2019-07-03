<?php
if(isset($_GET['data'])){
    $table_profiles = array();
    $query_profiles = $Bsk->View("radgroupcheck", "groupname", "identity = '$Menu[identity]' and users = '$Menu[id]' group by groupname");
    foreach($query_profiles as $show_profiles){
        $id_profile     = $Bsk->Tampil("radgroupcheck", "id",    "groupname = '$show_profiles[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]'");
        $shared_profile = $Bsk->Tampil("radgroupcheck", "value", "groupname = '$show_profiles[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]' and attribute = 'Simultaneous-Use'");
        $exp1_profile    = $Bsk->Tampil("radgroupcheck", "value", "groupname = '$show_profiles[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]' and attribute = 'Access-Period'");
        $exp2_profile    = $Bsk->Tampil("radgroupcheck", "value", "groupname = '$show_profiles[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]' and attribute = 'Max-All-Session'");
        $exp3_profile    = $Bsk->Tampil("radgroupcheck", "value", "groupname = '$show_profiles[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]' and attribute = 'Max-Daily-Session'");
        $rate_profile   = $Bsk->Tampil("radgroupreply", "value", "groupname = '$show_profiles[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]' and attribute = 'Mikrotik-Rate-Limit'");
        $quota_profile  = $Bsk->Tampil("radgroupreply", "value", "groupname = '$show_profiles[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]' and attribute = 'Mikrotik-Total-Limit'");
        $price_profile  = $Bsk->Tampil("price",         "value", "groupname = '$show_profiles[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]'");
        $table_profiles[] = array(
            "id"        => $id_profile['id'],
            "groupname" => $show_profiles['groupname'],
            "shared"    => $shared_profile['value'],
            "rate"      => $rate_profile['value'],
            "quota"     => formatBytes($quota_profile['value']),
            "price"     => Rp($price_profile['value']),
            "expired"   => $exp1_profile['value']." ".$exp2_profile['value']." ".$exp3_profile['value']
        );
    }
    $json_data = array(
		"recordsTotal"    => intval(count($table_profiles)),
		"recordsFiltered" => intval(count($table_profiles)),
		"data"            => $table_profiles
	);
	echo json_encode($json_data, true);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $show_detail = $Bsk->Tampil(
        "radgroupcheck a 
        left join radgroupcheck b on a.groupname = b.groupname and b.attribute = 'Simultaneous-Use' 
        left join radgroupcheck c on a.groupname = c.groupname and c.attribute = 'Access-Period' 
        left join radgroupcheck d on a.groupname = d.groupname and d.attribute = 'Max-All-Session' 
        left join radgroupcheck e on a.groupname = e.groupname and e.attribute = 'Max-Daily-Session' 
        left join radgroupreply f on a.groupname = f.groupname and f.attribute = 'Mikrotik-Rate-Limit' 
        left join radgroupreply g on a.groupname = g.groupname and g.attribute = 'Mikrotik-Total-Limit' 
        left join radgroupcheck i on a.groupname = i.groupname and i.attribute = 'Max-Data' 
        left join price h on a.groupname = h.groupname", 
        "a.groupname as id, a.groupname, a.description, b.value as shared, c.value as period, d.value as times, e.value as daily, f.value as rate, g.value as quota, i.value as valume, h.value as price", 
        "a.id = '$id_detail' and a.identity = '$Menu[identity]' and a.users = '$Menu[id]'"
    );
    $replace_data = array_replace($show_detail, array("quota" => formatBytes($show_detail['quota']), "volume" => formatBytes($show_detail['quota'])));
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
        $check_check = $Bsk->Tampil(
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
                $Bsk->Ubah("radgroupcheck", $query_check, "groupname = '$check_check[groupname]' and attribute = '$attribute' and identity = '$Menu[identity]' and users = '$Menu[id]'");
            } else {
                $Bsk->Tambah("radgroupcheck", $query_check);
            }
        } else {
            $Bsk->Hapus("radgroupcheck", array(
                "groupname" => $check_check['groupname'],
                "attribute" => $attribute,
                "identity"  => $Menu['identity'],
                "users"     => $Menu['id']
            ));
        }
    }
    for($e=0; $e<$count_reply; $e++){
        $attribut = $_POST['attribut'][$e];
        $check_reply = $Bsk->Tampil(
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
                $Bsk->Ubah("radgroupreply", $query_reply, "groupname = '$check_reply[groupname]' and attribute = '$attribut' and identity = '$Menu[identity]' and users = '$Menu[id]'");
            } else {
                $Bsk->Tambah("radgroupreply", $query_reply);
            }
        } else {
            $Bsk->Hapus("radgroupreply", array(
                "groupname" => $check_reply['groupname'],
                "attribute" => $attribut,
                "identity"  => $Menu['identity'],
                "users"     => $Menu['id']
            ));
        }
    }
    $check_price = $Bsk->Tampil("price", "groupname", "groupname = '$post_profile' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    if(!empty($_POST['price'])){
        ($check_price ? 
            $Bsk->Ubah("price", 
                array(
                    "groupname" => $groupname,
                    "value"     => Rahmad($_POST['price'])
                ),
                "groupname = '$check_price[groupname]' and identity = '$Menu[identity]' and users = '$Menu[id]'"
            ) :
            $Bsk->Tambah("price", 
                array(
                    "groupname" => $groupname,
                    "value"     => Rahmad($_POST['price']),
                    "identity"  => $Menu['identity'],
                    "users"     => $Menu['id']
                )
            )
        );
    } else {
        $Bsk->Hapus("price", array("groupname" => $post_profile, "identity" => $Menu['identity'], "users" => $Menu['id']));
    }
    echo json_encode($groupname ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
    );
}
if(isset($_POST['delete'])){
    $id_delete = Rahmad($_POST['delete']);
    $check_delete = $Bsk->Tampil("radgroupcheck", "groupname", "id = '$id_delete' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $Bsk->Hapus("radgroupcheck", array("groupname" => $check_delete['groupname']));
    $Bsk->Hapus("radgroupreply", array("groupname" => $check_delete['groupname']));
    $Bsk->Hapus("radusergroup", array("groupname" => $check_delete['groupname']));
    echo json_encode($check_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}