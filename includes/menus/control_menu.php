<?php

class Control_Menu
{
    private $server;
    private $selected_server;

    public function __construct()
    {
        $this->server = new Server;
        CLI::clear_screen();
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

        if (!array_key_exists($choice, $servers))
        {
            \cli\err('Invalid ID!');
            \cli\line();
            $this->top_control_menu();
        }

        $server = $servers["$choice"];

        CLI::clear_screen();

        $this->server_control_menu($server);

    }

    public function server_control_menu($server)
    {
        \cli\out("Currently Selected Server: " . $server[2]);
        \cli\line();

        $menu = array(
            'reboot' => 'Reboot',
            'reboot_force' => 'Force Reboot',
            'back' => 'Back'
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
                        cli\line('%GServer rebooted!%n');
                        cli\line();
                        $this->server_control_menu($server);
                    }
                    else
                    {
                        cli\line('%RUnable to reboot server!%n');
                        cli\line();
                        $this->server_control_menu($server);
                    }

                    break;
                case 'reboot_force':
                    $result = $this->server->reboot_server($server[1], true);

                    if ($result)
                    {
                        cli\line('%GServer forcefully rebooted!$n');
                        cli\line();
                        $this->server_control_menu($server);
                    }
                    else
                    {
                        cli\line('%RUnable to reboot server!%n');
                        cli\line();
                        $this->server_control_menu($server);
                    }

                    break;
                case 'back':
                    $this->server_selection_menu();
                    break;
            }
        }
    }

}