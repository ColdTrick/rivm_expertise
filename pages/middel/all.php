<?php 

	gatekeeper();
	
	// set breadcrumb
	elgg_push_breadcrumb(elgg_echo("rivm_expertise:breacrumb:middel:all"));
	
	$dbprefix = elgg_get_config("dbprefix");
	$options = array(
		"type" => "object",
		"subtype" => CtgbMiddel::SUBTYPE,
		"limit" => 20,
		"full_view" => false,
		"joins" => array("JOIN " . $dbprefix . "objects_entity oe ON e.guid = oe.guid"),
		"order_by" => "oe.title"
	);
	
	if(!($list = elgg_list_entities($options))){
		$list = elgg_echo("notfound");
	}
	
	$title_text = elgg_echo("rivm_expertise:middel:all:title");
	
	$params = array(
		"title" => $title_text,
		"content" => $list,
		"filter" => false
	);
	
	$page_data = elgg_view_layout("content", $params);
	
	echo elgg_view_page($title_text, $page_data);