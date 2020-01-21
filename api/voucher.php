<?php
$radius = ($Menu['data'] ? " and a.users = '$Menu[id]'" : "");
if(isset($_GET['data'])){
    $get_group = (empty($_GET['data']) ? '' : " and a.groupname = '".Rahmad($_GET['data'])."' ");
    $tables = $Bsk->Table(
        "voucher a inner join radprice b on a.groupname = b.groupname", 
        "a.id, a.username, b.groupname as profile, b.price, a.created", 
        "a.identity = '$Menu[identity]' and a.users = '$Menu[id]' ".$get_group, 
        array("a.id", "a.username", "b.groupname", "b.price", "a.created", "a.id")
	);
	echo json_encode($tables, true);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $tm_detail = (empty($_GET['detail']) ? "" : " and a.id = '$id_detail' ");
    $show_detail = $Bsk->Show( 
        "voucher a inner join radprice b on a.groupname = b.groupname and a.identity = b.identity", 
        "a.id, a.username, a.passwd as password, a.groupname as profile, b.price", 
        "a.identity = '$Menu[identity]' and a.users = '$Menu[id]' ".$tm_detail, "a.id desc"
    );
    $merge_data = array_merge($show_detail, array("data" => $Identity['data'], "url" => $Config['sosmed']));
    echo json_encode($show_detail ? 
		array("status" => true, "message" => "success", "data" => $merge_data) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['packet'])){
    $checks = (empty($_GET['packet']) ? "" : " and a.groupname = '".$_GET['packet']."'");
    $packet = $Bsk->Show(
        "radgroupcheck a left join radgroupreply b on a.groupname = b.groupname", 
        "a.groupname as id, a.groupname as profile", 
        "a.identity = '$Menu[identity]' $radius $checks GROUP BY a.groupname", "a.groupname asc"
    );
    echo json_encode($packet ? 
		array("status" => true, "message" => "success", "data" => array_merge($packet, array("voucher" => 44))) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['profiles'])){
    $array_profile = array();
    $query_profile = $Bsk->Select(
        "radgroupcheck a left join radgroupreply b on a.groupname = b.groupname", 
        "a.groupname as id, a.groupname as name, a.groupname", 
        "a.identity = '$Menu[identity]' $radius GROUP BY a.groupname", "a.groupname asc"
    );
    foreach ($query_profile as $show_profile) {
        $array_profile[] = $show_profile;
    }
    echo json_encode($array_profile ? 
		array("status" => true, "message" => "success", "data" => $array_profile) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['theme'])){
    $themes = array();
    $query_themes = $Bsk->Select("themes a", "a.id, a.name", "a.identity = '$Menu[identity]' and a.type = 'radius' $radius", "a.id asc");
    foreach ($query_themes as $value_themes) {
        $themes[] = $value_themes;
    }
    echo json_encode($themes ? 
		array("status" => true, "message" => "success", "data" => $themes) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['qty'])){
    $success= 0; 
    $failed = 0;
    $qty    = Rahmad($_POST['qty']);
    $mode   = Rahmad($_POST['mode']);
    $length = Rahmad($_POST['length']);
    $prefix = Rahmad($_POST['prefix']);
    $charec = Rahmad($_POST['charecter']);
    $profil = Rahmad($_POST['groupname']);
    for($i=0; $i<$qty; $i++){
        $batch_user = $prefix.random_str($length, $charec);
        $batch_pswd = random_str($length, $charec);
        $mode_paswd = ($mode == 'true' ? $batch_pswd : $batch_user);
        $post_array = array(
            "identity"   => $Menu['identity'],
            "users"      => $Menu['id'],
            "username"   => $batch_user,
            "attribute"  => "Cleartext-Password", 
            "op"         => ":=",
            "value"      => $mode_paswd,
            "description"=> $_POST['description'],
            "created"    => $_POST['created']
        );
        $add_voucher = $Bsk->Insert("radcheck", $post_array);
        $Bsk->Insert("radusergroup", 
            array(
                "identity"  => $Menu['identity'],
                "users"     => $Menu['id'],
                "username"  => $batch_user,
                "groupname" => Rahmad($_POST['groupname'])
            )
        );
        if($add_voucher){ 
            $success++; 
        } else {
            $failed++;
        }
    }
    echo json_encode($qty ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Generate voucher success ".$success." & failed ".$failed) : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Generate voucher failed!"), true
	);
}
if(isset($_POST['delete'])){
    $id_delete = Rahmad($_POST['delete']);
    $check_delete1 = $Bsk->Show("radcheck a", "a.username", "a.identity = '$Menu[identity]' and a.id = '$id_delete' and a.users = '$Menu[id]'");
    $check_delete2 = $Bsk->Show("radcheck a", "a.username", "a.identity = '$Menu[identity]' and a.username = '$id_delete' and a.users = '$Menu[id]'");
    $check_delete = ($check_delete1 ? $check_delete1 : $check_delete2);
    $Bsk->Delete("radacct", array("username" => $check_delete['username']));
    $Bsk->Delete("radcheck", array("username" => $check_delete['username']));
    $Bsk->Delete("radreply", array("username" => $check_delete['username']));
    $Bsk->Delete("radusergroup", array("username" => $check_delete['username']));
    echo json_encode($check_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}
if(isset($_GET['print'])){
    $number = 0;
    $array_print = array();
    $data_print  = array();
    $id_print    = Rahmad($_GET['print']);
    $type_print  = Rahmad($_GET['type']);
    $type_theme  = (empty($_GET['themes']) ? "" : "and a.id = '".Rahmad($_GET['themes'])."'");
    $where_print = ($type_print == 'batch' ? "and a.created = '$id_print'" : "and a.username = '$id_print'"); 
    $query_theme = $Bsk->Show("themes a", "a.content", "a.identity = '$Menu[identity]' and a.type = 'radius' $radius ".$type_theme, "a.id asc");
    $batch_print = $Bsk->Select("print a", "*", "a.identity = '$Menu[identity]' $radius $where_print" );
    foreach ($batch_print as $value_print) {
        $number++;
        $array_print[] = $value_print;
        $data_print[] = HTMLReplace($query_theme['content'],
            array_replace(
                $value_print, 
                array(
                    "no"        => $number,
                    "period"    => secTime($value_print['period']),
                    "times"     => secTime($value_print['times']),
                    "daily"     => secTime($value_print['daily']),
                    "price"     => Money($value_print['price'], $Config['currency']),
                    "qr_code"   => '<div class="qr-code" data-code="'.$value_print['qr_code'].'"></div>'
                )
            )
        );
    }
    echo json_encode($array_print ? 
		array("status" => true, "message" => "success", "color" => "green", "themes" => $query_theme, "print" => $data_print, "data" => $array_print) : 
		array("status" => false, "message" => "error", "color" => "red", "themes" => false, "data" => "Print data failed!"), true
	);
}