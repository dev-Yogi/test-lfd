<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package lemonfreshday
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<img src="<?php bloginfo('template_url'); ?>/lib/imgs/logo_footer.jpg" alt="<?php bloginfo('name'); ?>" /><br />
			<br />
			&copy; <?php echo date("o"); ?> <?php bloginfo('name'); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
