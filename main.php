<?php
//TODO: Autoloaders.

require 'config.php';
require 'includes/apiclient.php';

#CLI
require 'includes/cli/cli.php';
\cli\register_autoload();

#Helpers
require 'includes/helpers/exception.helper.php';
require 'includes/helpers/cli.helper.php';
require 'includes/helpers/help.helper.php';
require 'includes/helpers/server.helper.php';

#API Services
require 'services/api.php';
require 'services/server.php';
require 'services/network.php';

#Menus
require 'includes/menus/top_menu.php';
require 'includes/menus/create_menu.php';
require 'includes/menus/control_menu.php';
require 'includes/menus/tools_menu.php';