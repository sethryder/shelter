<?php

class Network extends API
{
    private $network;

    public function list_regions()
    {
        $params = array();

        $regions = $this->call('Network/Zone/list', $params);

        return $regions;
    }
}