<?php
namespace CWG\OneSignal;
use CWG\OOneSignal\Exception\OneSignalException;
use CWG\OneSignal\Notification;
class Notification {
    private $curl;
    private $appID;
    private $campos = [ ];
    public function __construct($appID) {
        $this->curl = CURL::getInstance();
        $this->appID = $appID;
    }
    public function setSegment($segments) {
        if (!is_array($segments)) $segments = [$segments];
        $this->campos['included_segments'] = $segments;
        return $this;
    }
    public function addSegment($segment) {
        $this->campos['included_segments'][] = $segment;
        return $this;
    }
    public function addDevice($deviceID) {
        $this->campos['include_player_ids'][] = $deviceID;
        return $this;
    }
    public function addTag($campo, $valor, $operator = 'OR') {
        $tag = [
            'field'     => 'tag',
            'key'       => $campo,
            'relation'  => '=',
            'value'     => $valor
        ];
        if (isset($this->campos['filters']) && count($this->campos['filters']) > 0)
            $this->campos['filters'][] = ['operator' => $operator]; 
        $this->campos['filters'][] = $tag;
        return $this;
    }
    public function setBody($contents, $lang = 'id') {
        $this->campos['contents'][$lang] = $contents;
        if (!isset($this->campos['contents']['en'])) 
            $this->campos['contents']['en'] = $contents;
        return $this;
    }
    public function setGroup($group){
        $this->campos['android_group'] = $group;
        if (!isset($this->campos['android_group'])) 
            $this->campos['android_group'] = $group;
        return $this;
    }
    public function setGroups($groups, $lang = 'id') {
        $this->campos['android_group_message'][$lang] = $groups;
        if (!isset($this->campos['android_group_message']['en'])) 
            $this->campos['android_group_message']['en'] = $groups;
        return $this;
    }
    public function setIcon($icon) {
        $this->campos['large_icon'] = $icon;
        if (!isset($this->campos['large_icon'])) 
            $this->campos['large_icon'] = $icon;
        return $this;
    }
    public function setSmall($small) {
        $this->campos['small_icon'] = $small;
        if (!isset($this->campos['small_icon'])) 
            $this->campos['small_icon'] = $small;
        return $this;
    }
    public function setTitle($title, $lang = 'id') {
        $this->campos['headings'][$lang] = $title;
        if (!isset($this->campos['headings']['en']))
            $this->campos['headings']['en'] = $title;
        return $this;
    }
    public function send($campos = null) {
        if ($campos != null) $this->campos = $campos;
        $this->campos['app_id'] = $this->appID;
        if (empty($this->campos['included_segments']) && empty($this->campos['filters']) && empty($this->campos['include_player_ids'])) 
            $this->campos['included_segments'] = ['All'];  
        return $this->curl->post('notifications', $this->campos);
    }
    public function cancel($deviceID) {
        return $this->curl->delete('notifications/' . $notificationID.'?app_id=' . $this->appID);
    }
    private function setAppID($appID) {
        $this->appID = $appID;
    }

}