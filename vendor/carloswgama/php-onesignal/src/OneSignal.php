<?php
namespace CWG\OneSignal;
use CWG\OneSignal\Notification;
use CWG\OneSignal\Device;
use CWG\OneSignal\CURL;
class OneSignal {
    private $notification;
    private $device;
    public function __construct($appID, $authorizationID) {
        $this->notification = new Notification($appID);
        $this->device = new Device($appID);
    
        $curl = CURL::getInstance();
        $curl->setAuthorization($authorizationID);
    }
    private function setAppID($appID) {
        $this->notification->setAppID($appID);
        $this->device->setAppID($appID);
    }
    public function __get($field) {
        if (isset($this->$field))
            return $this->$field; 
    }
}