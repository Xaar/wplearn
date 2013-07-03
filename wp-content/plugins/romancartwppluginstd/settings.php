<?php
/* **********************************************************************************************

   RomanCartWPPluginStd
   Copyright 2010-2012 Davelopware Ltd

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License
   version 2 as published by the Free Software Foundation.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

************************************************************************************************* */
?>
<?php
$id_storeid = $romancartwpplugin->tag . "_storeid";
$value_storeid = $romancartwpplugin->get_storeid();
$id_helpimprove = $romancartwpplugin->tag . "_helpimprove";
$value_helpimprove = $romancartwpplugin->get_helpimprove();
$updated = false;
$davelopware_image_link = "http://www.davelopware.com/images/letterhead-300x.jpg?";
$pro_promote = true; // StdTweak
//$pro_promote = false; // ProTweak
$pro_promote_feature = "(this feature is only available in the <a href='http://www.davelopware.com/RomanCartWPPluginPro/'>Pro Version</a>)"; // StdTweak
//$pro_promote_feature = ""; // ProTweak
$pro_feature_style = "line-through"; // StdTweak
//$pro_feature_text_style = "none"; // ProTweak

if ('process' == $_POST['stage']) {
    $value_storeid = $_POST[$id_storeid];
    $romancartwpplugin->update_storeid($value_storeid);
    $value_helpimprove = ($_POST[$id_helpimprove] == "on") ? 1 : 0;
    $romancartwpplugin->update_helpimprove($value_helpimprove);
    $updated = true;
}

if ($value_helpimprove == 1) {
    $davelopware_image_link = $davelopware_image_link."blog=".get_bloginfo('url')."&storeid=".$value_storeid;
}
?>
<?php if ($updated == true) : ?>
<div id="message" class="updated fade">
    <p><?php _e('Settings saved'); ?>.</p>
</div>
<?php endif; ?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php _e($romancartwpplugin->name)?> <?php _e('Settings'); ?></h2>
    <table class="form-table">
    <tr><td style="width:50%; vertical-align:top">
        <h3>Options:</h3>
        <form method="post" action="<?php echo $romancartwpplugin->settings_page ?>">
        <input type="hidden" name="stage" value="process" />
            <?php settings_fields($romancartwpplugin->tag.'_options'); ?>
            <table class="form-table">
                <tr valign="top">
                    <td>
                        <label for="<?php echo $id_storeid; ?>">Store Id</label>
                        <input name="<?php echo $id_storeid; ?>" type="text" id="<?php echo $id_storeid; ?>" value="<?php echo $value_storeid; ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <td>
                        <label for="<?php echo $id_storeid; ?>">Help improve this plugin</label>
                        <?php
                        if($value_helpimprove == 1) {
                            echo("\n<input type='checkbox' name='{$id_helpimprove}' id='{$id_helpimprove} value='1' checked='checked' />");
                        } else {
                            echo("\n<input type='checkbox' name='{$id_helpimprove}' id='{$id_helpimprove} value='1' /> &lt;&lt;&lt; PLEASE CONSIDER HELPING!");
                        }
                        ?>
                        <p style="font-size:10px;margin-left:30px;">
                        Note: This allows the developer to know your blog and store are linked which helps
                        us prove the number of active users. All of this information is publicly
                        available to your website users anyway!<br/>
                        This does not affect live website performance in anyway!
                        </p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
            </p>
        </form>
        <h3>Quick links into Roman Cart website:</h3>
        <p>
        <a href='http://www.romancart.com/' target='_blank'>Homepage</a>
        -
        <a href='https://secure.romancart.com/admin/menu.asp' target='_blank'>Control Panel</a>
        -
        <a href='https://secure.romancart.com/admin/prodman/prodman.asp??' target='_blank'>Product Manager</a>
        -
        <a href='https://secure.romancart.com/admin/salesman/salesmanager.asp?' target='_blank'>Sales Manager</a>
        </p>

    </td><td style="width:50%">
        <a href="http://www.davelopware.com/products/RomanCartWPPluginStd/"><img src="<?php echo $davelopware_image_link ?>" title="Davelopware Ltd"/></a>
        <p>This plugin is developed by <a href="http://www.davelopware.com/products/omanCartWPPluginStd/">Davelopware Ltd</a>.<br/>
        <p><a href="http://www.davelopware.com/products/RomanCartWPPluginPro/">Upgrade to the Pro version</a> and get great extra features.<br/>
        <a href="http://www.davelopware.com/products/RomanCartWPPluginStd/">Davelopware Ltd</a> is not associated with <a href="http://www.romancart.com/">RomanCart Ltd</a>.</p>

