<?php
/**
 * Advisory · 2. Challenges we solve.
 *
 * Soft green. The six challenges as speech bubbles (white, forest, lime), as if voiced by a
 * client, with each challenge's name as a small tag. The middle column sits lower, so the
 * bubbles read as a conversation. The closing "they are solvable" line sits centred below.
 * Copy: content/pages/advisory.md, section 2 (approved).
 *
 * @package JoDyn
 */

$jodyn_challenges = array(
	array( 'Strategy without execution', 'Plans are written and approved, but progress stalls and priorities keep shifting.' ),
	array( 'Growth that outpaces structure', 'The business has grown, but roles, processes and systems have not kept up.' ),
	array( 'Underperforming teams', 'Targets are missed, accountability is unclear and productivity is lower than it should be.' ),
	array( 'A leadership gap', 'Managers are capable in their roles but not yet equipped to lead people and drive results.' ),
	array( 'Resistance to change', 'New initiatives are introduced, but the culture and behaviours stay the same.' ),
	array( 'Low engagement and high turnover', 'Good people are leaving, and those who stay are not fully committed.' ),
);
$jodyn_tones = array( 'white', 'forest', 'lime' );
?>
<section class="jd-adv-ch" aria-labelledby="jd-adv-ch-title" data-jd-particles="light" data-jd-keep-out=".jd-adv-ch__head > *, .jd-adv-ch__wall, .jd-adv-ch__close">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-adv-ch__head">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Sound familiar?</p>
				<h2 class="jd-adv-ch__title" id="jd-adv-ch-title" data-jd-lines>Challenges We Help Organisations <em>Overcome</em></h2>
			</div>
			<p class="jd-adv-ch__intro" data-jd-rise="0.1">Every organisation reaches a point where what got it here will not get it to the next level. If any of these sound familiar, our advisory team can help.</p>
		</div>

		<ul class="jd-adv-ch__wall">
			<?php foreach ( $jodyn_challenges as $i => $jodyn_challenge ) : ?>
				<li class="jd-adv-ch__bubble jd-adv-ch__bubble--<?php echo esc_attr( $jodyn_tones[ $i % 3 ] ); ?>" data-jd-rise="<?php echo esc_attr( number_format( 0.06 * $i, 2 ) ); ?>">
					<h3 class="jd-adv-ch__tag"><?php echo esc_html( $jodyn_challenge[0] ); ?></h3>
					<p>&ldquo;<?php echo esc_html( $jodyn_challenge[1] ); ?>&rdquo;</p>
				</li>
			<?php endforeach; ?>
		</ul>

		<p class="jd-adv-ch__close" data-jd-rise>These challenges are common, and they are solvable. The right diagnosis, a clear plan and the support to see it through make all the difference.</p>
	</div>
</section>
