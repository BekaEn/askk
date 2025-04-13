<div id="qodef-404-page">
	<span class="qodef-404-background-text"><?php echo esc_html( $background_text ); ?></span>
	<span class="qodef-404-tagline"><?php echo esc_html( $tagline ); ?></span>
	<h1 class="qodef-404-title"><?php echo esc_html( $title ); ?></h1>
	<p class="qodef-404-text"><?php echo esc_html( $text ); ?></p>
	<div class="qodef-404-button">
		<?php
		$button_params = array(
			'link' => esc_url( home_url( '/' ) ),
			'text' => esc_html( $button_text ),
		);

		askka_render_button_element( $button_params );
		?>
	</div>
</div>
