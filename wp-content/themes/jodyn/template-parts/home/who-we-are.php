<?php
/**
 * Home · 2. Who we are.
 *
 * White section with brand particles: title and belief beside the three-circle
 * diagram, then a bento of photos and colour blocks closed by the purpose tile.
 * Copy: content/pages/home.md, section 2 (approved). Motion: assets/js/who-we-are.js.
 *
 * @package JoDyn
 */

$jodyn_img = get_template_directory_uri() . '/assets/img/home/';
?>
<section class="jd-who" id="who-we-are" aria-labelledby="jd-who-title" data-jd-who
	data-jd-particles="light" data-jd-keep-out=".jd-who__intro-text > *, .jd-venn, .jd-who__bento, .jd-who__purpose">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">

		<div class="jd-who__intro">
			<div class="jd-who__intro-text">
				<p class="jd-eyebrow" data-jd-rise>Who we are</p>
				<h2 class="jd-who__title" id="jd-who-title" data-jd-lines>Your Partner for Strategy, Systems, People and <em>Performance</em></h2>
				<p class="jd-who__lead" data-jd-rise="0.1">At JoDyn Advisory and Consulting, we believe that every organisation's growth begins with three things: the clarity of its <strong>direction</strong>, the strength of its <strong>systems</strong> and the capability of its <strong>people</strong>.</p>
				<p class="jd-who__caption" data-jd-rise="0.2">When all three are strong, organisations adapt faster, perform better and grow with confidence.</p>
			</div>

			<div class="jd-venn">
				<svg class="jd-venn__svg" viewBox="0 0 560 500" role="img" aria-label="<?php esc_attr_e( 'Three overlapping circles, Direction, Systems and People, meeting at Growth', 'jodyn' ); ?>">
					<defs>
						<?php // One set of circles, reused for fills, overlaps and outlines, so moving a circle moves all three. ?>
						<circle id="jd-vc1" cx="280" cy="175" r="152" pathLength="1"/>
						<circle id="jd-vc2" cx="192" cy="327" r="152" pathLength="1"/>
						<circle id="jd-vc3" cx="368" cy="327" r="152" pathLength="1"/>
						<clipPath id="jd-vclip1"><use href="#jd-vc1"/></clipPath>
						<clipPath id="jd-vclip2"><use href="#jd-vc2"/></clipPath>
						<clipPath id="jd-vclip12" clip-path="url(#jd-vclip1)"><use href="#jd-vc2"/></clipPath>
					</defs>
					<g class="jd-venn__rig">
						<use href="#jd-vc1" class="jd-venn__fill"/>
						<use href="#jd-vc2" class="jd-venn__fill"/>
						<use href="#jd-vc3" class="jd-venn__fill"/>
						<use href="#jd-vc2" class="jd-venn__lens" clip-path="url(#jd-vclip1)"/>
						<use href="#jd-vc3" class="jd-venn__lens" clip-path="url(#jd-vclip1)"/>
						<use href="#jd-vc3" class="jd-venn__lens" clip-path="url(#jd-vclip2)"/>
						<use href="#jd-vc3" class="jd-venn__triple" clip-path="url(#jd-vclip12)"/>
						<use href="#jd-vc1" class="jd-venn__stroke"/>
						<use href="#jd-vc2" class="jd-venn__stroke"/>
						<use href="#jd-vc3" class="jd-venn__stroke"/>
						<g class="jd-venn__labels"><text class="jd-venn__sub" x="280" y="108" text-anchor="middle">CLARITY OF</text><text class="jd-venn__label" x="280" y="142" text-anchor="middle">Direction</text></g>
						<g class="jd-venn__labels"><text class="jd-venn__sub" x="130" y="356" text-anchor="middle">STRENGTH OF</text><text class="jd-venn__label" x="130" y="390" text-anchor="middle">Systems</text></g>
						<g class="jd-venn__labels"><text class="jd-venn__sub" x="430" y="356" text-anchor="middle">CAPABILITY OF</text><text class="jd-venn__label" x="430" y="390" text-anchor="middle">People</text></g>
						<g class="jd-venn__centre">
							<circle class="jd-venn__ripple" cx="280" cy="277" r="64"/>
							<circle class="jd-venn__ripple" cx="280" cy="277" r="64"/>
							<g class="jd-venn__core-group"><circle class="jd-venn__core" cx="280" cy="277" r="64"/><text class="jd-venn__core-text" x="280" y="284" text-anchor="middle">Growth</text></g>
						</g>
					</g>
				</svg>
			</div>
		</div>

		<div class="jd-who__bento">
			<div class="jd-tile jd-tile--photo-a" data-jd-reveal>
				<img src="<?php echo esc_url( $jodyn_img . 'who-team.webp' ); ?>" alt="" width="1600" height="1067" loading="lazy" decoding="async" data-jd-parallax="10">
			</div>
			<div class="jd-tile jd-tile--lime" data-jd-rise="0.05">
				<span class="jd-tile__mark" aria-hidden="true"><i></i><i></i><i></i></span>
				<p>We work alongside business owners, executives and teams to solve real challenges. That might mean refining a strategy that is not delivering results, auditing the systems and controls your business runs on, getting your tax position in order, or preparing managers and teams with the skills a changing market demands.</p>
			</div>
			<div class="jd-tile jd-tile--photo-b" data-jd-reveal="0.1">
				<img src="<?php echo esc_url( $jodyn_img . 'who-workshop.webp' ); ?>" alt="" width="1600" height="1067" loading="lazy" decoding="async" data-jd-parallax="10">
			</div>
			<div class="jd-tile jd-tile--dark" data-jd-rise="0.15">
				<span class="jd-tile__mark" aria-hidden="true"><i></i><i></i><i></i></span>
				<p>Our approach is practical and collaborative. We take time to understand your context before recommending a solution, and every engagement is designed around your goals rather than a one-size-fits-all template. The result is advice you can act on and learning your people can apply from day one.</p>
			</div>
		</div>

		<div class="jd-who__purpose">
			<div data-jd-rise>
				<p class="jd-eyebrow">Our purpose</p>
				<p class="jd-who__purpose-text">To help organisations and professionals unlock their full potential through sound advice, strong systems, practical learning and a genuine commitment to <em>lasting results</em>.</p>
			</div>
			<a class="jd-btn jd-btn--lg jd-btn--arrow" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>" data-jd-rise="0.15">
				Learn More About Us
				<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		</div>

	</div>
</section>
