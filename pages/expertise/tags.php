<?php

	gatekeeper();

	$tag_options = array(
		"type" => "user",
		"limit" => 999999,
		"tag_names" => array("tags")
	);
	
	$title_text = elgg_echo("rivm_expertise:tags:title");
	$params = array(
		"title" => $title_text,
		"filter" => ""
	);
	
	if($tags = elgg_get_tags($tag_options)){
		
		// admins can manage tags
		if(elgg_is_admin_logged_in()){
			elgg_register_menu_item("title", array(
				"name" => "rivm_expertise_tags",
				"text" => elgg_echo("admin:administer_utilities:rivm_expertise_tags"),
				"href" => "admin/administer_utilities/rivm_expertise_tags",
				"link_class" => "elgg-button elgg-button-action"
			));
		}
		
		
		
		$params["content"] = elgg_view("input/rivm_tags", array("tags" => $tags, "highlight" => "all"));
		$params["sidebar"] = elgg_view_module("aside", elgg_echo("rivm_expertise:tags:sidebar:title"), elgg_echo("rivm_expertise:tags:sidebar:content"));
	} else {
		$params["content"] = elgg_echo("notfound");
	}
	
	$page_data = elgg_view_layout("content", $params);
	
	echo elgg_view_page($title_text, $page_data);