<?php

	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/hooks.php");
	require_once(dirname(__FILE__) . "/lib/page_handlers.php");
	
	define("RIVM_CTGB_LOCATION", "rivm_expertise/");

	function rivm_expertise_init(){
		// register objects
		add_subtype("object", CtgbMiddel::SUBTYPE, "CtgbMiddel");
		add_subtype("object", CtgbOrganisatie::SUBTYPE, "CtgbOrganisatie");
		add_subtype("object", CtgbProductType::SUBTYPE, "CtgbProductType");
		add_subtype("object", CtgbStof::SUBTYPE, "CtgbStof");
		
		// register pagehandler for nice URL's
		elgg_register_page_handler("expertise", "rivm_expertise_page_handler");
		elgg_register_page_handler("middel", "rivm_expertise_middel_page_handler");
		
		// register js file
		elgg_register_simplecache_view("js/rivm_expertise/stoffen");
		elgg_register_simplecache_view("js/rivm_expertise/organisaties");
		
		$url = elgg_get_simplecache_url("js", "rivm_expertise/stoffen");
		elgg_register_js("rivm_expertise.stoffen", $url);
		
		$url = elgg_get_simplecache_url("js", "rivm_expertise/organisaties");
		elgg_register_js("rivm_expertise.organisaties", $url);
		
		// extend css
		elgg_extend_view("css/elgg", "rivm_expertise/css/site");
		
		// register profile fields
		rivm_expertise_register_profile_field_types();
		
		// register admin menu item
		elgg_register_admin_menu_item("administer", "rivm_expertise_tags", "administer_utilities");
		
		// register plugin hooks
		elgg_unregister_plugin_hook_handler("search", "user", "search_users_hook");
		elgg_register_plugin_hook_handler("search", "user", "rivm_expertise_search_users_hook");
		elgg_register_plugin_hook_handler("cron", "daily", "rivm_expertise_cron_hook");
		elgg_register_plugin_hook_handler("action", "profile/edit", "rivm_expertise_action_profile_edit_hook");
		
		// register actions
		elgg_register_action("rivm_expertise/manual_import", dirname(__FILE__) . "/actions/manual_import.php", "admin");
		elgg_register_action("rivm_expertise/settings/save", dirname(__FILE__) . "/actions/settings/save.php", "admin");
		elgg_register_action("rivm_expertise/manage_tags", dirname(__FILE__) . "/actions/manage_tags.php", "admin");
	}
	
	// register default elgg events
	elgg_register_event_handler("init", "system", "rivm_expertise_init");