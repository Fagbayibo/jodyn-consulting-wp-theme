<?php
/**
 * JoDyn branding in the WordPress admin, without a plugin.
 *
 * - Login page: forest green with the white JoDyn logo, a white card and a lime button
 *   (assets/css/login.css). The logo links to the website, not WordPress.org.
 * - "JoDyn" admin colour scheme (assets/css/admin-colors.css): forest menu and toolbar, lime
 *   highlights. Setup version 12 gives it to administrators still on WordPress's default; anyone
 *   can switch under Users → Profile → Admin Color Scheme.
 * - Dashboard: a "JoDyn website" box with shortcuts and the last 30 days of form entries, then
 *   Site Health, At a Glance and Activity only: other boxes, including any a plugin adds, go. The toolbar's WordPress logo menu is
 *   removed and the footer names the site.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Login page ---------- */

add_action(
	'login_enqueue_scripts',
	static function () {
		wp_enqueue_style( 'jodyn-login', get_template_directory_uri() . '/assets/css/login.css', array(), jodyn_asset_version( 'assets/css/login.css' ) );
		wp_add_inline_style( 'jodyn-login', '.login h1 a{background-image:url(' . esc_url( get_template_directory_uri() . '/assets/img/logo-white.svg' ) . ')}' );
	}
);
add_filter( 'login_headerurl', static fn() => home_url( '/' ) );
add_filter( 'login_headertext', static fn() => get_bloginfo( 'name' ) );

/* ---------- Admin colour scheme ---------- */

add_action(
	'admin_init',
	static function () {
		wp_admin_css_color(
			'jodyn',
			__( 'JoDyn', 'jodyn' ),
			get_template_directory_uri() . '/assets/css/admin-colors.css?ver=' . jodyn_asset_version( 'assets/css/admin-colors.css' ),
			array( '#17261f', '#233831', '#e5fd4a', '#ffffff' ),
			array(
				'base'    => '#b9c8c1',
				'focus'   => '#e5fd4a',
				'current' => '#233831',
			)
		);
	}
);

/* ---------- Dashboard ---------- */

/**
 * The dashboard keeps four boxes: the JoDyn box first, then Site Health, At a Glance and
 * Activity. WordPress's welcome banner, news and quick-draft boxes go, and so does any box
 * another plugin adds, now or later.
 */
function jodyn_dashboard_boxes() {
	return array( 'jodyn_welcome', 'dashboard_site_health', 'dashboard_right_now', 'dashboard_activity' );
}

add_action(
	'wp_dashboard_setup',
	static function () {
		remove_action( 'welcome_panel', 'wp_welcome_panel' ); // added by WordPress after the theme loads
		wp_add_dashboard_widget( 'jodyn_welcome', __( 'JoDyn website', 'jodyn' ), 'jodyn_dashboard_widget' );
	}
);

/**
 * Runs after every plugin has added its boxes.
 */
