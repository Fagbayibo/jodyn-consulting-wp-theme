<?php
/**
 * Home · 8. Who we serve.
 *
 * Forest section: five audience cards (photo, lime icon tab, name, line) and the industries
 * as pills. Copy: content/pages/home.md, section 8 (approved). Motion: data-jd-* hooks only.
 *
 * @package JoDyn
 */

$jodyn_img = get_template_directory_uri() . '/assets/img/home/';

// Icon, name, line, photo, photo size, optional crop focus (keeps faces in frame).
$jodyn_audiences = array(
	array( 'building', 'Corporate Organisations', 'Helping established businesses sharpen strategy, strengthen controls and prepare their leaders and workforce for the future.', 'serve-corporate.webp', 900, 507, '22% 50%' ),
	array( 'landmark', 'Public Sector and Institutions', 'Supporting government agencies, parastatals and institutions with systems reviews, capacity building and organisational development.', 'serve-public.webp', 733, 1100, '' ),
	array( 'store', 'SMEs and Growing Businesses', 'Helping founders and growing teams build the structures, systems, tax discipline and skills needed to scale with confidence.', 'serve-sme.webp', 733, 1100, '' ),
	array( 'heart', 'Non-Profits and Development Organisations', 'Strengthening governance, programme teams and leadership to deliver greater impact in their communities.', 'serve-nonprofit.webp', 900, 600, '' ),
	array( 'user', 'Individual Professionals', 'Equipping ambitious professionals with the practical skills to advance their careers.', 'serve-professional.webp', 900, 600, '' ),
);

$jodyn_industries = array( 'Financial Services', 'Oil & Gas and Energy', 'Telecommunications', 'Public Sector', 'Healthcare', 'Education', 'Manufacturing', 'Professional Services', 'Non-Profit & Development' );
?>
<section class="jd-serve" id="who-we-serve" aria-labelledby="jd-serve-title"
	data-jd-particles="forest" data-jd-keep-out=".jd-serve__head > *, .jd-serve__cards, .jd-serve__industries">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">

		<div class="jd-serve__head">
			<div>
				<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Who we serve</p>
				<h2 class="jd-serve__title" id="jd-serve-title" data-jd-lines>Supporting Organisations and Professionals <em>Across Sectors</em></h2>
			</div>
			<p class="jd-serve__text" data-jd-rise="0.1">Our advisory, audit, tax and training solutions are designed for a wide range of organisations and professionals who are committed to growth.</p>
		</div>

		<ul class="jd-serve__cards">
			<?php foreach ( $jodyn_audiences as $i => $a ) : ?>
				<li class="jd-serve__card" data-jd-rise="<?php echo esc_attr( $i * 0.08 ); ?>">
					<div class="jd-serve__media">
						<img src="<?php echo esc_url( $jodyn_img . $a[3] ); ?>" alt="" width="<?php echo (int) $a[4]; ?>" height="<?php echo (int) $a[5]; ?>" loading="lazy" decoding="async"<?php echo $a[6] ? ' style="object-position:' . esc_attr( $a[6] ) . '"' : ''; ?>>
					</div>
					<div class="jd-serve__body">
						<span class="jd-serve__icon"><?php echo jodyn_icon( $a[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<h3><?php echo esc_html( $a[1] ); ?></h3>
						<p><?php echo esc_html( $a[2] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="jd-serve__industries" data-jd-rise>
			<h3 class="jd-serve__sub">Industries We Serve</h3>
			<ul class="jd-serve__chips" data-jd-stagger>
				<?php foreach ( $jodyn_industries as $industry ) : ?>
					<li><?php echo esc_html( $industry ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

	</div>
</section>
