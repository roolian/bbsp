<?php get_header(); ?>
<div class="container middle-container">

<?php while ( have_posts() ) : the_post(); ?>
  <div class="row">

      

      <div class="col">
        <div class="content-wrapper">
          <div class="content-container">
              <?php the_title('<h1>', '</h1>'); ?>
              <?php get_template_part( 'templates/partials/page/page-content' ); ?>
          </div>
        </div>
      </div>



  </div>
<?php endwhile; // End of the loop.?>

</div>

<?php get_footer();
