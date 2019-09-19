<?php
if(isset($_GET['radius'])){
    $radius = $Bsk->Tampil("nas", "count(*) as total", "identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true' ");
    $client = $Bsk->Tampil("radcheck", "count(*) as total", "identity = '$Menu[identity]' and users = '$Menu[id]' and attribute = 'Cleartext-Password'");
    $active = $Bsk->Tampil("active", "count(*) as total", "identity = '$Menu[identity]' and users = '$Menu[id]'");
    $show = array(
        "radius" => $radius['total'],
        "client" => $client['total'],
        "active" => $active['total']
    );
    echo json_encode($show ? 
		array("status" => true, "message" => "success", "data" => $show) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['pie'])){
    $pie = array();
    $lgn = array();
    $nas = $Bsk->View("nas", "nasname, description", "identity = '$Menu[identity]' and users = '$Menu[id]'");
    foreach($nas as $key){
        $sum = $Bsk->Tampil("active", "count(*) as total", "nasname = '$key[nasname]' and identity = '$Menu[identity]' and users = '$Menu[id]'");
        $lgn[] = $key['description'];
        $pie[] = array(
            "name" => $key['description'], 
            "value" => $sum['total']
        );
    }
    $chart = array("legend" => $lgn, "series" => $pie);
    echo json_encode($pie ? 
		array("status" => true, "message" => "success", "data" => $chart) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['income'])){
    $income = array();
    $starts = date('Y-m-d', strtotime('this week'));
    $finish = date('Y-m-d', strtotime('0 weeks ago +1 day'));
    $weeks = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
    foreach($weeks as $week){
        $coming = $Bsk->Tampil("resume", "sum(value) as total", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' and week = '$week' and date BETWEEN '$starts' and '$finish'");
        $income[] = array("name" => $week, "value" => $coming['total']);
    }
    echo json_encode($income ? 
		array("status" => true, "message" => "success", "data" => $income) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['log'])){
    $log = array();
    $result = array();
    $replay = $Bsk->View(
        "replay", "time, username, info, time as date", 
        "identity = '$Menu[identity]' and users = '$Menu[id]'", "time desc", "15"
    );
    $lost = $Bsk->View(
        "lost", "time, username, info, time as date", 
        "identity = '$Menu[identity]' and users = '$Menu[id]'", "time desc", "15"
    );
    foreach ($replay as $valu) {
        $log[] = $valu;
    }
    foreach ($lost as $vals) {
        $log[] = $vals;
    }
    Orders($log, "date");
	foreach ($log as $test) {
		$result[] = $test;
	}
    echo json_encode($log ? 
		array("status" => true, "message" => "success", "data" => $result) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['traffic'])){
    $datarx = array();
    $datatx = array();
    $datapc = array();
    $routes = $Bsk->View(
        "nas", "id, nasname, username, password, port, description", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' and status = 'true'", "id asc"
    );
    foreach ($routes as $trafic) {
        $ports = ($trafic['port'] ? ":".$trafic['port'] : "");
        if ($Router->connect($trafic['nasname'].$ports, $trafic['username'], $Auth->decrypt($trafic['password'], 'BSK-RAHMAD'))) {
            $items = $Router->comm("/system/resource/print");
            $trafx = $Router->comm("/interface/monitor-traffic", array("interface" => "ether1", "once" => ""));
            $x = time() * 1000;
            $datarx[] = (empty($_GET['traffic']) ?
                array("name" => $trafic['description'], "data" => array($x, $trafx[0]['rx-bits-per-second'])) :
                array("name" => $trafic['description'], "data" => array())
            );
            $datatx[] = (empty($_GET['traffic']) ?
                array("name" => $trafic['description'], "data" => array($x, $trafx[0]['tx-bits-per-second'])) :
                array("name" => $trafic['description'], "data" => array())
            );
            $datapc[] = (empty($_GET['traffic']) ?
                array("name" => $trafic['description'], "data" => array($x, $items['0']['cpu-load'])) :
                array("name" => $trafic['description'], "data" => array())
            );
        } 
    }
    $return = array("cpu"=> $datapc, "tx" => $datatx, "rx" => $datarx);
    $Router->disconnect();
    echo json_encode($return ? 
		array("status" => true, "message" => "success", "data" => $return) : 
		array("status" => false, "message" => "error", "data" => false), JSON_NUMERIC_CHECK
    );
}

if(isset($_GET['acme'])){

}
?>
