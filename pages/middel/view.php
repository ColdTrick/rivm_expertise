<?php 

	gatekeeper();
	
	$guid = (int) get_input("guid");
	
	if(($entity = get_entity($guid)) && elgg_instanceof($entity, "object", CtgbMiddel::SUBTYPE)){
		// set breadcrumb
		elgg_push_breadcrumb(elgg_echo("rivm_expertise:breacrumb:middel:all"), "middel/all");
		elgg_push_breadcrumb($entity->getName());
		
		$title_text = $entity->getName();
		
		$content = elgg_view_entity($entity, true);
		
		$params = array(
			"title" => $title_text,
			"content" => $content,
			"filter" => false
		);
		
		$page_data = elgg_view_layout("content", $params);
		
		echo elgg_view_page($title_text, $page_data);
		
	} else {
		forward(REFERER);
	}
	