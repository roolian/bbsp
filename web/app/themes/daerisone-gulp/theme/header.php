<!DOCTYPE html>

<html <?php language_attributes(); ?> class="no-js">
    <head>
        <meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <script defer src="https://use.fontawesome.com/releases/v5.8.1/js/all.js" integrity="sha384-g5uSoOSBd7KkhAMlnQILrecXvzst9TdC09/VM+pjDTCM+1il8RHz5fKANTFFb+gQ" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,600" rel="stylesheet">
        
        <link href="https://fonts.googleapis.com/css?family=Dancing+Script:700" rel="stylesheet">

        <?php wp_head(); ?>

        <?php $favicon_url = get_bloginfo('template_url')."/img/favicon"; ?>

	</head>
	<body <?php body_class(); ?>>

        <div class="main-wrapper">

            <div class="header-wrapper">
                <div class="container header-container">
                    <?php get_template_part( 'templates/partials/common/navigation' ); ?>
                </div>
            </div>

            <?php if( have_rows('home_galery', 'site_options') && is_front_page() ): ?>

                <div class="slider-wrapper">
                    <!-- Slider main container -->
                    <div class="swiper-container">
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                            <!-- Slides -->
                            <?php while( have_rows('home_galery', 'site_options') ): the_row(); ?>

                                <?php 
                                    $image = get_sub_field('image'); 
                                ?>
                                <div class="swiper-slide">
                                    <div class="slide-img" style="background-image:url(<?php echo $image['url'] ?>)">
                                        <div class="container">
                                            <div class="slide-title h1"><?php the_sub_field('title') ?></div>
                                        </div>
                                    </div>
                                </div>

                            <?php endwhile; ?>
                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>

                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                        <!-- If we need scrollbar -->
                        <div class="swiper-scrollbar"></div>
                    </div>

                </div>

            <?php endif; ?>


                <div id="barba-wrapper">
                    <div class="barba-container">