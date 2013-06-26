<?php 

	class CtgbOrganisatie extends ElggObject {
		
		const SUBTYPE = "rivm_organisatie";
		
		public function initializeAttributes(){
			parent::initializeAttributes();
			
			$site = elgg_get_site_entity();
			
			$this->attributes["subtype"] = self::SUBTYPE;
			$this->attributes["access_id"] = ACCESS_PUBLIC;
			$this->attributes["owner_guid"] = $site->getGUID();
			$this->attributes["container_guid"] = $site->getGUID();
		}
	}