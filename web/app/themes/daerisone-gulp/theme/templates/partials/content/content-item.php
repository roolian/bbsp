<?php 
  $style="";
  if( $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'medium') ){
      $style="background-image:url(".$featured_img_url.")";
  }
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
  <div class="entry-header"  style="<?php echo $style ?>">
    <h2 class="h2"><?php the_title(); ?></h2>
  </div>
  <div class="entry-content">
      <?php the_excerpt(); ?>
  </div><!-- .entry-content -->
  </a>
</article><!-- #post-## -->
