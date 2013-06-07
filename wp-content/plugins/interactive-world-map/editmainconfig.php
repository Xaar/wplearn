<?php
if(isset($_POST['act_type']) && $_POST['act_type'] == 'usa_map_plugin_main_save') {
    update_option('freeworldcontinenthtml5map_nameFontSize', $_POST['name_font_size'].'px');
    update_option('freeworldcontinenthtml5map_borderColor', $_POST['borders_color']);
    update_option('freeworldcontinenthtml5map_nameColor', $_POST['name_color']);
    update_option('freeworldcontinenthtml5map_mapWidth', $_POST['mapWidth']);
    update_option('freeworldcontinenthtml5map_mapHeight', $_POST['mapHeight']);
    update_option('freeworldcontinenthtml5map_statesInfoArea', $_POST['statesArea']);
}



echo "<h2>" . __( 'Map settings', 'freeworldcontinent-html5-map' ) . "</h2>";
?>
<script xmlns="http://www.w3.org/1999/html">
    jQuery(function(){
        jQuery('.tipsy-q').tipsy({gravity: 'w'}).css('cursor', 'default');

        jQuery('.color~.colorpicker').each(function(){
            jQuery(this).farbtastic(jQuery(this).prev().prev());
            jQuery(this).hide();
            jQuery(this).prev().prev().bind('focus', function(){
                jQuery(this).next().next().fadeIn();
            });
            jQuery(this).prev().prev().bind('blur', function(){
                jQuery(this).next().next().fadeOut();
            });
        });

    });
</script>

<form method="POST" class="usa-html5-map">
    <p>Specify general settings of the map. To choose a color, click a color box, select the desired color in the color selection dialog and click anywhere outside the dialog to apply the chosen color.</p>

    <h3 class="settings-chapter">
        Names Settings
    </h3>
    <span class="title">Name Font Size: </span><input class="span2" type="text" name="name_font_size" value="<?php echo preg_replace('/[^\d]+/i', '', get_option('freeworldcontinenthtml5map_nameFontSize')); ?>" />
    <span class="tipsy-q" original-title="Font size of names on the map">[?]</span><br />

    <span class="title">Name Color: </span><input id='color' class="color" type="text" name="name_color" value="<?php echo get_option('freeworldcontinenthtml5map_nameColor'); ?>" style="background-color: #<?php echo get_option('usahtml5map_nameColor'); ?>" readonly />
    <span class="tipsy-q" original-title="The color of names on the map">[?]</span><div class="colorpicker"></div><br />

    <input type="hidden" name="act_type" value="usa_map_plugin_main_save" />
    <p class="submit"><input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit"></p>
</form>

