<?php

class Control_Menu
{
    private $server;

    public function __construct()
    {
        $this->server = new Server;
        \cli\clear();
        $this->server_selection_menu();
    }

    public function server_selection_menu()
    {
        $servers = $this->server->list_servers('100');
        $headers = array('ID', 'UID', 'Hostname', 'IP', 'Config', 'Template');

        foreach ($servers as $k => $s)
        {
            array_unshift($servers["$k"], $k);
        }

        CLI::output_table($headers, $servers);
        \cli\line();

        $choice = \cli\prompt('Select Server ID', false, $marker = ': ');
        \cli\line();

        if (!array_key_exists($choice, $servers))
        {
            \cli\err('Invalid ID!');
            \cli\line();
            $this->server_selection_menu();
        }

        $server = $servers["$choice"];

        \cli\clear();

        $this->server_control_menu($server);

    }

    public function server_control_menu($server)
    {
        \cli\out("Currently Selected Server: " . $server[2]);
        \cli\line();

        $menu = array(
            'reboot' => 'Reboot',
            'reboot_force' => 'Force Reboot',
            'destroy' => 'Destroy',
            'back' => 'Back',
            'top' => 'Top Menu'
        );

        while (true)
        {
            $choice = CLI::menu($menu, 'Select an action');

            switch ($choice)
            {
                case 'reboot':
                    $result = $this->server->reboot_server($server[1]);

                    if ($result)
                    {
                        cli\line('%GServer rebooted.%n');
                        cli\line();
                        $this->server_control_menu($server);
                    }
                    else
                    {
                        cli\line('%RUnable to reboot server.%n');
                        cli\line();
                        $this->server_control_menu($server);
                    }

                    break;
                case 'reboot_force':
                    $result = $this->server->reboot_server($server[1], true);

                    if ($result)
                    {
                        cli\line('%GServer forcefully rebooted.%n');
                        cli\line();
                        $this->server_control_menu($server);
                    }
                    else
                    {
                        cli\line('%RUnable to reboot server.%n');
                        cli\line();
                        $this->server_control_menu($server);
                    }
                    break;
                case 'destroy':
                    $this->server_destroy_menu($server);
                    break;
                case 'back':
                    $this->server_selection_menu();
                    break;
                case 'top':
                    new Top_Menu;
            }
        }
    }

    public function server_destroy_menu($server)
    {
        \cli\out('You have selected to destroy: ' . $server[2]);
        \cli\line();
        \cli\line();
        $confirm = \cli\prompt('Please type "DESTROY" to confirm', false, ': ');

        if (strtolower($confirm) == 'destroy' || strtolower($confirm) == 'detroit')
        {
            $result = $this->server->destroy_server($server[1], true);

            if ($result)
            {
                \cli\line();
                \cli\line('%GYour server is destroying now.%n');
                \cli\line();
                \cli\prompt('Hit enter to return to the main menu.', 'Enter', '');
                \cli\clear();
                new Top_Menu;
            }
            else
            {
                \cli\line();
                \cli\err('%RUnable to destroy server.');
                $this->server_control_menu($server);
            }
        }
        else
        {
            \cli\line();
            \cli\err('%RInvalid confirmation.%n');
            \cli\line();
            $this->server_control_menu($server);
        }
    }
}