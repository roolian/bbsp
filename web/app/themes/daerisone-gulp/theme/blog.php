<?php
/*
Template Name: Blog
*/
get_header();
?>
<div class="blog">
  <div class="title-wrapper">
      <div class="title-container">
          <div class="container">
              <?php the_title('<h1>', '</h1>'); ?>
          </div>
      </div>
  </div>

  <div class="container middle-container">
    <div class="row">

      <div class="col">

      <?php 

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 10,
            'paged'=> $paged,
            'post_status'=>'publish'
        );

        $the_query = new WP_Query($args);

        if ( $the_query->have_posts() ):

          echo '<div class="row px-4">';

          while ( $the_query->have_posts() ) :

            $the_query->the_post();
            echo '<div class="col-md-6 p-2">';
            get_template_part( 'templates/partials/content/content', 'item' );
            echo '</div>';

          endwhile;

          echo '</div>';

          // Previous/next page navigation.
          daerisone_the_posts_navigation($the_query->max_num_pages);

        // If no content, include the "No posts found" template.
        else :

          get_template_part( 'templates/partials/content/content', 'none' );

        endif;

        wp_reset_postdata();

        ?>
      
      </div>

      <?php get_template_part( 'templates/partials/common/sidebar-right' ); ?>



    </div>
  </div>
</div>

<?php
get_footer();
