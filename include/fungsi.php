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
class SimpleImage {
   var $image;
   var $image_type;
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }       
}
function Nama($kalimat, $kata){
	$arr_str = explode(' ', $kalimat);
	$arr_str = array_slice($arr_str, 0, $kata );
	return implode(' ', $arr_str);
}
function Domain($kata){
	$b = explode("/", $kata);
	$e = array(".com", ".net", ".id", ".gl");
	$c = str_replace($e, "", ($b[2]));
	$d = str_replace(".com", "", ($c));
	return $d;
}
function Jadwal($data, $dates=null){
	$date = ($dates ? $dates : date('Y-m-d'));
	$week = date('w', strtotime($date));
	$days = date('w', strtotime($data));
	$retr = date('Y-m-d', strtotime(($data - $week).' day', strtotime($date)));
	return date('Y-m-d', strtotime($days.' day', strtotime($retr)));
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
function Rp($angka){
	$angka = number_format($angka);
	$angka = str_replace(',', '.', $angka);
	$angka ="$angka";
	return $angka;
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
function Romawi($n){
	$hasil = "";
	$iromawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X",20=>"XX",30=>"XXX",40=>"XL",50=>"L",
	60=>"LX",70=>"LXX",80=>"LXXX",90=>"XC",100=>"C",200=>"CC",300=>"CCC",400=>"CD",500=>"D",600=>"DC",700=>"DCC",
	800=>"DCCC",900=>"CM",1000=>"M",2000=>"MM",3000=>"MMM");
	if(array_key_exists($n,$iromawi)){
		$hasil = $iromawi[$n];
	} else if($n >= 11 && $n <= 99){
		$i = $n % 10;
		$hasil = $iromawi[$n-$i] . Romawi($n % 10);
	} else if($n >= 101 && $n <= 999){
		$i = $n % 100;
		$hasil = $iromawi[$n-$i] . Romawi($n % 100);
	} else {
		$i = $n % 1000;
		$hasil = $iromawi[$n-$i] . Romawi($n % 1000);
	}
	return $hasil;
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
function SendMail($config, $data, $html){
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug = 0;
	$mail->isHTML(true);
	$mail->SMTPAuth = true;
	$mail->Host = $config['host'];
	$mail->From = $config['email'];
	$mail->Port = $config['port'];
	$mail->AddAddress($data['email']);
	$mail->Username = $config['email'];
	$mail->Password = $config['pswd'];
	$mail->SetFrom($config['email'], $config['name']);
	$mail->addReplyTo($config['email'], $config['name']);
	$mail->Subject = $data['data'];
	$mail->msgHTML($html);
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	$mail->isHTML(true);	
	return ($mail->Send()? true : false);
}
function DataTable($table, $from, $where, $columns, $group = null){
	$Bsk = new Basuki();
	$requestData = $_REQUEST;
	$totalData = $Bsk->Tampil($table, "count(*) as total", $where);
	$totalFiltered = $totalData['total'];
	$totalColumn = count($requestData['columns']);
	$sql = $where;
	$search = array();
	if(!empty($requestData['search']['value'])){
		foreach($columns as $colums){
			$search[] ="lower($colums::text) LIKE '%".strtolower($requestData['search']['value'])."%' ";
		}
		$sql.= " AND (".implode(' OR ', $search).")";
	}
	for($i=0; $i<$totalColumn; $i++){
		if( !empty($requestData['columns'][$i]['search']['value']) ){ 
			$sql.= " AND $columns[$i] = '".$requestData['columns'][$i]['search']['value']."' ";
		}
	}
	if($group != null){
		$sql.= " GROUP BY ".$group;
	}
	$query = $Bsk->Tampil($table, "count(*) as total", $sql);
	$totalFiltered = $query['total'];
	if(!empty($requestData['order'][0]['column'])){
		$order = (array_key_exists($requestData['order'][0]['column'], $columns) ? $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'] : "");
	} else {
		$order = (!empty($requestData['order']) ? $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'] : "");
	}
	$limit = ($requestData['length'] != '-1' ? $requestData['length']." OFFSET ".$requestData['start']." " : "");
	$users = $Bsk->View($table, $from, $sql, $order, $limit);
	$data = array();
	foreach($users as $row) { 
		$data[] = $row;
	}
	$json_data = array(
		"draw"            => intval((!empty($requestData['draw']) ? $requestData['draw'] : 0) ),
		"recordsTotal"    => intval( $totalData['total'] ),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);
	return $json_data;
}
function Waktu($timestamp){
	$selisih = time() - strtotime($timestamp) ;
    $detik = $selisih ;
    $menit = round($selisih / 60 );
    $jam = round($selisih / 3600 );
    $hari = round($selisih / 86400 );
    $minggu = round($selisih / 604800 );
    $bulan = round($selisih / 2419200 );
    $tahun = round($selisih / 29030400 );
    if ($detik <= 60) {
        $waktu = 'Baru saja';
    } else if ($menit <= 60) {
        $waktu = $menit.' mnt yang lalu';
    } else if ($jam <= 24) {
        $waktu = $jam.' jam yang lalu';
    } else if ($hari <= 7) {
        $waktu = $hari.' hari yang lalu';
    } else if ($minggu <= 4) {
        $waktu = $minggu.' minggu yang lalu';
    } else if ($bulan <= 12) {
        $waktu = $bulan.' bln yang lalu';
    } else {
        $waktu = $tahun.' thn yang lalu';
    }
    return $waktu;
}
function DateFormat($date, $format=null){
	return date($format ? $format : 'd-m-Y', strtotime($date));
}
function Tanggal($date=null, $format=null){
	$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
	$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus',
	'September','Oktober', 'November','Desember');
	if($date == null) {
		$hari = $array_hari[date('N')];
		$tanggal = date ('j');
		$bulan = $array_bulan[date('n')];
		$month = date('m');
		$tahun = date('Y');
	} else {
		$date = strtotime($date);
		$hari = $array_hari[date('N',$date)];
		$tanggal = date ('j', $date);
		$bulan = $array_bulan[date('n',$date)];
		$month = date('m',$date);
		$tahun = date('Y',$date);
	}
	$formatTanggal = $hari.", ".$tanggal."-".($format ? $bulan : $month)."-".$tahun;
	return $formatTanggal;
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
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		break;
		case 5:
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		break;
		case 6:
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
		preg_match('/(\d+)(\w+)/', $input, $matches);
		$type = strtolower($matches[2]);
		switch ($type) {
		case "b":
			$output = $matches[1];
			break;
		case "kb":
			$output = $matches[1]*1024;
			break;
		case "mb":
			$output = $matches[1]*1024*1024;
			break;
		case "gb":
			$output = $matches[1]*1024*1024*1024;
			break;
		case "tb":
			$output = $matches[1]*1024*1024*1024;
			break;
		}
		return $output;
	} else {
		return null;
	}
}
function formatBytes($bytes){
	if ($bytes >= 1073741824){
		$bytes = number_format($bytes / 1073741824) . 'GB';
	} else if ($bytes >= 1048576){
		$bytes = number_format($bytes / 1048576) . 'MB';
	} else if ($bytes >= 1024){
		$bytes = number_format($bytes / 1024) . 'KB';
	} else if ($bytes > 1)
	{
		$bytes = $bytes . 'B';
	} else if ($bytes == 1){
		$bytes = $bytes . 'B';
	} else {
		$bytes = '';
	}
	return $bytes;
}
function DateTime($data){
	$variable = preg_split('#(?<=\d)(?=[a-z])#i', $data);
	switch (strtolower($variable[1])) {
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
		default:
			$second = $data;
			break;
	}
	return $second;
}
if(!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'excel.php');
