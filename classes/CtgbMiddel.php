<?php 

	class CtgbMiddel extends ElggObject {
		
		const SUBTYPE = "rivm_middel";
		const MOEDER_REL = "moedertoelating";
		const TOELATING_HOUDER_REL = "toelatinghouder";
		const STOFFEN_REL = "stoffen";
		const PRODUCT_TYPE_REL = "producttype";
		
		public function initializeAttributes(){
			parent::initializeAttributes();
			
			$site = elgg_get_site_entity();
			
			$this->attributes["subtype"] = self::SUBTYPE;
			$this->attributes["access_id"] = ACCESS_PUBLIC;
			$this->attributes["owner_guid"] = $site->getGUID();
			$this->attributes["container_guid"] = $site->getGUID();
		}
		
		public function getURL(){
			return "middel/view/" . $this->getGUID() . "/" . elgg_get_friendly_title($this->getName());
		}
		
		/**
		 * Set toelatingsnummer
		 * 
		 * @param int $toelatingsnummer
		 */
		public function setToelatingsNummer($toelatingsnummer){
			$toelatingsnummer = sanitise_int($toelatingsnummer);
			
			return $this->toelatingsnummer = $toelatingsnummer;
		}
		
		public function getToelatingsNummer(){
			return $this->toelatingsnummer;
		}
		
		/**
		 * Set the name of the Middel
		 * 
		 * @param string $name
		 */
		public function setName($name){
			$this->title = $name;
			
			return $this->save();
		}
		
		public function getName(){
			return $this->title;
		}
		
		/**
		 * Set MAP from export
		 * 
		 * @param string $map
		 */
		public function setNiveau($map){
			$this->niveau = $map;
		}
		
		public function getNiveau(){
			return $this->niveau;
		}
		
		public function getMAP(){
			return $this->getNiveau();
		}
		
		/**
		 * set a reference to the moeder
		 * 
		 * @param int $moedertoelating
		 */
		public function setMoederToelating($moedertoelating){
			// clear previous relationships
			remove_entity_relationships($this->getGUID(), self::MOEDER_REL);
			
			// check if there is a new relationship
			if($moeder = rivm_expertise_get_middel_by_toelatingsnummer($moedertoelating)){
				$this->addRelationship($moeder->getGUID(), self::MOEDER_REL);
			}
		}
		
		public function getMoederToelating(){
			$result = false;
			
			if($moeders = $this->getEntitiesFromRelationship(self::MOEDER_REL)){
				$result = $moeders[0];
			}
			
			return $result;
		}
		
		/**
		 * set toelatinghouder
		 * 
		 * @param string $organisatie
		 */
		public function setToelatingHouder($organisatie){
			// clear previous relationship
			remove_entity_relationships($this->getGUID(), self::TOELATING_HOUDER_REL);
			
			if($toelatinghouder = rivm_expertise_get_organisatie($organisatie)){
				$this->addRelationship($toelatinghouder->getGUID(), self::TOELATING_HOUDER_REL);
			}
		}
		
		public function getToelatingHouder(){
			$result = false;
			
			if($toelatinghouders = $this->getEntitiesFromRelationship(self::TOELATING_HOUDER_REL)){
				$result = $toelatinghouders[0];
			}
			
			return $result;
		}
		
		/**
		 * set start date
		 * 
		 * @param string date (format dd-mm-yyyy)
		 */
		public function setStartDate($date){
			// first transform date to work better in the database
			if(!empty($date)){
				$date = $this->convertDate($date);
			}
			
			return $this->startdate = $date;
		}
		
		public function getStartDate(){
			return $this->startdate;
		}
		
		/**
		 * set expire date
		 * 
		 * @param string $date (format dd-mm-yyyy)
		 */
		public function setExpireDate($date){
			// first transform date to work better in the database
			if(!empty($date)){
				$date = $this->convertDate($date);
			}
			
			return $this->expiredate = $date;
		}
		
		public function getExpireDate(){
			return $this->expiredate;
		}
		
		/**
		 * set biogewas
		 * 
		 * @param string $gewas
		 */
		public function setBioGewas($gewas){
			return $this->biogewas = $gewas;
		}
		
		public function getBioGewas(){
			return $this->biogewas;
		}
		
		/**
		 * set werkzame stoffen
		 * 
		 * @param array CtgbStof $stoffen
		 */
		public function setStoffen($stoffen){
			// remove previous stoffen
			remove_entity_relationships($this->getGUID(), self::STOFFEN_REL);
			
			if(!empty($stoffen)){
				foreach($stoffen as $stof){
					$this->addRelationship($stof->getGUID(), self::STOFFEN_REL);
				}
			}
		}
		
		public function getStoffen(){
			$result = false;
			
			$dbprefix = elgg_get_config("dbprefix");
			
			$options = array(
				"type" => "object",
				"subtype" => CtgbStof::SUBTYPE,
				"relationship" => self::STOFFEN_REL,
				"relationship_guid" => $this->getGUID(),
				"limit" => false,
				"joins" => array("JOIN " . $dbprefix . "objects_entity oe ON oe.guid = e.guid"),
				"order_by" => "oe.title"
			);
			
			if($stoffen = elgg_get_entities_from_relationship($options)){
				$result = $stoffen;
			}
			
			return $result;
		}
		
		/**
		 * Set application
		 * 
		 * @param string $application
		 */
		public function setApplication($application){
			return $this->application = $application;
		}
		
		public function getApplication(){
			return $this->application;
		}
		
		/**
		 * Set date of opgebruiktermijn professional
		 * 
		 * @param string $date (format dd-mm-yyyy)
		 */
		public function setOpgebruikTermijnPro($date){
			// first transform date to work better in the database
			if(!empty($date)){
				$date = $this->convertDate($date);
			}
				
			return $this->opgebruik_termijn_pro = $date;
		}
		
		public function getOpgebruikTermijnPro(){
			return $this->opgebruik_termijn_pro;
		}
		
		/**
		* Set date of aflever termijn professional
		*
		* @param string $date (format dd-mm-yyyy)
		*/
		public function setAfleverTermijnPro($date){
			// first transform date to work better in the database
			if(!empty($date)){
				$date = $this->convertDate($date);
			}
				
			return $this->aflever_termijn_pro = $date;
		}
		
		public function getAfleverTermijnPro(){
			return $this->aflever_termijn_pro;
		}
		
		/**
		* Set date of opgebruiktermijn non professional
		*
		* @param string $date (format dd-mm-yyyy)
		*/
		public function setOpgebruikTermijnNonPro($date){
			// first transform date to work better in the database
			if(!empty($date)){
				$date = $this->convertDate($date);
			}
				
			return $this->opgebruik_termijn_non_pro = $date;
		}
		
		public function getOpgebruikTermijnNonPro(){
			return $this->opgebruik_termijn_non_pro;
		}
		
		/**
		* Set date of aflevertermijn non professional
		*
		* @param string $date (format dd-mm-yyyy)
		*/
		public function setAfleverTermijnNonPro($date){
			// first transform date to work better in the database
			if(!empty($date)){
				$date = $this->convertDate($date);
			}
				
			return $this->aflever_termijn_non_pro = $date;
		}
		
		public function getAfleverTermijnNonPro(){
			return $this->aflever_termijn_non_pro;
		}
		
		/**
		 * set GNO (Gewasbestrijdingsmiddel van natuurlijke oorsprong)
		 * 
		 * @param string $gno
		 */
		public function setGNO($gno){
			return $this->gno = $gno;
		}
		
		public function getGNO(){
			return $this->gno;
		}
		
		/**
		 * Set formulering
		 * 
		 * @param string $formulering
		 */
		public function setFormulering($formulering){
			$this->description = $formulering;
			
			return $this->save();
		}
		
		public function getFormulering(){
			return $this->description;
		}
		
		/**
		 * Set toelatingstype
		 * 
		 * @param strign $type
		 */
		public function setToelatingsType($type){
			return $this->toelatings_type = $type;
		}
		
		public function getToelatingsType(){
			return $this->toelatings_type;
		}
		
		/**
		 * Set product types
		 * 
		 * @param array CtgbProductType $types
		 */
		public function setProductTypes($types){
			// remove previous stoffen
			remove_entity_relationships($this->getGUID(), self::PRODUCT_TYPE_REL);
			
			if(!empty($types)){
				foreach($types as $type){
					$this->addRelationship($type->getGUID(), self::PRODUCT_TYPE_REL);
				}
			}
		}
		
		public function getProductTypes(){
			$result = false;
			
			$dbprefix = elgg_get_config("dbprefix");
			
			$options = array(
				"type" => "object",
				"subtype" => CtgbProductType::SUBTYPE,
				"relationship" => self::PRODUCT_TYPE_REL,
				"relationship_guid" => $this->getGUID(),
				"limit" => false,
				"joins" => array("JOIN " . $dbprefix . "objects_entity oe ON oe.guid = e.guid"),
				"order_by" => "oe.title"
			);
			
			if($types = elgg_get_entities_from_relationship($options)){
				$result = $types;
			}
			
			return $result;
		}
		
		/**
		 * convert a date from format dd-mm-yyyy to yyyy-mm-dd
		 * 
		 * @param string $date
		 */
		protected function convertDate($date){
			list($day, $month, $year) = explode("-", $date);
			
			return $year . "-" . $month . "-" . $day;
		}
	}