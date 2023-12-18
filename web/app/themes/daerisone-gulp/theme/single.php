<?php get_header(); ?>
 <?php 
    $style="";
    if( $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full') ){
        $style="background-image:url(".$featured_img_url.")";
    }
?>
<div class="title-wrapper" style="<?php echo $style ?>">
    <div class="title-container">
        <div class="container">
            <?php the_title('<h1>', '</h1>'); ?>
        </div>
    </div>
</div>
<div class="container middle-container">

  <div class="row">

    <div class="col">
      <div class="content-wrapper">
        <div class="content-container">

          <?php
          while ( have_posts() ) : the_post();

            get_template_part( 'templates/partials/page/page-content' );

            if ( comments_open() || get_comments_number() ) :
              comments_template();
            endif;

          endwhile; // End of the loop.
          ?>

        </div>
      </div>
    </div>


  
        <?php get_template_part( 'templates/partials/common/sidebar-right' ); ?>
      

  </div>

</div>

<?php get_footer();
