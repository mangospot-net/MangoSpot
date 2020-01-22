<?php
if(isset($_GET['data'])){
    echo json_encode($Config ? 
        array("status" => true, "message" => "success", "data" => $Config) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_GET['theme'])){
    $getId = Rahmad($_GET['theme']);
    $theme = $Bsk->Show("themes", "content", "type = '$getId' and identity = '$Menu[identity]' ");
    echo json_encode($theme ? 
        array("status" => true, "message" => "success", "data" => $theme) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_GET['docs'])){
    $document = array();
    $docs = $Bsk->Select("type", "name, info", "type = 'mail' and status = 'true'", "id asc");
    foreach ($docs as $vals) {
        $document[] = $vals;
    }
    echo json_encode($document ? 
        array("status" => true, "message" => "success", "data" => $document) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_POST['content'])){
    $id_post = Rahmad($_POST['type']);
    $check_post = $Bsk->Show("themes", "id", "type = '$id_post' and identity = '$Menu[identity]' ");
    $query_post = ($check_post ? 
        $Bsk->Change("themes", $_POST, "id = '$check_post[id]' and identity = '$Menu[identity]' ") : 
        $Bsk->Insert("themes", 
            array_merge(
                $_POST, 
                array(
                    "name"      => ucwords($id_post), 
                    "users"     => 0,
                    "identity"  => $Menu['identity']
                )
            )
        )
    );
    echo json_encode($query_post ? 
		  array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
		  array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
	);
}
if(isset($_POST['save'])){
	unset($_POST['save']);
	$save = $Bsk->Change("config", $_POST, "id = '$Menu[identity]' ");
    echo json_encode($save ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
    );
}
if(isset($_GET['service'])){
    $service = array();
    $cdir = scandir('/etc/init.d/');
    foreach ($cdir as $key => $value){
        if(!in_array($value,array(".",".."))){
            if(is_dir($key . DIRECTORY_SEPARATOR . $value)){
                $service[$value] = dirToArray($key . DIRECTORY_SEPARATOR . $value);
            } else {
                $service[] = $value;
            }
        }
    }
    echo json_encode($service ? 
        array("status" => true, "message" => "success", "data" => $service) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_POST['command'])){
    $SSH = new SSH2();
    if($SSH->auth()){
        $SSH->exec('/etc/init.d/'.$_POST['command'].' '.$_POST['action']);
        $result = $SSH->output();
    } else {
        $result = false;
    }
    echo ($result ? $result : "Failed connect to ssh!");
}
if(isset($_POST['check'])){
    try {
        if (!$SMTP->connect($_POST['host'], $_POST['port'])) {
            throw new Exception('Connect failed');
        }
        if (!$SMTP->hello(gethostname())) {
            throw new Exception('EHLO failed: ' . $SMTP->getError()['error']);
        }
        $e = $SMTP->getServerExtList();
        if (is_array($e) && array_key_exists('STARTTLS', $e)) {
            $tlsok = $SMTP->startTLS();
            if (!$tlsok) {
                throw new Exception('Failed to start encryption: ' . $SMTP->getError()['error']);
            }
            if (!$SMTP->hello(gethostname())) {
                throw new Exception('EHLO (2) failed: ' . $SMTP->getError()['error']);
            }
            $e = $SMTP->getServerExtList();
        }
        if (is_array($e) && array_key_exists('AUTH', $e)) {
            if ($SMTP->authenticate($_POST['email'], $_POST['pswd'])) {
                echo 'Connected ok!';
            } else {
                throw new Exception('Authentication failed: ' . $SMTP->getError()['error']);
            }
        }
    } catch (Exception $e) {
        echo 'SMTP error: ' . $e->getMessage(), "\n";
    }
    $SMTP->quit();
}