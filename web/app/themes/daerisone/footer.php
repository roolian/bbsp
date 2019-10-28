<?php

/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) {
    get_template_part('template-parts/footer');
}
?>

<div class="modal fade" id="requestTrial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php
                echo do_shortcode('[hubspot type=form portal=6068051 id=a2c77b2e-0823-44ae-82b1-44f88d092742]');
                ?>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="contactUs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php
                echo do_shortcode('[hubspot type=form portal=6068051 id=58205762-79a0-4dab-beaa-cd3172047931]');
                ?>
            </div>
        </div>
    </div>
</div>

<?php wp_footer(); ?>

</body>

</html>