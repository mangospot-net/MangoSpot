<?php
$radius = ($Menu['data'] ? " inner join packet c on a.groupname = c.groupname " : "and a.users = '$Menu[id]'");
if(isset($_GET['data'])){
    $table = array();
    $chang = (empty($_GET['data']) ? "" : " and profile = '".Rahmad($_GET['data'])."'");
    $users = (empty($_GET['users']) ? $Menu['id'] : Rahmad($_GET['users']));
    $query = DataTable(
        "expired", 
        "profile, username, attribute, time, expired, price", 
        "identity = '$Menu[identity]' and users = '$users' ".$chang, 
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
        "radgroupcheck a left join radgroupreply b on a.groupname = b.groupname ".$radius, 
        "a.groupname as id, a.groupname as name, a.groupname", 
        "a.identity = '$Menu[identity]' GROUP BY a.groupname", "a.groupname asc"
    );
    foreach ($query_profile as $show_profile) {
        $array_profile[] = $show_profile;
    }
    echo json_encode($array_profile ? 
		array("status" => true, "message" => "success", "data" => $array_profile) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['level'])){
    $level = array();
    $seler = $Bsk->View(
        "users a inner join level b on a.level = b.id", 
        "a.id, a.name", 
        "a.identity = '$Menu[identity]' and (b.slug = '$Menu[level]' or b.id = '$Menu[level]')", 
        "a.id asc"
    );
    foreach ($seler as $reseller) {
        $level[] = $reseller;
    }
    echo json_encode($level ? 
		array("status" => true, "message" => "success", "data" => $level, "value" => $Menu['id']) : 
		array("status" => false, "message" => "error", "data" => false, "value" => false), true
	);
}
if(isset($_POST['delete'])){
    $count = count($_POST['delete']);
    $getId = Rahmad($_POST['client']);
    for($i = 0; $i<$count; $i++){
        $Bsk->Hapus("radacct", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $getId));
        $Bsk->Hapus("radcheck", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $getId));
        $Bsk->Hapus("radreply", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $getId));
        $Bsk->Hapus("radpostauth", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $getId));
        $Bsk->Hapus("radusergroup", array("username" => Rahmad($_POST['delete'][$i]), "identity" => $Menu['identity'], "users" => $getId));
    }
    if(!empty($_POST['price'])){
        $Bsk->Tambah("income", array(
            "identity" => $Menu['identity'],
            "users"    => $getId,
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