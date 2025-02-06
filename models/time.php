<?php

class TimeModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function saveServerTime($data) {
        $this->db->query('INSERT INTO server_time (server_time, server_year, server_month, server_day, server_hour, server_minute, current_season, weather, temperature) VALUES (:server_time, :server_year, :server_month, :server_day, :server_hour, :server_minute, :current_season, :weather, :temperature)');
        $this->db->bind(':server_time', $data['serverTime']);
        $this->db->bind(':server_year', $data['serverYear']);
        $this->db->bind(':server_month', $data['serverMonth']);
        $this->db->bind(':server_day', $data['serverDay']);
        $this->db->bind(':server_hour', $data['serverHour']);
        $this->db->bind(':server_minute', $data['serverMinute']);
        $this->db->bind(':current_season', $data['currentSeason']);
        $this->db->bind(':weather', $data['weather']);
        $this->db->bind(':temperature', $data['temperature']);

        return $this->db->execute();
    }
}