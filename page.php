<?php
get_header();
the_post();
?>
<?php
$item instanceof WP_Post;

if (has_post_thumbnail(get_the_ID())) {

    $attachment_id = get_post_meta(get_the_ID(), '_thumbnail_id', true);

    $image = wp_get_attachment_image_src($attachment_id, 'full');

    $image_src = $image[0];
} else {
    $image_src = get_bloginfo('stylesheet_directory') . '/images/featured-not-found.jpg';
}
?>
<div class="featured-image" style="background:url(<?php echo $image_src; ?>) 50% 0 no-repeat fixed #000000 !important; background-size: cover;">

</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="container-white">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="breadcrumb clearfix pull-right">
                            <a class="home" href="<?php echo home_url('') ?>" title="Inicio">
                                <i class="fa fa-home"></i>
                            </a>
                            <span class="navigation-pipe">&gt;</span>
                            <?php the_title(); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="content-central">
                            <div class="titles">
                                <h1>
                                    <?php the_title(); ?>
                                </h1>
                                <hr class="tall">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="post-content pd30 text-justify">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
                <?php get_template_part('templates/social') ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
