<?php 

	function rivm_expertise_page_handler($page){
		
		switch($page[0]){
			case "tags":
			case "tag":
				include(dirname(dirname(__FILE__)) . "/pages/expertise/tags.php");
				break;
			default:
				include(dirname(dirname(__FILE__)) . "/pages/expertise/search.php");
				break;
		}
		
		return true;
	}
	
	function rivm_expertise_middel_page_handler($page){
		
		switch($page[0]){
			case "view":
				if(isset($page[1])){
					set_input("guid", $page[1]);
					
					include(dirname(dirname(__FILE__)) . "/pages/middel/view.php");
				}
				break;
			default:
				include(dirname(dirname(__FILE__)) . "/pages/middel/all.php");
				break;
		}
		
		return true;
	}