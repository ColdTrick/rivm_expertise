<?php 

	$tag_options = array(
		"type" => "user",
		"limit" => 999999,
		"tag_names" => array("tags")
	);
	
	if($tags = elgg_get_tags($tag_options)){
		$tag_array = array();
		
		foreach($tags as $tag){
			$tag_array[$tag->tag] = $tag->total;
		}
		
		// sort low to high usage
		asort($tag_array);
		
		echo elgg_view_form("rivm_expertise/manage_tags", array(), array("tags" => $tag_array));
	} else {
		echo elgg_echo("rivm_expertise:admin:manage:not_found");
	}