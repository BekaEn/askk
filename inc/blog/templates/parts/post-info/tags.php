<?php
$tags = get_the_tags();

if ( $tags ) { ?>
	<div class="qodef-e-info-item qodef-e-info-tags">
		<?php the_tags( '', '<span class="qodef-info-separator-single"></span>' ); ?>
	</div>
	<div class="qodef-info-separator-end"></div>
<?php } ?>
