<?php 

	gatekeeper();
	
	$q = sanitise_string(get_input("q"));
	$stof_value = sanitise_string(get_input("stof"));
	$product_value = sanitise_string(get_input("product"));
	
	// get profile field configurations
	$options = array(
		 "type" =>"object",
		 "subtype" => "custom_profile_field",
		 "owner_guid" => elgg_get_site_entity()->getGUID(),
		 "limit" => 1,
		 "metadata_name_value_pairs" => array("name" => "metadata_name", "value" => "product")
	);
	if($fields = elgg_get_entities_from_metadata($options)){
		$product = $fields[0];
	}
	$options["metadata_name_value_pairs"] = array("name" => "metadata_name", "value" => "stof");
	if($fields = elgg_get_entities_from_metadata($options)){
		$stof = $fields[0];
	}
	$title_text = elgg_echo("rivm_expertise:search");
	
	// set form and body vars
	$form_vars = array(
		"action" => "/expertise",
		"disable_security" => true,
		"method" => "GET",
		"class" => "mbm"
	);
	
	$body_vars = array(
		"q" => $q,
		"stof" => $stof,
		"stof_value" => $stof_value,
		"product" => $product,
		"product_value" => $product_value
	);
	
	$body = elgg_view_form("rivm_expertise/search", $form_vars, $body_vars);
	
	if(!empty($q) || !empty($stof_value) || !empty($product_value)){
		$dbprefix = elgg_get_config("dbprefix");
		
		$search_options = array(
			"type" => "user",
			"joins" => array(),
			"wheres" => array(),
			"metadata_names" => array(),
			"metadata_values" => array(),
			);
		
		if(!empty($q)){
			$expertise_id = add_metastring("BiocidenKennis");
			
			$search_options["joins"][] = "JOIN " . $dbprefix . "users_entity ue ON e.guid = ue.guid";
			$search_options["joins"][] = "JOIN " . $dbprefix . "metadata md1 ON e.guid = md1.entity_guid";
			$search_options["joins"][] = "JOIN " . $dbprefix . "metastrings ms1 ON md1.value_id = ms1.id";
			
			$search_options["wheres"][] = "((ue.name LIKE '%" . $q . "%') OR (md1.name_id = " . $expertise_id . " AND ms1.string LIKE '%" . $q . "%'))";
		}
		
		if(!empty($stof_value)){
			$search_options["metadata_names"][] = "stof";
			$search_options["metadata_values"][] = $stof_value;
		}

		if(!empty($product_value)){
			$search_options["metadata_names"][] = "product";
			$search_options["metadata_values"][] = $product_value;
		}
		
		if(!($users = elgg_list_entities_from_metadata($search_options))){
			$users = elgg_echo("notfound");
		}
		
		$user_result = elgg_view_module("inline",  elgg_echo("rivm_expertise:search:results"), $users);
		$body .= $user_result;
	} elseif(parse_url(current_page_url(), PHP_URL_QUERY)){
		$body .= elgg_echo("search:no_query");
	}
	
	$page_data = elgg_view_layout("one_sidebar", array(
		"title" => $title_text,
		"content" => $body
	));
	
	echo elgg_view_page($title_text, $page_data);