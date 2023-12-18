<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
            <h1>Blog</h1>
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

<?php get_footer();
