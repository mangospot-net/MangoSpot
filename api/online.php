<?php
$radius = ($Menu['data'] ? "and id in('$Menu[data]')" : "and users = '$Menu[id]'");
if(isset($_GET['data'])){
    $online = array();
    $setData = (empty($_GET['data']) ? "" : " and id = '".Rahmad($_GET['data'])."'");
    $setType = (empty($_GET['type']) ? false : Rahmad($_GET['type']));
    $routes = $Bsk->Select(
        "nas", "id, identity, nasname, username, password, port, description", 
        "identity = '$Menu[identity]' and status = 'true' $radius ".$setData, "id asc"
    );
    $setPPP = ($setType ? array("?service" => $setType) : false);
    $setHSP = ($setType ? ($setType == 1 ? false : array("?address" => $setType)) : false);
    foreach ($routes as $trafic) {
        $ports = ($trafic['port'] ? ":".$trafic['port'] : "");
        if ($Router->connect($trafic['nasname'].$ports, $trafic['username'], $Auth->decrypt($trafic['password'], 'BSK-RAHMAD'))) {
            $hspArr = $Router->comm("/ip/hotspot/active/print", $setHSP);
            $pppArr = $Router->comm("/ppp/active/print", $setPPP);
            foreach ($hspArr as $hspList) {
                $online[] = array(
                    "identity"  => $trafic['identity'],
                    "server"    => $trafic['description'],
                    "users"     => $hspList['user'],
                    "type"      => $hspList['server'],
                    "address"   => $hspList['address'],
                    "id"        => $hspList['.id']
                );
            }
            foreach ($pppArr as $pppList) {
                $online[] = array(
                    "identity"  => $trafic['identity'],
                    "server"    => $trafic['description'],
                    "users"     => $pppList['name'],
                    "type"      => $pppList['service'],
                    "address"   => $pppList['address'],
                    "id"        => $pppList['.id']
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
if(isset($_POST['delete'])){
    $remove = false;
    $getUser = Rahmad($_POST['id']);
    $getType = Rahmad($_POST['type']);
    $getRout = Rahmad($_POST['delete']);
    $raouter = $Bsk->Show(
        "nas", "nasname, username, password, port", 
        "id = '$getRout' and identity = '$Menu[identity]' and status = 'true' ".$radius
    );
    $getPort = ($raouter['port'] ? ":".$raouter['port'] : "");
    $pppArray = array("pppoe", "pptp", "sstp", "l2tp", "ovpn");
    if ($Router->connect($raouter['nasname'].$getPort, $raouter['username'], $Auth->decrypt($raouter['password'], 'BSK-RAHMAD'))) {
        $offline = (in_array($getType, $pppArray) ? '/ppp/active/remove' : '/ip/hotspot/active/remove');
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