<?php

	function rivm_expertise_search_users_hook($hook, $type, $value, $params) {
		$db_prefix = elgg_get_config('dbprefix');
	
		$query = sanitise_string($params['query']);
	
		$join = "JOIN {$db_prefix}users_entity ue ON e.guid = ue.guid";
		$params['joins'] = array($join);
	
		$fields = array('username', 'name');
		$where = rivm_expertise_search_get_where_sql('ue', $fields, $params, FALSE);
	
		$params['wheres'] = array($where);
	
		// override subtype -- All users should be returned regardless of subtype.
		$params['subtype'] = ELGG_ENTITIES_ANY_VALUE;
	
		$params['count'] = TRUE;
		
	
	// 	$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";
	// 	$params['joins'][] = "JOIN {$db_prefix}metastrings msn on md.name_id = msn.id";
	// 	$params['joins'][] = "JOIN {$db_prefix}metastrings msv on md.value_id = msv.id";
		
	// 	$access = get_access_sql_suffix('md');
	// 	$sanitised_tags = array();
		
	// 	foreach ($search_tag_names as $tag) {
	// 		$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
	// 	}
		
	// 	$tags_in = implode(',', $sanitised_tags);
		
	// 	$params['wheres'][] = "(msn.string IN ($tags_in) AND msv.string = '$query' AND $access)";
		
		$count = elgg_get_entities($params);
	
		// no need to continue if nothing here.
		if (!$count) {
			return array('entities' => array(), 'count' => $count);
		}
	
		$params['count'] = FALSE;
		$entities = elgg_get_entities($params);
	
		// add the volatile data for why these entities have been returned.
		foreach ($entities as $entity) {
			$username = search_get_highlighted_relevant_substrings($entity->username, $query);
			$entity->setVolatileData('search_matched_title', $username);
	
			$name = search_get_highlighted_relevant_substrings($entity->name, $query);
			$entity->setVolatileData('search_matched_description', $name);
		}
	
		return array(
			'entities' => $entities,
			'count' => $count,
		);
	}
	
	function rivm_expertise_search_get_where_sql($table, $fields, $params, $use_fulltext = TRUE) {
		global $CONFIG;
		$query = $params['query'];
	
		// add the table prefix to the fields
		foreach ($fields as $i => $field) {
			if ($table) {
				$fields[$i] = "$table.$field";
			}
		}
	
		$where = '';
	
		// if query is shorter than the min for fts words
		// it's likely a single acronym or similar
		// switch to literal mode
		if (elgg_strlen($query) < $CONFIG->search_info['min_chars']) {
			$likes = array();
			$query = sanitise_string($query);
			foreach ($fields as $field) {
				$likes[] = "$field LIKE '%$query%'";
			}
			$likes_str = implode(' OR ', $likes);
			$where = "($likes_str)";
		} else {
			// if we're not using full text, rewrite the query for bool mode.
			// exploiting a feature(ish) of bool mode where +-word is the same as -word
			if (!$use_fulltext) {
				$query = '+' . str_replace(' ', ' +', $query);
			}
	
			// if using advanced, boolean operators, or paired "s, switch into boolean mode
			$booleans_used = preg_match("/([\-\+~])([\w]+)/i", $query);
			$advanced_search = (isset($params['advanced_search']) && $params['advanced_search']);
			$quotes_used = (elgg_substr_count($query, '"') >= 2);
	
			if (!$use_fulltext || $booleans_used || $advanced_search || $quotes_used) {
				$options = 'IN BOOLEAN MODE';
			} else {
				// natural language mode is default and this keyword isn't supported in < 5.1
				//$options = 'IN NATURAL LANGUAGE MODE';
				$options = '';
			}
	
			// if short query, use query expansion.
			// @todo doesn't seem to be working well.
			//		if (elgg_strlen($query) < 5) {
			//			$options .= ' WITH QUERY EXPANSION';
			//		}
			$query = sanitise_string($query);
	
			$fields_str = implode(',', $fields);
			$where = "(MATCH ($fields_str) AGAINST ('$query*' $options))";
		}
	
		return $where;
	}
	
	function rivm_expertise_cron_hook($hook, $type, $return_value, $params){
		// ignore access
		$ia = elgg_set_ignore_access(true);
		
		// import the CTGB file
		rivm_expertise_import_ctgb_file();
		
		// restore access
		elgg_set_ignore_access($ia);
	}
	
	/**
	 * We need to changed the type of profile field from ctgb_stof to tags
	 *
	 * @param string $hook
	 * @param string $type
	 * @param mixed $return_value
	 * @param array $params
	 */
	function rivm_expertise_action_profile_edit_hook($hook, $type, $return_value, $params) {
		// get profile fields
		if ($profile_fields = elgg_get_config("profile_fields")) {
			foreach ($profile_fields as $metadata_name => $type) {
				// change the type
				if ($type == "ctgb_stof") {
					$profile_fields[$metadata_name] = "tags";
				}
			}
				
			// store new config
			elgg_set_config("profile_fields", $profile_fields);
		}
	}
	