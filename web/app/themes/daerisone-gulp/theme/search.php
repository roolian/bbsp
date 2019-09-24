<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();
?>
<div class="title-wrapper">
    <div class="title-container">
        <div class="container">
            <h1>
              <?php _e( 'Recherche : ', 'daerisone' ); ?> <?php echo get_search_query(); ?>
            </h1>
        </div>
    </div>
</div>

<div class="container middle-container">
  <div class="row">

    <div class="col">

      <?php if ( have_posts() ) : ?>

        <?php
        // Start the Loop.
        while ( have_posts() ) :
          the_post();

          get_template_part( 'templates/partials/content/content', 'excerpt' );

          // End the loop.
        endwhile;

        // Previous/next page navigation.
        daerisone_the_posts_navigation();

        // If no content, include the "No posts found" template.
      else :
        get_template_part( 'templates/partials/content/content', 'none' );

      endif;
      ?>

    </div>

   <?php get_template_part( 'templates/partials/common/sidebar-right' ); ?>



  </div>
</div>

<?php
get_footer();
