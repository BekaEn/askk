<?php
$link_url_meta  = get_post_meta( get_the_ID(), 'qodef_post_format_link', true );
$link_url       = ! empty( $link_url_meta ) ? $link_url_meta : get_the_permalink();
$link_text_meta = get_post_meta( get_the_ID(), 'qodef_post_format_link_text', true );

if ( ! empty( $link_url ) ) {
	?>
	<div class="qodef-e-link">
		<?php askka_render_svg_icon( 'link', 'qodef-e-link-icon' ); ?>
		<div class="qodef-e-link-text">
			<?php
			// Include post excerpt
			askka_template_part( 'blog', 'templates/parts/post-info/excerpt' );
			?>
		</div>
		<a itemprop="url" class="qodef-e-link-url" href="<?php echo esc_url( $link_url ); ?>" target="_blank"></a>
	</div>
<?php } ?>
