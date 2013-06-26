<?php 
exit();

	/**
	 * Import all available Product Types
	 */

	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . "/engine/start.php");
	
	admin_gatekeeper();
	
	$ptcodes = array(
		"PT01", "PT02", "PT03", "PT04", "PT05", "PT06", "PT07", "PT08", "PT09", "PT10", 
		"PT11", "PT12", "PT13", "PT14", "PT15", "PT16", "PT17", "PT18", "PT19", "PT20", 
		"PT21", "PT22", "PT23"
	);
	
	foreach($ptcodes as $ptcode){
		rivm_expertise_get_product_type($ptcode);
	}
	
	echo "done";