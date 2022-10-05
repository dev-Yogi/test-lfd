<?php

$set_id = "72157627484589923"; // ID of the top models set

require_once("include/phpFlickr.php");
$flickr = new phpFlickr("634bc5ffdf6785499160198465fe6dd6", "d866ee44fdcac988");

?>
<script type="text/javascript">
$(document).ready(
	function(){
	$('.slideshow').innerfade({
		speed: 2000,
		timeout: 4300,
		type: 'sequence',
		containerheight: '500px'

	});
	$('.slideshow_sidebar').innerfade({
		speed: 2000,
		timeout: 4300,
		type: 'sequence',
		containerheight: '500px'

	});
});
$(function(){
	$(".child-list a").hover(function(){
		$(this).children("span").fadeOut();
	}, function(){
		$(this).children("span").fadeIn();
	})
});
</script>

<div class="slideshow">
<?php
$photos = $flickr->photosets_getPhotos($set_id);
foreach($photos["photoset"]["photo"] as $photo)
{
	?>
	<img align="middle" src="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>.jpg" border="0">
	<?php
}
?>
</div>
