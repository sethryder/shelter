<?php

class CLI
{
    public static function output_table($headers, $data)
    {
        $table = new \cli\Table();
        $table->setHeaders($headers);
        $table->setRows($data);
        $table->display();
    }

    public static function clear_screen()
    {
        echo exec('clear');
    }

    public static function menu($options, $message='Select an option')
    {
        \cli\line();
        $choice = \cli\menu($options, null, $message);
        \cli\line();

        return $choice;
    }
}