<?php
/**
 * Title: Author bio
 * Slug: yesterday/author-bio
 * Categories: yesterday, about
 * Keywords: author, bio, about, profile, writer
 * Description: A short author introduction with a round portrait, name, bio and a link button.
 *
 * @package Yesterday
 */

?>
<!-- wp:media-text {"mediaType":"image","mediaWidth":25,"verticalAlignment":"center","className":"yesterday-author-bio"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-vertically-aligned-center yesterday-author-bio" style="grid-template-columns:25% auto"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/patterns/portrait.svg' ) ); ?>" alt=""/></figure><div class="wp-block-media-text__content">
<!-- wp:paragraph {"className":"yesterday-eyebrow"} -->
<p class="yesterday-eyebrow"><?php esc_html_e( 'About the author', 'yesterday' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Your Name', 'yesterday' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'A line or two about who you are, what you write about, and why. Keep it warm and personal — readers like knowing the person behind the words.', 'yesterday' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'More about me', 'yesterday' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div></div>
<!-- /wp:media-text -->
