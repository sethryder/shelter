<?php

class Top_Menu
{
    private $server;

    public function __construct()
    {
        $this->server = new Server;
        $this->topMenu();
    }

    public function topMenu()
    {
        $menu = array(
            'create' => 'Create Server(s)',
            'list' => 'List Servers',
            'control' => 'Control Server',
            'tools' => 'Tools',
            'quit' => 'Quit'
        );

        while (true)
        {
            \cli\line();
            $choice = CLI::menu($menu);
            \cli\line();

            switch ($choice)
            {
                case 'create':
                    CLI::clear_screen();
                    $create_menu = new Create_Menu;
                    break;
                case 'list':
                    $this->server->list_servers(100, 0);
                    break;
                case 'tools':
                    break;
                case 'quit':
                    break 2;
            }
        }
    }
}
