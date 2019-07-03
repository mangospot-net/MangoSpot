<?php
if(isset($_GET['data'])){
    $signal = array();
    $select = (empty($_GET['data']) ? "" : " and id = '".Rahmad($_GET['data'])."'");
    $routes = $Bsk->View(
        "nas", "id, identity, nasname, username, password, port, description", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true' ".$select, "id asc"
    );
    foreach ($routes as $trafic) {
        $ports = ($trafic['port'] ? ":".$trafic['port'] : "");
        if ($Router->connect($trafic['nasname'].$ports, $trafic['username'], $Auth->decrypt($trafic['password'], 'BSK-RAHMAD'))) {
            $items = $Router->comm("/ip/hotspot/active/print");
            $pppoe = $Router->comm("/ppp/active/print");
            foreach ($items as $value) {
                $signal[] = array(
                    "identity"  => $trafic['identity'],
                    "server"    => $trafic['description'],
                    "users"     => $value['user'],
                    "type"      => $value['server'],
                    "address"   => $value['address'],
                    "id"        => $value['.id']
                );
            }
            foreach ($pppoe as $online) {
                $signal[] = array(
                    "identity"  => $trafic['identity'],
                    "server"    => $trafic['description'],
                    "users"     => $online['name'],
                    "type"      => $online['service'],
                    "address"   => $online['address'],
                    "id"        => $online['.id']
                );
            }
        }
    }
    $Router->disconnect();
    $json_data = array(
		"draw"            => 1,
		"recordsTotal"    => count($signal),
		"recordsFiltered" => count($signal),
        "data"            => $signal
	);
    echo json_encode($json_data, true);
}
if(isset($_GET['server'])){
    $server = array();
    $querys = $Bsk->View("nas", "id, description as name", "identity = '$Menu[identity]' and users = '$Menu[id]' ", "id asc");
    foreach ($querys as $values) {
        $server[] = $values;
    }
    echo json_encode($server ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => $server) : 
        array("status" => false, "message" => "error", "color" => "red", "data" => false), true
    );
}
if(isset($_POST['delete'])){
    $remove = false;
    $getUser = Rahmad($_POST['id']);
    $getType = Rahmad($_POST['type']);
    $getRout = Rahmad($_POST['delete']);
    $raouter = $Bsk->Tampil(
        "nas", "nasname, username, password, port", 
        "id = '$getRout' and identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true'"
    );
    $getPort = ($raouter['port'] ? ":".$raouter['port'] : "");
    if ($Router->connect($raouter['nasname'].$getPort, $raouter['username'], $Auth->decrypt($raouter['password'], 'BSK-RAHMAD'))) {
        $offline = ($getType == 'pppoe' ? '/ppp/active/remove' : '/ip/hotspot/active/remove');
        $Router->write($offline, false);
        $remove = $Router->write('=.id='.$getUser, true);
        $Router->read();
    }
    $Router->disconnect();
    echo json_encode($remove ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Disconnect data success") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Disconnect data failed!"), true
    );
}