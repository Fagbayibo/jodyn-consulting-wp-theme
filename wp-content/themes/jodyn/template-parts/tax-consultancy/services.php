<?php
/**
 * Tax Consultancy · 3. Our tax services (#services, the hero's "See How We Help").
 *
 * Forest. Heading beside the intro, then the four services as tall photo panels side by side.
 * One is open at a time (the first to start): it widens to show the number, name, text, What we
 * help with, Ideal for and Enquire About This Service, while the others shrink to their number
 * and a sideways title. Hover, click, tap or Enter opens a panel (assets/js/tax-consultancy.js).
 * On tablets and phones the panels stack and open one at a time.
 * Copy: content/pages/tax-consultancy.md, section 3 (approved).
 *
 * @package JoDyn
 */

$jodyn_services = array(
	array(
		'id'    => 'tax-compliance',
		'name'  => 'Tax Compliance & Filing',
		'photo' => 'svc-compliance',
		'text'  => 'Accurate, on-time returns and filings, so you meet every obligation without the last-minute rush.',
		'help'  => array( 'Company income tax computations and returns', 'VAT returns and records', 'PAYE for employees, including annual returns', 'Withholding tax deductions, remittances and credit notes', 'A filing calendar so no deadline is missed', 'Records that stand up to review' ),
		'ideal' => 'organisations without an in-house tax specialist, and finance teams that want a reliable second pair of eyes.',
	),
	array(
		'id'    => 'tax-planning',
		'name'  => 'Tax Planning & Advisory',
		'photo' => 'svc-planning',
		'text'  => 'Good tax planning happens before decisions are made, not after. We help you structure decisions and transactions to manage your tax position efficiently and within the law.',
		'help'  => array( 'Tax implications of business decisions and contracts', 'Structuring for growth, investment or restructuring', 'Staff pay and benefits structures', 'Making use of reliefs and incentives you qualify for', 'Understanding how changes in tax law affect you', 'Ongoing advice as questions arise' ),
		'ideal' => 'growing businesses, organisations planning a major change, and leaders who want tax considered from the start.',
	),
	array(
		'id'    => 'tax-health',
		'name'  => 'Tax Health Checks',
		'photo' => 'svc-health',
		'text'  => 'A clear review of your current tax position, exposures and opportunities, so you know where you stand before anyone else asks.',
		'help'  => array( 'Review of past returns and payments', 'Exposure to penalties and interest', 'Gaps in records and documentation', 'Overpayments and credits you may be able to recover', 'Readiness for a tax audit', 'A prioritised list of actions' ),
		'ideal' => 'organisations that have never had an independent tax review, are changing advisers, or are preparing for investment or a sale.',
	),
	array(
		'id'    => 'tax-authorities',
		'name'  => 'Support with Tax Authorities',
		'photo' => 'svc-authorities',
		'text'  => 'When the tax authority asks questions, how you respond matters. We guide and represent you through reviews, audits and enquiries, so they are resolved fairly and quickly.',
		'help'  => array( 'Responding to letters, queries and information requests', 'Preparing for and supporting you during tax audits', 'Reviewing assessments for accuracy', 'Objections and resolving disputes', 'Agreeing payment arrangements where needed', 'Putting fixes in place so issues do not recur' ),
		'ideal' => 'organisations that have received an assessment, audit notice or enquiry, or want support before one arrives.',
	),
);
$jodyn_tick = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
$jodyn_img  = get_template_directory_uri() . '/assets/img/tax-consultancy/';
?>
<section class="jd-tx-svc" id="services" aria-labelledby="jd-tx-svc-title">
	<div class="jd-container">
		<div class="jd-tx-svc__head">
			<div>
				<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>How we help</p>
				<h2 class="jd-tx-svc__title" id="jd-tx-svc-title" data-jd-lines>Our Tax Consultancy <em>Services</em></h2>
			</div>
			<p class="jd-tx-svc__intro" data-jd-rise="0.1">Every organisation’s tax position is different. We start by understanding yours, then support you where you need it, from routine filing to complex decisions.</p>
		</div>

		<div class="jd-tx-svc__row">
			<?php foreach ( $jodyn_services as $jodyn_i => $jodyn_s ) : ?>
				<?php $jodyn_n = sprintf( '%02d', $jodyn_i + 1 ); ?>
				<article class="jd-tx-svc__panel<?php echo 0 === $jodyn_i ? ' is-open' : ''; ?>" id="<?php echo esc_attr( $jodyn_s['id'] ); ?>" aria-labelledby="<?php echo esc_attr( $jodyn_s['id'] ); ?>-name" tabindex="0" data-jd-tx-panel>
					<img class="jd-tx-svc__bg" src="<?php echo esc_url( $jodyn_img . $jodyn_s['photo'] . '.webp' ); ?>" alt="" width="933" height="1400" loading="lazy" decoding="async">
					<div class="jd-tx-svc__shut" aria-hidden="true">
						<span class="jd-tx-svc__n"><?php echo esc_html( $jodyn_n ); ?></span>
						<p class="jd-tx-svc__vert"><?php echo esc_html( $jodyn_s['name'] ); ?></p>
					</div>
					<div class="jd-tx-svc__open">
						<span class="jd-tx-svc__n" aria-hidden="true"><?php echo esc_html( $jodyn_n ); ?></span>
						<h3 id="<?php echo esc_attr( $jodyn_s['id'] ); ?>-name"><?php echo esc_html( $jodyn_s['name'] ); ?></h3>
						<p class="jd-tx-svc__text"><?php echo esc_html( $jodyn_s['text'] ); ?></p>
						<p class="screen-reader-text">What we help with:</p>
						<ul class="jd-tx-svc__list">
							<?php foreach ( $jodyn_s['help'] as $jodyn_item ) : ?>
								<li><span class="jd-tx-svc__tick"><?php echo $jodyn_tick; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php echo esc_html( $jodyn_item ); ?></li>
							<?php endforeach; ?>
						</ul>
						<p class="jd-tx-svc__ideal"><b>Ideal for:</b> <?php echo esc_html( $jodyn_s['ideal'] ); ?></p>
						<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=tax' ) ); ?>">
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
