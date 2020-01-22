<?php
if(isset($_GET['data'])){
    $signal = array();
    $select = (empty($_GET['data']) ? "" : " and id = '".Rahmad($_GET['data'])."'");
    $routes = $Bsk->Select(
        "nas", "id, nasname, username, password, port, description", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true' ".$select, "id asc"
    );
    foreach ($routes as $trafic) {
        $ports = ($trafic['port'] ? ":".$trafic['port'] : "");
        if ($Router->connect($trafic['nasname'].$ports, $trafic['username'], $Auth->decrypt($trafic['password'], 'BSK-RAHMAD'))) {
            $items = $Router->comm("/interface/wireless/registration-table/print");
            foreach ($items as $value) {
                $signal[] = array(
                    "router"=> $trafic['description'],
                    "radio" => $value['radio-name'],
                    "mac"   => $value['mac-address'],
                    "signal"=> $value['signal-strength'],
                    "rx"    => $value['rx-rate'],
                    "tx"    => $value['tx-rate']
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
if(isset($_GET['router'])){
    $route = array();
    $query = $Bsk->Select(
        "nas", "id, description as name", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true'", "id asc"
    );
    foreach ($query as $key) {
        $route[] = $key;
    }
    echo json_encode($route ? 
		array("status" => true, "message" => "success", "data" => $route) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}