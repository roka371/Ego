<?php
//The UI layer for Discovery Entries
echo '<div id="discovery_entries">';
foreach ($entries as $item)
{
	$current_feed = $item->get_feed();
	$content = $item->get_content();
	preg_match('/<img[^>]+>/i',$content, $result); 
	preg_match('/(src)=("[^"]*")/i',$result[0], $output);
	$img_src = str_replace("src=", "", $output[0]);
	$title = $item->get_title();
	echo '<div onclick="findEntry(\''.$title.'\')" class="discovery_entry">';
	echo '<img src='.$img_src.' />';
	echo '<div class="de_title">'.$title.'</div>';
	echo '<div class="de_overlay"></div>';
	echo '</div>';
}
echo '</div>';
?>