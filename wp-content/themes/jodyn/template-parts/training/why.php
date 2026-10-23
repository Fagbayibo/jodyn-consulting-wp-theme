<?php
/**
 * Training · 2. Why train with JoDyn.
 *
 * White. Heading beside the three paragraphs; below, the four pillars as photo cards, the
 * even ones set lower so the row steps up and down.
 * Copy: content/pages/training.md, section 2 (approved).
 *
 * @package JoDyn
 */

$jodyn_img = get_template_directory_uri() . '/assets/img/training/';
// Name, description, photo, object-position.
$jodyn_pillars = array(
	array( 'Practical', 'Real workplace case studies, exercises and tools participants can apply immediately.', 'why-practical.webp', '62% 50%' ),
	array( 'Interactive', 'Group discussions, role plays and activities that keep participants engaged throughout.', 'why-interactive.webp', '50% 50%' ),
	array( 'Relevant', 'Content adapted to the Nigerian business environment and your industry context.', 'why-relevant.webp', '40% 50%' ),
	array( 'Flexible', "In-person, online and hybrid delivery to fit your team's schedule and location.", 'why-flexible.webp', '55% 50%' ),
);
?>
<section class="jd-tr-why" aria-labelledby="jd-tr-why-title" data-jd-particles="light" data-jd-keep-out=".jd-tr-why__top > *, .jd-tr-why__grid">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-tr-why__top">
			<div>
				<p class="jd-eyebrow" data-jd-rise>Learning that works</p>
				<h2 class="jd-tr-why__title" id="jd-tr-why-title" data-jd-lines>Training Designed for Real <em>Results</em></h2>
			</div>
			<div class="jd-tr-why__body">
				<p data-jd-rise>Training is one of the most valuable investments an organisation can make, but only when the learning makes its way back to the workplace. Too often, participants enjoy a session, return to their desks and carry on exactly as before.</p>
				<p data-jd-rise="0.1">At JoDyn, we design every programme around one outcome: changed behaviour and better performance at work. Our sessions are practical and interactive, built on real workplace scenarios and finished with clear action plans, so participants leave knowing exactly what to do differently and how to do it.</p>
				<p data-jd-rise="0.2">Whether you are developing a new generation of managers, strengthening an HR team or equipping staff with the skills to serve customers better, our programmes are designed to deliver learning that lasts.</p>
			</div>
		</div>

		<ul class="jd-tr-why__grid" data-jd-stagger>
			<?php foreach ( $jodyn_pillars as $jodyn_i => $jodyn_pillar ) : ?>
				<li class="jd-tr-why__card">
					<figure>
						<img src="<?php echo esc_url( $jodyn_img . $jodyn_pillar[2] ); ?>" alt="" width="1500" height="1000" loading="lazy" decoding="async" style="object-position: <?php echo esc_attr( $jodyn_pillar[3] ); ?>">
					</figure>
					<span class="jd-tr-why__n"><?php echo esc_html( sprintf( '%02d', $jodyn_i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $jodyn_pillar[0] ); ?></h3>
					<p><?php echo esc_html( $jodyn_pillar[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
