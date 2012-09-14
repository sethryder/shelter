<?php

require 'main.php';

API::config('https://api.stormondemand.com/1.0/', 'username', 'password', 'base-domain.com');

if (!isset($argv[1]))
{
    CLI::clear_screen();
    new Top_Menu;
}