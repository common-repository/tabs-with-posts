<?php

if ( ! isset( $artblock ) ) {
	return;
}

?>

<article class="twrp-ss twrp-block <?php $artblock->the_block_class(); ?>">
	<a class="twrp-link-expand twrp-ss__link" href="<?php $artblock->the_permalink(); ?>">
		<?php $artblock->the_title( '<h3 class="twrp-ss__title">', '</h3>' ); ?>
	</a>

	<?php if ( $artblock->thumbnail_is_displayed() ) : ?>
		<div class="twrp-ss__thumbnail-container">
			<div class="twrp-thumbnail-wrapper twrp-ss__thumbnail-wrapper">
				<?php $artblock->display_post_thumbnail( 'medium', array( 'class' => 'twrp-thumbnail twrp-ss__thumbnail' ) ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $artblock->one_or_more_meta_is_displayed( array( 1, 2, 3 ) ) ) : ?>
		<div class="twrp-ss__first-meta-wrapper twrp-ss__flex-meta-wrapper--<?php $artblock->meta_suffix_classes( array( 1, 2, 3 ) ); ?>">
			<?php if ( $artblock->get_meta_displayed_name( 1 ) ) : ?>
				<span class="twrp-ss__meta twrp-ss__meta--1 twrp-ss__<?php $artblock->meta_suffix_class( 1 ); ?>"><?php $artblock->display_meta( 1 ); ?></span>
			<?php endif; ?>

			<?php if ( $artblock->get_meta_displayed_name( 2 ) ) : ?>
				<span class="twrp-ss__meta twrp-ss__meta--2 twrp-ss__<?php $artblock->meta_suffix_class( 2 ); ?>"><?php $artblock->display_meta( 2 ); ?></span>
			<?php endif; ?>

			<?php if ( $artblock->get_meta_displayed_name( 3 ) ) : ?>
				<span class="twrp-ss__meta twrp-ss__meta--3 twrp-ss__<?php $artblock->meta_suffix_class( 3 ); ?>"><?php $artblock->display_meta( 3 ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $artblock->one_or_more_meta_is_displayed( array( 4, 5, 6 ) ) ) : ?>
		<div class="twrp-ss__second-meta-wrapper twrp-ss__flex-meta-wrapper--<?php $artblock->meta_suffix_classes( array( 4, 5, 6 ) ); ?>">
			<?php if ( $artblock->get_meta_displayed_name( 4 ) ) : ?>
				<span class="twrp-ss__meta twrp-ss__meta--4 twrp-ss__<?php $artblock->meta_suffix_class( 4 ); ?>"><?php $artblock->display_meta( 4 ); ?></span>
			<?php endif; ?>

			<?php if ( $artblock->get_meta_displayed_name( 5 ) ) : ?>
				<span class="twrp-ss__meta twrp-ss__meta--5 twrp-ss__<?php $artblock->meta_suffix_class( 5 ); ?>"><?php $artblock->display_meta( 5 ); ?></span>
			<?php endif; ?>

			<?php if ( $artblock->get_meta_displayed_name( 6 ) ) : ?>
				<span class="twrp-ss__meta twrp-ss__meta--6 twrp-ss__<?php $artblock->meta_suffix_class( 6 ); ?>"><?php $artblock->display_meta( 6 ); ?></span>
			<?php endif; ?>
		</div>
		<?php endif; ?>
</article>
