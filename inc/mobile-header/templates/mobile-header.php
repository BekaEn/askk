<header id="qodef-page-mobile-header" role="banner">
	<?php
	// Hook to include additional content before page mobile header inner
	do_action( 'askka_action_before_page_mobile_header_inner' );
	?>
	<div id="qodef-page-mobile-header-inner" <?php askka_class_attribute( apply_filters( 'askka_filter_mobile_header_inner_class', array(), 'mobile' ) ); ?>>
		<?php
		// Include module content template
		echo apply_filters( 'askka_filter_mobile_header_content_template', askka_get_template_part( 'mobile-header', 'templates/mobile-header-content' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>
	<?php
	// Hook to include additional content after page mobile header inner
	do_action( 'askka_action_after_page_mobile_header_inner' );
	?>
</header>
