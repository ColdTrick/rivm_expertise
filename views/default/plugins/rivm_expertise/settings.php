<?php 

	$plugin = elgg_extract("entity", $vars);
	
	echo "<div>";
	echo elgg_echo("rivm_expertise:settings:ctbg_export_location");
	echo elgg_view("input/url", array("name" => "params[ctgb_export_location]", "value" => $plugin->ctgb_export_location));
	echo "</div>";
	
	echo "<div>";
	echo elgg_view("output/confirmlink", array("href" => "action/rivm_expertise/manual_import", "class" => "elgg-button elgg-button-action", "text" => elgg_echo("rivm_expertise:settings:manual_import")));
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("rivm_expertise:settings:manual_upload");
	echo "<br />";
	echo elgg_view("input/file", array("name" => "ctgb_manual_file"));
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("rivm_expertise:settings:stof_replacement");
	echo "<br />";
	echo elgg_view("input/plaintext", array("name" => "params[stof_replacement]", "value" => $plugin->stof_replacement));
	echo "<div class='elgg-subtext'>" . elgg_echo("rivm_expertise:settings:stof_replacement:description") . "</div>";
	echo "</div>";
?>
<script type="text/javascript">
	$('#rivm_expertise-settings').attr("enctype", "multipart/form-data");
</script>