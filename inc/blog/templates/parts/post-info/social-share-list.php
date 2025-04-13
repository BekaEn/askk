<?php if ( class_exists( 'AskkaCore_Social_Share_Shortcode' ) ) { ?>
	<div class="qodef-e-info-item qodef-e-info-social-share">
		<?php
		$params           = array();
		$params['layout'] = 'text';

		echo AskkaCore_Social_Share_Shortcode::call_shortcode( $params );
		?>
		<a class="qodef-social-share-opener" href="javascript:void(0)">
			<?php echo askka_get_svg_icon( 'share' ); ?>
		</a>
	</div>
<?php } ?>
