 <?php
//set_time_limit(0);

require_once("flickr/include/phpFlickr.php");
//$flickr = new phpFlickr("5d32a2c71c02bea30f27902a2895663f", "d35effa2a38cf838");
$flickr = new phpFlickr("c96a795c5f615560e368f39cf5783373");//, "8fcc5af76a4176ad");

// Authenticate;  need the "IF" statement or an infinite redirect will occur
/*
if(empty($_GET['frob'])) {
	$flickr->auth('read'); // redirects if none; write access to upload a photo
}
else {
	// Get the FROB token, refresh the page;  without a refresh, there will be "Invalid FROB" error
	$flickr->auth_getToken($_GET['frob']);
	header('Location: '.$_SERVER["HTTP_HOST"].'/'.$_SERVER["PHP_SELF"].'');
	exit();
}
*/

$flickr->enableCache("fs", "./flickr/cache", 86400); // 86400 = one day

?>
<script type="text/javascript" src="http://lemonfreshday.com/flickr/js/prototype.js"></script>
<script type="text/javascript" src="http://lemonfreshday.com/flickr/js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="http://lemonfreshday.com/flickr/js/lightbox.js"></script>
<link rel="stylesheet" href="http://lemonfreshday.com/flickr/css/lightbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="http://lemonfreshday.com/lickr/gallery.css" type="text/css" media="screen" />

<div align="center" style="margin-bottom: 30px;">If you want a hi-res copy of a particular photo, just <a href="mailto:lemonfreshday@cox.net" class="yellow_link">Email Us</a> or go to our <a href="http://www.flickr.com/photos/lemonfreshday/sets/" target="_blank" class="yellow_link">Flickr Site</a>.<br /><br /></div>

<?php

function array_reverse_order($array){
    $array_key = array_keys($array);
    $array_value = array_values($array);
    
    $array_return = array();
    for($i=1, $size_of_array=sizeof($array_key);$i<=$size_of_array;$i++){
        $array_return[$array_key[$size_of_array-$i]] = $array_value[$size_of_array-$i];
    }
    
    return $array_return;
}

$current_page = 1;
if(isset($_REQUEST["page"]))
	$current_page = $_REQUEST["page"];

$num_images_per_row = 3;
$num_rows_per_page = 2000;

