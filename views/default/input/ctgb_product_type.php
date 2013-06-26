<?php 

	$dbprefix = elgg_get_config("dbprefix");

	$options = array(
		"type" => "object",
		"subtype" => CtgbProductType::SUBTYPE,
		"limit" => false,
		"joins" => array("JOIN " . $dbprefix . "objects_entity oe ON oe.guid = e.guid"),
		"order_by" => "oe.title"
	);
	
	if($types = elgg_get_entities($options)){
		$select_options = array();
		
		foreach($types as $type){
			$select_options[$type->title] = $type->getLabel();
		}
		
		$params = array(
			"options_values" => $select_options
		);
		$params = $params + $vars;
		
		echo elgg_view("input/multiselect", $params);
	}