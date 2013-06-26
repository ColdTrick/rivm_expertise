<?php 

	elgg_load_js("rivm_expertise.organisaties");
	elgg_load_js('elgg.autocomplete');
	elgg_load_js('jquery.ui.autocomplete.html');
	
	if (isset($vars['class'])) {
		$vars['class'] = "rivm-autocomplete {$vars['class']}";
	} else {
		$vars['class'] = "rivm-autocomplete";
	}
	
	$defaults = array(
			'value' => '',
			'disabled' => false,
	);
	
	$vars = array_merge($defaults, $vars);
	
	
	?>
		<input type="text" <?php echo elgg_format_attributes($vars); ?> />
		<script type="text/javascript">
			$("input[name='<?php echo $vars["name"];?>']")
				.autocomplete({
					source: ctgb_organisaties,
					minLength: 0,
					focus: function() {
						// prevent value inserted on focus
						return false;
					}					
				});
		</script>