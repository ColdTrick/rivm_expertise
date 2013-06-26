<?php 

	function rivm_expertise_download_ctgb_export(){
		$result = false;
		
		if($location = elgg_get_plugin_setting("ctgb_export_location", "rivm_expertise")){
			$path = parse_url($location, PHP_URL_PATH);
			$filename = basename($path);
			
			if($content = file_get_contents($location)){
				$dataroot = elgg_get_config("dataroot");
				
				$new_location = $dataroot . RIVM_CTGB_LOCATION . $filename;
				
				// make directory structure
				if(!is_dir($dataroot . RIVM_CTGB_LOCATION)){
					mkdir($dataroot . RIVM_CTGB_LOCATION);
				}
				
				if($result = file_put_contents($new_location, $content)){
					// save where we put the file
					elgg_set_plugin_setting("local_ctgb_location", RIVM_CTGB_LOCATION . $filename, "rivm_expertise");
				}
			}
		}
		
		return $result;
	}
	
	function rivm_expertise_read_ctgb_file(){
		$result = false;
		
		if($location = elgg_get_plugin_setting("local_ctgb_location", "rivm_expertise")){
			$dataroot = elgg_get_config("dataroot");
			
			if($fh = fopen($dataroot . $location, "r")){
				$result = array();
				
				while(($data = fgetcsv($fh, 0, "\t")) !== false){
					$result[] = $data;
				}
				
				fclose($fh);
			}
		}
		
		return $result;
	}
	
	function rivm_expertise_import_ctgb_file($download = true){
		// this could take a while
		set_time_limit(0);
		
		if($download){
			rivm_expertise_download_ctgb_export();
		}
		
		if($data = rivm_expertise_read_ctgb_file()){
			// disable db query caching to save memory
			elgg_set_config("db_disable_query_cache", true);
			setup_db_connections();
			
			foreach($data as $index => $row){
				// just to be safe
				set_time_limit(0);
				
				// skip first row
				if($index === 0){
					continue;
				}
				
				/**
				 * data structure
				 * 
				 * [0] => toelatingsnummer
				 * [1] => middelnaam
				 * [2] => map (niveau)
				 * [3] => moedertoelating (can be empty)
				 * [4] => toelatinghouder
				 * [5] => startdatum (format dd-mm-yyyy)
				 * [6] => expiratiedatum (format dd-mm-yyyy)
				 * [7] => biogewas
				 * [8] => werkzamestoffen (must be converted to CtgbStof)
				 * [9] => toepassing
				 * [10] => w-code prfessioneel (ignore)
				 * [11] => datum opgebruiktermijn professioneel (format dd-mm-yyyy)
				 * [12] => datum aflevertermijn professioneel (format dd-mm-yyyy)
				 * [13] => w-code niet professioneel (ignore)
				 * [14] => datum opgebruiktermijn niet-professioneel (format dd-mm-yyyy)
				 * [15] => datum aflevertermijn niet-proffessioneel (format dd-mm-yyyy)
				 * [16] => gno
				 * [17] => formulering
				 * [18] => toelatingstype
				 * [19] => ptcodes (must be converted to CtgbProductType)
				 * 
				 */
				
				if($middel = rivm_expertise_get_middel_by_toelatingsnummer($row[0])){
					$middel->setName($row[1]);
					$middel->setNiveau($row[2]);
					$middel->setMoederToelating($row[3]);
					$middel->setToelatingHouder($row[4]);
					$middel->setStartDate($row[5]);
					$middel->setExpireDate($row[6]);
					$middel->setBioGewas($row[7]);
					
					// convert stoffen to correct format
					$stof_namen = rivm_expertise_get_stofnamen($row[8]);
					$stoffen = array();
					if(!empty($stof_namen)){
						foreach($stof_namen as $stof_naam){
							if($stof = rivm_expertise_get_stof($stof_naam)){
								$stoffen[] = $stof;
							}
						}
					}
					
					$middel->setStoffen($stoffen);
					// save memory
					unset($stof_namen);
					unset($stoffen);
					
					$middel->setApplication($row[9]);
					$middel->setOpgebruikTermijnPro($row[11]);
					$middel->setAfleverTermijnPro($row[12]);
					$middel->setOpgebruikTermijnNonPro($row[14]);
					$middel->setAfleverTermijnNonPro($row[15]);
					$middel->setGNO($row[16]);
					$middel->setFormulering($row[17]);
					$middel->setToelatingsType($row[18]);
					
					// convert ptcodes to correct format
					$ptcodes = explode("#", $row[19]);
					$ptcodes = array_map("trim", $ptcodes);
					$product_types = array();
					if(!empty($ptcodes)){
						foreach($ptcodes as $ptcode){
							if($product_type = rivm_expertise_get_product_type($ptcode)){
								$product_types[] = $product_type;
							}
						}
					}
					
					$middel->setProductTypes($product_types);
					// save memory
					unset($ptcodes);
					unset($product_types);
				}
				
				// save memory
				unset($middel);
			}
			
			// recache stoffen
			rivm_expertise_cache_stoffen();
			
			// recache organisaties
			rivm_expertise_cache_organisaties();
		}
	}
	
	function rivm_expertise_get_organisatie($organisatie){
		$result = false;
		
		if(!empty($organisatie)){
			$dbprefix = elgg_get_config("dbprefix");
			$organisatie = sanitise_string(trim($organisatie));
			
			$options = array(
				"type" => "object",
				"subtype" => CtgbOrganisatie::SUBTYPE,
				"limit" => 1,
				"joins" => array("JOIN " . $dbprefix . "objects_entity oe ON e.guid = oe.guid"),
				"wheres" => array("oe.title = '" . $organisatie . "'")
			);
			
			if($entities = elgg_get_entities($options)){
				$result = $entities[0];
			} else {
				$result = new CtgbOrganisatie();
				$result->title = $organisatie;
				
				$result->save();
			}
		}
		
		return $result;
	}
	
	function rivm_expertise_get_product_type($ptcode){
		$result = false;
		
		if(!empty($ptcode)){
			$dbprefix = elgg_get_config("dbprefix");
			$ptcode = sanitise_string($ptcode);
			
			$options = array(
				"type" => "object",
				"subtype" => CtgbProductType::SUBTYPE,
				"limit" => 1,
				"joins" => array("JOIN " . $dbprefix . "objects_entity oe ON e.guid = oe.guid"),
				"wheres" => array("oe.title = '" . $ptcode . "'")
			);
				
			if($entities = elgg_get_entities($options)){
				$result = $entities[0];
			} else {
				$result = new CtgbProductType();
				$result->title = $ptcode;
			
				$result->save();
			}
		}
		
		return $result;
	}
	
	function rivm_expertise_get_stofnamen($input_stoffen){
		static $replacement;
		
		$result = false;
		
		if(!isset($replacement)){
			$replacement = false;
			
			if($setting = elgg_get_plugin_setting("stof_repacement", "rivm_expertise")){
				$replacement = explode("\n", $setting);
				$replacement = array_map("trim", $stoffen);
			}
		}
		
		if(!empty($input_stoffen) && !empty($replacement)){
			$stoffen = explode("#", $input_stoffen);
			
			if(!is_array($stoffen)){
				$stoffen = array($stoffen);
			}
			
			$stoffen = array_map("trim", $stoffen);
			
			$result = array();
			foreach($stoffen as $stof){
				$stof = preg_replace($replacement, "", $stof);
// 				$stof = preg_replace("/ \(ALS ACTIEF CHLOOR\)$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+,[0-9]+G\/(L|KG)$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+G\/(L|KG)$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+,[0-9]+%$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+%$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+,[0-9]+-[0-9]+,[0-9]+%$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+-[0-9]+%$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+IU\/MG$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+ ?[x|\*] ?10[\^E][0-9]+ ?(CFU|SP|GV)\/(G|ML|L)$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+,[0-9]+ ?x ?10[\^E][0-9]+ ?(CFU|SP|GV)\/(G|ML|L)$/", "", $stof);
// 				$stof = preg_replace("/ 10[\^E][0-9]+ ?(CFU|SP|GV)\/(G|ML|L)$/", "", $stof);
// 				$stof = preg_replace("/ E\([0-9]+\)GV\/L$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+\*10\([0-9]+\)GV\/L$/", "", $stof);
// 				$stof = preg_replace("/ 10E[0-9]+CFU\/G$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+,?[0-9]*\*10expCFU\/G$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+,?[0-9]*\/[0-9]+,?[0-9]*\/[0-9]+,?[0-9]*%$/", "", $stof);
// 				$stof = preg_replace("/ [0-9]+,?[0-9]*\/[0-9]+,?[0-9]*%$/", "", $stof);
				
				$result[] = $stof;
			}
			
			// trim trailing spaces
			$result = array_map("trim", $result);
		}
		
		return $result;
	}
	
	function rivm_expertise_get_stof($stofnaam){
		$result = false;
		
		if(!empty($stofnaam)){
			$dbprefix = elgg_get_config("dbprefix");
			$stofnaam = sanitise_string(trim($stofnaam));
		
			$options = array(
				"type" => "object",
				"subtype" => CtgbStof::SUBTYPE,
				"limit" => 1,
				"joins" => array("JOIN " . $dbprefix . "objects_entity oe ON e.guid = oe.guid"),
				"wheres" => array("oe.title = '" . $stofnaam . "'")
			);
		
			if($entities = elgg_get_entities($options)){
				$result = $entities[0];
			} else {
				$result = new CtgbStof();
				$result->title = $stofnaam;
					
				$result->save();
			}
		}
		
		return $result;
	}
	
	function rivm_expertise_get_middel_by_toelatingsnummer($toelatingsnummer){
		$result = false;
		
		$toelatingsnummer = sanitise_int($toelatingsnummer);
		
		if(!empty($toelatingsnummer)){
			$options = array(
				"type" => "object",
				"subtype" => CtgbMiddel::SUBTYPE,
				"limit" => 1,
				"metadata_name_value_pairs" => array(
					"name" => "toelatingsnummer",
					"value" => $toelatingsnummer
				)
			);
			
			if($entities = elgg_get_entities_from_metadata($options)){
				$result = $entities[0];
			} else {
				$result = new CtgbMiddel();
				$result->setToelatingsNummer($toelatingsnummer);
				
				$result->save();
			}
		}
		
		return $result;
	}
	
	function rivm_expertise_register_profile_field_types(){
		$profile_options = array(
			"show_on_register" => true,
			"mandatory" => true,
			"user_editable" => true,
			"output_as_tags" => false,
			"admin_only" => true,
			"count_for_completeness" => true,
			"blank_available" => true
		);
		
		add_custom_field_type("custom_profile_field_types", "ctgb_product_type", elgg_echo("rivm_expertise:profile_field:product_type"), $profile_options);
		add_custom_field_type("custom_profile_field_types", "ctgb_stof", elgg_echo("rivm_expertise:profile_field:stof"), $profile_options);
		add_custom_field_type("custom_profile_field_types", "ctgb_organisatie", elgg_echo("rivm_expertise:profile_field:organisatie"), $profile_options);
	}
	
	function rivm_expertise_get_product_type_label($title){
		$result = $title;
		
		if(!empty($title)){
			$lan_key = "rivm_expertise:product_type:" . strtolower($title) . ":label";
			if(elgg_echo($lan_key) != $lan_key){
				$result = elgg_echo($lan_key);
			}
		}
		
		return $result;
	}
	
	function rivm_expertise_get_stof_label($title){
		$result = $title;
		
		if(!empty($title)){
			$lan_key = "rivm_expertise:stof:" . strtolower($title) . ":label";
			if(elgg_echo($lan_key) != $lan_key){
				$result = elgg_echo($lan_key);
			}
		}
		
		return $result;
	}
	
	function rivm_expertise_cache_stoffen(){
		$cache = array();
		
		$options = array(
			"type" => "object",
			"subtype" => CtgbStof::SUBTYPE,
			"limit" => false,
			"joins" => array("JOIN " . elgg_get_config("dbprefix") . "objects_entity oe ON e.guid = oe.guid"),
			"order_by" => "oe.title"
		);
		
		if($stoffen = elgg_get_entities($options)){
			foreach($stoffen as $stof){
				$cache[$stof->getGUID()] = $stof->title;
			}
		}
		
		// store the cache
		$dataroot = elgg_get_config("dataroot");
		$filename = $dataroot . RIVM_CTGB_LOCATION . "stof_cache.json";
		
		if(!is_dir($dataroot . RIVM_CTGB_LOCATION)){
			mkdir($dataroot . RIVM_CTGB_LOCATION);
		}
		
		$contents = json_encode($cache);
		
		// invalidate simplecache
		elgg_invalidate_simplecache();
		
		return file_put_contents($filename, $contents);
	}
	
	function rivm_expertise_read_stof_cache(){
		$result = array();
		
		$dataroot = elgg_get_config("dataroot");
		$filename = $dataroot . RIVM_CTGB_LOCATION . "stof_cache.json";
		
		if(file_exists($filename)){
			if($cache = file_get_contents($filename)){
				$result = json_decode($cache, true);
			}
		}
		
		return $result;
	}
	
	function rivm_expertise_cache_organisaties(){
		$cache = array();
		
		$options = array(
			"type" => "object",
			"subtype" => CtgbOrganisatie::SUBTYPE,
			"limit" => false,
			"joins" => array("JOIN " . elgg_get_config("dbprefix") . "objects_entity oe ON e.guid = oe.guid"),
			"order_by" => "oe.title"
		);
		
		if($organisaties = elgg_get_entities($options)){
			foreach($organisaties as $organisatie){
				$cache[$organisatie->getGUID()] = $organisatie->title;
			}
		}
		
		// store the cache
		$dataroot = elgg_get_config("dataroot");
		$filename = $dataroot . RIVM_CTGB_LOCATION . "organisatie_cache.json";
		
		if(!is_dir($dataroot . RIVM_CTGB_LOCATION)){
			mkdir($dataroot . RIVM_CTGB_LOCATION);
		}
		
		$contents = json_encode($cache);
		
		// invalidate simplecache
		elgg_invalidate_simplecache();
		
		return file_put_contents($filename, $contents);
	}
	
	function rivm_expertise_read_organisaties_cache(){
		$result = array();
		
		$dataroot = elgg_get_config("dataroot");
		$filename = $dataroot . RIVM_CTGB_LOCATION . "organisatie_cache.json";
		
		if(file_exists($filename)){
			if($cache = file_get_contents($filename)){
				$result = json_decode($cache, true);
			}
		}
		
		return $result;
	}
	