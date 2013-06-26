<?php 

	if($stoffen = rivm_expertise_read_stof_cache()){
		$stoffen = array_values($stoffen);
		
		echo "var ctgb_stoffen = " . json_encode($stoffen) . ";";
	} else {
		echo "var ctgb_stoffen = [];";
	}
	
	