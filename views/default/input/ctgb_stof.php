<?php 
	
	elgg_load_js("rivm_expertise.stoffen");
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
	
	if(!empty($vars["value"]) && is_array($vars["value"])){
		$vars["value"] = implode(", ", $vars["value"]);
	}
	
	?>
	<input type="text" <?php echo elgg_format_attributes($vars); ?> />
	<script type="text/javascript">
		$("input[name='<?php echo $vars["name"];?>']")
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				source: function( request, response ) {
						response( $.ui.autocomplete.filter(ctgb_stoffen, request.term.split( /,\s*/ ).pop()));
				},
				minLength: 0,
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					var terms = this.value.split( /,\s*/ );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( ", " );
					return false;
				}
				
			});
	</script>