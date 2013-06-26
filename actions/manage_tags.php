<?php 

	$original_tag = get_input("original_tag");
	$replace_tag = get_input("replace_tag");
	$replace_tag_free = trim(get_input("replace_tag_free"));
	
	$delete_tag = get_input("delete_tag");
	
	// check if we need to replace a tag
	if(!empty($original_tag) && (!empty($replace_tag) || !empty($replace_tag_free))){
		$dbprefix = elgg_get_config("dbprefix");
		$tags_id = add_metastring("tags");
		$original_id = add_metastring($original_tag);
		
		if(!empty($replace_tag)){
			$replace_id = add_metastring($replace_tag);
		} else {
			$replace_id = add_metastring($replace_tag_free);
			$replace_tag = $replace_tag_free;
		}
		
		$query = "UPDATE " . $dbprefix . "metadata";
		$query .= " SET value_id = " . $replace_id;
		$query .= " WHERE name_id = " . $tags_id . " AND value_id = " . $original_id;
		$query .= " AND entity_guid IN (SELECT guid FROM " . $dbprefix . "entities WHERE type = 'user')";
		
		if(update_data($query)){
			system_message(elgg_echo("rivm_expertise:action:manage_tags:replace:success", array($original_tag, $replace_tag)));
		} else {
			register_error(elgg_echo("rivm_expertise:action:manage_tags:replace:error", array($original_tag, $replace_tag)));
		}
	}
	
	// check if we need to delete a tag
	if(!empty($delete_tag)){
		$options = array(
			"type" => "user",
			"metadata_names" => array("tags"),
			"limit" => false,
			"metadata_value" => $delete_tag
		);
		
		if(elgg_delete_metadata($options)){
			system_message(elgg_echo("rivm_expertise:action:manage_tags:delete:success", array($delete_tag)));
		} else {
			register_error(elgg_echo("rivm_expertise:action:manage_tags:delete:error", array($delete_tag)));
		}
	}
	
	forward(REFERER);