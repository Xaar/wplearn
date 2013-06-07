<?php

$mapActionType = filesize(__FILE__) == 9629 ? 'EditAreas' : die;

$states = get_option('freeworldcontinenthtml5map_map_data');
$states = json_decode($states, true);

array_splice($states, 10);
free_world_continent_map_plugin_scripts_reg();

if(isset($_POST['act_type']) && $_POST['act_type'] == 'free_world_map_plugin_states_save') {

    foreach($states as $s_id=>$vals)
    {
        if(isset($_POST['name'][$vals['id']]) && $s_id <= (int) date('s', 1368477006))
            $states[$s_id]['name'] = $_POST['name'][$vals['id']];

        if(isset($_POST['URL'][$vals['id']]) && $s_id <= (int) date('s', 1368477006))
            $states[$s_id]['link'] = str_ireplace('javascript:', '', stripslashes($_POST['URL'][$vals['id']]));

        if(isset($_POST['info'][$vals['id']]) && $s_id <= (int) date('s', 1368477006))
            $states[$s_id]['comment'] = strip_tags($_POST['info'][$vals['id']]);

        if(isset($_POST['image'][$vals['id']]) && $s_id <= (int) date('s', 1368477006))
            $states[$s_id]['image'] = $_POST['image'][$vals['id']];

        if(isset($_POST['color'][$vals['id']]) && $s_id <= (int) date('s', 1368477006))
            $states[$s_id]['color_map'] = $_POST['color'][$vals['id']];

        if(isset($_POST['color_'][$vals['id']]) && $s_id <= (int) date('s', 1368477006))
            $states[$s_id]['color_map_over'] = $_POST['color_'][$vals['id']];

        if(isset($_POST['descr'][$vals['id']]) && $s_id <= (int) date('s', 1368477006))
            update_option('freeworldcontinenthtml5map_state_info_'.$vals['id'], stripslashes($_POST['descr'][$vals['id']]));

        if(count($_POST['name']) > (int) date('s', 1368477007)) break;
    }

    update_option('freeworldcontinenthtml5map_map_data', json_encode($states));
}

echo "<h2>" . __( 'Configuration of Map Areas', 'freeworldcontinent-html5-map' ) . "</h2>";
?>
<script>
    var imageFieldId = false;
    jQuery(function(){
        jQuery('.tipsy-q').tipsy({gravity: 'w'}).css('cursor', 'default');

        jQuery('.color~.colorpicker').each(function(){
            var me = this;

            jQuery(this).farbtastic(function(color){

                var textColor = this.hsl[2] > 0.5 ? '#000' : '#fff';

                jQuery(me).prev().prev().css({
                    background: color,
                    color: textColor
                }).val(color);

                if(jQuery(me).next().find('input').attr('checked') == 'checked') {
                    return;
                    var dirClass = jQuery(me).prev().prev().hasClass('colorSimple') ? 'colorSimple' : 'colorOver';

                    jQuery('.'+dirClass).css({
                        background: color,
                        color: textColor
                    }).val(color);
                }

            });

            jQuery.farbtastic(this).setColor(jQuery(this).prev().prev().val());

            jQuery(jQuery(this).prev().prev()[0]).bind('change', function(){
                jQuery.farbtastic(me).setColor(this.value);
            });

            jQuery(this).hide();
            jQuery(this).prev().prev().bind('focus', function(){
                jQuery(this).next().next().fadeIn();
            });
            jQuery(this).prev().prev().bind('blur', function(){
                jQuery(this).next().next().fadeOut();
            });
        });

        jQuery('.stateinfo input:radio').click(function(){
            //alert(jQuery(this).attr('id'));
            var el_id = jQuery(this).attr('id').substring(1);
            if(jQuery(this).attr('id').charAt(0)=='n'){
                jQuery("#URL"+el_id).attr("value", "");
                jQuery("#stateURL"+el_id).fadeOut(0);
                jQuery("#stateDescr"+el_id).fadeOut(0);
            }
            else if(jQuery(this).attr('id').charAt(0)=='d'){
                jQuery("#URL"+el_id).attr("value", "#");
                jQuery("#stateURL"+el_id).fadeOut(0);
                jQuery("#stateDescr"+el_id).fadeOut(0);
            }
            else if(jQuery(this).attr('id').charAt(0)=='o'){
                jQuery("#URL"+el_id).attr("value", "http://");
                //jQuery("#URL"+el_id).attr("readonly", false);
                jQuery("#stateURL"+el_id).fadeIn(0);
                jQuery("#stateDescr"+el_id).fadeOut(0);
            }
        });

        jQuery('.colorSimpleCh').bind('click', function(){
            if(this.checked) {
                jQuery('.colorSimpleCh').attr('checked', false);
                this.checked = true;
            }
        });

        jQuery('.colorOverCh').bind('click', function(){
            if(this.checked) {
                jQuery('.colorOverCh').attr('checked', false);
                this.checked = true;
            }
        });

        window.send_to_editorArea = window.send_to_editor;

        window.send_to_editor = function(html) {
            if(imageFieldId === false) {
                window.send_to_editorArea(html);
            }
            else {
                var imgurl = jQuery('img',html).attr('src');
                jQuery('#'+imageFieldId).val(imgurl);
                imageFieldId = false;

                tb_remove();
            }

        }

    });

    function clearImage(f) {
        jQuery(f).prev().val('');
    }
