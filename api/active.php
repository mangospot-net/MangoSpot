<?php
$radius = ($Menu['data'] ? " and users = '$Menu[id]'" : "");
if(isset($_GET['data'])){
    $chang = (empty($_GET['data']) ? "" : " and server = '".Rahmad($_GET['data'])."'");
    $seler = (empty($_GET['users']) ? " and users = '$Menu[id]'" : " and users = '".Rahmad($_GET['users'])."'");
    $query = $Bsk->Table(
        "active", 
        "server, username, address, profile, time", 
        "identity = '$Menu[identity]' ".$seler.$chang, 
        array("server", "username", "profile", "address", "time")
	);
    echo json_encode($query, true);
}
if(isset($_GET['server'])){
    $server = array();
    $querys = $Bsk->Select("active", "server as id, server as name", "identity = '$Menu[identity]' $radius group by server", "server asc");
    foreach ($querys as $value) {
        $server[] = $value;
    }
    echo json_encode($server ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => $server) : 
        array("status" => false, "message" => "error", "color" => "red", "data" => false), true
    );
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