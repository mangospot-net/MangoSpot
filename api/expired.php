<?php
if(isset($_GET['data'])){
    $table = array();
    $chang = (empty($_GET['data']) ? "" : " and profile = '".Rahmad($_GET['data'])."'");
    $query = DataTable(
        "expired", 
        "profile, username, attribute, time, expired, price", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' ".$chang, 
        array("username", "profile", "username", "time", "expired", "price")
    );
    foreach($query['data'] as $list){
		$table[] = array_replace($list, array("expired" => $list['attribute'] == 'Max-Data' ? formatBytes($list['expired']) : $list['expired']));
	}
    echo json_encode(array_replace($query, array("data" => $table)), true);
}
if(isset($_GET['profile'])){
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
if(isset($_POST['delete'])){
    $count = count($_POST['delete']);
    for($i = 0; $i<$count; $i++){
        $Bsk->Hapus("radacct", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $Menu['id']));
        $Bsk->Hapus("radcheck", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $Menu['id']));
        $Bsk->Hapus("radreply", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $Menu['id']));
        $Bsk->Hapus("radpostauth", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $Menu['id']));
        $Bsk->Hapus("radusergroup", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $Menu['id']));
    }
    if(!empty($_POST['price'])){
        $Bsk->Tambah("income", array(
            "identity" => $Menu['identity'],
            "users"    => $Menu['id'],
            "total"    => $count,
            "value"    => Rahmad($_POST['price']),
            "date"     => date('Y-m-d H:i:s')
        ));
    }
    echo json_encode($count ? 
		array("status" => true, "message" => "success", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "data" => "Delete data failed!"), true
	);
}