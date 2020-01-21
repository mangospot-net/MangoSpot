<?php
$radius = ($Menu['data'] ? "and id in('$Menu[data]')" : "and users = '$Menu[id]'");
function Replace($data){
    $resul = str_replace(array('<','>'),'',$data);
    return $resul;
}
if(isset($_GET['data'])){
    $online = array();
    $client = array();
    $setData = (empty($_GET['data']) ? "" : " and id = '".Rahmad($_GET['data'])."'");
    $setType = Rahmad($_GET['type']);
    $routes = $Bsk->Select(
        "nas", "id, identity, nasname, username, password, port, description", 
        "identity = '$Menu[identity]' and status = 'true' $radius ".$setData, "id asc"
    );
    foreach ($routes as $trafic) {
        $ports = ($trafic['port'] ? ":".$trafic['port'] : "");
        if ($Router->connect($trafic['nasname'].$ports, $trafic['username'], $Auth->decrypt($trafic['password'], 'BSK-RAHMAD'))) {
            $IPAddr = $Router->comm("/ip/dhcp-server/print");
            $IPDhcp = $Router->comm("/ip/dhcp-client/print");
            foreach ($IPAddr as $IPList) {
                $online[] = array(
                    "identity"  => $trafic['id'],
                    "router"    => $trafic['description'],
                    "interface" => $IPList['interface'],
                    "name"      => $IPList['name'],
                    "lease"     => $IPList['lease-time'],
                    "pool"      => $IPList['address-pool'],
                    "status"    => $IPList['disabled'],
                    "id"        => $IPList['.id']
                );
            }
            foreach ($IPDhcp as $DHList) {
                $client[] = array(
                    "identity"  => $trafic['id'],
                    "router"    => $trafic['description'],
                    "interface" => $DHList['interface'],
                    "name"      => $DHList['address'],
                    "lease"     => $DHList['use-peer-dns']."/".$DHList['add-default-route'],
                    "pool"      => $DHList['status'],
                    "status"    => $DHList['disabled'],
                    "id"        => $DHList['.id']
                );
            }
        }
    }
    $Router->disconnect();
    $json_data = array(
		"draw"            => 1,
		"recordsTotal"    => count($online),
		"recordsFiltered" => count($online),
        "data"            => $online
    );
    $json_table = array(
		"draw"            => 1,
		"recordsTotal"    => count($client),
		"recordsFiltered" => count($client),
        "data"            => $client
	);
    echo json_encode(($setType == 'server' ? $json_data : $json_table), true);
}
if(isset($_GET['server'])){
    $server = array();
    $querys = $Bsk->Select("nas", "id, description as name", "identity = '$Menu[identity]' ".$radius, "id asc");
    foreach ($querys as $hspLists) {
        $server[] = $hspLists;
    }
    echo json_encode($server ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => $server) : 
        array("status" => false, "message" => "error", "color" => "red", "data" => false), true
    );
}
if(isset($_GET['router'])){
    $route = array();
    $infac = array();
    $mikro = Rahmad($_GET['router']);
    $chang = $Bsk->Show("nas", "nasname, username, password, port", "id = '$mikro' and identity = '$Menu[identity]' ".$radius);
    $port1 = ($chang['port'] ? ":".$chang['port'] : "");
    if ($Router->connect($chang['nasname'].$port1, $chang['username'], $Auth->decrypt($chang['password'], 'BSK-RAHMAD'))) {
        $RoutLust = $Router->comm('/ip/pool/print');
        $Interfac = $Router->comm('/interface/print');
    }
    foreach ($RoutLust as $values) {
        $route[] = array("id" => $values['.id'], "name" => $values['name']);
    }
    foreach ($Interfac as $faces) {
        if(!$faces['dynamic']){
            $infac[] = array("id" => $faces['.id'], "name" => Replace($faces['name']));
        }
    }
    $Router->disconnect();
    $result = array("interface" => $infac, "pool" => $route);
    echo json_encode($route ? 
		array("status" => true, "message" => "success", "data" => $result) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['detail'])){
    $id_detail = explode('*', $_GET['detail']);
    $id_type = Rahmad($_GET['type']);
    $query_detail = $Bsk->Show("nas", "id, nasname, username, password, port", "id = '$id_detail[0]' and identity = '$Menu[identity]' ".$radius);
    $showPort = ($query_detail['port'] ? ":".$query_detail['port'] : "");
    if ($Router->connect($query_detail['nasname'].$showPort, $query_detail['username'], $Auth->decrypt($query_detail['password'], 'BSK-RAHMAD'))) {
        $DHCPServer = $Router->comm('/ip/dhcp-server/print', array("?.id"=> '*'.$id_detail[1]));
        $DHCPClient = $Router->comm('/ip/dhcp-client/print', array("?.id"=> '*'.$id_detail[1]));
    }
    $detail = array(
        "id"        => $query_detail['id'].$DHCPServer[0]['.id'],
        "router"    => $query_detail['id'], 
        "interface" => $DHCPServer[0]['interface'],
        "name"      => $DHCPServer[0]['name'],
        "lease"     => $DHCPServer[0]['lease-time'],
        "pool"      => $DHCPServer[0]['address-pool'],
        "status"    => ($DHCPServer[0]['disabled'] == 'true' ? false: true)
    );
    $show = array(
        "client"    => $query_detail['id'].$DHCPClient[0]['.id'],
        "routers"   => $query_detail['id'], 
        "interfaces"=> $DHCPClient[0]['interface'],
        "dns"       => $DHCPClient[0]['use-peer-dns'] == 'true' ? true : false,
        "ntp"       => $DHCPClient[0]['use-peer-ntp'] == 'true' ? true : false,
        "route"     => $DHCPClient[0]['add-default-route'],
        "comment"   => $DHCPClient[0]['comment'],
        "active"    => ($DHCPClient[0]['disabled'] == 'true' ? false: true)
    );
    $Router->disconnect();
    echo json_encode($query_detail ? 
		array("status" => true, "message" => "success", "data" => ($id_type == 'server' ? $detail : $show)) : 
		array("status" => false, "message" => "error", "data" => false), true
	);
}
if(isset($_GET['dns'])){
    $dns_router = Rahmad($_GET['dns']);
    $dns_detail = $Bsk->Show("nas", "id, nasname, username, password, port", "id = '$dns_router' and identity = '$Menu[identity]' ".$radius);
    $dns_port = ($dns_detail['port'] ? ":".$dns_detail['port'] : "");
    if ($Router->connect($dns_detail['nasname'].$dns_port, $dns_detail['username'], $Auth->decrypt($dns_detail['password'], 'BSK-RAHMAD'))) {
        $DNSServer = $Router->comm('/ip/dns/print');
    }
    $dns_server = array(
        "id_dns"        => $dns_detail['id'],
        "servers"       => $DNSServer[0]['servers'],
        "remote"        => $DNSServer[0]['allow-remote-requests'] == 'true' ? true : false,
        "udp"           => $DNSServer[0]['max-udp-packet-size'],
        "query-server"  => $DNSServer[0]['query-server-timeout'],
        "query-total"   => $DNSServer[0]['query-total-timeout'],
        "concurrent"    => $DNSServer[0]['max-concurrent-queries'],
        "concurrent-tcp"=> $DNSServer[0]['max-concurrent-tcp-sessions'],
        "cache-ttl"     => $DNSServer[0]['cache-max-ttl']
    );
    $Router->disconnect();
    echo json_encode($dns_server ? 
        array("status" => true, "message" => "success", "data" => $dns_server) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_POST['name'])){
    $id_route = explode('*', $_POST['id']);
    $ps_unset = array('id', 'router', 'status');
    $disabled = (isset($_POST['status']) ? 'no' : 'yes'); 
    $id_posts = ($id_route[1] ? $id_route[0] : $_POST['router']);
    foreach ($ps_unset as $key) {
        unset($_POST[$key]);
    }
    $ip_route = $Bsk->Show("nas", "id, nasname, username, password, port", "id = '$id_posts' and identity = '$Menu[identity]' ".$radius);
    $ip_ports = ($ip_route['port'] ? ":".$ip_route['port'] : "");
    if ($Router->connect($ip_route['nasname'].$ip_ports, $ip_route['username'], $Auth->decrypt($ip_route['password'], 'BSK-RAHMAD'))) {
        $ip_query = array_merge($_POST, array('disabled' => $disabled));
        if($id_route[1]){
            $post = $Router->comm('/ip/dhcp-server/set', array_merge($ip_query, array(".id" => "*".$id_route[1])));
        } else {
            $post = $Router->comm("/ip/dhcp-server/add", $ip_query);
        }
    }
    $Router->disconnect();
    echo json_encode($ip_route ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success.") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
    );
}
if(isset($_POST['client'])){
    $id_routes = explode('*', $_POST['client']);
    $ps_unsets = array('client', 'router', 'active', 'dns', 'ntp');
    $disableds = (isset($_POST['active']) ? 'no' : 'yes'); 
    $dhcp_dns = (isset($_POST['dns']) ? 'yes' : 'no'); 
    $dhcp_ntp = (isset($_POST['ntp']) ? 'yes' : 'no'); 
    $id_client = ($id_routes[1] ? $id_routes[0] : $_POST['router']);
    foreach ($ps_unsets as $keys) {
        unset($_POST[$keys]);
    }
    $ip_routes = $Bsk->Show("nas", "id, nasname, username, password, port", "id = '$id_client' and identity = '$Menu[identity]' ".$radius);
    $ip_client = ($ip_routes['port'] ? ":".$ip_routes['port'] : "");
    if ($Router->connect($ip_routes['nasname'].$ip_client, $ip_routes['username'], $Auth->decrypt($ip_routes['password'], 'BSK-RAHMAD'))) {
        $cl_query = array_merge($_POST, 
            array(
                'use-peer-dns' => $dhcp_dns,
                'use-peer-ntp' => $dhcp_ntp,
                'disabled'     => $disableds
            )
        );
        if($id_routes[1]){
            $posts = $Router->comm('/ip/dhcp-client/set', array_merge($cl_query, array(".id" => "*".$id_routes[1])));
        } else {
            $posts = $Router->comm("/ip/dhcp-client/add", $cl_query);
        }
    }
    $Router->disconnect();
    echo json_encode($ip_routes ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success.") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
    );
}
if(isset($_POST['dns-server'])){
    $id_dns = Rahmad($_POST['dns-server']);
    $hd_dns = array('dns-server', 'remote');
    $remote = (isset($_POST['remote']) ? 'yes' : 'no'); 
    foreach ($hd_dns as $hide) {
        unset($_POST[$hide]);
    }
    $ip_dns = $Bsk->Show("nas", "id, nasname, username, password, port", "id = '$id_dns' and identity = '$Menu[identity]' ".$radius);
    $po_dns = ($ip_dns['port'] ? ":".$ip_dns['port'] : "");
    if ($Router->connect($ip_dns['nasname'].$po_dns, $ip_dns['username'], $Auth->decrypt($ip_dns['password'], 'BSK-RAHMAD'))) {
        $Router->comm('/ip/dns/set', array_merge($_POST, array("allow-remote-requests" => $remote)));
    }
    $Router->disconnect();
    echo json_encode($ip_dns ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Proccess data success.") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Proccess data failed!"), true
    );
}
if(isset($_POST['active'])){
    $id_active = explode('*', $_POST['active']);
    $tp_active = ($id_active[2] == 'server' ? 'dhcp-server' : 'dhcp-client');
    $check_active = $Bsk->Show("nas", "id, nasname, username, password, port", "id = '$id_active[0]' and identity = '$Menu[identity]' ".$radius);
    $stausPort = ($check_active['port'] ? ":".$check_active['port'] : "");
    if ($Router->connect($check_active['nasname'].$stausPort, $check_active['username'], $Auth->decrypt($check_active['password'], 'BSK-RAHMAD'))) {
        $prints = $Router->comm('/ip/'.$tp_active.'/print', array("?.id"=> '*'.$id_active[1]));
        $status = ($prints[0]['disabled'] == 'true' ? 'enable' : 'disable');
        $Router->write('/ip/'.$tp_active.'/'.$status, false);
        $query_active = $Router->write('=.id=*'.$id_active[1], true);
        $Router->read();
    }
    $Router->disconnect();
    echo json_encode($query_active ? 
		array("status" => true, "message" => "success", "color" => "green", "data" => "Active data success") : 
		array("status" => false, "message" => "error", "color" => "red", "data" => "Active data failed!"), true
	);
}
if(isset($_POST['delete'])){
    $remove = false;
    $getRout = explode('*', $_POST['delete']);
    $tp_rmv = ($getRout[2] == 'server' ? 'dhcp-server' : 'dhcp-client');
    $raouter = $Bsk->Show(
        "nas", "nasname, username, password, port", 
        "id = '$getRout[0]' and identity = '$Menu[identity]' and status = 'true' ".$radius
    );
    $getPort = ($raouter['port'] ? ":".$raouter['port'] : "");
    if ($Router->connect($raouter['nasname'].$getPort, $raouter['username'], $Auth->decrypt($raouter['password'], 'BSK-RAHMAD'))) {
        $Router->write('/ip/'.$tp_rmv.'/remove', false);
        $remove = $Router->write('=.id=*'.$getRout[1], true);
        $Router->read();
    }
    $Router->disconnect();
    echo json_encode($remove ? 
        array("status" => true, "message" => "success", "color" => "green", "data" => "Delete data success") : 
        array("status" => false, "message" => "error", "color" => "red", "data" => "Delete data failed!"), true
    );
}