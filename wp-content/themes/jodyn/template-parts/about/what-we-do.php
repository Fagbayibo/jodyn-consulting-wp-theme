<?php
/**
 * About · 5. What we do.
 *
 * White. The four services as a linked chain: four columns whose icons are joined by a lime
 * line that draws itself as you scroll (assets/js/about.js). On phones the chain runs down
 * the left. At rest (no JS, reduced motion) the line is fully drawn.
 * Copy: content/pages/about.md, section 5 (approved; Systems Audit and Tax scope confirmed by
 * the client 2026-10-23).
 *
 * @package JoDyn
 */

$jodyn_services = array(
	array( 'Advisory', 'compass', 'We help leaders make better decisions and build stronger organisations. Our advisory work covers strategy and business advisory, organisational transformation, people and culture, performance improvement, and learning and development. We work with leadership teams to diagnose challenges, design practical solutions and support implementation.', 'Explore Advisory Services', '/services/advisory/' ),
	array( 'Systems Audit', 'search', 'Independent reviews of the systems, processes and controls your organisation runs on, so you can see clearly where the risks and inefficiencies are and fix them before they become costly. Our reviews cover internal controls, processes and operations, IT and information systems, and governance and compliance.', 'Explore Systems Audit', '/services/systems-audit/' ),
	array( 'Tax Consultancy', 'receipt', 'Practical tax advice and compliance support that keeps your organisation compliant, reduces risk and avoids costly surprises. Our support covers compliance and filing, tax planning, tax health checks and support during reviews by the tax authorities.', 'Explore Tax Consultancy', '/services/tax-consultancy/' ),
	array( 'Training & Development', 'cap', 'We equip individuals and teams with the skills to perform and lead. Our training portfolio includes leadership and management development, corporate in-house programmes, open programmes, capacity building and professional skills training, delivered in person, online or in a hybrid format.', 'Explore Training Programmes', '/training/programmes/' ),
);
?>
<section class="jd-wwd" aria-labelledby="jd-wwd-title" data-jd-particles="light" data-jd-keep-out=".jd-wwd__head > *, .jd-wwd__chain">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-wwd__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>What we do</p>
				<h2 class="jd-wwd__title" id="jd-wwd-title" data-jd-lines>Four Services, <em>Working Together</em></h2>
			</div>
			<p class="jd-wwd__intro" data-jd-rise="0.1">Our work is built on four connected areas of expertise. Many clients start with one and discover the value of combining them.</p>
		</div>

		<div class="jd-wwd__chain" data-jd-wwd-chain>
			<div class="jd-wwd__track" aria-hidden="true"><span class="jd-wwd__line" data-jd-wwd-line></span></div>
			<ol class="jd-wwd__cols">
				<?php foreach ( $jodyn_services as $i => $jodyn_service ) : ?>
					<li class="jd-wwd__col" data-jd-rise="<?php echo esc_attr( number_format( 0.08 * $i, 2 ) ); ?>">
						<span class="jd-wwd__node"><?php echo jodyn_icon( $jodyn_service[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="jd-wwd__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h3><?php echo esc_html( $jodyn_service[0] ); ?></h3>
						<p><?php echo esc_html( $jodyn_service[2] ); ?></p>
						<a class="jd-wwd__link" href="<?php echo esc_url( home_url( $jodyn_service[4] ) ); ?>">
							<?php echo esc_html( $jodyn_service[3] ); ?>
							<span aria-hidden="true"><?php echo jodyn_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
