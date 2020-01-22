<?php
$radius = ($Menu['data'] ? "and id in('$Menu[data]')" : "and users = '$Menu[id]'");
function Replace($data){
    $resul = str_replace(array('<','>'),'',$data);
    return $resul;
}
if(isset($_GET['data'])){
    $online = array();
    $setData = (empty($_GET['data']) ? "" : " and id = '".Rahmad($_GET['data'])."'");
    $routes = $Bsk->Select(
        "nas", "id, identity, nasname, username, password, port, description", 
        "identity = '$Menu[identity]' and status = 'true' $radius ".$setData, "id asc"
    );
    foreach ($routes as $trafic) {
        $ports = ($trafic['port'] ? ":".$trafic['port'] : "");
        if ($Router->connect($trafic['nasname'].$ports, $trafic['username'], $Auth->decrypt($trafic['password'], 'BSK-RAHMAD'))) {
            $IPAddr = $Router->comm("/ip/pool/print");
            foreach ($IPAddr as $IPList) {
                $online[] = array(
                    "identity"  => $trafic['id'],
                    "router"    => $trafic['description'],
                    "name"      => $IPList['name'],
                    "address"   => $IPList['ranges'],
                    "id"        => $IPList['.id']
                );
            }
        }
    }
    $Router->disconnect();
    $json_data = array(
		"draw"            => 1,
		"recordsTotal"    => count($online),
		"recordsFiltered" => count($online),
        "data"            => $online
	);
    echo json_encode($json_data, true);
}
if(isset($_GET['server'])){
    $server = array();
    $querys = $Bsk->Select("nas", "id, description as name", "identity = '$Menu[identity]' ".$radius, "id asc");
    foreach ($querys as $hspLists) {
        $server[] = $hspLists;
    }
    echo json_encode($server ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => $server) : 
        array("status" => false, "message" => "error", "color" => "red", "data" => false), true
    );
}
if(isset($_GET['detail'])){
    $id_detail = explode('*', $_GET['detail']);
    $query_detail = $Bsk->Show("nas", "id, nasname, username, password, port", "id = '$id_detail[0]' and identity = '$Menu[identity]' ".$radius);
    $showPort = ($query_detail['port'] ? ":".$query_detail['port'] : "");
    if ($Router->connect($query_detail['nasname'].$showPort, $query_detail['username'], $Auth->decrypt($query_detail['password'], 'BSK-RAHMAD'))) {
        $RoutShow = $Router->comm('/ip/pool/print', array("?.id"=> '*'.$id_detail[1]));
    }
    $detail = array(
        "id"        => $query_detail['id'].$RoutShow[0]['.id'],
        "router"    => $query_detail['id'], 
        "name"      => $RoutShow[0]['name'],
        "ranges"    => $RoutShow[0]['ranges']
    );
    echo json_encode($detail ? 
		array("status" => true, "message" => "success", "data" => $detail) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_POST['name'])){
    $id_route = explode('*', $_POST['id']);
    $ps_unset = array('id', 'router');
    $id_posts = ($id_route[1] ? $id_route[0] : $_POST['router']);
    foreach ($ps_unset as $key) {
        unset($_POST[$key]);
    }
    $ip_route = $Bsk->Show("nas", "id, nasname, username, password, port", "id = '$id_posts' and identity = '$Menu[identity]' ".$radius);
    $ip_ports = ($ip_route['port'] ? ":".$ip_route['port'] : "");
    if ($Router->connect($ip_route['nasname'].$ip_ports, $ip_route['username'], $Auth->decrypt($ip_route['password'], 'BSK-RAHMAD'))) {
        if($id_route[1]){
            $post = $Router->comm('/ip/pool/set', array_merge($_POST, array(".id" => "*".$id_route[1])));
        } else {
            $post = $Router->comm("/ip/pool/add", $_POST);
        }
    }
    $Router->disconnect();
    echo json_encode($ip_route ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success.") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
    );
}
if(isset($_POST['delete'])){
    $remove = false;
    $getRout = explode('*', $_POST['delete']);
    $raouter = $Bsk->Show(
        "nas", "nasname, username, password, port", 
        "id = '$getRout[0]' and identity = '$Menu[identity]' and status = 'true' ".$radius
    );
    $getPort = ($raouter['port'] ? ":".$raouter['port'] : "");
    if ($Router->connect($raouter['nasname'].$getPort, $raouter['username'], $Auth->decrypt($raouter['password'], 'BSK-RAHMAD'))) {
        $Router->write('/ip/pool/remove', false);
        $remove = $Router->write('=.id=*'.$getRout[1], true);
        $Router->read();
    }
    $Router->disconnect();
    echo json_encode($remove ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
    );
}