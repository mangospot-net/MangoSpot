<?php
if(isset($_GET['data'])){
    $facility = (empty($_GET['facility']) ? "" : " and sysfacility in (".implode(',', $_GET['facility']).") ");
    $priority = (empty($_GET['priority']) ? "" : " and syspriority in (".implode(',', $_GET['priority']).") ");
    $query = $Bsk->Table(
        "syslog", 
        "id, date, facility, priority, color, syslog, message", 
        "id is not null ".$facility.$priority, 
        array("id", "date", "facility", "priority", "syslog")
    );
    echo json_encode($query, true);
}
if(isset($_GET['detail'])){
    $getIDS = Rahmad($_GET['detail']); 
    $detail = $Bsk->Show("syslog", "*", "id = '$getIDS' ");
    unset($detail['id']);
    unset($detail['color']);
    echo json_encode($detail ? 
        array("status" => true, "message" => "success", "data" => $detail) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_GET['option'])){
    $option = array();
    $getOpt = Rahmad($_GET['option']);
    $syslog = ($getOpt == 'sysfacility' ? "sysfacility" : "sysseverity");
    $result = $Bsk->Select($syslog, "id, name", "id is not null", "id asc");
    foreach ($result as $value) {
        $option[] = $value;
    }
    echo json_encode($option ? 
        array("status" => true, "message" => "success", "data" => $option) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_POST['delete'])){
    $id_delete = Rahmad($_POST['delete']);
    $query_delete = $Bsk->Delete("systemevents", array("id" => Rahmad($_POST['delete'])));
    echo json_encode($query_delete ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
	);
}
if(isset($_POST['reset'])){
    $reset = $Bsk->Reset("systemevents", "id");
    echo json_encode($reset ? 
		array("status" => true, "message" => "success", "data" => "Reset data success") : 
		array("status" => false, "message" => "error", "data" => "Reset data failed!"), true
	);
}