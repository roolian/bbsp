
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="entry-content">
    
    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
      <h2><?php the_title(); ?></h2>
      <p><?php the_excerpt(); ?></p>
    </a>
    
  </div><!-- .entry-content -->
</article><!-- #post-## -->
