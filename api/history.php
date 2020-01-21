<?php
if(isset($_GET['data'])){
    $query = $Bsk->Table(
        "radpostauth a inner join radcheck b on a.username = b.username left join radusergroup c on a.username = c.username", 
        "a.id, a.username, c.groupname as profile, a.authdate as date, a.reply", 
        "a.username is not null and b.identity = '$Menu[identity]' and b.users = '$Menu[id]'", 
        array("a.id", "a.username", "c.groupname", "a.reply", "a.authdate")
    );
        echo json_encode($query, true);
}
if(isset($_POST['delete'])){
    $deleteID = $Bsk->Delete("radpostauth", array("id" => Rahmad($_POST['delete'])));
    echo json_encode($deleteID ? 
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