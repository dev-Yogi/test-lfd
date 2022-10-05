<?php
/**
 * @package lemonfreshday
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="date">
			<span class="month"><?php the_time('M') ?></span>
			<span class="day"><?php the_time('d') ?></span>
			<span class="year"><?php the_time('Y') ?></span>
		</div><!-- .date -->
		<?php the_title( sprintf( '<h1 class="entry-title">', esc_url( get_permalink() ) ), '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'lemonfreshday' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'lemonfreshday' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php lemonfreshday_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->