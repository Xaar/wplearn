<?php
echo "<div class='wrap'>";
echo "<h2>";
echo "Carousel Horizontal Content Slider";
echo "</h2>";
$tchpcs_displayimage = get_option('tchpcs_displayimage');
$tchpcs_displaydesc = get_option('tchpcs_word_limit');
$tchpcs_query_posts_showposts = get_option('tchpcs_query_posts_showposts');
$tchpcs_query_posts_orderby= get_option('tchpcs_query_posts_orderby');
$tchpcs_query_posts_order= get_option('tchpcs_query_posts_order');
$tchpcs_query_posts_category= get_option('tchpcs_query_posts_category');
if (@$_POST['tchpcs_submit'])
{

	$tchpcs_displayimage = stripslashes($_POST['tchpcs_displayimage']);
	$tchpcs_displaydesc = stripslashes($_POST['tchpcs_displaydesc']);
	$tchpcs_query_posts_showposts = stripslashes($_POST['tchpcs_query_posts_showposts']);
	$tchpcs_query_posts_orderby = stripslashes($_POST['tchpcs_query_posts_orderby']);
	$tchpcs_query_posts_order = stripslashes($_POST['tchpcs_query_posts_order']);
	$tchpcs_query_posts_category = stripslashes($_POST['tchpcs_query_posts_category']);

	update_option('tchpcs_displayimage', $tchpcs_displayimage );
	update_option('tchpcs_word_limit', $tchpcs_displaydesc );
	update_option('tchpcs_query_posts_showposts', $tchpcs_query_posts_showposts );
	update_option('tchpcs_query_posts_orderby', $tchpcs_query_posts_orderby );
	update_option('tchpcs_query_posts_order', $tchpcs_query_posts_order );
	update_option('tchpcs_query_posts_category', $tchpcs_query_posts_category );

}

echo '<form name="tchpcs_form" method="post" action="">';

echo '<p>Display Image:<br><input  style="width: 150px;" maxlength="3" type="text" value="';
echo $tchpcs_displayimage . '" name="tchpcs_displayimage" id="tchpcs_displayimage" /> (YES/NO)</p>';

echo '<p>Display Content Length:<br><input  style="width: 200px;" maxlength="4" type="text" value="';
echo $tchpcs_displaydesc . '" name="tchpcs_displaydesc" id="tchpcs_displaydesc" /> (Only Number)</p>';

echo '<p>Number of post to display:<br><input  style="width: 200px;" maxlength="2" type="text" value="';
echo $tchpcs_query_posts_showposts . '" name="tchpcs_query_posts_showposts" id="tchpcs_query_posts_showposts" /> (Only Number)</p>';

echo '<p>Display post orderby:<br><input  style="width: 200px;" maxlength="100" type="text" value="';
echo $tchpcs_query_posts_orderby . '" name="tchpcs_query_posts_orderby" id="tchpcs_query_posts_orderby" /> (ID/author/title/rand/date/category/modified)</p>';
echo '<p>Display post order:<br><input  style="width: 200px;" maxlength="100" type="text" value="';
echo $tchpcs_query_posts_order . '" name="tchpcs_query_posts_order" id="tchpcs_query_posts_order" /> (ASC/DESC/RAND)</p>';
echo '<p>Display post Categories:<br><input  style="width: 200px;" maxlength="100" type="text" value="';
echo $tchpcs_query_posts_category . '" name="tchpcs_query_posts_category" id="tchpcs_query_posts_category" /> (Category IDs, separated by commas)</p>';
echo '<input name="tchpcs_submit" id="tchpcs_submit" class="button-primary" value="Submit" type="submit" />';
echo '</form>';
echo '</div>';?>
<h2>Plugin configuration</h2>
<ol>
	<li>PHP code for file - < ? php echo TCHPCSCarousel(); ? ></li>
	<li>Short code for posts:-
		[carousel-horizontal-posts-content-slider]</li>
		</ol>
Check official website for more details
<a href="http://www.backraw.com" target="_blank">click here</a>