<?php

/*
 Plugin Name: Multiple Sidebars
 Author: Andrico - Nicolás Guglielmi
 Author URI: http://andrico.com.ar
 Plugin URI: http://andrico.com.ar/multiple-sidebars
 Text Domain: multiple_sidebars
 Domain Path: /languages/
 Description: Create sidebars and assign to each post / page / custom_post / taxonomy. - Crear sidebars y asignarlos a cada post / page / custom_post / taxonomy.
 Version: 1.2.8
 */

/*	MultipleSidebars - por Andrico
 Copyright (C) 2012  Nicolás Guglielmi

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define('MULTIPLESIDEBARS_VERSION', '1.2.8');
define('MULTIPLESIDEBARS_URL', plugin_dir_url(__FILE__));
define('MULTIPLESIDEBARS_LANG', 'multiple_sidebars');


include_once (dirname(__FILE__) . "/core.php");
include_once (dirname(__FILE__) . "/herramientas.php");
include_once (dirname(__FILE__) . "/widget.php");
?>