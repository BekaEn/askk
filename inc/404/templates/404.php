<?php

// Hook to include additional content before 404 page content
do_action( 'askka_action_before_404_page_content' );

// Include module content template
echo apply_filters( 'askka_filter_404_page_content_template', askka_get_template_part( '404', 'templates/404-content', '', askka_get_404_page_parameters() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

// Hook to include additional content after 404 page content
do_action( 'askka_action_after_404_page_content' );
