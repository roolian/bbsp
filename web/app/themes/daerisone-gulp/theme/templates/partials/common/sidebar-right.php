 <?php if( is_active_sidebar( 'right_sidebar' ) ): ?>
<div class="col-md-5 col-lg-4 col-xl-3 sidebar-wrapper">
    <div class="sidebar-container">
      <div class="widget">
        <form role="search" method="get" class="search-form row justify-content-between" action="/">
          <div class="form-group col pr-2">
            <label class="screen-reader-text">Rechercher&nbsp;:</label>
            <input type="search" class="search-field form-control" placeholder="Recherche…" value="" name="s">
            
          </div>
          <div class="form-group col-auto pl-2">
            <input type="submit" class="search-submit btn btn-secondary" value="Rechercher">
          </div>
        </form>
      </div>
      
      <?php dynamic_sidebar( 'right_sidebar' ); ?>
      <?php last_posts_widget(); ?>
    </div>
</div>
<?php endif; ?>