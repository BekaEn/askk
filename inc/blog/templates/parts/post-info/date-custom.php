<?php
$date_link = empty( get_the_title() ) && ! is_single() ? get_the_permalink() : get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) );
$classes   = '';
if ( is_single() || is_page() || is_archive() ) { // This check is to prevent classes for Gutenberg block
	$classes = 'published updated';
}
?>
<a itemprop="dateCreated" href="<?php echo esc_url( $date_link ); ?>" class="qodef-e-info-date entry-date <?php echo esc_attr( $classes ); ?>">
	<span><?php the_time( 'M' ); ?></span>
	<span><?php the_time( 'j' ); ?></span>
</a>
