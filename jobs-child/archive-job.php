<?php get_header(); ?>
<header class="page-header alignwide">
	<h1 class="page-title">Job Postings</h1>
</header>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php jobs_display_fields( get_the_ID() ); ?>
		<a href="<?php the_permalink(); ?>">View details &rarr;</a>
	</article>
<?php endwhile; the_posts_pagination(); else : ?>
	<p>No jobs posted yet.</p>
<?php endif; ?>
<?php get_footer(); ?>
