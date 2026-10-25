<?php
/**
 * Tax Consultancy · 5. Why JoDyn for tax.
 *
 * Forest. The heading stays on the left while the five points pass on the right as large
 * numbered lines, each with its sentence beside it; the one under the pointer slides in and
 * turns lime. One column on tablets and phones.
 * Copy: content/pages/tax-consultancy.md, section 5 (approved).
 *
 * @package JoDyn
 */

$jodyn_points = array(
	array( 'Within the law', 'Every recommendation is compliant. We help you pay what you owe, not more and not less.' ),
	array( 'Plain language', 'We explain what applies to you and why, without jargon.' ),
	array( 'On time, every time', 'Deadlines are tracked so filings are never a last-minute rush.' ),
	array( 'Clear fees', 'You know the scope and the cost before any work begins.' ),
	array( 'Confidential', 'Your financial information is handled with strict confidentiality.' ),
);
?>
<section class="jd-tx-why" aria-labelledby="jd-tx-why-title" data-jd-particles="forest" data-jd-keep-out=".jd-tx-why__head > *, .jd-tx-why__list">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container jd-tx-why__grid">
		<div class="jd-tx-why__head">
			<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Why JoDyn</p>
			<h2 class="jd-tx-why__title" id="jd-tx-why-title" data-jd-lines>Tax Advice You Can <em>Rely On</em></h2>
		</div>

		<ol class="jd-tx-why__list">
			<?php foreach ( $jodyn_points as $jodyn_i => $jodyn_point ) : ?>
				<li class="jd-tx-why__row" data-jd-rise="<?php echo esc_attr( $jodyn_i * 0.06 ); ?>">
					<span class="jd-tx-why__n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $jodyn_i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $jodyn_point[0] ); ?></h3>
					<p><?php echo esc_html( $jodyn_point[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
