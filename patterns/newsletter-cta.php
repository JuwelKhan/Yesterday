<?php
/**
 * Title: Newsletter call to action
 * Slug: yesterday/newsletter-cta
 * Categories: yesterday, call-to-action
 * Keywords: newsletter, subscribe, email, signup, call to action
 * Description: A centred box inviting readers to subscribe, with a heading, a short note and a button.
 *
 * @package Yesterday
 */

?>
<!-- wp:group {"className":"yesterday-newsletter"} -->
<div class="wp-block-group yesterday-newsletter">
<!-- wp:paragraph {"className":"yesterday-eyebrow"} -->
<p class="yesterday-eyebrow"><?php esc_html_e( 'Newsletter', 'yesterday' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Get new stories in your inbox', 'yesterday' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'No spam — just a short note whenever something new is published. Unsubscribe any time.', 'yesterday' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Subscribe', 'yesterday' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
