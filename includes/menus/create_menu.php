<?php

class Create_Menu
{
    private $bulk_linux_templates = array('CENTOS_62_UNMANAGED', 'ARCHLINUX_20110819_UNMANAGED', 'DEBIAN_60_UNMANAGED');
    private $max_bulk = 10;
    private $server;
    private $network;

    public function __construct()
    {
        $this->server = new Server;
        $this->network = new Network;
        $this->top_create_menu();
    }
    public function top_create_menu()
    {
        $menu = array(
            'single' => 'Create Single Server (Detailed Setup)',
            'bulk' => 'Create Bulk Servers',
            'back' => 'Back'
        );

        while (true)
        {
            $choice = CLI::menu($menu);

            switch ($choice)
            {
                case 'single':
                    \cli\clear();
                    $this->single_create_menu();
                    break;
                case 'back':
                    \cli\clear();
                    new Top_Menu;
                    break;
                case 'bulk':
                    \cli\clear();
                    $this->bulk_create_menu();
                    break;
            }
        }
    }

    public function single_create_menu()
    {
        $raw_regions = $this->network->list_regions();
        $regions = array();

        foreach ($raw_regions['items'] as $region)
        {
            if ($region['status'] == 'Open')
            {
                $region_id = $region['id'];
                $region_name = $region['region']['name'];

                if (isset($region['valid_source_hvs']['kvm']))
                {
                    $hypervisor = 'KVM';
                }
                else
                {
                    $hypervisor = 'Xen';
                }

                $regions["$region_id"] = $region_name.' ('.$hypervisor.')';
            }
        }

        while (true)
        {
            \cli\clear();
            $region = CLI::menu($regions);
            break;
        }

        $raw_configs = $this->server->list_configs();
        $configs = array();

        foreach ($raw_configs['items'] as $config)
        {
            if ($config['available'] == 1 && $config['zone_availability']["$region"] == 1)
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
            $config = CLI::menu($configs);
            break;
        }

        $raw_templates = $this->server->list_templates();
        $template_zones = $this->server->template_zones();
        $templates = array();

        foreach ($raw_templates['items'] as $template)
        {
            $template_id = $template['id'];
            $template_name = $template['name'];
            if (in_array($region, $template_zones["$template_id"]))
            {
                $templates["$template_name"] = $template['description'];
            }
        }

        while (true)
        {
            \cli\clear();
            $template = CLI::menu($templates);
            break;
        }

        \cli\clear();
        $hostname = \cli\prompt('Hostname', ServerHelper::randomHostname(API::get_domain(), 'none'), $marker = ': ');
        $password = \cli\prompt('Password', false, $marker = ': ');
        $ip_count = \cli\prompt('Number of IPs', 1, $marker = ': ');
        $backup_enabled = \cli\choose('Enable Backups', 'yn', $default = 'n');

        if ($backup_enabled == 'n')
        {
            $backup = 0;
        }
        else
        {
            $backup = 1;
        }

        $result = $this->server->create_server($hostname, $region, $template, $config, $password, $ip_count, $backup);

        if ($result)
        {
            \cli\clear();
            \cli\line('%s is creating!', $result['domain']);
        }
        else
        {
            \cli\err('%s was unable to create', $hostname);
        }
    }

    public function bulk_create_menu()
    {
        $menu = array(
            'linux' => 'Linux',
            'windows' => 'Windows',
            'back' => 'Back'
        );

        while (true)
        {
            $choice = CLI::menu($menu);

            switch ($choice)
            {
                case 'linux':
                    $os = 'linux';
                    break;
                case 'windows':
                    $os = 'windows';
                    break;
                case 'back':
                    \cli\clear();
                    $this->top_create_menu();
            }

            $count = \cli\prompt('How many instances', 1, ':');

            if ($count > $this->max_bulk)
            {
                \cli\err('%s is to larger then the max allowed. (%s)', $count, $this->max_bulk);
                break;
            }


            $i = 0;
            while ($i < $count)
            {
                $hostname = ServerHelper::randomHostname(API::get_domain(), 'linux');
                $password = ServerHelper::generatePassword(12, 9);
                \cli\line();

                if ($os == 'linux')
                {
                    shuffle($this->bulk_linux_templates);
                    $result = $this->server->create_server($hostname, 12, $this->bulk_linux_templates[0], 114, $password);

                    if ($result)
                    {
                        \cli\line('%GYour server '.$result['domain']. ' is now creating.%n');
                        \cli\line();
                    }
                    else
                    {
                        \cli\line('%RUnable to create server.%n');
                        \cli\line();
                    }
                }
                elseif ($os == 'windows')
                {
                    $result = $this->server->create_server($hostname, 12, 'WINDOWS_2008_UNMANAGED', 3, $password, 1, 0);

                    if ($result)
                    {
                        \cli\line('%GYour server '.$result['domain']. ' is now creating.%n');
                        \cli\line();
                    }
                    else
                    {
                        \cli\line('%RUnable to create server.%n');
                        \cli\line();
                    }
                }

                $i++;
            }
        }
    }

    public function bulk_count_menu($os)
    {
    }
}