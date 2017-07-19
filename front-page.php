<?php
get_header();
?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="container-white">
                <?php get_template_part('templates/bs-carousel-default'); ?>
                <div class="semibox-shadow text-center">
                    <img src="<?php bloginfo('template_url') ?>/images/"  alt="">
                </div>
                <div class="content-central">
                    <div class="titles">
                        <h2><span></span> <span></span> <span></span></h2>
                        <h3> <span></span> <span></span></h3>
                        <!--<a class="btn-green hvr-shutter-in-vertical" href="<?php echo home_url('nacional') ?>">   
                            ¡Entérate cómo! <i class="fa fa-arrow-right animated fadeInLeft"></i>
                        </a>-->
                        <hr class="tall">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>

