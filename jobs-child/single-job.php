<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php jobs_display_fields( get_the_ID() ); ?>
		<div class="entry-content"><?php the_content(); ?></div>
		<p><a href="<?php echo esc_url( get_post_type_archive_link( 'job' ) ); ?>">&larr; Back to all jobs</a></p>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
