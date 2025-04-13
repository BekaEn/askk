<?php
$quote_meta = get_post_meta( get_the_ID(), 'qodef_post_format_quote_text', true );
$quote_text = ! empty( $quote_meta ) ? $quote_meta : get_the_title();

if ( ! empty( $quote_text ) ) {
	$quote_author             = get_post_meta( get_the_ID(), 'qodef_post_format_quote_author', true );
	$quote_author_description = get_post_meta( get_the_ID(), 'qodef_post_format_quote_author_description', true );
	$quote_author_image       = get_post_meta( get_the_ID(), 'qodef_post_format_quote_author_image', true );
	$title_tag                = isset( $title_tag ) && ! empty( $title_tag ) ? $title_tag : 'p';
	$author_title_tag         = isset( $author_title_tag ) && ! empty( $author_title_tag ) ? $author_title_tag : 'h5';
	?>
	<div class="qodef-e-quote">
		<?php askka_render_svg_icon( 'quote', 'qodef-e-quote-icon' ); ?>
		<<?php echo esc_attr( $title_tag ); ?> class="qodef-e-quote-text"><?php echo esc_html( $quote_text ); ?></<?php echo esc_attr( $title_tag ); ?>>
		<div class="qodef-e-quote-author-holder">
			<?php if ( ! empty( $quote_author_image ) ) { ?>
				<?php echo wp_get_attachment_image( $quote_author_image, 'full', '', array( 'class' => 'qodef-e-quote-author-image' ) ); ?>
			<?php } ?>
			<?php if ( ! empty( $quote_author ) ) { ?>
				<<?php echo esc_attr( $author_title_tag ); ?> class="qodef-e-quote-author"><?php echo esc_html( $quote_author ); ?></<?php echo esc_attr( $author_title_tag ); ?>>
			<?php } ?>
			<?php if ( ! empty( $quote_author_description ) ) { ?>
				<p class="qodef-e-quote-author-description"><?php echo esc_html( $quote_author_description ); ?></p>
			<?php } ?>
		</div>
		<?php if ( ! is_single() ) { ?>
			<a itemprop="url" class="qodef-e-quote-url" href="<?php the_permalink(); ?>"></a>
		<?php } ?>
	</div>
<?php } ?>
