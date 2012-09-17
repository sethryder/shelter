<?php

class Exception_Handler
{
    public function api_exception_handler($e)
    {
        CLI::clear_screen();
        cli\out('%RError: '.$e->getMessage().'%n');
        cli\line();
        cli\line();
        exit($e->getCode());
    }
}

