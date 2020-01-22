<?php
if(isset($_GET['identity'])){
    $unlink = array("id", "register", "expayed", "token");
    foreach ($unlink as $key) {
        unset($Identity[$key]);
    }
    echo json_encode($Identity ?
        array("status" => true, "message" => "success", "color" => "green","data" => $Identity) :
        array("status" => false, "message" => "error", "color" => "green","data" => false), true
    );
}
if(isset($_GET['cover'])){
    $encode = json_decode($Identity['cover'], true);
    echo json_encode($encode ? 
        array("status" => true, "message" => "success", "data" => $encode) : 
        array("status" => false, "message" => "error", "data" => false), true
    );
}
if(isset($_GET['config'])){
    $unset = array("id", "host", "name", "email", "pswd", "smtp", "port");
    foreach ($unset as $keys) {
        unset($Config[$keys]);
    }
    echo json_encode($Config ?
        array(
            "status"	=> true, 
            "message"	=> "success", 
            "color"		=> "green",
            "data"		=> $Config
        ) :
        array(
            "status"	=> false, 
            "message"	=> "error", 
            "color"		=> "green",
            "data"		=> false
        ), true
    );
}
if(isset($_GET['menu'])){
    $parent_menu = $Bsk->Select("menu", "slug", "id in ($Menu[value]) group by slug", "slug asc");
    $array_menu = array(); 
    foreach($parent_menu as $slug_menu){ 
        $array_menu[] = $slug_menu['slug']; 
    } 
    $implode_menu = implode(',', $array_menu);
    $query_menu = $Bsk->Select(
        "menu","id, name, value, icon, slug",
        "id in ($implode_menu,$Menu[value]) and status = 'true'",
        "number asc"
    );
    $tree_menu = array();
    foreach($query_menu as $link_menu){
        $tree_menu[] = $link_menu;
    }
    $tree = Tree($tree_menu);
    echo json_encode($tree ?
        array(
            "status"	=> true, 
            "message"	=> "success", 
            "color"		=> "green",
            "data"		=> $tree
        ) :
        array(
            "status"	=> false, 
            "message"	=> "error", 
            "color"		=> "green",
            "data"		=> false
        ), true
    );
}
if(isset($_GET['accept'])){
    $menu_access = array();
    $query_access = $Bsk->Select("menu", "value", "id in ($Menu[value])");
    foreach ($query_access as $show_access) {
        $menu_access[] = ucwords($show_access['value']);
    }
    echo json_encode($menu_access ?
        array(
            "status"	=> true, 
            "message"	=> "success", 
            "color"		=> "green",
            "data"		=> array_merge($menu_access, array('Profile', ''))
        ) :
        array(
            "status"	=> false, 
            "message"	=> "error", 
            "color"		=> "green",
            "data"		=> array('Login', 'Forgot', 'Register')
        ), true
    );
}