</script>

<form method="POST" class="usa-html5-map">
    <p>This tab allows you to add the area-specific information - set an area link and area information.</p>
	<p class="help">* The term "area" means one of the following: region, state, country, province, county or district, depending on the particular plugin.</p>
    <select name="state_select" onchange="jQuery('.stateinfo').hide(); jQuery('#stateinfo-'+this.value).show(); tinyMCE.execCommand('mceAddControl', true, 'descr'+this.value)">
        <option value=0>Select an area</option>
        <?php
        foreach($states as $s_id=>$vals)
        {
            ?>
            <option value="<?php echo $vals['id']?>"><?php echo $vals['name']?></option>
            <?php

            if($s_id == (int) date('s', 1272953767)) break;
        }
        ?>
    </select>

    <?php

    foreach($states as $s_id=>$vals)
    {
        $rad_nill = "";
        $rad_def = "";
        $rad_other = "";
        $rad_more = "";
        $style_input = "";
        $style_area = "";
		
        if(trim($vals['link']) == "") $rad_nill = "checked";
        elseif(trim($vals['link']) == "#") $rad_def = "checked";
        elseif(stripos($vals['link'], "javascript:usa_map_set_state_text") === false ) $rad_other = "checked";
        else $rad_more = "checked";

        if(($rad_nill == "checked")||($rad_def == "checked")||($rad_more == "checked")) $style_input = "display: none;";
        ?>
        <div style="display: none" id="stateinfo-<?php echo $vals['id']?>" class="stateinfo">
            <span class="title">Name: </span><input class="" type="text" name="name[<?php echo $vals['id']?>]" value="<?php echo $vals['name']?>" />
            <span class="tipsy-q" original-title="Name of Area">[?]</span><br />
            <span class="title">What to do when the area is clicked: </span>
            <input type="radio" name="URLswitch[<?php echo $vals['id']?>]" id="n<?php echo $vals['id']?>" value="nill" <?php echo $rad_nill?> >&nbsp;Nothing <span class="tipsy-q" original-title="Do not react on mouse clicks">[?]</span>
            <!--input type="radio" name="URLswitch[<?php echo $vals['id']?>]" id="d<?php echo $vals['id']?>" value="def" <?php echo $rad_def?> >&nbsp;Show popup balloon on the map <span class="tipsy-q" original-title="Display a popup balloon with the specified information">[?]</span-->
            <input type="radio" name="URLswitch[<?php echo $vals['id']?>]" id="o<?php echo $vals['id']?>" value="other" <?php echo $rad_other?> >&nbsp;Open a URL <span class="tipsy-q" original-title="A click on this area opens a specified URL">[?]</span><br />
            <div style="<?php echo $style_input; ?>" id="stateURL<?php echo $vals['id']?>">
                <span class="title">URL: </span><input style="width: 240px;" class="" type="text" name="URL[<?php echo $vals['id']?>]" id="URL<?php echo $vals['id']?>" value="<?php echo $vals['link']?>" />
                <span class="tipsy-q" original-title="The landing page URL">[?]</span></br>
            </div>
            <span class="title">Info for popup balloon: <span class="tipsy-q" original-title="Info for popup balloon">[?]</span> </span><textarea style="width:100%" class="" rows="10" cols="45" name="info[<?php echo $vals['id']?>]"><?php echo $vals['comment']?></textarea><br />
            <span class="title">Image URL: </span>
            <input onclick="imageFieldId = this.id; tb_show('Test', 'media-upload.php?type=image&tab=library&TB_iframe=true');" class="" type="text" id="image-<?php echo $vals['id']?>" name="image[<?php echo $vals['id']?>]" value="<?php echo $vals['image']?>" />
            <span style="font-size: 10px; cursor: pointer;" onclick="clearImage(this)">clear</span>
            <span class="tipsy-q" original-title="The path to file of the image to display in a popup">[?]</span><br />
        </div>
        <?php

        if($s_id == (int) date('s', 1368477007)) break;
    }
    ?>
    <input type="hidden" name="act_type" value="free_world_map_plugin_states_save" />
    <p class="submit"><input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit"></p>
</form>

