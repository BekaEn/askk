<article <?php post_class( 'qodef-blog-item qodef-e' ); ?>>
	<div class="qodef-e-inner">
		<div class="qodef-e-media-holder">
			<?php
			// Include post media
			askka_template_part( 'blog', 'templates/parts/post-info/media' );

			if ( has_post_thumbnail() && ( askka_is_installed( 'framework' ) && askka_is_installed( 'core' ) ) ) {
				// Include post date info
				askka_template_part( 'blog', 'templates/parts/post-info/date', 'custom' );
			}
			?>
		</div>
		<div class="qodef-e-content">
			<div class="qodef-e-top-holder">
				<div class="qodef-e-info">
					<?php
					if ( ! has_post_thumbnail() || ! ( askka_is_installed( 'framework' ) && askka_is_installed( 'core' ) ) ) {
						// Include post date info
						askka_template_part( 'blog', 'templates/parts/post-info/date' );
					}

					// Include post author info
					askka_template_part( 'blog', 'templates/parts/post-info/author' );

					// Include post category info
					askka_template_part( 'blog', 'templates/parts/post-info/categories' );
					?>
				</div>
			</div>
			<div class="qodef-e-text">
				<?php
				if ( askka_is_installed( 'framework' ) && askka_is_installed( 'core' ) ) {
					// Include post title
					askka_template_part( 'blog', 'templates/parts/post-info/title' );
				}

				// Include post content
				the_content();

				// Hook to include additional content after blog single content
				do_action( 'askka_action_after_blog_single_content' );
				?>
			</div>
			<div class="qodef-e-bottom-holder">
				<div class="qodef-e-left qodef-e-info">
					<?php
					// Include post tags info
					askka_template_part( 'blog', 'templates/parts/post-info/tags' );
					?>
				</div>
				<?php if ( askka_is_installed( 'framework' ) && askka_is_installed( 'core' ) ) : ?>
					<div class="qodef-e-right">
						<?php
						// Include post social share
						askka_template_part( 'blog', 'templates/parts/post-info/social-share' );
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>
