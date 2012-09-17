<?php

require 'main.php';

API::config('https://api.stormondemand.com/1.0/', 'username', 'password', 'base-domain.com');

if (!isset($argv[1]))
{
    \cli\clear();
    new Top_Menu;
}