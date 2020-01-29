<?php
$radius = ($Menu['data'] ? " inner join packet c on a.groupname = c.groupname " : "and a.users = '$Menu[id]'");
if(isset($_GET['data'])){
    $table = array();
    $chang = (empty($_GET['data']) ? "" : " and profile = '".Rahmad($_GET['data'])."'");
    $users = (empty($_GET['users']) ? $Menu['id'] : Rahmad($_GET['users']));
    $query = $Bsk->Table(
        "expired", 
        "profile, username, time, usages, usages as expired, price, discount, total", 
        "identity = '$Menu[identity]' and users = '$users' ".$chang, 
        array("username", "username", "profile", "time", "usages", "price", "discount", "total")
    );
    echo json_encode($query, true);
}
if(isset($_GET['profile'])){
    $array_profile = array();
    $query_profile = $Bsk->Select(
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
    $seler = $Bsk->Select(
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
if(isset($_GET['code'])){
    $code = array();
    $base = $Bsk->Select("type", "id, name", "type = 'cron' and status = 'true'", "id asc");
    foreach ($base as $type) {
        $code[] = $type;
    }
    echo json_encode($code ? 
        array("status" => true, "message" => "success", "data" => $code) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_GET['detail'])){
    $getType = Rahmad($_GET['detail']);
    $showData = $Bsk->Show("type", "info, lower(name) as mode", "id = '$getType' and type = 'cron' and status = 'true' ");
    echo json_encode($showData ? 
        array("status" => true, "message" => "success", "data" => $showData) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_POST['delete'])){
    $insql = array();
    $getId = Rahmad($_POST['client']);
    $implod = "'".implode("','", $_POST['delete'])."'";
    $resume = $Bsk->Show(
        "expired", "identity, users, count(*) AS total, sum(price) as price, sum(discount) as discount, sum(total) as income, sum(upload) as upload, sum(download) as download, now() as date",
        "identity = '$Menu[identity]' and users = '$getId' and username in (".$implod.") group by identity, users"
    );
    $saved = $Bsk->Select(
        "expired", "username, profile, time, usages, quota, price, discount, total",
        "identity = '$Menu[identity]' and users = '$getId' and username IN (".$implod.") ", "time asc"
    );
    foreach($saved as $insert){
        $insql[] = $insert;
    }
    $recap = $Bsk->Insert("income", array_merge($resume, array("data" => json_encode($insql, true))));
    foreach($_POST['delete'] as $removeID){
        $Bsk->Delete("radacct",     array("username" => $removeID));
        $Bsk->Delete("radpostauth", array("username" => $removeID));
        $Bsk->Delete("radcheck",    array("username" => $removeID, "identity" => $Menu['identity'], "users" => $getId));
        $Bsk->Delete("radreply",    array("username" => $removeID, "identity" => $Menu['identity'], "users" => $getId));
        $Bsk->Delete("radusergroup",array("username" => $removeID, "identity" => $Menu['identity'], "users" => $getId));
    }
    echo json_encode($recap ? 
		array("status" => true, "message" => "success", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "data" => "Delete data failed!"), true
    );
}