add_action(
	'wp_dashboard_setup',
	static function () {
		global $wp_meta_boxes;
		$keep = jodyn_dashboard_boxes();
		foreach ( (array) ( $wp_meta_boxes['dashboard'] ?? array() ) as $context => $priorities ) {
			foreach ( (array) $priorities as $priority => $boxes ) {
				foreach ( array_keys( (array) $boxes ) as $id ) {
					if ( ! in_array( $id, $keep, true ) ) {
						unset( $wp_meta_boxes['dashboard'][ $context ][ $priority ][ $id ] );
					}
				}
			}
		}
		// The JoDyn box first, at the top of the first column.
		$normal = $wp_meta_boxes['dashboard']['normal']['core'] ?? array();
		if ( isset( $normal['jodyn_welcome'] ) ) {
			$wp_meta_boxes['dashboard']['normal']['core'] = array( 'jodyn_welcome' => $normal['jodyn_welcome'] ) + $normal; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	},
	PHP_INT_MAX
);

/**
 * How many entries of a form arrived in the last 30 days.
 *
 * @param string $type Post type.
 * @return int
 */
function jodyn_recent_entries( $type ) {
	$query = new WP_Query(
		array(
			'post_type'      => $type,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'date_query'     => array( array( 'after' => '30 days ago' ) ),
		)
	);
	return (int) $query->found_posts;
}

/**
 * The "JoDyn website" box: form entries this month, then shortcuts.
 */
function jodyn_dashboard_widget() {
	$counts = array(
		array( __( 'Enquiries', 'jodyn' ), 'jodyn_enquiry' ),
		array( __( 'Registrations', 'jodyn' ), 'jodyn_registration' ),
		array( __( 'Calendar downloads', 'jodyn' ), 'jodyn_lead' ),
	);
	$links  = array(
		array( __( 'Write an article', 'jodyn' ), admin_url( 'post-new.php' ), 'dashicons-edit' ),
		array( __( 'Programmes', 'jodyn' ), admin_url( 'edit.php?post_type=jodyn_programme' ), 'dashicons-calendar-alt' ),
		array( __( 'Article authors', 'jodyn' ), admin_url( 'edit-tags.php?taxonomy=jodyn_writer' ), 'dashicons-groups' ),
		array( __( 'Contact details', 'jodyn' ), admin_url( 'customize.php?autofocus[section]=jodyn_contact' ), 'dashicons-phone' ),
		array( __( 'Media library', 'jodyn' ), admin_url( 'upload.php' ), 'dashicons-format-image' ),
		array( __( 'View the website', 'jodyn' ), home_url( '/' ), 'dashicons-external' ),
	);
	?>
	<div class="jodyn-dash">
		<p class="jodyn-dash__label"><?php esc_html_e( 'Last 30 days', 'jodyn' ); ?></p>
		<ul class="jodyn-dash__stats">
			<?php foreach ( $counts as $count ) : ?>
				<?php if ( post_type_exists( $count[1] ) ) : ?>
					<li>
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . $count[1] ) ); ?>">
							<b><?php echo esc_html( number_format_i18n( jodyn_recent_entries( $count[1] ) ) ); ?></b>
							<span><?php echo esc_html( $count[0] ); ?></span>
						</a>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<p class="jodyn-dash__label"><?php esc_html_e( 'Shortcuts', 'jodyn' ); ?></p>
		<ul class="jodyn-dash__links">
			<?php foreach ( $links as $link ) : ?>
				<li><a href="<?php echo esc_url( $link[1] ); ?>"><span class="dashicons <?php echo esc_attr( $link[2] ); ?>" aria-hidden="true"></span><?php echo esc_html( $link[0] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}

add_action(
	'admin_head-index.php',
	static function () {
		?>
		<style>
			#jodyn_welcome .inside{margin:0;padding:16px}
			.jodyn-dash__label{margin:0 0 8px;color:#4a5a54;font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase}
			.jodyn-dash__stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;margin:0 0 20px}
			.jodyn-dash__stats a{display:grid;gap:2px;padding:14px;border-radius:10px;background:#233831;color:#b9c8c1;text-decoration:none}
			.jodyn-dash__stats a:hover,.jodyn-dash__stats a:focus{background:#17261f;color:#fff}
			.jodyn-dash__stats b{color:#e5fd4a;font-size:26px;line-height:1.1}
			.jodyn-dash__links{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin:0}
			.jodyn-dash__links a{display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid #dbe2de;border-radius:10px;color:#233831;font-weight:500;text-decoration:none}
			.jodyn-dash__links a:hover,.jodyn-dash__links a:focus{border-color:#233831;background:#f3f6f2}
			.jodyn-dash__links .dashicons{color:#233831}
		</style>
		<?php
	}
);

/* ---------- Toolbar and footer ---------- */

add_action(
	'admin_bar_menu',
	static function ( $bar ) {
		$bar->remove_node( 'wp-logo' );
	},
	99
);
add_filter(
	'admin_footer_text',
	static fn() => esc_html( get_bloginfo( 'name' ) )
);
