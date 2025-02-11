<?php

require_once ROOT . 'utils/server_time.php';

class Times extends Controller {
    

    public function index() {
        $serverTimeData = getServerTime();
        echo json_encode($serverTimeData);
    }

    public function serverTimeBox() {
        $this->view('components/server_time_box');
    }
}