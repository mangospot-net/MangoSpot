<?php
if(isset($_GET['data'])){
    $explo = (empty($_GET['data']) ? array() : explode(' - ', $_GET['data']));
    $chang = (empty($_GET['data']) ? "" : " and to_char(date, 'YYYY-MM-DD')  BETWEEN '$explo[0]' AND '$explo[1]' ");
    $query = DataTable(
        "income", 
        "id, to_char(date, 'YYYY-MM-DD') as time, total, value", 
        "identity = '$Menu[identity]' and users = '$Menu[id]' ".$chang, 
        array("to_char(date, 'YYYY-MM-DD')", "total", "value")
    );
    echo json_encode($query, true);
}