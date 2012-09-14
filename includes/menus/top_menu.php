<?php

class Top_Menu
{
    public function __construct()
    {
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
            $choice = \cli\menu($menu, null, 'Choose an example');
            \cli\line();

            switch ($choice)
            {
                case 'create':
                    $create_menu = new Create_Menu;
                    break;
                case 'list':
                    $this->server->listServers(100, 0);
                    break;
                case 'tools':
                    print_r($this->server->listConfigs());
                    break;
                case 'quit':
                    break 2;
            }
        }
    }
}
