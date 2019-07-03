<?php
if(isset($_GET['data'])){
    $table_users = array();
    $query_users = (empty($_GET['data']) ? 
        $Bsk->View("radcheck", "username", "identity = '$Menu[identity]' and users = '$Menu[id]' group by username") : 
        $Bsk->View("radusergroup", "username", "identity = '$Menu[identity]' and users = '$Menu[id]' and groupname = '".Rahmad($_GET['data'])."'")
    );
    foreach($query_users as $show_users){
        $id_user     = $Bsk->Tampil("radcheck", "id, created", "username = '$show_users[username]' and attribute = 'Cleartext-Password' and identity = '$Menu[identity]' and users = '$Menu[id]'");
        $shared_user = $Bsk->Tampil("radcheck", "value", "username = '$show_users[username]' and attribute = 'Simultaneous-Use' and identity = '$Menu[identity]' and users = '$Menu[id]'");
        $rate_user   = $Bsk->Tampil("radreply", "value", "username = '$show_users[username]' and attribute = 'Mikrotik-Rate-Limit' and identity = '$Menu[identity]' and users = '$Menu[id]'");
        $expired_user= $Bsk->Tampil("radcheck", "value", "username = '$show_users[username]' and attribute = 'Access-Period' and identity = '$Menu[identity]' and users = '$Menu[id]'");
        $profile_user= $Bsk->Tampil("radusergroup", "groupname", "username = '$show_users[username]' and identity = '$Menu[identity]' and users = '$Menu[id]'");
        $table_users[] = array(
            "id"        => $id_user['id'],
            "username"  => $show_users['username'],
            "profiles"  => $profile_user['groupname'],
            "shared"    => $shared_user['value'],
            "rate"      => $rate_user['value'],
            "expired"   => $expired_user['value'],
            "created"   => $id_user['created']
        );
    }
    $json_data = array(
		"recordsTotal"    => intval(count($table_users)),
		"recordsFiltered" => intval(count($table_users)),
		"data"            => $table_users
	);
	echo json_encode($json_data, true);
}
if(isset($_GET['detail'])){
    $id_detail = Rahmad($_GET['detail']);
    $show_detail = $Bsk->Tampil(
        "radcheck a 
        left join radcheck b on a.username = b.username and b.attribute = 'Simultaneous-Use' 
        left join radcheck c on a.username = c.username and c.attribute = 'Access-Period' 
        left join radcheck d on a.username = d.username and d.attribute = 'Max-All-Session' 
        left join radcheck e on a.username = e.username and e.attribute = 'Max-Daily-Session' 
        left join radreply f on a.username = f.username and f.attribute = 'Mikrotik-Rate-Limit' 
        left join radreply g on a.username = g.username and g.attribute = 'Mikrotik-Total-Limit' 
        left join radcheck i on a.username = i.username and i.attribute = 'Max-Data' 
        left join radusergroup h on a.username = h.username", 
        "a.username as id, a.username, a.value as passwd, a.description, h.groupname, b.value as shared, c.value as period, d.value as times, e.value as daily, f.value as rate, g.value as quota, i.value as valume", 
        "a.id = '$id_detail' and a.identity = '$Menu[identity]' and a.users = '$Menu[id]'"
    );
    $replace_data = array_replace($show_detail, array("quota" => formatBytes($show_detail['quota']), "volume" => formatBytes($show_detail['quota'])));
    echo json_encode($show_detail ? 
		array("status" => true, "message" => "success", "data" => $replace_data) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['profiles'])){
    $array_profile = array();
    $query_profile = $Bsk->View(
        "radgroupcheck a left join radgroupreply b on a.groupname = b.groupname", 
        "a.groupname", 
        "a.identity = '$Menu[identity]' and a.users = '$Menu[id]' GROUP BY a.groupname", "a.groupname asc"
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
    $query_themes = $Bsk->View("themes", "id, name", "identity = '$Menu[identity]' and (users = '$Menu[id]' or users = 0)", "id asc");
    foreach ($query_themes as $value_themes) {
        $themes[] = $value_themes;
    }
    echo json_encode($themes ? 
		array("status" => true, "message" => "success", "data" => $themes) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['username'])){
    $id_users    = Rahmad($_POST['id']);
    $username    = Rahmad($_POST['username']);
    $count_check = count($_POST['radcheck']);
    $count_reply = count($_POST['radreply']);
    for($i=0; $i<$count_check; $i++){
        $attribute = $_POST['attribute'][$i];
        $check_check = $Bsk->Tampil(
            "radcheck", "username", 
            "username = '$id_users' and attribute = '$attribute' and identity = '$Menu[identity]' and users = '$Menu[id]'"
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
                $Bsk->Ubah("radcheck", $query_check, "username = '$check_check[username]' and attribute = '$attribute' and identity = '$Menu[identity]' and users = '$Menu[id]'");
            } else {
                $Bsk->Tambah("radcheck", array_merge($query_check, array("created" => date('Y-m-d H:i:s'))));
            }
        } else {
            $Bsk->Hapus("radcheck", array(
                "username"  => $check_check['username'],
                "attribute" => $attribute,
                "identity"  => $Menu['identity'],
                "users"     => $Menu['id']
            ));
        }
    }
    for($e=0; $e<$count_reply; $e++){
        $attribut = $_POST['attribut'][$e];
        $check_reply = $Bsk->Tampil(
            "radreply", "username", 
            "username = '$id_users' and attribute = '$attribut' and identity = '$Menu[identity]' and users = '$Menu[id]'"
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
                $Bsk->Ubah("radreply", $query_reply, "username = '$check_reply[username]' and attribute = '$attribut' and identity = '$Menu[identity]' and users = '$Menu[id]'");
            } else {
                $Bsk->Tambah("radreply", array_merge($query_reply, array("created" => date('Y-m-d H:i:s'))));
            }
        } else {
            $Bsk->Hapus("radreply", array(
                "username"  => $check_reply['username'],
                "attribute" => $attribut,
                "identity"  => $Menu['identity'],
                "users"     => $Menu['id']
            ));
        }
    }
    $check_group = $Bsk->Tampil("radusergroup", "username", "username = '$id_users' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    if(!empty($_POST['groupname'])){
        ($check_group ? 
            $Bsk->Ubah("radusergroup", 
                array(
                    "username"  => $username,
                    "groupname" => Rahmad($_POST['groupname'])
                ),
                "username = '$check_group[username]' and identity = '$Menu[identity]' and users = '$Menu[id]'"
            ) :
            $Bsk->Tambah("radusergroup", 
                array(
                    "username"  => $username,
                    "groupname" => Rahmad($_POST['groupname']),
                    "identity"  => $Menu['identity'],
                    "users"     => $Menu['id']
                )
            )
        );
    } else {
        $Bsk->Hapus("radusergroup", array("username" => $id_users, "identity" => $Menu['identity'], "users" => $Menu['id']));
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
        $add_batch = $Bsk->Tambah("radcheck", $post_qty);
        $Bsk->Tambah("radusergroup", 
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
    $check_delete = $Bsk->Tampil("radcheck", "username", "id = '$id_delete' and identity = '$Menu[identity]' and users = '$Menu[id]'");
    $Bsk->Hapus("radacct", array("username" => $check_delete['username']));
    $Bsk->Hapus("radcheck", array("username" => $check_delete['username']));
    $Bsk->Hapus("radreply", array("username" => $check_delete['username']));
    $Bsk->Hapus("radusergroup", array("username" => $check_delete['username']));
    echo json_encode($check_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}
if(isset($_POST['remove'])){
    $count_remove = count($_POST['remove']);
    for($r = 0; $r < $count_remove; $r++){
        $id_remove = Rahmad($_POST['remove'][$r]);
        $check_remove = $Bsk->Tampil("radcheck", "username", "id = '$id_remove' and identity = '$Menu[identity]' and users = '$Menu[id]'");
        $Bsk->Hapus("radacct", array("username" => $check_remove['username']));
        $Bsk->Hapus("radcheck", array("username" => $check_remove['username']));
        $Bsk->Hapus("radreply", array("username" => $check_remove['username']));
        $Bsk->Hapus("radusergroup", array("username" => $check_remove['username']));
    }
    echo json_encode($count_remove ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}
if(isset($_GET['print'])){
    $id_print    = Rahmad($_GET['print']);
    $type_print  = Rahmad($_GET['type']);
    $type_theme  = (empty($_GET['themes']) ? "" : "and id = '".Rahmad($_GET['themes'])."'");
    $array_print = array();
    $where_print = ($type_print == 'batch' ? "and a.created = '$id_print'" : "and a.username = '$id_print'"); 
    $query_theme = $Bsk->Tampil("themes", "content", "identity = '$Menu[identity]' and (users = '$Menu[id]' or users = 0) ".$type_theme, "id asc");
    $batch_print = $Bsk->View(
        "radcheck a left join radusergroup b on a.username = b.username left join price c on b.groupname = c.groupname", 
        "a.username, a.value as password, b.groupname as profile, c.value as price", 
        "a.attribute = 'Cleartext-Password' and a.identity = '$Menu[identity]' and a.users = '$Menu[id]' $where_print"
    );
    foreach ($batch_print as $value_print) {
        $array_print[] = array_replace($value_print, array("identity" => $Identity['data'], "price" => Rp($value_print['price'])));
    }
    echo json_encode($array_print ? 
		array("status" => true, "message" => "success", "color" => "green", "themes" => $query_theme, "data" => $array_print) : 
		array("status" => false, "message" => "error", "color" => "red", "themes" => false, "data" => "Print data failed!"), true
	);
}
if(isset($_POST['import'])){
	$ok = 0; $faild = 0;
	if($_FILES['file']['name']!=""){
		$fl = $_FILES['file']['tmp_name'];
		error_reporting(E_ALL ^ E_NOTICE);
		$file = new Spreadsheet_Excel_Reader($fl);
		$baris = $file->rowcount(0);
		for ($ii=2; $ii<=$baris; $ii++){
			$datakolom1 = $file->val($ii, 1, 0);
			$datakolom2 = $file->val($ii, 2, 0);
			$check_import = $Bsk->Tampil("radcheck", "*", "username = '$datakolom1'");
			if(!$check_import){
                $post_file = array(
                    "identity"   => $Menu['identity'],
                    "users"      => $Menu['id'],
                    "username"   => $datakolom1,
                    "attribute"  => "Cleartext-Password", 
                    "op"         => ":=",
                    "value"      => $datakolom2,
                    "description"=> $_POST['description'],
                    "created"    => $_POST['created']
                );
                $post_group = array(
                    "identity"  => $Menu['identity'],
                    "users"     => $Menu['id'],
                    "username"  => $datakolom1,
                    "groupname" => Rahmad($_POST['groupname'])
                );
                $Bsk->Tambah("radcheck", $post_file);
                if(!empty($_POST['groupname'])){
                    $Bsk->Tambah("radusergroup", $post_group);
                }
				$ok++;
			} else {
				$faild++;
			}
		}
		$import = true;
	} else {
		$import = false;
	}
	echo json_encode($import ?
        array("status" => true, "message" => "success", "color" => "green", "data" => "Import data success ".$ok." & failed ".$faild) : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Import data failed!"), true
    );
}