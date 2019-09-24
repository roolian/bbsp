<?php if( has_category() ) : ?>
	<p class="text-right"><?php _e( 'Posted In', 'bootstrap-four' ); ?>: <?php the_category( __( ', ', 'bootstrap-four' ) ); ?></p>
<?php endif; ?>

<?php if( has_tag() ) : ?>
	<p class="text-right"><?php the_tags(); ?></p>
<?php endif; ?>