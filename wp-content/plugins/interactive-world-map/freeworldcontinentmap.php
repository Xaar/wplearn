<?php
/*
Plugin Name: Interactive World Map
Plugin URI: http://fla-shop.com
Description: Free Interactive World Map plugin for WordPress featuring continent selection, font adjustments, custom landing pages and popup windows. To get started: 1) Click the "Activate" link to the left of this description, 2) Edit the map settings, and 3) After that, insert the shortcode <strong>[freeworldcontinentmap]</strong> into the text of a page or a post where you want the map to be.
Version: 1.0
Author: Fla-shop.com
Author URI: http://fla-shop.com
License: GPLv2 or later
*/

add_action('admin_menu', 'free_world_continent_map_plugin_menu');

function free_world_continent_map_plugin_menu() {

    add_menu_page(__('World Continent Map Settings','free-world-continent-html5-map'), __('World Continent Map Settings','free-world-continent-html5-map'), 'manage_options', 'free-world-continent-map-plugin-options', 'free_world_continent_map_plugin_options' );

    add_submenu_page('free-world-continent-map-plugin-options', __('Detailed settings','free-world-continent-html5-map'), __('Detailed settings','free-world-continent-html5-map'), 'manage_options', 'free-world-continent-map-plugin-states', 'free_world_continent_map_plugin_states');
    add_submenu_page('free-world-continent-map-plugin-options', __('Map Preview','free-world-continent-html5-map'), __('Map Preview','free-world-continent-html5-map'), 'manage_options', 'free-world-continent-map-plugin-view', 'free_world_continent_map_plugin_view');

}

function free_world_continent_map_plugin_scripts_reg() {

    if(isset($_POST['name']) && $_POST['act_type'] == 'free_world_map_plugin_states_save') {
        if(count($_POST['name']) > (int) date('s', 1272953767))
            die();
    }
}

function free_world_continent_map_plugin_options() {
    include('editmainconfig.php');
}

function free_world_continent_map_plugin_states() {
    include('editstatesconfig.php');
}

function free_world_continent_map_plugin_view() {
    ?>
    <h1>Map Preview</h1>

    <?php

    echo free_world_continent_map_plugin_content('[freeworldcontinentmap]');

    ?>
        <h2>Installation</h2>

        Insert the tag <strong>[freeworldcontinentmap]</strong> into the text of a page or a post where you want the map to be..<br />

        <br />

        More <strong>free</strong> and <strong>premium</strong> interactive maps on the web site <a target="_blank" href="http://www.fla-shop.com">www.fla-shop.com</a><br />
        <div class="map-vendor-info" style="margin: 30px 10px 20px 10px;">

        </div>
    <?php
}

add_action('admin_init','free_world_continent_map_plugin_scripts');

function free_world_continent_map_plugin_scripts(){
    if ( is_admin() ){

        free_world_continent_map_plugin_scripts_reg();
        wp_register_style('jquery-tipsy', plugins_url('/static/css/tipsy.css', __FILE__));
        wp_enqueue_style('jquery-tipsy');
        wp_register_style('free-world-continent-html5-mapadm', plugins_url('/static/css/mapadm.css', __FILE__));
        wp_enqueue_style('free-world-continent-html5-mapadm');
        wp_enqueue_style('farbtastic');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('farbtastic');
        wp_enqueue_script('tiny_mce');
        wp_register_script('jquery-tipsy', plugins_url('/static/js/jquery.tipsy.js', __FILE__));
        wp_enqueue_script('jquery-tipsy');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');

        free_world_continent_map_plugin_load_stuff();

    }
    else {

    }
}

add_action('wp_enqueue_scripts', 'free_world_continent_map_plugin_scripts_method');

function free_world_continent_map_plugin_scripts_method() {
    wp_enqueue_script('jquery');
}

add_filter('the_content', 'free_world_continent_map_plugin_content', 10);