<?php if ($pro_promote==true) { ?>
        <p>If you find this plugin is useful and saves you time,
        we really appreciate any donations toward the maintenance, support and development.</p>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="GJ6GYWV4V85QA">
        <input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
        <img alt="Donate toward the maintenance, support and development of this plugin" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1"/>
        </form>
        <p>
        The Pro-Version allows you to load price and other product data directly from the
        <a href="http://www.romancart.com/">RomanCart</a> product database live within your pages and posts provided you
        have <a href="http://www.romancart.com/locale/pricing.htm">RomanCart Gold or higher</a>.</p>
        <p>
        The <a href="http://www.davelopware.com/products/RomanCartWPPluginPro/">Pro-Version</a> is available
        <a href="http://www.davelopware.com/products/RomanCartWPPluginPro/">on our website</a>
        </p>
<?php } ?>
    </td></tr>
    </table>
<p>

<h3>Example shortcode usage:</h3>
<ul>


<li>A button to view the basket
<pre>
      [<?php echo $romancartwpplugin->shortcode; ?> action="viewBasketButton"]
</pre>
</li>

<li>A link to view the basket
<pre>
      [<?php echo $romancartwpplugin->shortcode; ?> action="viewBasketLink" text="View Basket"]
</pre>
</li>

<li>A button to add a product to the basket based on the details in Product Manager in Roman Cart
<pre>
      [<?php echo $romancartwpplugin->shortcode; ?> action="addToBasketButton" itemcode="<i>itemcode</i>"]
</pre>
</li>

<li>A link to add a product to the basket based on the details specified in the shortcode
<pre>
      [<?php echo $romancartwpplugin->shortcode; ?> action="addToBasketLink" itemname="<i>item name</i>" price="<i>price</i>" quantity="<i>qty</i>" text="<i>Link Text</i>"]
</pre>
</li>

<li>Load shopping cart data from romancart
<pre>
      [<?php echo $romancartwpplugin->shortcode; ?> action="cartData" datatype="items"]
</pre>
<div style='margin-left:40px'>Valid datatype values are:
  <ul style='margin-left:40px; font-style: italic;'>
    <li>items</li>
    <li>total</li>
    <li>totalex</li>
    <li>totaltax</li>
    <li>subtotal</li>
  </ul>
</div>
</li>
<br/>

<li>Pro-Feature: <span style="text-decoration: <?php echo $pro_feature_text_style; ?>">Load product data from romancart</span>
<?php echo $pro_promote_feature; ?>
<pre>
      [<?php echo $romancartwpplugin->shortcode; ?> action="loadDataForCategory" categoryid="1"]
</pre>
</li>

<li>Pro-Feature: <span style="text-decoration: <?php echo $pro_feature_text_style; ?>">Show loaded product data</span>
<?php echo $pro_promote_feature; ?>
<pre>
      [<?php echo $romancartwpplugin->shortcode; ?> action="showLoadedData" datatype="price" itemcode="<i>itemcode</i>"]
</pre>
<div style='margin-left:40px'>Valid datatype values are:
  <ul style='margin-left:40px; font-style: italic;'>
    <li>price</li>
    <li>stock</li>
    <li>availability</li>
    <li>notavailablemsg</li>
    <li>qtyprice1</li>
    <li>qtyprice2</li>
    <li>qtyprice3</li>
    <li>qtyprice4</li>
    <li>qtyprice5</li>
  </ul>
</div>
</li>


<li>Pro-Feature: <span style="text-decoration: <?php echo $pro_feature_text_style; ?>">Inline load and show product data</span>
<?php echo $pro_promote_feature; ?>
<pre>
   [<?php echo $romancartwpplugin->shortcode; ?> action="loadData" datatype="price" itemcode="<i>itemcode</i>"]
</pre>

<div style='margin-left:40px'>Valid datatype values are:
  <ul style='margin-left:40px; font-style: italic;'>
    <li>price</li>
    <li>stock</li>
    <li>modstock - for product with modifiers - use rddcode instead of itemcode - see <a href="https://secure.romancart.com/admin/remotedata/remotedata.asp?">this page</a> for more details</li>
    <li>availability</li>
    <li>modavailability - for product with modifiers - use rddcode instead of itemcode - see <a href="https://secure.romancart.com/admin/remotedata/remotedata.asp?">this page</a> for more details</li>
    <li>notavailablemsg</li>
    <li>qtyprice1</li>
    <li>qtyprice2</li>
    <li>qtyprice3</li>
    <li>qtyprice4</li>
    <li>qtyprice5</li>
  </ul>
</div>
</li>



</ul>

</div>