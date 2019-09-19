<?php
if(isset($_GET['data'])){
    $query = DataTable(
        "radpostauth a inner join radcheck b on a.username = b.username", 
        "a.id, a.username, a.authdate as date, a.reply", 
        "a.username is not null and identity = '$Menu[identity]' and users = '$Menu[id]'", 
        array("a.id", "a.username", "a.reply", "a.authdate")
    );
        echo json_encode($query, true);
}
if(isset($_POST['delete'])){
    $count = count($_POST['delete']);
    for($i = 0; $i < $count; $i++){
        $Bsk->Hapus("radpostauth", array("id" => Rahmad($_POST['delete'][$i])));
    }
    echo json_encode($count ? 
		array("status" => true, "message" => "success", "data" => "Delete data success") : 
		array("status" => false, "message" => "error", "data" => "Delete data failed!"), true
	);
}
if(isset($_POST['reset'])){
    $reset = $Bsk->Reset("radpostauth", "id");
    echo json_encode($reset ? 
		array("status" => true, "message" => "success", "data" => "Reset data success") : 
		array("status" => false, "message" => "error", "data" => "Reset data failed!"), true
	);
}