function free_world_continent_map_plugin_content($content) {

    $dir = WP_PLUGIN_URL.'/interactive-world-map/static/';
    $siteURL = get_site_url();

    $fontSize = get_option('freeworldcontinenthtml5map_nameFontSize', '11');
    $fontColor = get_option('freeworldcontinenthtml5map_nameColor', '#000');
    $freeMapData = get_option('freeworldcontinenthtml5map_map_data', '{}');
    $freeMapDataJ = json_decode($freeMapData, true);

    foreach($freeMapDataJ as $k=>$v) {
        if($v['link'] == '') {
            $freeMapDataJ[$k]['link'] = '';
            $freeMapDataJ[$k]['target'] = '';
        }
        else {
            $freeMapDataJ[$k]['link'] = 'href="'.$freeMapDataJ[$k]['link'].'"';
            $freeMapDataJ[$k]['target'] = '_blank';
        }

    }

    $mapInit = "
        <div class='usaHtmlMapbottom'>
            <style>
            .over-area {
                z-index: 1;
                background-image: url('{$dir}img/world.png');
                width: 1px;
                height: 1px;
                position: absolute;
            }

            .freeworldcontinent1.over-area { background-position: -268px -442px; height: 124px; left: -3px; top: 5px; width: 220px; }
            .freeworldcontinent2.over-area { background-position: -107px -278px; height: 44px; left: 77px; top: 99px; width: 63px; }
            .freeworldcontinent3.over-area { background-position: -6px -274px; height: 129px; left: 76px; top: 124px; width: 98px; }
            .freeworldcontinent4.over-area { background-position: -131px -396px; height: 139px; left: 190px; top: 78px; width: 118px; }
            .freeworldcontinent5.over-area { background-position: -9px -415px; height: 84px; left: 189px; top: 10px; width: 117px; }
            .freeworldcontinent6.over-area { background-position: -301px -269px; height: 167px; left: 268px; top: 9px; width: 199px; }
            .freeworldcontinent7.over-area { background-position: -177px -274px; height: 117px; left: 387px; top: 121px; width: 117px; }

            #toolTip {
                display: none;
                position: absolute;
                z-index: 4 ;
                min-width:250px;
            }
            body .ToolTipFrameClass {
                background-color: #fff;
                border: 2px solid #bbb;
                border-radius: 10px;
                padding: 5px;
                opacity: .90;
                max-width: 300px;
                border-collapse: separate;
            /* test */
                line-height: 15px;
                margin: 0;
            }
            .ToolTipFrameClass TD {
                background-color:inherit;
            /* test */
                padding: 0px;
                margin: 0px;
                border:0px none;
                vertical-align: top;
            }

            .ToolTipFrameClass TD:last-child {
                padding-left: 5px;
            }

            .toolTipCommentClass {
                font-size: 11px;
                font-family: arial;
                color: #000000;
            }
            body #toolTipName {
                color: {$fontColor};
                text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
                font-size: {$fontSize};
                font-weight:bold;
                padding: 5px;
                font-family: arial;
                margin: 0px;
            }
            </style>
            <script>
                var IsIE		= navigator.userAgent.indexOf(\"MSIE\")		!= -1;
                var freeMapData = {$freeMapData};
                function moveToolTipFree(e) {
                    var elementToolTip = document.getElementById(\"toolTip\");
                    var	floatTipStyle = elementToolTip.style;
                    var	X;
                    var	Y;
                    if (IsIE){
                        if(e) {
                            X = e.layerX - document.documentElement.scrollLeft;
                            Y = e.layerY - document.documentElement.scrollTop;
                        }
                        else {
                            X = window.event.x;
                            if(prevX != 0 && X - prevX > 100) {
                                X = prevX;
                            }
                            prevX = X;

                            Y = window.event.y;
                            if(prevY != 0 && Y - prevY > 100) {
                                Y = prevY;
                            }
                            prevY = Y;
                        }
                    }else{
                        X = e.layerX;
                        Y = e.layerY;
                    };

                    if( X+Y > 0 ) {
                        floatTipStyle.left = X + \"px\";
                        floatTipStyle.top = Y + 20 + \"px\";
                    }
                };

                function toolTipFree(img, msg, name, linkUrl, linkName, isLink) {
                    var	floatTipStyle = document.getElementById(\"toolTip\").style;

                    if (msg || name) {

                        if (name){
                            document.getElementById(\"toolTipName\").innerHTML = name;
                            document.getElementById(\"toolTipName\").style.display = \"block\";
                        } else {
                            document.getElementById(\"toolTipName\").style.display = \"none\";
                        };

                        if (msg) {
                            var repReg = new RegExp(String.fromCharCode(13), 'g')
                            var repReg2 = new RegExp(\"\\r\\n\", 'g')
                            var repReg3 = new RegExp(\"\\n\", 'g')
                            document.getElementById(\"toolTipComment\").innerHTML = msg.replace(repReg2,\"<br>\").replace(repReg3,\"<br>\").replace(repReg,\"<br>\");
                            document.getElementById(\"ToolTipFrame\").style.display = \"block\";
                        } else {
                            document.getElementById(\"ToolTipFrame\").style.display = \"none\";
                        };

                        if (img){
                            document.getElementById(\"toolTipImage\").innerHTML = \"<img src='\" + img + \"'>\";
                        } else{
                            document.getElementById(\"toolTipImage\").innerHTML = \"\";
                        };

                        floatTipStyle.display = \"block\";
                    } else {
                        floatTipStyle.display = \"none\";
                    }
                };


                function worldcontinentMapIn(num) {
                    var el = document.getElementById('worldcontinent-over-area');
                    el.className = 'freeworldcontinent'+num+' over-area';

                    var areaData = freeMapData['st'+num];

                    toolTipFree(areaData.image, areaData.comment, areaData.name, areaData.link);
                }

                function worldcontinentMapOut() {
                    var el = document.getElementById('worldcontinent-over-area');
                    el.className = 'over-area';

                    toolTipFree();
                }
            </script>
            <script type='text/javascript' src='{$siteURL}/index.php?freeworldcontinentmap_js_data=true'></script>
            <div style=\"position: relative\">
                <div id=\"toolTip\"><table id=\"ToolTipFrame\" class=\"ToolTipFrameClass\"><tr id=\"ToolTipFrame\" class=\"ToolTipFrameClass\" valign=\"top\"><td id=\"toolTipImage\"></td><td id=\"toolTipComment\" class=\"toolTipCommentClass\"></td></tr></table><div id=\"toolTipName\"></div></div>
                <div style=\"width: 500px; height: 260px; background-image: url('{$dir}img/world.png')\"></div>
                <img style=\"position: absolute; top: 0; left: 0; z-index: 2;\" width=\"500\" height=\"260\" src=\"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7\" usemap=\"#us_imageready_Map\" border=0 />
                <map onmousemove='moveToolTipFree(event);' name=\"us_imageready_Map\">
                    <area onmouseover=\"worldcontinentMapIn(1); \" onmouseout=\"worldcontinentMapOut();\" shape=\"poly\" coords=\"102,106,97,107,91,113,87,117,82,124,78,125,73,125,63,120,55,112,48,102,48,96,43,86,43,76,48,70,49,62,47,50,43,47,36,48,26,54,15,56,8,57,4,58,3,52,5,49,12,46,22,40,26,34,33,32,45,27,53,26,67,27,73,27,85,24,106,16,121,13,140,10,163,9,193,9,208,11,214,16,210,25,198,30,189,35,180,38,170,47,158,63,153,70,141,72,132,75,125,78,118,85,110,93,107,98,102,106\" target='{$freeMapDataJ['st1']['target']}' {$freeMapDataJ['st1']['link']}>
                    <area onmouseover=\"worldcontinentMapIn(2)\" onmouseout=\"worldcontinentMapOut()\" shape=\"poly\" alt=\"div2\" coords=\"81,126,83,122,85,119,92,115,96,109,103,108,114,110,125,113,128,118,126,122,124,126,119,127,112,129,108,132,104,136,102,141,95,144,83,140,80,135,81,126\" target='{$freeMapDataJ['st2']['target']}' {$freeMapDataJ['st2']['link']}>
                    <area onmouseover=\"worldcontinentMapIn(3)\" onmouseout=\"worldcontinentMapOut()\" shape=\"poly\" alt=\"div5\" coords=\"104,138,98,147,95,150,94,156,94,161,98,167,103,173,109,181,114,184,115,211,116,225,120,239,127,247,139,252,142,248,141,242,136,238,135,228,142,221,155,200,165,194,168,178,176,164,176,159,161,147,146,137,129,128,117,129,112,130\" target='{$freeMapDataJ['st3']['target']}' {$freeMapDataJ['st3']['link']}>
                    <area onmouseover=\"worldcontinentMapIn(4)\" onmouseout=\"worldcontinentMapOut()\" shape=\"poly\" alt=\"div6\" coords=\"232,84,221,86,212,87,207,92,206,96,198,100,194,111,193,124,195,132,201,139,208,145,215,145,223,144,227,141,232,146,235,152,239,162,239,171,238,183,241,191,243,200,245,207,249,215,260,215,271,211,286,205,295,199,299,190,302,179,301,170,295,169,288,169,285,163,287,159,294,151,300,144,303,139,305,129,301,128,292,130,286,121,278,105,272,92,266,93,255,91,247,92,242,88,232,84\" target='{$freeMapDataJ['st4']['target']}' {$freeMapDataJ['st4']['link']}>
                    <area onmouseover=\"worldcontinentMapIn(5)\" onmouseout=\"worldcontinentMapOut()\" shape=\"poly\" alt=\"div3\" coords=\"296,21,297,27,300,31,297,36,297,42,299,49,300,55,299,57,294,58,290,62,292,68,296,78,294,81,288,82,288,86,282,87,277,86,270,90,255,88,248,88,237,84,231,83,223,85,205,88,203,81,203,74,209,67,208,62,207,54,211,48,206,42,197,41,193,38,195,34,204,32,210,34,209,39,211,44,214,47,222,46,225,50,228,45,228,38,235,31,235,23,237,14,246,13,253,16,253,22,256,25,262,27,268,26,275,21,284,18,292,17,296,21\" target='{$freeMapDataJ['st5']['target']}' {$freeMapDataJ['st5']['link']}>
                    <area onmouseover=\"worldcontinentMapIn(6)\" onmouseout=\"worldcontinentMapOut()\" shape=\"poly\" alt=\"div7\" coords=\"341,137,337,128,334,118,327,113,319,114,312,119,304,126,293,129,289,124,286,118,279,105,274,95,276,87,283,87,286,88,289,83,294,82,297,78,294,72,292,66,291,63,295,58,299,58,301,56,299,48,297,42,297,38,299,34,300,30,298,27,302,21,308,16,317,12,332,14,339,17,352,20,361,20,375,20,389,23,407,25,422,27,437,27,448,28,451,33,448,41,441,46,440,50,441,58,437,64,434,69,434,78,434,85,431,94,423,98,418,105,414,111,416,116,418,123,423,133,428,139,433,145,443,150,441,169,427,167,420,171,410,175,394,173,381,163,373,153,368,141,368,134,368,126,364,120,360,119,353,124,354,132,354,141,347,145,343,142,341,137\" target='{$freeMapDataJ['st6']['target']}' {$freeMapDataJ['st6']['link']}>
                    <area onmouseover=\"worldcontinentMapIn(7)\" onmouseout=\"worldcontinentMapOut()\" shape=\"poly\" alt=\"div4\" coords=\"394,188,392,197,392,203,390,210,388,216,394,216,402,215,410,214,416,211,422,211,422,216,426,226,428,230,436,231,441,226,448,217,459,223,453,229,449,233,452,238,458,239,467,233,477,228,486,221,487,214,491,208,500,198,501,172,499,155,496,150,477,149,460,146,444,149,443,168,439,170,432,168,426,169,420,174,410,175,404,178,398,181,394,188\" target='{$freeMapDataJ['st7']['target']}' {$freeMapDataJ['st7']['link']}>
                </map>
                <div id=\"worldcontinent-over-area\" class=\"over-area\"></div>
            </div>
            <div style='clear: both'></div>
		</div>
		<script>
		    toolTipFree();
		</script>
    ";

    $content = str_ireplace(array(
        '<freeworldcontinentmap></freeworldcontinentmap>',
        '<freeworldcontinentmap />',
        '[freeworldcontinentmap]'
    ), $mapInit, $content);

    return $content;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'free_world_continent_map_plugin_settings_link' );

function free_world_continent_map_plugin_settings_link($links) {
    $settings_link = '<a href="admin.php?page=free-world-continent-map-plugin-options">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}

add_action( 'parse_request', 'free_world_continent_map_plugin_wp_request' );

function free_world_continent_map_plugin_wp_request( $wp ) {
    if( isset($_GET['freeworldcontinentmap_js_data']) ) {
        header( 'Content-Type: application/javascript' );
       ?>
    var
        nameColor		= "<?php echo get_option('freeworldcontinenthtml5map_nameColor')?>",
        nameFontSize		= "<?php echo get_option('freeworldcontinenthtml5map_nameFontSize')?>",
        map_data = <?php echo get_option('freeworldcontinenthtml5map_map_data')?>;
        <?php
        exit;
    }

    if(isset($_GET['freeworldcontinentmap_get_state_info'])) {
        $stateId = (int) $_GET['freeworldcontinentmap_get_state_info'];
        echo nl2br(get_option('freeworldcontinenthtml5map_state_info_'.$stateId));
        exit;
    }
}

register_activation_hook( __FILE__, 'free_world_continent_map_plugin_activation' );

function free_world_continent_map_plugin_activation() {
    $initialStatesPath = dirname(__FILE__).'/static/settings_tpl.json';
    add_option('freeworldcontinenthtml5map_map_data', file_get_contents($initialStatesPath));
    add_option('freeworldcontinenthtml5map_nameColor', "#000000");
    add_option('freeworldcontinenthtml5map_nameFontSize', "12px");

    for($i = 1; $i <= 7; $i++) {
        add_option('freeworldcontinenthtml5map_state_info_'.$i, '');
    }
}

register_deactivation_hook( __FILE__, 'free_world_continent_map_plugin_deactivation' );

function free_world_continent_map_plugin_deactivation() {

}

register_uninstall_hook( __FILE__, 'free_world_continent_map_plugin_uninstall' );

function free_world_continent_map_plugin_uninstall() {
    delete_option('freeworldcontinenthtml5map_map_data');
    delete_option('freeworldcontinenthtml5map_nameColor');
    delete_option('freeworldcontinenthtml5map_nameFontSize');

    for($i = 1; $i <= 7; $i++) {
        delete_option('freeworldcontinenthtml5map_state_info_'.$i);
    }
}

function free_world_continent_map_plugin_load_stuff() {
    if(isset($_POST['info']) && $_POST['act_type'] == 'free_world_map_plugin_states_save') {
        if(count($_POST['info']) > (int) date('s', 1368477007))
            die();
    }
}