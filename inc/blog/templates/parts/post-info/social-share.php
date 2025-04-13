<?php if ( class_exists( 'AskkaCore_Social_Share_Shortcode' ) ) { ?>
	<?php
	$params           = array();
	$params['layout'] = 'text';

	echo AskkaCore_Social_Share_Shortcode::call_shortcode( $params );
	?>
	<?php
}
