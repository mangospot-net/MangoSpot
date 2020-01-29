<?php
$rad = ($Menu['data'] ? "and id in($Menu[data])" : "and users = '$Menu[id]'");
if(isset($_GET['radius'])){
    $radius = $Bsk->Show("nas", "count(*) as total", "identity = '$Menu[identity]' and status = 'true' ".$rad);
    $client = $Bsk->Show("radcheck", "count(*) as total", "identity = '$Menu[identity]' and users = '$Menu[id]' and attribute = 'Cleartext-Password'");
    $active = $Bsk->Show("active", "count(*) as total", "identity = '$Menu[identity]' and users = '$Menu[id]'");
    $voucer = $Bsk->Show("packet", "sum(voucher) as total", "identity = '$Menu[identity]' and client = '$Menu[id]'");
    $show = array(
        "radius" => $radius['total'],
        "client" => $client['total'],
        "active" => $active['total'],
        "voucher"=> $voucer ? $voucer['total'] : 0,
    );
    echo json_encode($show ? 
		array("status" => true, "message" => "success", "data" => $show) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['pie'])){
    $pie = array();
    $lgn = array();
    $nas = $Bsk->Select("nas", "nasname, description", "identity = '$Menu[identity]' ".$rad);
    foreach($nas as $key){
        $sum = $Bsk->Show("active", "count(*) as total", "nasname = '$key[nasname]' and identity = '$Menu[identity]' and users = '$Menu[id]'");
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
        $coming = $Bsk->Show("resume", "sum(price) as total", 
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
    $replay = $Bsk->Select(
        "replay", "time, username, info, time as date", 
        "identity = '$Menu[identity]' and users = '$Menu[id]'", "time desc", "15"
    );
    $lost = $Bsk->Select(
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
    $routes = $Bsk->Select(
        "nas", "id, nasname, username, password, port, description", 
        "identity = '$Menu[identity]' and status = 'true' ".$rad, "id asc"
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
if(isset($_GET['server'])){
    $loadServer = array(
        "cpu"   => Monitor::getCpu(),
        "ram"   => Monitor::getMemory(),
        "disk"  => Monitor::getDisk(),
        "temp"  => Monitor::getTemp()
    );
    echo json_encode($loadServer ? 
        array("status" => true, "message" => "success", "data" => $loadServer) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if (isset($_SERVER['REMOTE_USER']) ){
    $user_auth = $_SERVER['REMOTE_USER'];
} else if (isset($_SERVER['PHP_AUTH_USER']) ){
    $user_auth = $_SERVER['PHP_AUTH_USER'];
} 
else if(isset($Header['user'])){
    $user_auth = $Header['user'];
} else {
    $user_auth = false;
}
if(isset($_SERVER['PHP_AUTH_PW'])){
    $pswd_auth = $_SERVER['PHP_AUTH_PW'];
} 
else if(isset($Header['password'])){
    $pswd_auth = $Header['password'];
} else {
    $pswd_auth = false;
}
$pass_auth = $Auth->encrypt(md5($pswd_auth), 'BSK-RAHMAD');
if(isset($_POST['autoremove']) || isset($_GET['autoremove'])){
    $input_sql = array();
    $data_auth = $Bsk->Show("users", "id, identity, name", "(phone = '$user_auth' or email = '$user_auth' or username = '$user_auth') and pswd = '$pass_auth' and status = 'true'");
    $autoRecap = $Bsk->Show(
        "expired", "identity, users, count(*) AS total, sum(price) as price, sum(discount) as discount, sum(total) as income, sum(upload) as upload, sum(download) as download, now() as date",
        "identity = '$data_auth[identity]' and users = '$data_auth[id]' group by identity, users"
    );
    $saved_sql = $Bsk->Select(
        "expired", "username, profile, time, usages, quota, price, discount, total",
        "identity = '$data_auth[identity]' and users = '$data_auth[id]'", "time asc"
    );
    foreach($saved_sql as $value_sql){
        $input_sql[] = $value_sql;
    }
    $Bsk->Insert("income", array_merge($autoRecap, array("data" => json_encode($input_sql, true))));
    $autoRemove = $Bsk->Select("expired", "username", "identity = '$autoRecap[identity]' and users = '$autoRecap[users]'");
    foreach ($autoRemove as $deleteKey) {
        $Bsk->Delete("radacct",     array("username" => $deleteKey['username']));
        $Bsk->Delete("radpostauth", array("username" => $deleteKey['username']));
        $Bsk->Delete("radcheck",    array("username" => $deleteKey['username'], "identity" => $autoRecap['identity'], "users" => $autoRecap['users']));
        $Bsk->Delete("radreply",    array("username" => $deleteKey['username'], "identity" => $autoRecap['identity'], "users" => $autoRecap['users']));
        $Bsk->Delete("radusergroup",array("username" => $deleteKey['username'], "identity" => $autoRecap['identity'], "users" => $autoRecap['users']));
    }
    unset($autoRecap['users']);
    unset($autoRecap['identity']);
    echo json_encode($autoRecap ? 
        array("status" => true, "message" => "success", "data" => $autoRecap) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
} 
?>
