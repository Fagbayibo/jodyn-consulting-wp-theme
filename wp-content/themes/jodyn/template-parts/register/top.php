<?php
/**
 * Registration · Hero and How registration works (copy sections 1 and 2).
 *
 * Soft green, under the solid header. The heading on the left and the intro on the right, then
 * the four steps as one ticket: a lime "Your seat · 4 steps" stub and four parts split by dashed
 * perforations (two by two on tablets, stacked on phones).
 * Copy: content/pages/registration.md, sections 1 and 2 (approved).
 *
 * @package JoDyn
 */

$jodyn_steps = array(
	array( 'Complete the form', 'Choose your programme and enter your details. It takes about two minutes.' ),
	array( 'Receive confirmation', 'You will receive an email with your registration details and payment information.' ),
	array( 'Make payment', 'Your seat is secured once payment is received.' ),
	array( 'Get ready', 'Joining instructions, venue details or online links are sent before the programme starts.' ),
);
?>
<section class="jd-reg-top" aria-labelledby="jd-reg-title">
	<div class="jd-container">
		<div class="jd-reg-top__copy">
			<h1 class="jd-reg-top__title" id="jd-reg-title" data-jd-lines>Register for a Training <em>Programme</em></h1>
			<p class="jd-reg-top__text" data-jd-rise="0.15">Secure your seat in a few simple steps. Complete the form below, and we will send your confirmation, payment details and programme information by email.</p>
		</div>

		<div class="jd-reg-top__pass" data-jd-reg-pass>
			<p class="jd-reg-top__stub" aria-hidden="true"><span>Your seat</span><b><?php echo count( $jodyn_steps ); ?></b><span>steps</span></p>
			<h2 class="screen-reader-text">How registration works</h2>
			<ol class="jd-reg-top__steps">
				<?php foreach ( $jodyn_steps as $jodyn_i => $jodyn_step ) : ?>
					<li>
						<span class="jd-reg-top__n">Step <?php echo (int) $jodyn_i + 1; ?></span>
						<h3><?php echo esc_html( $jodyn_step[0] ); ?></h3>
						<p><?php echo esc_html( $jodyn_step[1] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
