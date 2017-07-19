<?php get_header(); ?>
<div class="container">
	<div class="row">
		<div class="col-sm-3">
				<?php get_sidebar() ?>
				<?php // get_template_part( 'templates/sidebar-search' ) ?>
			<a href="<?php echo wp_logout_url( home_url() ); ?>">logout</a>

		</div>
		<div class="col-sm-9">
			<?php get_template_part( 'templates/wp-register-form' ) ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>