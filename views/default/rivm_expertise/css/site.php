<?php 

?>
.ui-autocomplete .ui-menu-item {
	padding: 0px;
}

.ui-autocomplete .ui-menu-item a {
	display: block;
	padding: 0 4px;
}

.ui-autocomplete .ui-menu-item .ui-state-hover {
	background-color: #EEE;
}

.ui-autocomplete {
	max-height: 200px;
	overflow-y: auto;
	/* prevent horizontal scrollbar */
	overflow-x: hidden;
	/* add padding to account for vertical scrollbar */
	padding-right: 20px;
}
/* IE 6 doesn't support max-height
 * we use height instead, but this forces the menu to always be this tall
 */
* html .ui-autocomplete {
	height: 200px;
}

/* Tag picker */
.rivm-expertise-tag-picker {
	background-color: transparent;
}