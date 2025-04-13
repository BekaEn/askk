<div class="qodef-e-media">
	<?php
	switch ( get_post_format() ) {
		case 'gallery':
			askka_template_part( 'blog', 'templates/parts/post-format/gallery' );
			break;
		case 'video':
			askka_template_part( 'blog', 'templates/parts/post-format/video' );
			break;
		case 'audio':
			askka_template_part( 'blog', 'templates/parts/post-format/audio' );
			break;
		default:
			askka_template_part( 'blog', 'templates/parts/post-info/image' );
			break;
	}
	?>
</div>
