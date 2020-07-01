<?php get_header(); ?>
<main id="content">
<img id="image-back" src="">
	<div id="playlist-post-content">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<a id="<?php the_ID(); ?>" class="audio-link post_content" href="#" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
		<?php endwhile; endif; ?>
		<?php get_template_part( 'nav', 'below' ); ?>
	</div>
</main>
<div id="post-text-content">
	<div id="content-post-ajax">
	</div>
</div>
<?php get_footer(); ?>