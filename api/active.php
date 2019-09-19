<?php
if(isset($_GET['data'])){
    $chang = (empty($_GET['data']) ? "" : " and server = '".Rahmad($_GET['data'])."'");
    $query = DataTable(
        "active", 
        "server, username, address, profile, time", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' ".$chang, 
        array("server", "username", "profile", "address", "time")
	);
    echo json_encode($query, true);
}
if(isset($_GET['server'])){
    $server = array();
    $querys = $Bsk->View("active", "server as id, server as name", "identity = '$Menu[identity]' and users = '$Menu[id]' group by server", "server asc");
    foreach ($querys as $value) {
        $server[] = $value;
    }
    echo json_encode($server ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => $server) : 
        array("status" => false, "message" => "error", "color" => "red", "data" => false), true
    );
}