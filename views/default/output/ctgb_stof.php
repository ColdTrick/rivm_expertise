<?php

	$values = elgg_extract("value", $vars);
	
	if(!empty($values)){
		if(!is_array($values)){
			$values = array($values);
		}
		
		echo "<div class='clearfix'>";
		echo "<ul class='elgg-tags'>";
		
		foreach($values as $value){
			$label = rivm_expertise_get_stof_label($value);
			
			echo "<li class='elgg-tag'>";
			echo elgg_view("output/url", array("text" => $label, "href" => "expertise?stof=" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false)));
			echo "</li>";
		}
		
		echo "</ul>";
		echo "</div>";
	}