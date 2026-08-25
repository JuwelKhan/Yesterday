<?php
/**
 * Search form (used by get_search_form()). Matches the theme's .search-form.
 *
 * @package Yesterday
 */

$yd_search_id = 'search-field-' . wp_unique_id();
?>
<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $yd_search_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'yesterday' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $yd_search_id ); ?>" name="s" placeholder="<?php esc_attr_e( 'Search posts', 'yesterday' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
	<button type="submit">
		<i class="bi bi-search" aria-hidden="true"></i>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'yesterday' ); ?></span>
	</button>
</form>
