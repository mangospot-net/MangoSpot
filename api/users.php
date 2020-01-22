<?php
$radius = ($Menu['data'] ? "and a.users = '$Menu[id]'" : "");
$radiuz = ($Menu['data'] ? "" : "and a.users = '$Menu[id]'");
if(isset($_GET['data'])){
    $getUsers = (empty($_GET['users']) ? $Menu['id'] : Rahmad($_GET['users'])); 
    $getGroup = (empty($_GET['data']) ? "" : " and profiles = '".Rahmad($_GET['data'])."' ");
    $radcheck = $Bsk->Table(
        "voucher", 
        "id, username, profiles, description, created", 
        "identity = '$Menu[identity]' and users = '$getUsers' ".$getGroup, 
        array("id", "username", "profiles", "description", "created", "id")
	);
	echo json_encode($radcheck, true);
}
if(isset($_GET['table'])){
    $get_user = (empty($_GET['users']) ? $Menu['id'] : Rahmad($_GET['users']));
    $get_group = (empty($_GET['table']) ? '' : " and a.profiles = '".Rahmad($_GET['table'])."' ");
    $tables = $Bsk->Table(
        "voucher a left join radprice b on a.profiles = b.groupname and a.identity = b.identity", 
        "a.id, a.username, a.profiles as profile, b.price, a.created", 
        "a.identity = '$Menu[identity]' and a.users = '$get_user' ".$get_group, 
        array("a.username", "a.profiles", "b.price", "a.created", "a.id")
	);
	echo json_encode($tables, true);
}
if(isset($_GET['level'])){
    $level = array();
    $seler = $Bsk->Select(
        "users a inner join level b on a.level = b.id", 
        "a.id, a.name", 
        "a.identity = '$Menu[identity]' and b.slug = '$Menu[level]'", 
        "a.name asc"
    );
    foreach ($seler as $reseller) {
        $level[] = $reseller;
    }
    echo json_encode($level ? 
		array("status" => true, "message" => "success", "data" => $level) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $show_detail = $Bsk->Show("voucher a", "*", "a.id = '$id_detail' and a.identity = '$Menu[identity]' $radius");
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

if(isset($_GET['profiles'])){
    $array_profile = array();
    $query_profile = $Bsk->Select(
        "radgroupcheck a left join radgroupreply b on a.groupname = b.groupname", 
        "a.groupname as id, a.groupname as name, a.groupname", 
        "a.identity = '$Menu[identity]' $radiuz GROUP BY a.groupname", "a.groupname asc"
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
    $query_themes = $Bsk->Select("themes a", "a.id, a.name", "a.identity = '$Menu[identity]' and a.type = 'radius' $radiuz", "a.id asc");
    foreach ($query_themes as $value_themes) {
        $themes[] = $value_themes;
    }
    echo json_encode($themes ? 
		array("status" => true, "message" => "success", "data" => $themes) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['username'])){
    $id_users    = Rahmad($_POST['username']);
    $username    = Rahmad($_POST['username']);
    $count_check = count($_POST['radcheck']);
    $count_reply = count($_POST['radreply']);
    for($i=0; $i<$count_check; $i++){
        $attribute = $_POST['attribute'][$i];
        $check_check = $Bsk->Show(
            "radcheck a", "a.username", 
            "a.username = '$id_users' and a.attribute = '$attribute' and a.identity = '$Menu[identity]' $radius"
        );
        if(!empty($_POST['radcheck'][$i])){
            $query_check = array(
                "identity"   => $Menu['identity'],
                "users"      => $Menu['id'],
                "username"   => $username, 
                "attribute"  => $_POST['attribute'][$i],
                "op"         => ":=",
                "value"      => ($_POST['attribute'][$i] == 'Simultaneous-Use' ? $_POST['radcheck'][$i] : ($_POST['attribute'][$i] == 'Max-Data' ? ByteConvert($_POST['radcheck'][$i]) : DateTime($_POST['radcheck'][$i]))),
                "description"=> $_POST['description']
            );
            if($check_check){
                $Bsk->Update("radcheck", $query_check, "username = '$check_check[username]' and attribute = '$attribute' and identity = '$Menu[identity]' ");
            } else {
                $Bsk->Insert("radcheck", array_merge($query_check, array("created" => date('Y-m-d H:i:s'))));
            }
        } else {
            $Bsk->Delete("radcheck", array(
                "username"  => $check_check['username'],
                "attribute" => $attribute,
                "identity"  => $Menu['identity']
            ));
        }
    }
    for($e=0; $e<$count_reply; $e++){
        $attribut = $_POST['attribut'][$e];
        $check_reply = $Bsk->Show(
            "radreply a", "a.username", 
            "a.username = '$id_users' and a.attribute = '$attribut' and a.identity = '$Menu[identity]' $radius"
        );
        if(!empty($_POST['radreply'][$e])){
            $query_reply = array(
                "identity"   => $Menu['identity'],
                "users"      => $Menu['id'],
                "username"   => $username,
                "attribute"  => $_POST['attribut'][$e], 
                "op"         => ":=",
                "value"      => ($_POST['attribut'][$e] == 'Mikrotik-Total-Limit' ? ByteConvert($_POST['radreply'][$e]) : $_POST['radreply'][$e]),
                "description"=> $_POST['description']
            );
            if($check_reply){
                $Bsk->Update("radreply", $query_reply, "username = '$check_reply[username]' and attribute = '$attribut' and identity = '$Menu[identity]' ");
            } else {
                $Bsk->Insert("radreply", array_merge($query_reply, array("created" => date('Y-m-d H:i:s'))));
            }
        } else {
            $Bsk->Delete("radreply", array(
                "username"  => $check_reply['username'],
                "attribute" => $attribut,
                "identity"  => $Menu['identity']
            ));
        }
    }
    $check_group = $Bsk->Show("radusergroup a", "a.username", "a.username = '$id_users' and a.identity = '$Menu[identity]' $radius");
    if(!empty($_POST['groupname'])){
        if($check_group){
            $Bsk->Update("radusergroup", 
                array(
                    "username"  => $username,
                    "groupname" => Rahmad($_POST['groupname'])
                ),
                "username = '$check_group[username]' and identity = '$Menu[identity]' "
            );
        } else {
            $Bsk->Insert("radusergroup", 
                array(
                    "username"  => $username,
                    "groupname" => Rahmad($_POST['groupname']),
                    "identity"  => $Menu['identity'],
                    "users"     => $Menu['id']
                )
            );
        }
    } else {
        $Bsk->Delete("radusergroup", array("username" => $id_users, "identity" => $Menu['identity']));
    }
    echo json_encode($username ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['qty'])){
    $success = 0; $failed = 0;
    $qty = Rahmad($_POST['qty']);
    $mod = Rahmad($_POST['mode']);
    $lng = Rahmad($_POST['length']);
    $prf = Rahmad($_POST['prefix']);
    $crt = Rahmad($_POST['charecter']);
    for($o=0; $o<$qty; $o++){
        $batch_user = $prf.random_str($lng, $crt);
        $batch_pswd = random_str($lng, $crt);
        $mode = ($mod == 'true' ? $batch_pswd : $batch_user);
        $post_qty = array(
            "identity"   => $Menu['identity'],
            "users"      => $Menu['id'],
            "username"   => $batch_user,
            "attribute"  => "Cleartext-Password", 
            "op"         => ":=",
            "value"      => $mode,
            "description"=> $_POST['description'],
            "created"    => $_POST['created']
        );
        $add_batch = $Bsk->Insert("radcheck", $post_qty);
        $Bsk->Insert("radusergroup", 
            array(
                "identity"  => $Menu['identity'],
                "users"     => $Menu['id'],
                "username"  => $batch_user,
                "groupname" => Rahmad($_POST['groupname'])
            )
        );
        if($add_batch){ 
            $success++; 
        } else {
            $failed++;
        }
    }
    echo json_encode($qty ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Batch data success ".$success." & failed ".$failed) : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Batch data failed!"), true
	);
}
if(isset($_POST['delete'])){
    $id_delete = Rahmad($_POST['delete']);
    $check_delete1 = $Bsk->Show("radcheck a", "a.username", "a.identity = '$Menu[identity]' and a.id = '$id_delete' and a.attribute = 'Cleartext-Password' ".$radius);
    $check_delete2 = $Bsk->Show("radcheck a", "a.username", "a.identity = '$Menu[identity]' and a.username = '$id_delete' ".$radius);
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
if(isset($_POST['import'])){
	$valid = 0; $failed = 0;
	if($_FILES['file']['name']!=""){
        $excelFile = $_FILES['file']['tmp_name'];
        $excelRead = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($excelFile);
        $excelObjc = $excelRead->load($excelFile);
        $worksheet = $excelObjc->getSheet(0);
        $excelRows = $worksheet->getHighestRow();
		for ($x = 2; $x <= $excelRows; $x++){
			$excelData1 = $worksheet->getCell('A'.$x)->getValue();
			$excelData2 = $worksheet->getCell('B'.$x)->getValue();
			$checkQuery = $Bsk->Show("radcheck", "*", "username = '$excelData1'");
			if(!$checkQuery){
                $postQuery = array(
                    "identity"   => $Menu['identity'],
                    "users"      => $Menu['id'],
                    "username"   => $excelData1,
                    "attribute"  => "Cleartext-Password", 
                    "op"         => ":=",
                    "value"      => $excelData2,
                    "description"=> $_POST['description'],
                    "created"    => $_POST['created']
                );
                $gruopQuery = array(
                    "identity"  => $Menu['identity'],
                    "users"     => $Menu['id'],
                    "username"  => $excelData1,
                    "groupname" => Rahmad($_POST['groupname'])
                );
                $Bsk->Insert("radcheck", $postQuery);
                if(!empty($_POST['groupname'])){
                    $Bsk->Insert("radusergroup", $gruopQuery);
                }
				$valid++;
			} else {
				$failed++;
			}
		}
	}
	echo json_encode($_FILES ?
        array("status" => true, "message" => "success", "color" => "green", "data" => "Import data success ".$valid." & failed ".$failed) : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Import data failed!"), true
    );
}