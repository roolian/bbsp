<?php
	
	$args = array(
	  'fields' => apply_filters( 'comment_form_default_fields', array(
	    'author' =>
	      '<p class="comment-form-author">' .
	      '<label for="author">' . __( 'Name', 'domainreference' ) . '</label> ' .
	      ( $req ? '<span class="required">*</span>' : '' ) .
	      '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
	      '" size="30"' . ( $req ? " aria-required='true'" : '' ) . ' /></p>',
	    'email' =>
	      '<p class="comment-form-email"><label for="email">' . __( 'Email', 'domainreference' ) . '</label> ' .
	      ( $req ? '<span class="required">*</span>' : '' ) .
	      '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
	      '" size="30"' . ( $req ? " aria-required='true'" : '' ) . ' /></p>',
	    'url' =>
	      '<p class="comment-form-url"><label for="url">' .
	      __( 'Website', 'domainreference' ) . '</label>' .
	      '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
	      '" size="30" /></p>'
    )),
    'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>'
	);

	if ( is_single() || is_page() ) :
		echo '<div class="clearfix"></div>';
		if ( have_comments() && comments_open() ) :
?>
			<h4 id="comments"><?php comments_number( __( 'Leave a Comment', 'bootstrap-four' ), __( 'One Comment', 'bootstrap-four' ), '%' . __( ' Comments', 'bootstrap-four' ) );?></h4>
			<ul class="commentlist">
				<?php
					wp_list_comments();
					paginate_comments_links();
					if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
				?>
			</ul>
<?php
			comment_form($args);
		else :
			if ( comments_open() ) comment_form($args);
		endif;
	endif;
?>
