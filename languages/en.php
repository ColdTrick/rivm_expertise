<?php 

	$english = array(
		// general
		'item:object:rivm_middel' => "CTGB appliance",
		'item:object:rivm_organisatie' => "CTGB Organisation",
		'item:object:rivm_product_type' => "CTGB Product Type",
		'item:object:rivm_stof' => "CTGB Substance",
		
		'rivm_expertise:profile_field:product_type' => "Ctgb Product Type",
		'rivm_expertise:profile_field:stof' => "Ctgb Substance",
		'rivm_expertise:profile_field:organisatie' => "Ctgb Organisation",
		
		'rivm_expertise:find' => "Find",
		'rivm_expertise:replace' => "replace with",
		'rivm_expertise:select_value' => "Select a value",
	
		// admin menu
		'admin:administer_utilities:rivm_expertise_tags' => "Manage user tags",
		
		// settings
		'rivm_expertise:settings:ctbg_export_location' => "Location of CTGB export file",
		'rivm_expertise:settings:manual_import' => "Download the CTGB file now",
		'rivm_expertise:settings:manual_upload' => "Manualy upload the CTGB file",
		'rivm_expertise:settings:stof_replacement' => "Substance replacement rules",
		'rivm_expertise:settings:stof_replacement:description' => "Please put one replacement rule per line. Please use the format '/REGEXPRESSION/' (without single quote)",
		
		// middel
		'rivm_expertise:breacrumb:middel:all' => "Appliance",
		
		'rivm_expertise:middel:all:title' => "All appliances",
		
		'rivm_expertise:middel:toelatingsnummer' => "Authorization number",
		'rivm_expertise:middel:niveau' => "MAP",
		'rivm_expertise:middel:moedertoelating' => "Mother authorization",
		'rivm_expertise:middel:toelatinghouder' => "Authorization holder",
		'rivm_expertise:middel:startdate' => "Start date",
		'rivm_expertise:middel:expiredate' => "Expire date",
		'rivm_expertise:middel:biogewas' => "Organic crop",
		'rivm_expertise:middel:stoffen' => "Substances",
		'rivm_expertise:middel:application' => "Application",
		'rivm_expertise:middel:opgebruiktermijn_pro' => "Usability period Pro",
		'rivm_expertise:middel:aflevertermijn_pro' => "Delivery period Pro",
		'rivm_expertise:middel:opgebruiktermijn_non_pro' => "Usability period non-Pro",
		'rivm_expertise:middel:aflevertermijn_non_pro' => "Delivery period non-Pro",
		'rivm_expertise:middel:gno' => "GNO",
		'rivm_expertise:middel:formulering' => "Description",
		'rivm_expertise:middel:toelatingstype' => "Admission type",
		'rivm_expertise:middel:product_types' => "Product types",
		
		// product type
		'rivm_expertise:product_type:pt01:label' => "PT01",
		'rivm_expertise:product_type:pt02:label' => "PT02",
		'rivm_expertise:product_type:pt03:label' => "PT03",
		'rivm_expertise:product_type:pt04:label' => "PT04",
		'rivm_expertise:product_type:pt05:label' => "PT05",
		'rivm_expertise:product_type:pt06:label' => "PT06",
		'rivm_expertise:product_type:pt07:label' => "PT07",
		'rivm_expertise:product_type:pt08:label' => "PT08",
		'rivm_expertise:product_type:pt09:label' => "PT09",
		'rivm_expertise:product_type:pt10:label' => "PT10",
		'rivm_expertise:product_type:pt11:label' => "PT11",
		'rivm_expertise:product_type:pt12:label' => "PT12",
		'rivm_expertise:product_type:pt13:label' => "PT13",
		'rivm_expertise:product_type:pt14:label' => "PT14",
		'rivm_expertise:product_type:pt15:label' => "PT15",
		'rivm_expertise:product_type:pt16:label' => "PT16",
		'rivm_expertise:product_type:pt17:label' => "PT17",
		'rivm_expertise:product_type:pt18:label' => "PT18",
		'rivm_expertise:product_type:pt19:label' => "PT19",
		'rivm_expertise:product_type:pt20:label' => "PT20",
		'rivm_expertise:product_type:pt21:label' => "PT21",
		'rivm_expertise:product_type:pt22:label' => "PT22",
		'rivm_expertise:product_type:pt23:label' => "PT23",
		
		// search
		'rivm_expertise:search' => "Search for expertise",
		'rivm_expertise:search:expertise' => "Expertise",
		'rivm_expertise:search:stof' => "Substance",
		'rivm_expertise:search:product_type' => "Product Type",
		'rivm_expertise:sarch:organisation:all' => "No preference",
		
		'rivm_expertise:search:select_value' => "Select a value",
		'rivm_expertise:search:results' => "Search results",
		
		// tags
		'rivm_expertise:tags:title' => "Expertise by tag",
		'rivm_expertise:tags:sidebar:title' => "Find users",
		'rivm_expertise:tags:sidebar:content' => "You can find users with a specific tag by clicking on the tag.",
		
		// admin - manage tags
		'rivm_expertise:forms:manage_tags:replace' => "Replace a tag with a different tag",
		'rivm_expertise:forms:manage_tags:replace_free' => "You can replace the selected tag with a new value if you keep the replace value empty and fill in this text field",
		'rivm_expertise:forms:manage_tags:delete' => "Delete a tag",
		
		// action - manage tags
		'rivm_expertise:action:manage_tags:replace:success' => "Successfully replaced '%s' with '%s'",
		'rivm_expertise:action:manage_tags:replace:error' => "An error occured while replacing '%s' with '%s'",
		'rivm_expertise:action:manage_tags:delete:success' => "Successfully deleted the tag(s) '%s'",
		'rivm_expertise:action:manage_tags:delete:error' => "An error occured while deleting the tag(s) '%s'",
		
	);
	
	add_translation("en", $english);