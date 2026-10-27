<?php
/**
 * Legal pages · the shared layout (option B).
 *
 * Soft green hero: the three policies as tabs, the title, the effective date and the opening
 * paragraph. White body: the policy on the left; on the right the contents list, which stays in
 * view and highlights the section being read, and a forest help card with the email and phone.
 * On tablets and phones the contents fold into an "On this page" dropdown above the text.
 * Then "Our other policies" on soft green, and the footer without the lime card.
 * Scroll highlight and contents glide: assets/js/legal.js.
 * Copy: content/pages/legal.md (approved); wording in inc/legal-content.php.
 *
 * @package JoDyn
 *
 * @param array $args { slug: privacy-policy, terms or cookie-policy }
 */

$jodyn_slug   = $args['slug'];
$jodyn_policy = jodyn_legal_content( $jodyn_slug );
$jodyn_help   = jodyn_legal_help( $jodyn_slug );
?>
<header class="jd-lg-hero">
	<div class="jd-container">
		<nav class="jd-lg-tabs" aria-label="<?php esc_attr_e( 'Policies', 'jodyn' ); ?>" data-jd-rise>
			<?php foreach ( jodyn_legal_policies() as $jodyn_key => $jodyn_p ) : ?>
				<a class="jd-lg-tab" href="<?php echo esc_url( jodyn_legal_url( $jodyn_key ) ); ?>"<?php echo $jodyn_key === $jodyn_slug ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $jodyn_p[0] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<div class="jd-lg-hero__grid">
			<h1 class="jd-lg-title" data-jd-lines><?php echo esc_html( $jodyn_policy['title'] ); ?></h1>
			<div class="jd-lg-hero__side">
				<p class="jd-lg-date" data-jd-rise="0.05"><?php esc_html_e( 'Effective date:', 'jodyn' ); ?> <?php echo esc_html( $jodyn_policy['date'] ); ?></p>
				<?php foreach ( $jodyn_policy['intro'] as $jodyn_i => $jodyn_text ) : ?>
					<p class="jd-lg-intro" data-jd-rise="<?php echo esc_attr( 0.1 + $jodyn_i * 0.08 ); ?>"><?php echo wp_kses_post( jodyn_legal_fill( $jodyn_text ) ); ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</header>

<div class="jd-lg-body">
	<div class="jd-container jd-lg-grid">
		<div>
			<details class="jd-lg-mtoc">
				<summary><?php esc_html_e( 'On this page', 'jodyn' ); ?><?php echo jodyn_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></summary>
				<ol>
					<?php foreach ( $jodyn_policy['sections'] as $jodyn_s ) : ?>
						<li><a href="#<?php echo esc_attr( $jodyn_s['id'] ); ?>"><?php echo esc_html( $jodyn_s['title'] ); ?></a></li>
					<?php endforeach; ?>
				</ol>
			</details>

			<div class="jd-lg-prose">
				<?php foreach ( $jodyn_policy['sections'] as $jodyn_n => $jodyn_s ) : ?>
					<section class="jd-lg-sec" aria-labelledby="<?php echo esc_attr( $jodyn_s['id'] ); ?>">
						<h2 id="<?php echo esc_attr( $jodyn_s['id'] ); ?>"><?php echo esc_html( ( $jodyn_n + 1 ) . '. ' . $jodyn_s['title'] ); ?></h2>
						<?php echo wp_kses_post( jodyn_legal_fill( $jodyn_s['html'] ) ); ?>
					</section>
				<?php endforeach; ?>
			</div>
		</div>

		<aside class="jd-lg-aside">
			<nav class="jd-lg-toc" aria-label="<?php esc_attr_e( 'On this page', 'jodyn' ); ?>" data-jd-lg-toc>
				<p class="jd-lg-toc__label"><?php esc_html_e( 'On this page', 'jodyn' ); ?></p>
				<ol>
					<?php foreach ( $jodyn_policy['sections'] as $jodyn_s ) : ?>
						<li><a href="#<?php echo esc_attr( $jodyn_s['id'] ); ?>"><?php echo esc_html( $jodyn_s['title'] ); ?></a></li>
					<?php endforeach; ?>
				</ol>
			</nav>
			<div class="jd-lg-ask">
				<span><?php echo jodyn_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<p>
					<b><?php echo esc_html( $jodyn_help['title'] ); ?></b>
					<?php
					/* translators: 1: email address, 2: phone number. */
					echo wp_kses_post( jodyn_legal_fill( sprintf( __( 'Email %1$s or call %2$s.', 'jodyn' ), $jodyn_help['email'], '{phone}' ) ) );
					?>
				</p>
			</div>
		</aside>
	</div>
</div>

<section class="jd-lg-more" aria-labelledby="jd-lg-more-title">
	<div class="jd-container">
		<h2 class="jd-lg-more__title" id="jd-lg-more-title" data-jd-rise><?php esc_html_e( 'Our other policies', 'jodyn' ); ?></h2>
		<div class="jd-lg-more__grid" data-jd-stagger>
			<?php foreach ( jodyn_legal_policies() as $jodyn_key => $jodyn_p ) : ?>
				<?php
				if ( $jodyn_key === $jodyn_slug ) {
					continue;
				}
				?>
				<a class="jd-lg-more__card" href="<?php echo esc_url( jodyn_legal_url( $jodyn_key ) ); ?>">
					<span><b><?php echo esc_html( $jodyn_p[0] ); ?></b><small><?php echo esc_html( $jodyn_p[1] ); ?></small></span>
					<i><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></i>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
