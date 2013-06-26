<?php 

	if($organisaties = rivm_expertise_read_organisaties_cache()){
		$organisaties = array_values($organisaties);
		
		echo "var ctgb_organisaties = " . json_encode($organisaties) . ";";
	} else {
		echo "var ctgb_organisaties = [];";
	}
	
	