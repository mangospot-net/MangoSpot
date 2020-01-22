<?php
date_default_timezone_set('Asia/Jakarta');
function autolinks($text){
    $ret = ' ' . $text;
    $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" target=\"_new\">\\2</a>'", $ret);
    $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" target=\"_new\">\\2</a>'", $ret);
    $ret = preg_replace("#(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\" target=\"_new\">\\2@\\3</a>", $ret);
    $ret = substr($ret, 1);
    return($ret);
}
function Rahmad($str){
    $xss = htmlspecialchars(trim($str));
    return $xss;
}
function Name($name, $lengt){
	$arr_str = explode(' ', $name);
	$arr_str = array_slice($arr_str, 0, $lengt);
	return implode(' ', $arr_str);
}
function CheckIP($ip){
	$explode = explode(".", $ip);
	foreach ($explode as $value){
		 if ($value < 0 || $value > 255)
		 return false;
	}
	return true;
}
function Domain($domain){
    if(preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches)){
        return $matches['domain'];
    } else {
        return $domain;
    }
}

function Subdomain($domain){
    $subdomains = $domain;
    $domain = Domain($subdomains);
    $subdomains = rtrim(strstr($subdomains, $domain, true), '.');
    return $subdomains;
}
function Orders(&$array, $key, $value=null) {
	$sorter=array();
	$ret=array();
	reset($array);
	foreach ($array as $ii => $va) {
		$sorter[$ii]=$va[$key];
	}
	strtolower($value) == 'asc' ? asort($sorter) : arsort($sorter);
	foreach ($sorter as $ii => $va) {
		$ret[$ii]=$array[$ii];
	}
	$array=$ret;
}
function Url($resul) { 
	$resul = preg_replace("/[^a-zA-Z0-9]+/", "-", $resul);
    $resul = str_replace(" ", "-", $resul);
    return strtolower($resul);
}
function Money($number, $type=null){
	$number = number_format($number);
	$number = str_replace(',', '.', $number);
	$number = $type." $number";
	return $number;
}
function Links($result) { 
	$result = strtolower($result);
    $result = preg_replace('/&.+?;/', '', $result);
    $result = preg_replace('/\s+/', '-', $result);
    $result = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '-', $result);
    $result = preg_replace('|-+|', '-', $result);
    $result = preg_replace('/&#?[a-z0-9]+;/i','',$result);
    $result = preg_replace('/[^%A-Za-z0-9 _-]/', '-', $result);
    $result = trim($result, '-');
    return $result;
}
function Tree(array $elements, $parentId = 0) {
	$branch = array();
	foreach ($elements as $element) {
		if ($element['slug'] == $parentId) {
			$children = Tree($elements, $element['id']);
			if ($children) {
				$element['children'] = $children;
			} else {
				$element['children'] = '';
			}
			$branch[] = $element;
		}
	}
	return $branch;
}
function array_group($array, $key) { 
	$temp_array = array(); 
	$i = 0; 
	$key_array = array(); 
	foreach($array as $val) { 
		if (!in_array($val[$key], $key_array)) { 
			$key_array[$i] = $val[$key]; 
			$temp_array[$i] = $val; 
		} 
		$i++; 
	} 
	return $temp_array; 
}
function Curl($url, $type = null, $header = null){
	$curl = curl_init();
	$http = array(CURLOPT_HTTPHEADER => $header);
	$aray = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => ($type ? $type : "GET"),
	);
	curl_setopt_array($curl, ($header ? array_merge($aray, $http) : $aray));
	$responz = curl_exec($curl);
	return $responz;
}
function HTMLReplace($data, $replace){
	$html = $data;
	foreach($replace as $key => $value){
		$html = str_replace('['.$key.']', $value, $html);
	}
	return $html;

}
function DateFormat($date, $format=null){
	return date($format ? $format : 'd-m-Y', strtotime($date));
}
function Timer($data){
	$date = date('d/m/y', strtotime($data));
	$time = date('H:i', strtotime($data));
	return date('d/m/y') == $date ? $time : $date; 
}
function random_str($lengt=null, $key=null){
	switch ($key) {
		case 1:
		$characters = 'abcdefghijklmnopqrstuvwxyz';
		break;
		case 2:
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		break;
		case 3:
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		break;
		case 4:
		$characters = '0123456789';
		break;
		case 5:
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		break;
		case 6:
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		break;
		case 7:
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		break;
		default:
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ~!@#$%^&*()_=+<>?';
		break;
	}
	$length = ($lengt ? $lengt : 3);
   	$charactersLength = strlen($characters);
   	$randomString = '';
   	for ($i = 0; $i < $length; $i++) {
   		$randomString .= $characters[rand(0, $charactersLength - 1)];
   	}
   	return $randomString;
}
function ByteConvert($input){
	if($input){
		preg_match_all('!\d+\.*\d*!', $input, $matches);
		$type = strtolower(preg_replace('!\d+\.*\d*!', '', $input));
		switch ($type) {
		case "tb":
			$output = $matches[0][0]*1024*1024*1024;
			break;
		case "gb":
			$output = $matches[0][0]*1024*1024*1024;
			break;
		case "mb":
			$output = $matches[0][0]*1024*1024;
			break;
		case "kb":
			$output = $matches[0][0]*1024;
			break;
		default:
			$output = $matches[0][0];
			break;
		}
		return ceil($output);
	} else {
		return null;
	}
}
function formatBytes($bytes){
	if($bytes >= 1099511627776){
		$bytes = number_format($bytes / 1099511627776, 2) . 'TB';
	} else if ($bytes >= 1073741824){
		$bytes = number_format($bytes / 1073741824, 2) . 'GB';
	} else if ($bytes >= 1048576){
		$bytes = number_format($bytes / 1048576, 2) . 'MB';
	} else if ($bytes >= 1024){
		$bytes = number_format($bytes / 1024, 2) . 'KB';
	} else if ($bytes > 1){
		$bytes = $bytes . 'B';
	} else if ($bytes == 1){
		$bytes = $bytes . 'B';
	} else {
		$bytes = '';
	}
	return $bytes;
}
function DateTime($data=null){
	$variable = preg_split('#(?<=\d)(?=[a-z])#i', $data);
	$swich = (count($variable) > 1 ? strtolower($variable[1]) : $variable[0]);
	switch ($swich) {
		case 'i':
			$second = ($variable[0] * 60);
			break;
		case 'h':
			$second = ($variable[0] * 3600);
			break;
		case 'd':
			$second = ($variable[0] * 86400);
			break;
		case 'm':
			$second = ($variable[0] * 2592000);
			break;
		case 'y':
			$second = ($variable[0] * 31104000);
			break;
		default:
			$second = $data;
			break;
	}
	return $second;
}
function secTime($data=null){
	$minute= ($data / 60);
	$hour  = ($data / 3600);
	$days  = ($data / 86400);
	$month = ($data / 2592000);
	$years = ($data / 31104000);
	if($data == null) {
		$format = '';
	} else if($minute % 60 <= 0 && $minute < 60){
		$format = $data.'S';
	} else if($minute % 60 > 0 && $hour % 24 <= 0){
		$format = (is_float($minute) ? $data.'S' : $minute.'I');
	} else if($hour % 24 > 0){
		$format = (is_float($hour) ? $data.'S' : $hour.'H');
	} else if($days % 30 > 0){
		$format = (is_float($days) ? $data.'S' : $days.'D');
	} else if($month % 12 > 0){
		$format = (is_float($month) ? $data.'S' : $month.'M');
	} else if($years > 0){
		$format = (is_float($years) ? $data.'S' : $years.'Y');
	}
	return $format;
}