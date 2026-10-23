<?php
/**
 * Advisory · 5. Ways to work with us.
 *
 * Forest. The four engagement models as cards, each with a small drawn visual (pure CSS,
 * looping gently; still under reduced motion): a project bar running start to finish,
 * calendar dots that keep ticking (retained), people around a table (workshops) and two
 * linked rings (advisory + training). The button sits centred below.
 * Copy: content/pages/advisory.md, section 5 (approved).
 *
 * @package JoDyn
 */

$jodyn_ways = array(
	array( 'project', 'Project-Based Engagements', 'A defined scope, timeline and set of deliverables, such as a strategy refresh, an organisational restructure or an HR policy review.' ),
	array( 'retained', 'Retained Advisory', 'Ongoing access to our advisors over an agreed period for continuous guidance, reviews and support as decisions arise.' ),
	array( 'workshop', 'Facilitated Workshops and Retreats', 'Focused sessions for leadership teams, such as strategy retreats, planning sessions and problem-solving workshops, held in person or online.' ),
	array( 'combined', 'Advisory + Training Programmes', 'Combined engagements where we design the solution and then train your people to deliver it, for change that sticks.' ),
);
// The drawings are decorative: a few empty elements styled in advisory.css.
$jodyn_visuals = array(
	'project'  => '<i class="jd-adv-ways__bar"></i><i class="jd-adv-ways__start"></i><i class="jd-adv-ways__end"></i>',
	'retained' => str_repeat( '<i></i>', 12 ),
	'workshop' => str_repeat( '<i></i>', 6 ) . '<b></b>',
	'combined' => '<i></i><i></i>',
);
?>
<section class="jd-adv-ways" aria-labelledby="jd-adv-ways-title" data-jd-particles="forest" data-jd-keep-out=".jd-adv-ways__head > *, .jd-adv-ways__grid, .jd-adv-ways__foot">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-adv-ways__head">
			<div>
				<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Flexible engagement</p>
				<h2 class="jd-adv-ways__title" id="jd-adv-ways-title" data-jd-lines>Ways to Work <em>With Us</em></h2>
			</div>
			<p class="jd-adv-ways__intro" data-jd-rise="0.1">We offer flexible engagement models to suit your needs, timelines and budget.</p>
		</div>

		<ul class="jd-adv-ways__grid" data-jd-stagger>
			<?php foreach ( $jodyn_ways as $jodyn_way ) : ?>
				<li class="jd-adv-ways__card">
					<span class="jd-adv-ways__vis jd-adv-ways__vis--<?php echo esc_attr( $jodyn_way[0] ); ?>" aria-hidden="true"><?php echo $jodyn_visuals[ $jodyn_way[0] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup above. ?></span>
					<h3><?php echo esc_html( $jodyn_way[1] ); ?></h3>
					<p><?php echo esc_html( $jodyn_way[2] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="jd-adv-ways__foot" data-jd-rise>
			<a class="jd-btn jd-btn--lg jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=advisory' ) ); ?>">
				Find the Right Engagement
				<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		</div>
	</div>
</section>
