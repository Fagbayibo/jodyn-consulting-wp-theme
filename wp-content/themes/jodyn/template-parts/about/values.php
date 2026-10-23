<?php
/**
 * About · 4. Values.
 *
 * Forest. A bento grid: a team photo plus six value tiles (lime, white, soft green) that wipe
 * open one after another (data-jd-reveal delays). Integrity and Impact are the wide tiles.
 * Copy: content/pages/about.md, section 4 (approved).
 *
 * @package JoDyn
 */

$jodyn_values = array(
	array( 'Integrity', 'shield', 'lime', 'We give honest advice, even when it is not what clients expect to hear, and we keep our commitments.' ),
	array( 'Excellence', 'award', 'white', 'We hold ourselves to high standards in every recommendation we make and every session we facilitate.' ),
	array( 'Partnership', 'users', 'tint', 'We work alongside our clients, not just for them, and we treat their success as our own.' ),
	array( 'Practicality', 'tool', 'tint', 'We focus on solutions that work in the real world and that people can apply immediately.' ),
	array( 'Growth', 'trend', 'white', 'We believe in the potential of every person and organisation, and we are committed to continuous learning ourselves.' ),
	array( 'Impact', 'target', 'lime', 'We measure our work by the difference it makes to our clients\' people, performance and results.' ),
);
?>
<section class="jd-values" aria-labelledby="jd-values-title" data-jd-particles="forest" data-jd-keep-out=".jd-values__head > *, .jd-values__bento">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>

	<div class="jd-container">
		<div class="jd-values__head">
			<div>
				<p class="jd-eyebrow jd-eyebrow--inverse" data-jd-rise>Our values</p>
				<h2 class="jd-values__title" id="jd-values-title" data-jd-lines>The Values That Guide <em>Our Work</em></h2>
			</div>
			<p class="jd-values__intro" data-jd-rise="0.1">Our values shape how we work with clients, how we design our solutions and how we measure our success.</p>
		</div>

		<div class="jd-values__bento">
			<figure class="jd-values__photo" data-jd-reveal>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/about/values-team.webp' ); ?>" alt="" width="1400" height="933" loading="lazy" decoding="async" data-jd-parallax>
			</figure>
			<?php foreach ( $jodyn_values as $i => $jodyn_value ) : ?>
				<article class="jd-values__tile jd-values__tile--<?php echo esc_attr( $jodyn_value[2] ); ?> jd-values__t<?php echo esc_attr( $i + 1 ); ?>" data-jd-reveal="<?php echo esc_attr( number_format( 0.08 * ( $i + 1 ), 2 ) ); ?>">
					<span class="jd-values__ic"><?php echo jodyn_icon( $jodyn_value[1] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<h3><?php echo esc_html( $jodyn_value[0] ); ?></h3>
					<p><?php echo esc_html( $jodyn_value[3] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
