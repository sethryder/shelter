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

    public static function menu($options)
    {
        \cli\line();
        $choice = \cli\menu($options, null, 'Select an option');
        \cli\line();

        return $choice;
    }
}