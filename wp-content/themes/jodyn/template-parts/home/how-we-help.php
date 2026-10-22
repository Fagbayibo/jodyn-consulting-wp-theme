<?php
/**
 * Home · 3. How we help.
 *
 * Forest section with lime particles. The four service lines are tabs; each panel holds
 * the line's summary, photo, four services and a link. Without JS the first panel shows.
 * Copy: content/pages/home.md, section 3 (approved). Behaviour: assets/js/how-we-help.js.
 *
 * @package JoDyn
 */

$jodyn_img = get_template_directory_uri() . '/assets/img/home/';

$jodyn_services = array(
	array(
		'name'  => 'Advisory Services',
		'text'  => 'Expert guidance to help your organisation make better decisions, work more effectively and perform at its best.',
		'image' => 'help-advisory.webp',
		'size'  => array( 1200, 800 ),
		'link'  => '/services/advisory/',
		'cta'   => 'Explore Advisory Services',
		'items' => array(
			array( 'Strategy & Business Advisory', 'We help you clarify your direction, set priorities and turn strategy into clear plans with measurable results.' ),
			array( 'Organisational Transformation', 'We redesign processes, structures and ways of working so your organisation runs more efficiently and responds faster to change.' ),
			array( 'People & Culture Advisory', 'We align your people, roles and culture with your business goals, from organisational design to performance management.' ),
			array( 'Performance Improvement', 'We identify what is holding performance back and work with you to implement solutions that raise productivity and accountability.' ),
		),
	),
	array(
		'name'  => 'Systems Audit',
		'text'  => 'Independent reviews of the systems, processes and controls your organisation runs on, so you can see clearly where the risks and inefficiencies are and fix them before they become costly.',
		'image' => 'help-audit.webp',
		'size'  => array( 1200, 904 ),
		'link'  => '/services/systems-audit/',
		'cta'   => 'Explore Systems Audit',
		'items' => array(
			array( 'Internal Controls Review', 'We test how well your controls protect your assets, data and decisions, and show you where the gaps are.' ),
			array( 'Process & Operations Audit', 'We trace how work actually flows through your organisation and pinpoint delays, duplication and weak points.' ),
			array( 'IT & Information Systems Review', 'We assess whether your systems are secure, reliable and fit for how your business works today.' ),
			array( 'Governance & Compliance Review', 'We check your policies and practices against regulatory and industry requirements.' ),
		),
	),
	array(
		'name'  => 'Tax Consultancy',
		'text'  => 'Practical tax advice and compliance support that keeps your organisation compliant, reduces risk and avoids costly surprises.',
		'image' => 'help-tax.webp',
		'size'  => array( 1200, 1000 ),
		'link'  => '/services/tax-consultancy/',
		'cta'   => 'Explore Tax Consultancy',
		'items' => array(
			array( 'Tax Compliance & Filing', 'Accurate, on-time returns and filings, so you meet every obligation without the last-minute rush.' ),
			array( 'Tax Planning & Advisory', 'Structuring decisions and transactions to manage your tax position efficiently and within the law.' ),
			array( 'Tax Health Checks', 'A clear review of your current tax position, exposures and opportunities.' ),
			array( 'Support with Tax Authorities', 'Guidance and representation during reviews, audits and enquiries.' ),
		),
	),
	array(
		'name'  => 'Training & Development',
		'text'  => 'Practical, engaging learning experiences that equip individuals and teams with skills they can apply immediately, in person, online or hybrid.',
		'image' => 'help-training.webp',
		'size'  => array( 1200, 800 ),
		'link'  => '/training/programmes/',
		'cta'   => 'Explore Training Programmes',
		'items' => array(
			array( 'Leadership & Management Development', 'Programmes that build confident, effective leaders, from first-time supervisors to senior executives.' ),
			array( 'Corporate In-House Training', "Customised programmes designed around your organisation's goals, culture and skill gaps, delivered exclusively for your team." ),
			array( 'Open Programmes', 'Scheduled courses that professionals from different organisations can join.' ),
			array( 'Capacity Building & Professional Skills', 'Structured programmes that strengthen skills across teams, from communication and problem-solving to emotional intelligence.' ),
		),
	),
);
$jodyn_count = count( $jodyn_services );
?>
<section class="jd-help" id="how-we-help" aria-labelledby="jd-help-title" data-jd-help
	data-jd-particles="forest" data-jd-keep-out=".jd-help__head > *, .jd-help__wrap">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">

		<div class="jd-help__head">
			<div>
				<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>How we help</p>
				<h2 class="jd-help__title" id="jd-help-title" data-jd-lines>Four Ways We Drive Your <em>Growth</em></h2>
			</div>
			<p class="jd-help__intro" data-jd-rise="0.1">Some challenges call for expert guidance. Others call for a hard look at your systems, your tax position or the skills of your people. Many need more than one. Our services work hand in hand, so your organisation gets the right support at every stage of its growth.</p>
		</div>

		<div class="jd-help__wrap">
			<div class="jd-help__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Our services', 'jodyn' ); ?>" data-jd-rise="0.1">
				<?php foreach ( $jodyn_services as $i => $service ) : ?>
					<button class="jd-help__tab" type="button" role="tab" id="jd-help-tab-<?php echo (int) $i; ?>"
						aria-controls="jd-help-panel-<?php echo (int) $i; ?>"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>">
						<span class="jd-help__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<span class="jd-help__name"><?php echo esc_html( $service['name'] ); ?></span>
						<?php echo jodyn_icon( 'arrow', 'jd-help__chev' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="jd-help__panels" data-jd-rise="0.2">
				<?php foreach ( $jodyn_services as $i => $service ) : ?>
					<div class="jd-help__panel" role="tabpanel" id="jd-help-panel-<?php echo (int) $i; ?>" aria-labelledby="jd-help-tab-<?php echo (int) $i; ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
						<div class="jd-help__body">
							<p class="jd-help__kicker"><?php echo esc_html( sprintf( '%02d / %02d', $i + 1, $jodyn_count ) ); ?></p>
							<h3 class="jd-help__panel-title"><?php echo esc_html( $service['name'] ); ?></h3>
							<p class="jd-help__text"><?php echo esc_html( $service['text'] ); ?></p>
							<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( home_url( $service['link'] ) ); ?>">
								<?php echo esc_html( $service['cta'] ); ?>
								<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</a>
						</div>
						<div class="jd-help__media">
							<img src="<?php echo esc_url( $jodyn_img . $service['image'] ); ?>" alt="" width="<?php echo (int) $service['size'][0]; ?>" height="<?php echo (int) $service['size'][1]; ?>" loading="lazy" decoding="async">
						</div>
						<ul class="jd-help__items">
							<?php foreach ( $service['items'] as $item ) : ?>
								<li><h4><?php echo esc_html( $item[0] ); ?></h4><p><?php echo esc_html( $item[1] ); ?></p></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

	</div>
</section>
