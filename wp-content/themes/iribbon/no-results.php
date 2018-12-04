<?php
/**
 * No Search Results Template
 *
 * Please do not edit this file. This file is part of the Cyber Chimps Framework and all modifications
 * should be made in a child theme.
 *
 * @category CyberChimps Framework
 * @package  Framework
 * @since    1.0
 * @author   CyberChimps
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v3.0 (or later)
 * @link     http://www.cyberchimps.com/
 */
?>

<div class="ribbon-top">
	<h1 class="entry-title"><?php _e( 'Nothing Found', 'iribbon' ); ?></h1>
</div>

<article id="post-0" class="post no-results not-found" xmlns="http://www.w3.org/1999/html">

	<div class="entry-content">
		<?php if( is_home() ) { ?>

			<p><?php printf( '%1$s <a href="%2$s"></a>.',
			                 __( 'Ready to publish your first post?', 'iribbon' ),
			                 admin_url( 'post-new.php' ),
			                 __( 'Get started here', 'iribbon' )
				); ?></p>

		<?php }
		elseif( is_search() ) { ?>

			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'iribbon' ); ?></p>
			<?php get_search_form(); ?>

		<?php }
		else { ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'iribbon' ); ?></p>
			<?php get_search_form(); ?>

		<?php } ?>
	</div>
	<!-- .entry-content -->
</article><!-- #post-0 -->