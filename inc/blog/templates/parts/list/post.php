<article <?php post_class( 'qodef-blog-item qodef-e' ); ?>>
	<div class="qodef-e-inner">
		<?php
		// Include post media
		askka_template_part( 'blog', 'templates/parts/post-info/media' );
		?>
		<div class="qodef-e-content">
			<div class="qodef-e-top-holder">
				<div class="qodef-e-info">
					<?php
					// Include post date info
					askka_template_part( 'blog', 'templates/parts/post-info/date' );

					// Include post author info
					askka_template_part( 'blog', 'templates/parts/post-info/author' );

					// Include post category info
					askka_template_part( 'blog', 'templates/parts/post-info/categories' );
					?>
				</div>
			</div>
			<div class="qodef-e-text">
				<?php
				// Include post title
				askka_template_part( 'blog', 'templates/parts/post-info/title', '', array( 'title_tag' => 'h2' ) );

				// Include post excerpt
				askka_template_part( 'blog', 'templates/parts/post-info/excerpt' );

				// Hook to include additional content after blog single content
				do_action( 'askka_action_after_blog_single_content' );
				?>
			</div>
			<div class="qodef-e-bottom-holder">
				<div class="qodef-e-left">
					<?php
					// Include post read more
					askka_template_part( 'blog', 'templates/parts/post-info/read-more' );
					?>
				</div>
				<?php if ( askka_is_installed( 'framework' ) && askka_is_installed( 'core' ) ) : ?>
					<div class="qodef-e-right">
						<?php
						// Include post social share
						askka_template_part( 'blog', 'templates/parts/post-info/social-share-list' );
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>
