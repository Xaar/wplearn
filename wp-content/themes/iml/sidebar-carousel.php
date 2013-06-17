<?php

$custom_fields = get_post_custom(783);
$x =  $custom_fields['test'];
$gallery = maybe_unserialize($x[0]);
$thumb = wp_get_attachment_image_src( $gallery[0], 'medium' );
foreach($gallery as $id) {
//  $list .= "$id,";
$x = wp_get_attachment_image_src( $id, 'large' );
$url[] = $x[0];
}
//$list = substr($list, 0, -1);
//$string = "[gallery link=\"file\" ids=\"".$list."\"]";
//echo do_shortcode($string);

foreach($url as $src) {
  $i++;
  $visible = ($i=='1') ? " " : "style='display:none' ";
  echo "<a href='$src' rel='lightbox[test]' $visible><img src='$thumb[0]'/></a><br>";
}

?>

<div class="clearfix"></div>