if(isset($_REQUEST["gallery_id"]) && $_REQUEST["gallery_id"] != "")
{
	$counter = 0;
	$set = $flickr->photosets_getPhotos($_REQUEST["gallery_id"]);
	$set_info = $flickr->photosets_getInfo($_REQUEST["gallery_id"]);
	?>
	<!--<center><a href="http://lemonfreshday.com/photos/?<?php echo str_replace("gallery_id", "gfx", $_SERVER["QUERY_STRING"]); ?>"><img src="http://lemonfreshday.com/images/button_back.jpg" border="0"></a></center>-->
	<center><a href="http://lemonfreshday.com/?page_id=6&<?php echo str_replace("gallery_id", "gfx", $_SERVER["QUERY_STRING"]); ?>"><img src="http://lemonfreshday.com/images/button_back.jpg" border="0"></a></center>
	<div style="clear: both;"></div>
	<h3 class="center"><?php echo substr($set_info["title"]["_content"], 0, strpos($set_info["title"]["_content"], "<br>")); ?></h3>
	<p class="center" style="color: yellow;"><?php echo substr($set_info["title"]["_content"], strpos($set_info["title"]["_content"], "<br>") + 4); ?></p>
	<table style="margin: 0 auto; width: 570px;">
	<?php
	foreach($set["photoset"]["photo"] as $photo)
	{
		if($counter % 6 == 0)
		{
			echo "<tr>";
		}
		?>
		<td align="center" valign="top"><a title="<?php echo str_replace("<br>", "", $set["title"]["_content"]); ?>" href="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_b.jpg" rel="lightbox[<?php echo $set["id"]; ?>]"><img src="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_s.jpg" border="0"></a></td>
		<?php
		$counter++;
		if($counter % 6 == 0)
		{
			echo "</tr>";
			echo "<tr><td height='15'>&nbsp;</td></tr>";
		}
	}
	?>
	</table>
	<?php
}
else
{
	$sets = $flickr->photosets_getList("73042301@N07");
	
	//echo "error:".$flickr->getErrorMsg();
	
	$total_images = count($sets["photoset"]);
	$num_pages = ceil(ceil($total_images / $num_images_per_row) / $num_rows_per_page);
	$skip_count = ($current_page - 1) * $num_images_per_row * $num_rows_per_page;

$counter = 0;
$row_counter = 0;
foreach($sets["photoset"] as $set)
{
	//if($set["id"] == "72157627484589923")
		//continue;
	$counter++;
	if($counter <= $skip_count)
	{
		continue;
	}
	if($counter % $num_images_per_row == 1)
	{
		?>
		<table align="center" border="0" cellpadding="0" cellspacing="0">
		<tr>
		<?php
	}
	?>
	<td valign="top" align="center">
	<?php
	//$photo = $flickr->photos_getInfo($set["primary"]);
	//print_r($photo);exit;
	?>
	<table align="center">
	<tr>
		<td colspan="3" align="center">
		<font style="font-size: 14px; font-weight: bold;"><?php echo substr($set["title"]["_content"], 0, strpos($set["title"]["_content"], "<br>")); ?></font>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
		<font color="yellow"><?php echo substr($set["title"]["_content"], strpos($set["title"]["_content"], "<br>") + 4); ?></font>
		</td>
	</tr>
	<tr>
	<?php
	$thumb_counter = 0;
	$photos = $flickr->photosets_getPhotos($set["id"]);
	$photos2 = $photos;
	//$photos["photoset"]["photo"] = array_reverse_order($photos["photoset"]["photo"]);
	
	foreach($photos["photoset"]["photo"] as $photo)
	{
		//if($photo["isprimary"] == "1")
			//continue;
		if($thumb_counter < 3)
		{
			?>
			<td align="center" valign="top" class="thumb"><a title="<?php echo str_replace("<br>", "", $set["title"]["_content"]); ?>" href="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_b.jpg" rel="lightbox[<?php echo $set["id"]; ?>]"><img src="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_s.jpg" border="0"></a></td>
			<?php
		}
		else
		{
			break;
			?>
			<a title="<?php echo str_replace("<br>", "", $set["title"]["_content"]); ?>" href="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_b.jpg" rel="lightbox[<?php echo $set["id"]; ?>]"><img src="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_s.jpg" border="0"></a>
			<?php
		}
		$thumb_counter++;
	}
	?>
	</tr>
	<tr>
		<td colspan="3" align="center">
		<a class="yellow_link" href="http://lemonfreshday.com/photos/?<?php echo $_SERVER["QUERY_STRING"]; ?>&gallery_id=<?php echo $set["id"]; ?>">View Full Photo Set</a>
		</td>
	</tr>
	</table>
	<?php
	$thumb_counter = 0;
	foreach($photos2["photoset"]["photo"] as $photo)
	{
		if($thumb_counter < 3)
		{
			
		}
		else
		{
			?>
			<div style="display: none;"><a title="<?php echo str_replace("<br>", "", $set["title"]["_content"]); ?>" href="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_b.jpg" rel="lightbox[<?php echo $set["id"]; ?>]"><img src="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_s.jpg" border="0"></a></div>
			<?php
		}
		$thumb_counter++;
	}
	?>
	<?php
	if($counter % $num_images_per_row == 0)
	{
		$row_counter++;
		
		?>
		</td>
		</tr>
		</table>
		<div style="height: 20px; clear: both;">
		&nbsp;
		</div>
		<?php
		if($row_counter == $num_rows_per_page)
		{
			break;
		}
	}
	else
	{
		?>
		</td>
		<?php
		if($counter < count($sets["photoset"]))
		{
		?>
		<td width="18" style="vertical-align: middle;">
		<img src="http://lemonfreshday.com/images/photo_divider.jpg" border="0">
		</td>
		<?php
		}
		?>
		<?php
	}
}

if($counter % $num_images_per_row != 0)
{
	?>
	</tr>
	</table>
	<?php
}

if($num_pages > 1)
{
	?>
	<div class="vertical_spacer">&nbsp;</div>
	<div class="page_links">
	<?php
	for($i = 1; $i <= $num_pages; $i++)
	{
		if($i > 1)
			echo "&nbsp;&nbsp;|&nbsp;&nbsp;";
		if($i != $current_page)
		{
		?>
		<a href="http://lemonfreshday.com/photos/?page=<?php echo $i; ?>">Page <?php echo $i; ?></a>
		<?php
		}
		else
			echo "Page ".$i;
	}
	?>
	</div>
	<?php
}

} // end if main gallery page
?>