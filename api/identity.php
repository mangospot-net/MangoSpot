<?php
if(isset($_POST['data']) or isset($_POST['lat']) or isset($_POST['icon']) or isset($_POST['logo'])){
	$updat = $Bsk->Update("identity", $_POST, "id = '$Menu[identity]'");
    echo json_encode($updat ? 
        array("status" => true, "message" => "success", "data" => "Data berhasil diproses") : 
        array("status" => false, "message" => "error", "data" => "Data gagal diproses"), true
    );
}
if(isset($_POST['add'])){
    echo json_encode($_POST ? 
        array("status" => true, "message" => "success", "data" => $_POST) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_GET['cover'])){
    $lists = $Bsk->Show("identity", "cover", "id = '$Menu[identity]'");
    $encod = json_decode($lists['cover'], true);
    echo json_encode($encod ? 
        array("status" => true, "message" => "success", "data" => $encod) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_POST['cover'])){
    $cover = array();
    $count = count($_POST['cover']);
    for($i=0; $i<$count; $i++){
        $cover[] = array("title" => $_POST['title'][$i], "info" => $_POST['info'][$i], "image" => $_POST['cover'][$i]);
    }
    $jsons = json_encode($cover, true);
    $chang = $Bsk->Change("identity", array("cover" => $jsons), "id = '$Menu[identity]'");
    echo json_encode($chang ? 
        array("status" => true, "message" => "success", "data" => "Data berhasil diproses") : 
        array("status" => false, "message" => "error", "data" => "Data gagal diproses"), true
    );
}