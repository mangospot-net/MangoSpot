<?php
if(isset($_GET['data'])){
    echo json_encode($Config ? 
        array("status" => true, "message" => "success", "data" => $Config) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_POST['save'])){
	unset($_POST['save']);
	$save = $Bsk->Ganti("config", $_POST, "id = '$Menu[identity]' ");
    echo json_encode($save ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
    );
}
if(isset($_POST['command'])){
    if($con = ssh2_connect($_SERVER['SERVER_NAME'], 22)){
        if(ssh2_auth_password($con, "root", "12345")) {
            if($stream = ssh2_exec($con, '/etc/init.d/'.$_POST['command'].' '.$_POST['action'])){
                stream_set_blocking($stream, true );
                $data = "";
                while( $buf = fread($stream, 4096)){
                    $data .= $buf;
                    echo "".$buf;                        
                }
                fclose($stream);
            }
        }
     } 
}
