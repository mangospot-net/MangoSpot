<?php
if(isset($_GET['data'])){
    $explo = (empty($_GET['data']) ? array() : explode(' - ', $_GET['data']));
    $chang = (empty($_GET['data']) ? "" : " and date  BETWEEN '$explo[0]' and '$explo[1]' ");
    $query = $Bsk->Table(
        "resume", 
        "id, date, total, upload, download, usages", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' ".$chang, 
        array("date", "total", "upload", "download", "usages")
    );
    echo json_encode($query, true);
}
if(isset($_GET['detail'])){
    $getID = Rahmad($_GET['detail']);
    $details = $Bsk->Show("income", "date, total, upload, download, (upload + download) as usages, data", "id = '$getID' and identity = '$Menu[identity]'");
    $jsonData = ($details['data'] ? json_decode($details['data'], true) : array());
    $arrayData = array_replace(
        $details, 
        array(
            "upload"    => formatBytes($details['upload']),
            "download"  => formatBytes($details['download']),
            "usages"    => formatBytes($details['usages']),
            "data"      => $jsonData, 
            "date"      => DateFormat($details['date'], 'Y-m-d')
        )
    );
    echo json_encode($details ? 
		array("status" => true, "message" => "success", "data" => $arrayData) : 
		array("status" => false, "message" => "error", "data" => false), true
	); 
}
if(isset($_GET['chart'])){
    $category = array();
    $vouchers = array();
    $upload = array();
    $download = array();
    $series = array();
    $explod = (empty($_GET['chart']) ? array() : explode(' - ', $_GET['chart']));
    $change = (empty($_GET['chart']) ? "" : " and date  BETWEEN '$explod[0]' and '$explod[1]' ");
    $charts = $Bsk->Select(
        "income", "id, date, total, upload, download", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' ".$change, "id asc"
    );
    foreach($charts as $resum){
        $category[] = DateFormat($resum['date'], 'y/m/d');
        $vouchers[] = $resum['total'];
        $upload[] = $resum['upload'];
        $download[] = $resum['download'];
    }
    $series[] = array("name" => "Download",   "data" => $download);
    $series[] = array("name" => "Upload",    "data" => $upload);
    $series[] = array("name" => "Users",    "data" => $vouchers);
    $results = array(
        "categories" => $category,
        "subtitle"   => $_GET['chart'],
        "series"     => $series
    );
    echo json_encode($results ? 
		array("status" => true, "message" => "success", "data" => $results) : 
		array("status" => false, "message" => "error", "data" => false), true
	); 
}
