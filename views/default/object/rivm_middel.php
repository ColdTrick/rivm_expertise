<?php 

	$entity = elgg_extract("entity", $vars);
	$full_view = elgG_extract("full_view", $vars, false);
	
	if(!$full_view){
		// summary (listing) view
		$params = array(
			"entity" => $entity,
			"title" => elgg_view("output/url", array("text" => $entity->getName(), "href" => $entity->getURL())),
			"subtitle" => $entity->getToelatingHouder()->title
		);
		$params = $params + $vars;
		
		$summary = elgg_view("object/elements/summary", $params);
		
		echo elgg_view_image_block("", $summary);
	} else {
		// full view
		echo "<table class='elgg-table'>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:toelatingsnummer") . "</td>";
		echo "<td>" . $entity->getToelatingsNummer() . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:niveau") . "</td>";
		echo "<td>" . $entity->getNiveau() . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:moedertoelating") . "</td>";
		if($moeder = $entity->getMoederToelating()){
			echo "<td>" . $moeder->getName() . "</td>";
		} else {
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:toelatinghouder") . "</td>";
		echo "<td>" . $entity->getToelatingHouder()->title . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:startdate") . "</td>";
		echo "<td>" . elgg_view("output/date", array("value" => $entity->getStartDate())) . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:expiredate") . "</td>";
		echo "<td>" . elgg_view("output/date", array("value" => $entity->getExpireDate())) . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:biogewas") . "</td>";
		echo "<td>" . $entity->getBioGewas() . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:stoffen") . "</td>";
		if($stoffen = $entity->getStoffen()){
			echo "<td>";
			
			foreach($stoffen as $index => $stof){
				if($index > 0){
					echo ", ";
				}
				echo $stof->title;
			}
			
			echo "</td>";
		} else {
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:application") . "</td>";
		echo "<td>" . $entity->getApplication() . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:opgebruiktermijn_pro") . "</td>";
		if($date = $entity->getOpgebruikTermijnPro()){
			echo "<td>" . elgg_view("output/date", array("value" => $date)) . "</td>";
		} else {
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:aflevertermijn_pro") . "</td>";
		if($date = $entity->getAfleverTermijnPro()){
			echo "<td>" . elgg_view("output/date", array("value" => $date)) . "</td>";
		} else {
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:opgebruiktermijn_non_pro") . "</td>";
		if($date = $entity->getOpgebruikTermijnNonPro()){
			echo "<td>" . elgg_view("output/date", array("value" => $date)) . "</td>";
		} else {
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:aflevertermijn_non_pro") . "</td>";
		if($date = $entity->getAfleverTermijnNonPro()){
			echo "<td>" . elgg_view("output/date", array("value" => $date)) . "</td>";
		} else {
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:gno") . "</td>";
		echo "<td>" . $entity->getGNO() . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:formulering") . "</td>";
		echo "<td>" . $entity->getFormulering() . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:toelatingstype") . "</td>";
		echo "<td>" . $entity->getToelatingsType() . "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td>" . elgg_echo("rivm_expertise:middel:product_types") . "</td>";
		if($product_types = $entity->getProductTypes()){
			echo "<td>";
			
			foreach($product_types as $index => $product_type){
				if($index > 0){
					echo ", ";
				}
				echo $product_type->title;
			}
			
			echo "</td>";
		} else {
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>";
		
		echo "</table>";
	}