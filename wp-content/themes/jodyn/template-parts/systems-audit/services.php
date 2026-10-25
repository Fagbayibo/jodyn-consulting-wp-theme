<?php
/**
 * Systems Audit · 3. Our audit services (#services, the hero's "See What We Review").
 *
 * White. Heading and intro, then a lime bar of the four services that stays at the top while
 * the section scrolls and highlights the one being read (assets/js/systems-audit.js). Each
 * service alternates photo left and right: number, name, text, What we review (ticked, on soft
 * green), Ideal for and Enquire About This Service (the Contact form with Systems Audit chosen).
 * Copy: content/pages/systems-audit.md, section 3 (approved).
 *
 * @package JoDyn
 */

$jodyn_services = array(
	array(
		'id'     => 'audit-controls',
		'name'   => 'Internal Controls Review',
		'photo'  => 'svc-controls',
		'text'   => 'Controls protect your assets, your data and the quality of your decisions. We test how well yours are designed and whether they work in practice, not just on paper.',
		'review' => array( 'Approval limits and authorisation processes', 'Segregation of duties', 'Cash, payments and procurement controls', 'Asset and inventory controls', 'Reconciliations and financial reporting checks', 'Fraud risk and how it is prevented and detected' ),
		'ideal'  => 'growing businesses formalising their controls, and organisations that have had a loss, error or audit finding.',
	),
	array(
		'id'     => 'audit-process',
		'name'   => 'Process & Operations Audit',
		'photo'  => 'svc-process',
		'text'   => 'How work is meant to happen and how it actually happens are often different. We trace your key processes from start to finish and pinpoint the delays, duplication and weak points that cost you time and money.',
		'review' => array( 'End-to-end mapping of key processes', 'Bottlenecks, rework and duplicated effort', 'Roles, handovers and accountability', 'Policies and procedures against actual practice', 'Turnaround times and service levels', 'Quick wins and longer-term improvements' ),
		'ideal'  => 'organisations with slow operations, rising costs or inconsistent service, and those preparing to automate or restructure.',
	),
	array(
		'id'     => 'audit-it',
		'name'   => 'IT & Information Systems Review',
		'photo'  => 'svc-it',
		'text'   => 'Your systems hold your data and run your operations. We assess whether they are secure, reliable and fit for how your business works today.',
		'review' => array( 'User access, passwords and permissions', 'Data backup and recovery', 'Information security and data protection practices', 'How well systems fit your processes and talk to each other', 'Software licences, vendors and support arrangements', 'IT policies and day-to-day practice' ),
		'ideal'  => 'organisations that have grown their systems piece by piece, are planning a new system, or are concerned about data security.',
	),
	array(
		'id'     => 'audit-governance',
		'name'   => 'Governance & Compliance Review',
		'photo'  => 'svc-governance',
		'text'   => 'Good governance gives boards, investors and regulators confidence. We check your structures, policies and practices against regulatory and industry requirements and show you where to strengthen them.',
		'review' => array( 'Board and management structures and reporting lines', 'Policies, delegated authority and decision-making', 'Regulatory and industry compliance obligations', 'Risk management framework and risk register', 'Documentation and record keeping', 'Readiness for external audits, inspections or due diligence' ),
		'ideal'  => 'organisations facing regulatory scrutiny, preparing for investment or a lender’s review, or strengthening oversight as they grow.',
	),
);
$jodyn_tick = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
$jodyn_img  = get_template_directory_uri() . '/assets/img/systems-audit/';
?>
<section class="jd-sa-svc" id="services" aria-labelledby="jd-sa-svc-title">
	<div class="jd-container">
		<div class="jd-sa-svc__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>What we review</p>
				<h2 class="jd-sa-svc__title" id="jd-sa-svc-title" data-jd-lines>Our Systems Audit <em>Services</em></h2>
			</div>
			<p class="jd-sa-svc__intro" data-jd-rise="0.1">Each audit is scoped to your organisation, your risks and the questions you need answered. You can start with one area or review several together.</p>
		</div>

		<nav class="jd-sa-svc__index" aria-label="<?php esc_attr_e( 'Audit services', 'jodyn' ); ?>">
			<?php foreach ( $jodyn_services as $jodyn_i => $jodyn_s ) : ?>
				<a href="#<?php echo esc_attr( $jodyn_s['id'] ); ?>" data-jd-sa-link><span><?php echo esc_html( sprintf( '%02d', $jodyn_i + 1 ) ); ?></span><?php echo esc_html( $jodyn_s['name'] ); ?></a>
			<?php endforeach; ?>
		</nav>

		<div class="jd-sa-svc__blocks">
			<?php foreach ( $jodyn_services as $jodyn_i => $jodyn_s ) : ?>
				<article class="jd-sa-svc__block<?php echo $jodyn_i % 2 ? ' is-flip' : ''; ?>" id="<?php echo esc_attr( $jodyn_s['id'] ); ?>" aria-labelledby="<?php echo esc_attr( $jodyn_s['id'] ); ?>-name" data-jd-sa-block>
					<figure class="jd-sa-svc__photo" data-jd-reveal>
						<img src="<?php echo esc_url( $jodyn_img . $jodyn_s['photo'] . '.webp' ); ?>" alt="" width="1200" height="800" loading="lazy" decoding="async" data-jd-parallax>
					</figure>
					<div class="jd-sa-svc__body">
						<span class="jd-sa-svc__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $jodyn_i + 1 ) ); ?></span>
						<h3 id="<?php echo esc_attr( $jodyn_s['id'] ); ?>-name" data-jd-rise><?php echo esc_html( $jodyn_s['name'] ); ?></h3>
						<p class="jd-sa-svc__text" data-jd-rise="0.1"><?php echo esc_html( $jodyn_s['text'] ); ?></p>
						<div class="jd-sa-svc__review" data-jd-rise="0.15">
							<p class="jd-sa-svc__label">What we review</p>
							<ul>
								<?php foreach ( $jodyn_s['review'] as $jodyn_item ) : ?>
									<li><span class="jd-sa-svc__tick"><?php echo $jodyn_tick; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php echo esc_html( $jodyn_item ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
						<p class="jd-sa-svc__ideal"><b>Ideal for:</b> <?php echo esc_html( $jodyn_s['ideal'] ); ?></p>
						<a class="jd-btn jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=audit' ) ); ?>">
							Enquire About This Service
							<span class="screen-reader-text">: <?php echo esc_html( $jodyn_s['name'] ); ?></span>
							<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
