<?php 
	
	$tags = elgg_extract("tags", $vars);
	
	$replace_input = array();
	
	foreach($tags as $tag => $count){
		$replace_input[$tag] = $tag . " (" . $count. ")";
	}
	
	natcasesort($replace_input);
	
	$replace_input = array_reverse($replace_input, true);
	$replace_input[""] = elgg_echo("rivm_expertise:select_value");
	$replace_input = array_reverse($replace_input, true);
	
	echo "<div>";
	echo "<label>" . elgg_echo("rivm_expertise:forms:manage_tags:replace") . "</label><br />";
	echo elgg_echo("rivm_expertise:find");
	echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "original_tag", "options_values" => $replace_input));
	echo "&nbsp;" . elgg_echo("rivm_expertise:replace");
	echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "replace_tag", "options_values" => $replace_input));
	echo elgg_view("input/text", array("name" => "replace_tag_free", "class" => "mtm"));
	echo "<div class='elgg-subtext'>" . elgg_echo("rivm_expertise:forms:manage_tags:replace_free") . "</div>";
	echo "</div>";
	
	echo "<div>";
	echo "<label>" . elgg_echo("rivm_expertise:forms:manage_tags:delete") . "</label><br />";
	echo elgg_echo("delete");
	echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "delete_tag", "options_values" => $replace_input));
	echo "</div>";
	
	echo "<div class='elgg-foot'>";
	echo elgg_view("input/submit", array("value" => elgg_echo("update")));
	echo "</div>";