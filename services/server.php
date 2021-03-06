<?php

class Server extends API
{
    public function list_servers($limit=10, $offset=0)
    {
        $params = array('limit' => $limit, 'offset' => $offset);

        $servers = $this->call('Storm/Server/list', $params);

        $data = array();

        $i = 1;

        foreach ($servers['items'] as $server)
        {
            $data["$i"][] = $server['uniq_id'];
            $data["$i"][] = $server['domain'];
            $data["$i"][] = $server['ip'];
            $data["$i"][] = $server['config_description'];
            $data["$i"][] = $server['template_description'];

            $i++;
        }

        return $data;
    }

    public function server_details($uid)
    {
        $params = array(
            'uniq_id' => $uid
        );

        $result = $this->call('Storm/Server/details', $params);

        if (isset($result['errors']))
        {
            return false;
        }
        else
        {
            return $result;
        }

     }

    public function create_server($domain, $zone, $template, $config, $password=1, $ip_count=1, $backup=1, $backup_plan='quota', $backup_quota=100, $bandwidth=0)
    {

        if ($password == 1)
        {
            $password = Password::generatePassword();
        }

        $params = array(
            'domain' => $domain,
            'password' => $password,
            'template' => $template,
            'config_id' => $config,
            'ip_count' => $ip_count,
            'backup_enabled' => $backup,
            'backup_plan' => $backup_plan,
            'backup_quota' => $backup_quota,
            'bandwidth_quota' => $bandwidth,
            'zone' => $zone
        );

        $result = $this->call('Storm/Server/create', $params);

        if (isset($result['errors']))
        {
            return false;
        }
        else
        {
            return $result;
        }
    }

    public function resize_server($uid, $config_id, $skip_fs_resize=false)
    {
        $params = array(
            'uniq_id' => $uid,
            'config_id' => $config_id,
            'skip_fs_resize' => $skip_fs_resize
        );

        $result = $this->call('Storm/Server/resize', $params);

        if (isset($result['errors']))
        {
            return false;
        }
        else
        {
            return $result;
        }
    }

    public function reboot_server($uid, $force=false)
    {
        $params = array(
            'uniq_id' => $uid,
            'force' => $force
        );

        $result = $this->call('Storm/Server/reboot', $params);

        if (isset($result['errors']))
        {
            return false;
        }
        else
        {
            return $result;
        }
    }

    public function list_templates()
    {
        $params = array();

        $templates = $this->call('Storm/Template/list', $params);

        return $templates;
    }

    public function template_zones()
    {
        /*
         * 8 = Xen: US Central
         * 12 = KVM: US Central
         * 15 = KVM: US West
         */
        $kvm = array(12, 15);
        $xen = array(8);

        $template_zones = array(
            '16' => $kvm,  //ARCHLINUX_20110819_UNMANAGED
            '27' => $kvm,  //CENTOS_62_COREMANAGED
            '28' => $kvm,  //CENTOS_62_CPANEL
            '26' => $kvm,  //CENTOS_62_UNMANAGED
            '7' => $xen,   //CENTOSUNMANAGED
            '9' => $xen,   //COREMANAGED2
            '11' => $xen,  //CPANELFANTASTICO2
            '4' => $xen,   //DEBIANUNMANAGED
            '21' => $kvm,  //DEBIAN_60_UNMANAGED
            '30' => $kvm,  //UBUNTU_1004_COREMANAGED
            '22' => $kvm,  //UBUNTU_1004_UNMANAGED
            '31' => $kvm,  //UBUNTU_1110_COREMANAGED
            '23' => $kvm,  //UBUNTU_1110_UNMANAGED
            '29' => $kvm,  //UBUNTU_1204_UNMANAGED
            '17' => $kvm,  //WINDOWS_2008_COREMANAGED
            '18' => $kvm,  //WINDOWS_2008_PLESK
            '19' => $kvm,  //WINDOWS_2008_UNMANAGED
            '5' => $xen,   //UBUNTULTSUNMANAGED
            '14' => $xen,  //UBUNTULUCID
            '12' => $xen,  //UBUNTUUNMANAGED
        );

        return $template_zones;
    }

    public function list_configs()
    {
        $params = array();

        $configs = $this->call('Storm/Config/list');

        return $configs;
    }

    public function destroy_server($uid, $confirm=false)
    {
        if (!$confirm)
        {
            return false;
        }

        $params = array(
            'uniq_id' => $uid
        );

        $result = $this->call('Storm/Server/destroy', $params);

        if (isset($result['errors']))
        {
            return false;
        }
        else
        {
            return $result;
        }
    }
}