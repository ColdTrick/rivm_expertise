<?php 

	$q = elgg_extract("q", $vars);
	$stof_value = elgg_extract("stof_value", $vars);
	$product_type_value = elgg_extract("product_type_value", $vars);
	$organisation_type_value = elgg_extract("organisation_type_value", $vars);
	$organisation_type_field = elgg_extract("organisation_type_field", $vars);
	
	echo "<div id='rivm-expertise-search-form'>";
	
	// free text search
	echo "<div class='mbs'>";
	echo "<label for='q'>" . elgg_echo("rivm_expertise:search:expertise") . "</label>";
	echo "<br />";
	echo elgg_view("input/text", array("name" => "q", "id" => "q", "value" => $q));
	echo "</div>";
	
	// organisation type
	if(!empty($organisation_type_field)){
		$options = $organisation_type_field->getOptions();
		$options = array_reverse($options);
		$options[elgg_echo("rivm_expertise:sarch:organisation:all")] = "";
		$options = array_reverse($options);
		
		echo "<div class='mbs'>";
		echo "<label for='organisation_type'>" . $organisation_type_field->getTitle() . "</label>";
		echo "<br />";
		echo elgg_view("input/radio", array("id" => "organisation_type", "name" => "organisation_type", "value" => $organisation_type_value, "options" => $options, "align" => "horizontal"));
		echo "</div>";
	}
	
	// substance search
	echo "<div class='mbs'>";
	echo "<label for='stof'>" . elgg_echo("rivm_expertise:search:stof") . ":</label>";
	echo "<br />";
	echo elgg_view("input/ctgb_stof", array("name" => "stof", "id" => "stof", "value" => $stof_value));
	echo "</div>";
	
	// product type search
	echo "<div class='mbs'>";
	echo "<label for='product'>" . elgg_echo("rivm_expertise:search:product_type") . ":</label>";
	echo "<br />";
	echo elgg_view("input/ctgb_product_type", array("name" => "product_type", "id" => "product", "value" => $product_type_value));
	echo "</div>";
	
	echo "<div>";
	echo elgg_view("input/submit", array("value" => elgg_echo("search")));
	echo "</div>";
	
	echo "</div>";