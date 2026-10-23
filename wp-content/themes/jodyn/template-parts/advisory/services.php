<?php
/**
 * Advisory · 3. Our advisory services.
 *
 * Forest. Five wide service panels (photo + full content) in a row. On desktop with motion,
 * assets/js/advisory.js pins the section and scrolling down slides the row sideways, with a
 * lime progress line; links to a panel (the hero's index) scroll to the matching point.
 * At rest (no JS, reduced motion, tablets and phones) the row is a native swipe row with
 * snap points, and the panel anchors work on their own.
 * Copy: content/pages/advisory.md, section 3 (approved).
 *
 * @package JoDyn
 */

$jodyn_services = array(
	array(
		'id'    => 'strategy',
		'name'  => 'Strategy & Business Advisory',
		'pos'   => '55% 40%',
		'text'  => 'A strong strategy gives your organisation direction, focus and a clear basis for decisions. We work with leadership teams to define where the organisation is going, how it will get there and what success will look like, then turn that strategy into a plan people can act on.',
		'how'   => array( 'Strategic planning and facilitated strategy retreats', 'Vision, mission and values development', 'Business model review and growth planning', 'Market and competitive analysis to inform decisions', 'Turning strategy into annual plans, targets and KPIs', 'Strategy execution reviews and course correction' ),
		'ideal' => 'organisations entering a new growth phase, leadership teams needing alignment, and businesses rethinking how they compete.',
	),
	array(
		'id'    => 'transformation',
		'name'  => 'Organisational Transformation',
		'pos'   => '35% 35%',
		'text'  => 'Lasting change requires more than new policies. We help organisations redesign how they are structured, how work flows and how decisions are made, so they become more efficient, responsive and ready for the future.',
		'how'   => array( 'Organisational structure review and redesign', 'Process mapping, analysis and improvement', 'Operating model design', 'Change management planning and support', 'Governance frameworks and decision-making structures', 'Digital adoption and new ways of working' ),
		'ideal' => 'organisations going through growth, restructuring, mergers, new leadership or a shift in strategy.',
	),
	array(
		'id'    => 'people',
		'name'  => 'People & Culture Advisory',
		'pos'   => '60% 30%',
		'text'  => 'Your people deliver your strategy. We help you build the structures, systems and culture that attract, develop and retain the talent you need and that bring out the best in them.',
		'how'   => array( 'HR policies, procedures and employee handbooks', 'Job descriptions, role clarity and competency frameworks', 'Performance management systems', 'Employee engagement surveys and action planning', 'Culture assessment and culture change programmes', 'Succession planning and talent development frameworks' ),
		'ideal' => 'growing organisations formalising their HR function, and established organisations strengthening engagement and culture.',
	),
	array(
		'id'    => 'performance',
		'name'  => 'Performance Improvement',
		'pos'   => '60% 40%',
		'text'  => 'When results fall short, the cause is often hidden in processes, structures or capability gaps. We diagnose what is holding performance back and work with you to put practical solutions in place.',
		'how'   => array( 'Organisational and team performance diagnostics', 'KPI design and performance dashboards', 'Productivity and efficiency reviews', 'Service delivery and customer experience improvement', 'Accountability frameworks and performance reviews', 'Implementation support and progress tracking' ),
		'ideal' => 'organisations facing stagnant results, rising costs, missed targets or declining service quality.',
	),
	array(
		'id'    => 'learning',
		'name'  => 'Learning & Development Advisory',
		'pos'   => '30% 35%',
		'text'  => 'Training delivers the best results when it is linked to strategy. We help organisations identify their real capability gaps and build learning strategies that develop the skills the business needs.',
		'how'   => array( 'Training needs analysis (TNA)', 'Competency gap assessments', 'Learning and development strategy', 'Annual training plans and budgets', 'Leadership development frameworks', 'Training evaluation and return-on-investment measurement' ),
		'ideal' => 'HR and L&D teams planning their training investment, and organisations building a culture of continuous learning.',
	),
);
$jodyn_img = get_template_directory_uri() . '/assets/img/advisory/';
?>
<section class="jd-adv-sv" id="services" aria-labelledby="jd-adv-sv-title" data-jd-particles="forest" data-jd-keep-out=".jd-adv-sv__head > *, .jd-adv-sv__viewport, .jd-adv-sv__progress">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-adv-sv__pin" data-jd-adv-pin>
		<div class="jd-container">
			<div class="jd-adv-sv__head">
				<div>
					<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>What we offer</p>
					<h2 class="jd-adv-sv__title" id="jd-adv-sv-title" data-jd-lines>Our Advisory <em>Services</em></h2>
				</div>
				<p class="jd-adv-sv__intro" data-jd-rise="0.1">Our advisory practice covers the areas that matter most to organisational performance. Each engagement is tailored to your context, goals and resources.</p>
			</div>
		</div>

		<div class="jd-adv-sv__viewport" data-jd-adv-viewport>
			<ol class="jd-adv-sv__track" data-jd-adv-track>
				<?php foreach ( $jodyn_services as $i => $jodyn_service ) : ?>
					<li class="jd-adv-sv__panel" id="advisory-<?php echo esc_attr( $jodyn_service['id'] ); ?>">
						<figure class="jd-adv-sv__photo">
							<img src="<?php echo esc_url( $jodyn_img . 'svc-' . $jodyn_service['id'] . '.webp' ); ?>" alt="" width="1000" height="667" loading="lazy" decoding="async" style="object-position: <?php echo esc_attr( $jodyn_service['pos'] ); ?>">
							<span class="jd-adv-sv__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						</figure>
						<div class="jd-adv-sv__copy">
							<h3><?php echo esc_html( $jodyn_service['name'] ); ?></h3>
							<p><?php echo esc_html( $jodyn_service['text'] ); ?></p>
							<div class="jd-adv-sv__how">
								<p class="jd-adv-sv__how-h">How we help</p>
								<ul>
									<?php foreach ( $jodyn_service['how'] as $jodyn_point ) : ?>
										<li><span aria-hidden="true"><?php echo jodyn_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php echo esc_html( $jodyn_point ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
							<p class="jd-adv-sv__ideal"><strong>Ideal for:</strong> <?php echo esc_html( $jodyn_service['ideal'] ); ?></p>
							<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=advisory&area=' . $jodyn_service['id'] ) ); ?>">
								Enquire About This Service
								<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</a>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>

		<div class="jd-container">
			<div class="jd-adv-sv__progress" aria-hidden="true"><span data-jd-adv-progress></span></div>
		</div>
	</div>
</section>
