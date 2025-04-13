<span><?php echo esc_html__( 'by ', 'askka' ); ?></span>
<a itemprop="author" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="qodef-e-info-author">
	<?php the_author_meta( 'display_name' ); ?>
</a><div class="qodef-info-separator-end"></div>
