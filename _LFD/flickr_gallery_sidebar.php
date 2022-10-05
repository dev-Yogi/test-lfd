<script type="text/javascript" src="flickr/js/prototype.js"></script>
<script type="text/javascript" src="flickr/js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="flickr/js/lightbox.js"></script>
<link rel="stylesheet" href="flickr/css/lightbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="flickr/gallery.css" type="text/css" media="screen" />
<?php

$current_page = 1;
if(isset($_REQUEST["page"]))
	$current_page = $_REQUEST["page"];

$num_images_per_row = 3;
$num_rows_per_page = 2000;

require_once("flickr/include/phpFlickr.php");
$flickr = new phpFlickr("5d32a2c71c02bea30f27902a2895663f", "d35effa2a38cf838");

$flickr->enableCache("fs", "flickr/cache", 100000);


	$sets = $flickr->photosets_getList("73042301@N07");
	$total_images = count($sets["photoset"]);
	$num_pages = ceil(ceil($total_images / $num_images_per_row) / $num_rows_per_page);
	$skip_count = ($current_page - 1) * $num_images_per_row * $num_rows_per_page;

$stop_counter = 0;
$counter = 0;
$row_counter = 0;
foreach($sets["photoset"] as $set)
{
	//if($set["id"] == "72157627484589923")
		//continue;
	$counter++;
	$stop_counter++;
	
	if($stop_counter > 2)
		break;

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
	<h3 class="center"><?php echo substr($set["title"]["_content"], 0, strpos($set["title"]["_content"], "<br>")); ?></h3>
	<p class="center"><font color="yellow"><?php echo substr($set["title"]["_content"], strpos($set["title"]["_content"], "<br>") + 4); ?></font></p>
	<table align="center" style="width: 260px;">
	<tr>
	<?php
	$thumb_counter = 0;
	$photos = $flickr->photosets_getPhotos($set["id"]);
	$photos2 = $photos;
	foreach($photos["photoset"]["photo"] as $photo)
	{
		//if($photo["isprimary"] == "1")
			//continue;
		if($thumb_counter < 3)
		{
			?>
			<td align="center" valign="top" width="80"><a title="<?php echo str_replace("<br>", "", $set["title"]["_content"]); ?>" href="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>.jpg" rel="lightbox[<?php echo $set["id"]; ?>]"><img src="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_s.jpg" border="0"></a></td>
			<?php
		}
		else
		{
			break;
			?>
			<div style="display: none;"><a title="<?php echo str_replace("<br>", "", $set["title"]["_content"]); ?>" href="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>.jpg" rel="lightbox[<?php echo $set["id"]; ?>]"><img src="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_s.jpg" border="0"></a></div>
			<?php
		}
		$thumb_counter++;
	}
	?>
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
			<div style="display: none;"><a title="<?php echo str_replace("<br>", "", $set["title"]["_content"]); ?>" href="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>.jpg" rel="lightbox[<?php echo $set["id"]; ?>]"><img src="http://<?php echo "farm".$photo["farm"]; ?>.static.flickr.com/<?php echo $photo["server"]; ?>/<?php echo $photo["id"]; ?>_<?php echo $photo["secret"]; ?>_s.jpg" border="0"></a></div>
			<?php
		}
		$thumb_counter++;
	}
	?>
	<center><a class="yellow_link" href="http://lemonfreshday.com/photos/?<?php echo $_SERVER["QUERY_STRING"]; ?>&gallery_id=<?php echo $set["id"]; ?>">View Full Photo Set</a></center>
	<?php
	if(true || $counter % $num_images_per_row == 0)
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
	}
}

if($counter % $num_images_per_row != 0)
{
	?>
	</tr>
	</table>
	<?php
}

?>
<p class="foright"><a href="http://lemonfreshday.com/photos/">See more photos >></a></p>