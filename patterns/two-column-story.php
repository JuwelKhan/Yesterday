<?php
/**
 * Title: Two-column storytelling
 * Slug: yesterday/two-column-story
 * Categories: yesterday, media
 * Keywords: story, image, text, columns, alternating, chapter
 * Description: Two image-and-text rows that alternate sides, for breaking a long story into visual chapters.
 *
 * @package Yesterday
 */

?>
<!-- wp:group {"className":"yesterday-story"} -->
<div class="wp-block-group yesterday-story">
<!-- wp:media-text {"mediaType":"image","verticalAlignment":"center"} -->
<div class="wp-block-media-text is-stacked-on-mobile is-vertically-aligned-center"><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/patterns/landscape.svg' ) ); ?>" alt=""/></figure><div class="wp-block-media-text__content">
<!-- wp:paragraph {"className":"yesterday-eyebrow"} -->
<p class="yesterday-eyebrow"><?php esc_html_e( 'Chapter one', 'yesterday' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Where it began', 'yesterday' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Set the scene. Describe the place, the moment, or the feeling that started it all, and let the picture beside it do some of the talking.', 'yesterday' ); ?></p>
<!-- /wp:paragraph -->
</div></div>
<!-- /wp:media-text -->

<!-- wp:media-text {"mediaPosition":"right","mediaType":"image","verticalAlignment":"center"} -->
<div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-center"><div class="wp-block-media-text__content">
<!-- wp:paragraph {"className":"yesterday-eyebrow"} -->
<p class="yesterday-eyebrow"><?php esc_html_e( 'Chapter two', 'yesterday' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'What changed', 'yesterday' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Move the story forward. Swap the image for one of your own, and add as many rows as the story needs — duplicate a row to continue.', 'yesterday' ); ?></p>
<!-- /wp:paragraph -->
</div><figure class="wp-block-media-text__media"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/patterns/landscape.svg' ) ); ?>" alt=""/></figure></div>
<!-- /wp:media-text -->
</div>
<!-- /wp:group -->
