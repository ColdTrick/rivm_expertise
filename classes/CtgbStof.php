<?php 

	class CtgbStof extends ElggObject {
		
		const SUBTYPE = "rivm_stof";
		
		public function initializeAttributes(){
			parent::initializeAttributes();
			
			$site = elgg_get_site_entity();
			
			$this->attributes["subtype"] = self::SUBTYPE;
			$this->attributes["access_id"] = ACCESS_PUBLIC;
			$this->attributes["owner_guid"] = $site->getGUID();
			$this->attributes["container_guid"] = $site->getGUID();
		}
		
		public function getLabel(){
			return rivm_expertise_get_stof_label($this->title);
		}
	}