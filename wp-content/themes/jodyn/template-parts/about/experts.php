<?php
/**
 * About · 7. Facilitators and advisors.
 *
 * White. Copy on the left; on the right the six specialist areas circle slowly around a round
 * photo of a facilitator at work (pure CSS; it stops under reduced motion). Below, a forest
 * "Join our network" bar. The photo is an action shot, not a headshot, so it is never read
 * as a named JoDyn team member (Leadership is deferred until the client sends real photos).
 * Copy: content/pages/about.md, section 7 (approved).
 *
 * @package JoDyn
 */

// The specialist areas named in the second paragraph, shown as the orbit's chips.
$jodyn_areas = array( 'Finance', 'Tax', 'Audit', 'Human resources', 'Operations', 'Technology' );
?>
<section class="jd-experts" aria-labelledby="jd-experts-title" data-jd-particles="light" data-jd-keep-out=".jd-experts__copy > *, .jd-experts__orbit, .jd-experts__join">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-experts__grid">
			<div class="jd-experts__copy">
				<p class="jd-eyebrow" data-jd-rise>Our experts</p>
				<h2 class="jd-experts__title" id="jd-experts-title" data-jd-lines>Experienced Facilitators and <em>Advisors</em></h2>
				<p class="jd-experts__lead" data-jd-rise="0.1">Every engagement, from advisory and audit to tax and training, is led by professionals selected for their expertise, practical experience and ability to connect with the people they work with. They bring real-world insight from the workplace into every session and every review, making our work relevant, engaging and immediately useful.</p>
				<p class="jd-experts__text" data-jd-rise="0.15">For specialised engagements, we draw on subject-matter experts in areas such as finance, tax, audit, human resources, operations and technology, ensuring every client receives the right expertise for their needs.</p>
			</div>

			<div class="jd-experts__orbit" data-jd-reveal aria-hidden="true">
				<div class="jd-experts__ring"></div>
				<figure class="jd-experts__face">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/about/experts-facilitator.webp' ); ?>" alt="" width="900" height="900" loading="lazy" decoding="async">
				</figure>
				<ul class="jd-experts__areas">
					<?php foreach ( $jodyn_areas as $i => $jodyn_area ) : ?>
						<li style="--a: <?php echo esc_attr( $i * 60 ); ?>deg"><span><?php echo esc_html( $jodyn_area ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<div class="jd-experts__join" data-jd-rise>
			<h3>Join our network</h3>
			<p>Are you an experienced facilitator or consultant? We are always interested in connecting with professionals who share our commitment to excellence.</p>
			<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( home_url( '/contact-us/?enquiry=facilitator' ) ); ?>">
				Join Our Facilitator Network
				<span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
		</div>
	</div>
</section>
