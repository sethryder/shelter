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
            'resize' => 'Resize',
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
                        \cli\line('%GServer rebooted.%n');
                        \cli\line();
                        $this->server_control_menu($server);
                    }
                    else
                    {
                        \cli\line('%RUnable to reboot server.%n');
                        \cli\line();
                        $this->server_control_menu($server);
                    }

                    break;
                case 'reboot_force':
                    $result = $this->server->reboot_server($server[1], true);

                    if ($result)
                    {
                        \cli\line('%GServer forcefully rebooted.%n');
                        \cli\line();
                        $this->server_control_menu($server);
                    }
                    else
                    {
                        \cli\line('%RUnable to reboot server.%n');
                        \cli\line();
                        $this->server_control_menu($server);
                    }
                    break;
                case 'resize':
                    $this->server_resize($server);
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

    public function server_resize($server)
    {
        $details = $this->server->server_details($server[1]);
        $raw_configs = $this->server->list_configs();
        $region = $details['zone']['id'];
        $current_config = $details['config_id'];

        foreach ($raw_configs['items'] as $config)
        {
            $config_id = $config['id'];
            $configs_disk["$config_id"] = $config['disk'];

            if ($config['available'] == 1 && $config['zone_availability']["$region"] == 1 && $config['id'] != $details['config_id'])
            {
                $config_id = $config['id'];
                $config_description = $config['description'];
                $config_vcpu = $config['vcpu'];
                $config_memory = $config['memory'];
                $config_disk = $config['disk'];

                $configs["$config_id"] = $config_description.' / CPU Cores: '.$config_vcpu.' / Memory: '.$config_memory.' MB / Disk: '.$config_disk.' GB';
            }
        }

        while (true)
        {
            \cli\clear();
            \cli\out('You have selected to resize: ' . $server[2]);
            \cli\line();
            $config = CLI::menu($configs, 'Select a config');
            break;
        }

        if ($configs_disk["$current_config"] < $configs_disk["$config"])
        {
            \cli\line();
            $resize_filesystem = \cli\choose('Resize filesystem?', 'yn', 'y');
            \cli\line();
        }

        $resize_filesystem = ($resize_filesystem == y) ? true : false;

        $result = $this->server->resize_server($server[1], $config, $resize_filesystem);

        if ($result)
        {
            \cli\clear();
            \cli\line('%GYour server '.$server[2].' is resizing.%n');
            \cli\line();
            $this->server_control_menu($server);
        }
        else
        {
            \cli\clear();
            \cli\line('%RUnable to resize '.$server[2].'.%n');
            \cli\line();
            $this->server_control_menu($server);
        }
    }
}