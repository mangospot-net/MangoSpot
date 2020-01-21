<?php
if(isset($_GET['data'])){
    $explo = (empty($_GET['data']) ? array() : explode(' - ', $_GET['data']));
    $chang = (empty($_GET['data']) ? "" : " and date  BETWEEN '$explo[0]' and '$explo[1]' ");
    $query = $Bsk->Table(
        "resume", 
        "id, date, total, price as value, discount, income price", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' ".$chang, 
        array("date", "total", "value", "discount", "price")
    );
    echo json_encode($query, true);
}
if(isset($_GET['detail'])){
    $getID = Rahmad($_GET['detail']);
    $details = $Bsk->Show("income", "date, total, price, discount, income, data", "id = '$getID' and identity = '$Menu[identity]'");
    $jsonData = ($details['data'] ? json_decode($details['data'], true) : array());
    $arrayData = array_replace(
        $details, 
        array(
            "discount"  => Money($details['discount'], $Config['currency']),
            "income"    => Money($details['income'], $Config['currency']),
            "price"     => Money($details['price'], $Config['currency']),
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
    $discount = array();
    $payments = array();
    $income = array();
    $series = array();
    $explod = (empty($_GET['chart']) ? array() : explode(' - ', $_GET['chart']));
    $change = (empty($_GET['chart']) ? "" : " and date  BETWEEN '$explod[0]' and '$explod[1]' ");
    $charts = $Bsk->Select(
        "resume", "id, date, total, price, discount, income", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' ".$change, "id asc"
    );
    foreach($charts as $resum){
        $category[] = DateFormat($resum['date'], 'y/m/d');
        $vouchers[] = $resum['total'];
        $payments[] = $resum['price'];
        $discount[] = $resum['discount'];
        $income[] = $resum['income'];
    }
    $series[] = array("name" => "Price",    "data" => $payments);
    $series[] = array("name" => "Income",   "data" => $income);
    $series[] = array("name" => "Discount", "data" => $discount);
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