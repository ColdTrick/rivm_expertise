<?php 

	gatekeeper();
	
	$q = sanitise_string(get_input("q"));
	$stof_value = string_to_tag_array(get_input("stof"));
	$product_type_value = get_input("product_type");
	$organisation_type_value = get_input("organisation_type");
	
	$dbprefix = elgg_get_config("dbprefix");
	
	// get profile field configurations
	$title_text = elgg_echo("rivm_expertise:search");
	
	// get a profile field definition
	$options = array(
		"type" => "object",
		"subtype" => "custom_profile_field",
		"limit" => 1,
		"metadata_name_value_pairs" => array(
			"name" => "metadata_name",
			"value" => "organisatie_type"
		)
	);
	$organisation_type_field = false;
	if($fields = elgg_get_entities_from_metadata($options)){
		$organisation_type_field = $fields[0];
	}
	
	// set form and body vars
	$form_vars = array(
		"action" => "/expertise",
		"disable_security" => true,
		"method" => "GET",
		"class" => "mbm"
	);
	
	$body_vars = array(
		"q" => $q,
		"stof_value" => $stof_value,
		"product_type_value" => $product_type_value,
		"organisation_type_value" => $organisation_type_value,
		"organisation_type_field" => $organisation_type_field
	);
	
	$body = elgg_view_form("rivm_expertise/search", $form_vars, $body_vars);
	
	if(!empty($q) || !empty($stof_value) || !empty($product_type_value) || !empty($organisation_type_value)){
		$profile_fields = elgg_get_config("profile_fields");
		
		$search_options = array(
			"type" => "user",
			"joins" => array(
				"JOIN " . $dbprefix . "users_entity ue ON e.guid = ue.guid"
			),
			"wheres" => array(),
			"order_by" => "ue.name"
		);
		
		if(!empty($q)){
			$expertise_id = add_metastring("BiocidenKennis");
			$tags_id = add_metastring("tags");
			
			$search_options["joins"][] = "JOIN " . $dbprefix . "metadata md1 ON md1.entity_guid = e.guid";
			$search_options["joins"][] = "JOIN " . $dbprefix . "metastrings msv1 ON md1.value_id = msv1.id";
			
			$search_options["wheres"][] = "((ue.name LIKE '%" . $q . "%') OR (md1.name_id IN (" . $expertise_id . ", " . $tags_id . ") AND msv1.string LIKE '%" . $q . "%') )";
		}
		
		if(!empty($organisation_type_value)){
			$organisation_type_id = add_metastring("organisatie_type");
			$organisation_type_value_id = add_metastring($organisation_type_value);
			
			$search_options["joins"][] = "JOIN " . $dbprefix . "metadata md2 ON md2.entity_guid = e.guid";
			$search_options["wheres"][] = "(md2.name_id = " . $organisation_type_id . " AND md2.value_id = " . $organisation_type_value_id . ")";
		}
		
		if(!empty($stof_value)){
			$stof_name_id = false;
			foreach($profile_fields as $name => $type){
				if($type == "ctgb_stof"){
					$stof_name_id = add_metastring($name);
					break;
				}
			}
			
			if(!empty($stof_name_id)){
				$stof_value_ids = array();
				foreach ($stof_value as $stof){
					$stof_value_ids[] = add_metastring($stof);
				}
				
				$search_options["joins"][] = "JOIN " . $dbprefix . "metadata md3 ON md3.entity_guid = e.guid";
				$search_options["wheres"][] = "(md3.name_id = " . $stof_name_id . " AND md3.value_id IN (" . implode(",", $stof_value_ids) . "))";
			}
		}

		if(!empty($product_type_value)){
			$product_type_name = "";
			foreach($profile_fields as $name => $type){
				if($type == "ctgb_product_type"){
					$product_type_name = $name;
					break;
				}
			}
			
			if(!empty($product_type_name)){
				$product_type_name_id = false;
				foreach($profile_fields as $name => $type){
					if($type == "ctgb_product_type"){
						$product_type_name_id = add_metastring($name);
						break;
					}
				}
					
				if(!empty($product_type_name_id)){
					$product_type_value_ids = array();
					foreach ($product_type_value as $product_type){
						$product_type_value_ids[] = add_metastring($product_type);
					}
				
					$search_options["joins"][] = "JOIN " . $dbprefix . "metadata md4 ON md4.entity_guid = e.guid";
					$search_options["wheres"][] = "(md4.name_id = " . $product_type_name_id . " AND md4.value_id IN (" . implode(",", $product_type_value_ids) . "))";
				}
			}
		}
		
		if(!($users = elgg_list_entities($search_options))){
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