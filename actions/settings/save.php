<?php 

	/**
	 * Replace the default plugin settings save action
	 */


	$params = get_input('params');
	$plugin_id = get_input('plugin_id');
	$plugin = elgg_get_plugin_from_id($plugin_id);
	
	if (!($plugin instanceof ElggPlugin)) {
		register_error(elgg_echo('plugins:settings:save:fail', array($plugin_id)));
		forward(REFERER);
	}
	
	$plugin_name = $plugin->getManifest()->getName();
	
	
	// check for a manualy uploaded file
	if($contents = get_uploaded_file("ctgb_manual_file")){
		$filename = $_FILES["ctgb_manual_file"]["name"];
		$dataroot = elgg_get_config("dataroot");
		
		$new_location = $dataroot . RIVM_CTGB_LOCATION . $filename;
		
		// make directory structure
		if(!is_dir($dataroot . RIVM_CTGB_LOCATION)){
			mkdir($dataroot . RIVM_CTGB_LOCATION);
		}
		
		if(file_put_contents($new_location, $contents)){
			// save where we put the file
			$plugin->setSetting("local_ctgb_location", RIVM_CTGB_LOCATION . $filename);
			
			// read the file
			rivm_expertise_import_ctgb_file(false);
		}
	}
	
	// save default settings
	$result = false;
	
	foreach ($params as $k => $v) {
		$result = $plugin->setSetting($k, $v);
		if (!$result) {
			register_error(elgg_echo('plugins:settings:save:fail', array($plugin_name)));
			forward(REFERER);
			exit;
		}
	}
	
	system_message(elgg_echo('plugins:settings:save:ok', array($plugin_name)));
	forward(REFERER);
	