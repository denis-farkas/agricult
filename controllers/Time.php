<?php

require_once ROOT . 'utils/server_time.php';

class Time extends Controller {
    private $timeModel;

    public function __construct() {
        $this->timeModel = $this->model('time');
    }

    public function index() {
        $serverTimeData = getServerTime();
        echo json_encode($serverTimeData);
    }

}