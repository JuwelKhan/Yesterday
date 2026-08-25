<?php
/**
 * Author Card widget.
 *
 * A self-contained "about the author" card: image, name, role, bio, social
 * links (icons auto-detected from each URL) and a "Get in touch" link. It is one
 * widget, so the title/content fragmentation of stacking separate blocks does
 * not apply. Renders inside the area's `.widget.card` wrapper (so it adds no card
 * of its own) using the design's `.author-card` markup.
 *
 * Layout: Auto / Vertical / Horizontal. Auto resolves by the area it sits in —
 * wide areas (the footer) use the horizontal layout, narrow rails the vertical.
 *
 * @package Yesterday
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Yesterday_Author_Card_Widget extends WP_Widget {

	/**
	 * Register the widget.
	 */
	public function __construct() {
		parent::__construct(
			'yesterday_author_card',
			__( 'Yesterday: Author Card', 'yesterday' ),
			array(
				'description' => __( 'A profile card — image, name, role, bio, social links and a contact link. Ideal for the left sidebar.', 'yesterday' ),
				'classname'   => 'widget_yesterday_author_card',
			)
		);
	}

	/**
	 * Field defaults.
	 *
	 * @return array
	 */
	protected function defaults() {
		return array(
			'image'         => '',
			'name'          => '',
			'role'          => '',
			'bio'           => '',
			'social'        => '',
			'contact_url'   => '',
			'contact_label' => __( 'Get in touch', 'yesterday' ),
			'layout'        => 'auto',
		);
	}

	/**
	 * Front-end output.
	 *
	 * @param array $args     Sidebar args (before/after widget, area id).
	 * @param array $instance Saved field values.
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults() );

		// Resolve the Auto layout by the area's width: the footer is wide, so use
		// the horizontal layout there; everywhere else (the narrow rails) vertical.
		$layout = $instance['layout'];
		if ( 'auto' === $layout ) {
			$is_wide = ! empty( $args['id'] ) && 'footer' === $args['id'];
			$layout  = $is_wide ? 'horizontal' : 'vertical';
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core-provided.

		echo '<div class="author-card author-card--' . esc_attr( $layout ) . '">';

		if ( $instance['image'] ) {
			printf(
				'<img class="author-avatar" src="%1$s" alt="%2$s">',
				esc_url( $instance['image'] ),
				esc_attr( $instance['name'] )
			);
		}

		// Text block — kept in its own wrapper so the horizontal layout can sit it
		// beside the avatar without the avatar's height spacing the lines apart.
		echo '<div class="author-card__body">';

		if ( $instance['name'] ) {
			printf( '<h2 class="author-name">%s</h2>', esc_html( $instance['name'] ) );
		}

		if ( $instance['role'] ) {
			printf( '<p class="author-role">%s</p>', esc_html( $instance['role'] ) );
		}

		if ( $instance['bio'] ) {
			printf( '<p class="author-bio">%s</p>', nl2br( esc_html( $instance['bio'] ) ) );
		}

		// Social icons — one URL per line, icon auto-detected from the host.
		$urls = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $instance['social'] ) ) );
		if ( $urls ) {
			echo '<div class="author-social">';
			foreach ( $urls as $url ) {
				$host  = preg_replace( '/^www\./', '', (string) wp_parse_url( $url, PHP_URL_HOST ) );
				$label = $host ? $host : $url;
				printf(
					'<a href="%1$s" aria-label="%2$s"><i class="bi %3$s" aria-hidden="true"></i></a>',
					esc_url( $url ),
					esc_attr( $label ),
					esc_attr( yesterday_social_icon( $url ) )
				);
			}
			echo '</div>';
		}

		if ( $instance['contact_url'] ) {
			$label = $instance['contact_label'] ? $instance['contact_label'] : __( 'Get in touch', 'yesterday' );
			printf(
				'<a class="author-contact" href="%1$s"><i class="bi bi-envelope" aria-hidden="true"></i> %2$s</a>',
				esc_url( $instance['contact_url'] ),
				esc_html( $label )
			);
		}

		echo '</div>'; // .author-card__body

		echo '</div>'; // .author-card

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core-provided.
	}

	/**
	 * Sanitize submitted values.
	 *
	 * @param array $new_instance New values.
	 * @param array $old_instance Previous values.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                  = array();
		$instance['image']         = isset( $new_instance['image'] ) ? esc_url_raw( $new_instance['image'] ) : '';
		$instance['name']          = isset( $new_instance['name'] ) ? sanitize_text_field( $new_instance['name'] ) : '';
		$instance['role']          = isset( $new_instance['role'] ) ? sanitize_text_field( $new_instance['role'] ) : '';
		$instance['bio']           = isset( $new_instance['bio'] ) ? sanitize_textarea_field( $new_instance['bio'] ) : '';
		$instance['social']        = isset( $new_instance['social'] ) ? sanitize_textarea_field( $new_instance['social'] ) : '';
		$instance['contact_url']   = isset( $new_instance['contact_url'] ) ? esc_url_raw( $new_instance['contact_url'], array( 'http', 'https', 'mailto' ) ) : '';
		$instance['contact_label'] = isset( $new_instance['contact_label'] ) ? sanitize_text_field( $new_instance['contact_label'] ) : '';

		$layout             = isset( $new_instance['layout'] ) ? $new_instance['layout'] : 'auto';
		$instance['layout'] = in_array( $layout, array( 'auto', 'vertical', 'horizontal' ), true ) ? $layout : 'auto';

		return $instance;
	}

	/**
	 * Admin form.
	 *
	 * @param array $instance Saved values.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults() );
		?>
		<p>
			<label><?php esc_html_e( 'Image', 'yesterday' ); ?></label>
			<img class="yd-ac-preview" src="<?php echo esc_url( $instance['image'] ); ?>" style="max-width:100%;height:auto;margin-bottom:.4rem;<?php echo $instance['image'] ? '' : 'display:none;'; ?>">
			<input type="hidden" class="yd-ac-image" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php echo esc_url( $instance['image'] ); ?>">
			<button type="button" class="button yd-ac-upload"><?php esc_html_e( 'Select image', 'yesterday' ); ?></button>
			<button type="button" class="button yd-ac-remove"<?php echo $instance['image'] ? '' : ' style="display:none;"'; ?>><?php esc_html_e( 'Remove', 'yesterday' ); ?></button>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_html_e( 'Name', 'yesterday' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" value="<?php echo esc_attr( $instance['name'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'role' ) ); ?>"><?php esc_html_e( 'Role / tagline', 'yesterday' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'role' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'role' ) ); ?>" value="<?php echo esc_attr( $instance['role'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'bio' ) ); ?>"><?php esc_html_e( 'Bio', 'yesterday' ); ?></label>
			<textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'bio' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bio' ) ); ?>"><?php echo esc_textarea( $instance['bio'] ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'social' ) ); ?>"><?php esc_html_e( 'Social links', 'yesterday' ); ?></label>
			<textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'social' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'social' ) ); ?>" placeholder="https://instagram.com/you&#10;https://x.com/you"><?php echo esc_textarea( $instance['social'] ); ?></textarea>
			<small><?php esc_html_e( 'One URL per line. The icon is chosen automatically from each link.', 'yesterday' ); ?></small>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'contact_url' ) ); ?>"><?php esc_html_e( 'Contact link (URL or mailto:)', 'yesterday' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'contact_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'contact_url' ) ); ?>" value="<?php echo esc_attr( $instance['contact_url'] ); ?>" placeholder="mailto:hello@example.com">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'contact_label' ) ); ?>"><?php esc_html_e( 'Contact label', 'yesterday' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'contact_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'contact_label' ) ); ?>" value="<?php echo esc_attr( $instance['contact_label'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Layout', 'yesterday' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
				<option value="auto" <?php selected( $instance['layout'], 'auto' ); ?>><?php esc_html_e( 'Auto (by area width)', 'yesterday' ); ?></option>
				<option value="vertical" <?php selected( $instance['layout'], 'vertical' ); ?>><?php esc_html_e( 'Vertical', 'yesterday' ); ?></option>
				<option value="horizontal" <?php selected( $instance['layout'], 'horizontal' ); ?>><?php esc_html_e( 'Horizontal', 'yesterday' ); ?></option>
			</select>
		</p>
		<?php
	}
}
