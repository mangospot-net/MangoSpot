<?php
if(isset($_GET['data'])){
    $explo = (empty($_GET['data']) ? array() : explode(' - ', $_GET['data']));
    $chang = (empty($_GET['data']) ? "" : " and date  BETWEEN '$explo[0]' and '$explo[1]' ");
    $query = DataTable(
        "resume", 
        "id, date, total, value", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' ".$chang, 
        array("date", "total", "value")
    );
    echo json_encode($query, true);
}