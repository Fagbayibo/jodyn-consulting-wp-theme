<?php
/**
 * Programme page (option B).
 *
 * The programme photo fills the hero under a forest shade (forest alone when there is no photo),
 * with the area, title, summary and buttons; the date, duration, format and fee sit in a white bar
 * across its bottom edge. Then the overview, who should attend, outcomes and modules, with the
 * Programme Details box staying in view on the right (below them on tablets and phones), the
 * facilitator, How You Will Learn, Bring This Programme to Your Team, FAQs and Related
 * Programmes. Sections whose fields are empty are left out.
 * Accordions: assets/js/programme.js. Copy: content/pages/programme.md (approved).
 *
 * @package JoDyn
 *
 * @param array $args { p: jodyn_programme_page() }
 */

$jodyn_p = $args['p'];
$jodyn_i = array(
	'cal'   => jodyn_icon( 'calendar' ),
	'clock' => jodyn_icon( 'clock' ),
	'pin'   => jodyn_icon( 'pin' ),
	'fee'   => jodyn_icon( 'tag' ),
	'check' => jodyn_icon( 'check' ),
	'user'  => jodyn_icon( 'user' ),
	'dl'    => jodyn_icon( 'download' ),
);
$jodyn_seat_cls = 'full' === $jodyn_p['seats'] ? ' is-full' : ( 'few' === $jodyn_p['seats'] ? ' is-few' : '' );

// The facts strip: date (or "New dates coming soon"), duration, format and venue, fee.
$jodyn_facts = array( array( 'cal', $jodyn_p['upcoming'] ? $jodyn_p['range'] : __( 'New dates coming soon', 'jodyn' ) ) );
if ( $jodyn_p['duration'] ) {
	$jodyn_facts[] = array( 'clock', $jodyn_p['duration'] );
}
if ( $jodyn_p['show_format'] ) {
	$jodyn_facts[] = array( 'pin', $jodyn_p['upcoming'] ? $jodyn_p['format_name'] . ( $jodyn_p['venue'] ? ', ' . $jodyn_p['venue'] : '' ) : $jodyn_p['format_name'] );
}
if ( $jodyn_p['fee'] && $jodyn_p['upcoming'] ) {
	/* translators: %s: fee, e.g. ₦250,000. */
	$jodyn_facts[] = array( 'fee', sprintf( __( '%s per participant', 'jodyn' ), $jodyn_p['fee'] ) );
}

// The Programme Details rows.
$jodyn_rows = array( array( __( 'Dates', 'jodyn' ), esc_html( $jodyn_p['upcoming'] ? $jodyn_p['range'] : __( 'New dates coming soon', 'jodyn' ) ) ) );
if ( $jodyn_p['duration'] ) {
	$jodyn_rows[] = array( __( 'Duration', 'jodyn' ), esc_html( $jodyn_p['duration'] ) );
}
if ( $jodyn_p['time'] && $jodyn_p['upcoming'] ) {
	$jodyn_rows[] = array( __( 'Time', 'jodyn' ), esc_html( $jodyn_p['time'] ) );
}
if ( $jodyn_p['show_format'] ) {
	$jodyn_rows[] = array( __( 'Format', 'jodyn' ), esc_html( $jodyn_p['format_name'] ) );
}
if ( $jodyn_p['venue'] && $jodyn_p['upcoming'] ) {
	$jodyn_rows[] = array( __( 'Venue', 'jodyn' ), esc_html( $jodyn_p['venue'] ) );
}
if ( $jodyn_p['fee'] && $jodyn_p['upcoming'] ) {
	/* translators: %s: fee, e.g. ₦250,000. */
	$jodyn_rows[] = array( __( 'Fee', 'jodyn' ), esc_html( sprintf( __( '%s per participant', 'jodyn' ), $jodyn_p['fee'] ) ) . '<small>' . esc_html( $jodyn_p['discount'] ) . '</small>' );
}
if ( $jodyn_p['includes'] ) {
	$jodyn_rows[] = array( __( 'Includes', 'jodyn' ), '<ul><li>' . implode( '</li><li>', array_map( 'esc_html', $jodyn_p['includes'] ) ) . '</li></ul>' );
}

// With nothing written yet, the content column is empty: the details box then stands on its own,
// and a programme without a date leaves the body out (the hero already says everything).
$jodyn_has_main = $jodyn_p['overview'] || $jodyn_p['who'] || $jodyn_p['outcomes'] || $jodyn_p['modules'];
$jodyn_show_box = $jodyn_has_main || $jodyn_p['upcoming'];

$jodyn_action = function ( $class = '' ) use ( $jodyn_p ) {
	return '<a class="jd-btn jd-btn--accent jd-btn--arrow ' . esc_attr( $class ) . '" href="' . esc_url( $jodyn_p['action'][1] ) . '">' . esc_html( $jodyn_p['action'][0] ) . '<span class="jd-btn__disc">' . jodyn_arrow_swap() . '</span></a>';
};
?>
<header class="jd-pg-hero<?php echo $jodyn_p['image'] ? '' : ' is-plain'; ?>">
	<?php
	if ( $jodyn_p['image'] ) {
		echo wp_get_attachment_image(
			$jodyn_p['image'],
			'full',
			false,
			array(
				'class'           => 'jd-pg-hero__img',
				'alt'             => '',
				'sizes'           => '100vw',
				'fetchpriority'   => 'high',
				'loading'         => 'eager',
				'data-jd-parallax' => '',
			)
		);
	}
	?>
	<div class="jd-container jd-pg-hero__in">
		<p class="jd-pg-label" data-jd-rise><?php echo esc_html( $jodyn_p['area_label'] ); ?></p>
		<h1 class="jd-pg-title" data-jd-lines><?php echo esc_html( $jodyn_p['title'] ); ?></h1>
		<?php if ( $jodyn_p['summary'] ) : ?>
			<p class="jd-pg-sum" data-jd-rise="0.1"><?php echo esc_html( $jodyn_p['summary'] ); ?></p>
		<?php endif; ?>
		<div class="jd-pg-btns" data-jd-rise="0.2">
			<?php if ( 'full' === $jodyn_p['seats'] && $jodyn_p['upcoming'] ) : ?>
				<span class="jd-pg-seat is-full"><?php esc_html_e( 'Fully booked', 'jodyn' ); ?></span>
			<?php endif; ?>
			<?php echo $jodyn_action(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the closure. ?>
			<?php if ( $jodyn_p['brochure'] ) : ?>
				<a class="jd-pg-dl" href="<?php echo esc_url( $jodyn_p['brochure'] ); ?>" download><?php echo $jodyn_i['dl']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Download Brochure', 'jodyn' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</header>

<div class="jd-container jd-pg-barwrap">
	<ul class="jd-pg-bar" data-jd-rise aria-label="<?php esc_attr_e( 'At a glance', 'jodyn' ); ?>">
		<?php foreach ( $jodyn_facts as $jodyn_f ) : ?>
			<li><span class="jd-pg-bar__ic"><?php echo $jodyn_i[ $jodyn_f[0] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php echo esc_html( $jodyn_f[1] ); ?></li>
		<?php endforeach; ?>
	</ul>
</div>

<?php if ( $jodyn_show_box ) : ?>
<div class="jd-pg-body">
	<div class="jd-container jd-pg-grid<?php echo $jodyn_has_main ? '' : ' is-solo'; ?>">
		<?php if ( $jodyn_has_main ) : ?>
		<div class="jd-pg-main">
			<?php if ( $jodyn_p['overview'] ) : ?>
				<section class="jd-pg-sec jd-pg-overview" aria-labelledby="jd-pg-overview">
					<h2 id="jd-pg-overview"><?php esc_html_e( 'Programme Overview', 'jodyn' ); ?></h2>
					<?php echo wp_kses_post( $jodyn_p['overview'] ); ?>
				</section>
			<?php endif; ?>

			<?php if ( $jodyn_p['who'] ) : ?>
				<section class="jd-pg-sec" aria-labelledby="jd-pg-who">
					<h2 id="jd-pg-who"><?php esc_html_e( 'Who Should Attend', 'jodyn' ); ?></h2>
					<ul class="jd-pg-who" data-jd-stagger>
						<?php foreach ( $jodyn_p['who'] as $jodyn_line ) : ?>
							<li><span><?php echo $jodyn_i['user']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php echo esc_html( $jodyn_line ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if ( $jodyn_p['outcomes'] ) : ?>
				<section class="jd-pg-sec" aria-labelledby="jd-pg-outcomes">
					<h2 id="jd-pg-outcomes"><?php esc_html_e( 'Learning Outcomes', 'jodyn' ); ?></h2>
					<p class="jd-pg-lead"><?php esc_html_e( 'By the end of this programme, you will be able to:', 'jodyn' ); ?></p>
					<ul class="jd-pg-out">
						<?php foreach ( $jodyn_p['outcomes'] as $jodyn_line ) : ?>
							<li><span><?php echo $jodyn_i['check']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php echo esc_html( $jodyn_line ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if ( $jodyn_p['modules'] ) : ?>
				<section class="jd-pg-sec" aria-labelledby="jd-pg-modules">
					<h2 id="jd-pg-modules"><?php esc_html_e( 'Programme Modules', 'jodyn' ); ?></h2>
					<div class="jd-pg-acc" data-jd-pg-acc>
						<?php foreach ( $jodyn_p['modules'] as $jodyn_n => $jodyn_m ) : ?>
							<?php $jodyn_id = 'jd-pg-mod-' . ( $jodyn_n + 1 ); ?>
							<div class="jd-pg-acc__item<?php echo $jodyn_m[1] ? '' : ' is-plain'; ?>">
								<h3>
									<?php if ( $jodyn_m[1] ) : ?>
										<button type="button" id="<?php echo esc_attr( $jodyn_id ); ?>-q" aria-expanded="false" aria-controls="<?php echo esc_attr( $jodyn_id ); ?>" data-jd-pg-btn>
									<?php else : ?>
										<span class="jd-pg-acc__row">
									<?php endif; ?>
										<span class="jd-pg-acc__n">
											<?php
											/* translators: %d: module number. */
											echo esc_html( sprintf( __( 'Module %d', 'jodyn' ), $jodyn_n + 1 ) );
											?>
										</span>
										<span class="jd-pg-acc__t"><?php echo esc_html( $jodyn_m[0] ); ?></span>
									<?php if ( $jodyn_m[1] ) : ?>
										<i class="jd-pg-ic" aria-hidden="true"></i>
										</button>
									<?php else : ?>
										</span>
									<?php endif; ?>
								</h3>
								<?php if ( $jodyn_m[1] ) : ?>
									<div class="jd-pg-acc__a" id="<?php echo esc_attr( $jodyn_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $jodyn_id ); ?>-q"><div><p><?php echo esc_html( $jodyn_m[1] ); ?></p></div></div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<aside class="jd-pg-side" aria-labelledby="jd-pg-box-title">
			<div class="jd-pg-box">
				<div class="jd-pg-box__top">
					<h2 id="jd-pg-box-title"><?php esc_html_e( 'Programme Details', 'jodyn' ); ?></h2>
					<?php if ( $jodyn_p['upcoming'] ) : ?>
						<span class="jd-pg-seat<?php echo esc_attr( $jodyn_seat_cls ); ?>"><?php echo esc_html( $jodyn_p['seats_label'] ); ?></span>
					<?php endif; ?>
				</div>
				<dl>
					<?php foreach ( $jodyn_rows as $jodyn_row ) : ?>
						<div><dt><?php echo esc_html( $jodyn_row[0] ); ?></dt><dd><?php echo wp_kses_post( $jodyn_row[1] ); ?></dd></div>
					<?php endforeach; ?>
				</dl>
				<?php echo $jodyn_action( 'jd-pg-box__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the closure. ?>
			</div>
		</aside>
	</div>
</div>
<?php endif; ?>

<?php if ( $jodyn_p['facilitator']['name'] ) : ?>
	<section class="jd-pg-fac" aria-labelledby="jd-pg-fac-title">
		<div class="jd-container jd-pg-fac__in">
			<?php if ( $jodyn_p['facilitator']['photo'] ) : ?>
				<img class="jd-pg-fac__photo" src="<?php echo esc_url( $jodyn_p['facilitator']['photo'] ); ?>" alt="<?php echo esc_attr( $jodyn_p['facilitator']['name'] ); ?>" loading="lazy" decoding="async">
			<?php else : ?>
				<span class="jd-pg-fac__photo is-initials" aria-hidden="true"><?php echo esc_html( implode( '', array_map( static fn( $w ) => mb_substr( $w, 0, 1 ), array_slice( preg_split( '/\s+/', $jodyn_p['facilitator']['name'] ), 0, 2 ) ) ) ); ?></span>
			<?php endif; ?>
			<div>
				<h2 class="jd-pg-fac__k" id="jd-pg-fac-title"><?php esc_html_e( 'Your Facilitator', 'jodyn' ); ?></h2>
				<p class="jd-pg-fac__name"><?php echo esc_html( $jodyn_p['facilitator']['name'] ); ?></p>
				<?php if ( $jodyn_p['facilitator']['role'] ) : ?>
					<p class="jd-pg-fac__role"><?php echo esc_html( $jodyn_p['facilitator']['role'] ); ?></p>
				<?php endif; ?>
				<?php foreach ( $jodyn_p['facilitator']['bio'] as $jodyn_line ) : ?>
					<p class="jd-pg-fac__bio"><?php echo esc_html( $jodyn_line ); ?></p>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<section class="jd-pg-learn" aria-labelledby="jd-pg-learn-title">
	<div class="jd-container jd-pg-learn__in">
		<h2 id="jd-pg-learn-title" data-jd-lines><?php esc_html_e( 'How You Will Learn', 'jodyn' ); ?></h2>
		<p data-jd-rise="0.1"><?php esc_html_e( 'Our programmes combine short expert input with case studies, role plays, group discussions and practical exercises. Participants practise the key skills in a safe, supportive environment and receive guidance from the facilitator.', 'jodyn' ); ?></p>
	</div>
</section>

<section class="jd-pg-team" aria-labelledby="jd-pg-team-title" data-jd-particles="forest" data-jd-keep-out=".jd-pg-team__in > *">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>
	<div class="jd-container jd-pg-team__in">
		<div>
			<h2 id="jd-pg-team-title" data-jd-lines><?php esc_html_e( 'Bring This Programme to Your Team', 'jodyn' ); ?></h2>
			<p data-jd-rise="0.1"><?php esc_html_e( 'Training several people at once? We can deliver this programme exclusively for your organisation, adapted to your industry, culture and goals.', 'jodyn' ); ?></p>
		</div>
		<a class="jd-btn jd-btn--accent jd-btn--arrow" href="<?php echo esc_url( $jodyn_p['in_house'] ); ?>" data-jd-rise="0.2"><?php esc_html_e( 'Request In-House Delivery', 'jodyn' ); ?><span class="jd-btn__disc"><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a>
	</div>
</section>

<?php if ( $jodyn_p['faq'] ) : ?>
	<section class="jd-pg-faq" aria-labelledby="jd-pg-faq-title">
		<div class="jd-container jd-pg-faq__in">
			<div>
				<h2 id="jd-pg-faq-title" data-jd-lines><?php esc_html_e( 'FAQs', 'jodyn' ); ?></h2>
				<a class="jd-pg-all" href="<?php echo esc_url( home_url( '/faqs/#training' ) ); ?>"><?php esc_html_e( 'See all FAQs', 'jodyn' ); ?><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</div>
			<div class="jd-pg-acc" data-jd-pg-acc>
				<?php foreach ( $jodyn_p['faq'] as $jodyn_n => $jodyn_q ) : ?>
					<?php $jodyn_id = 'jd-pg-faq-' . ( $jodyn_n + 1 ); ?>
					<div class="jd-pg-acc__item">
						<h3>
							<button type="button" id="<?php echo esc_attr( $jodyn_id ); ?>-q" aria-expanded="false" aria-controls="<?php echo esc_attr( $jodyn_id ); ?>" data-jd-pg-btn>
								<span class="jd-pg-acc__t"><?php echo esc_html( $jodyn_q[0] ); ?></span>
								<i class="jd-pg-ic" aria-hidden="true"></i>
							</button>
						</h3>
						<div class="jd-pg-acc__a" id="<?php echo esc_attr( $jodyn_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $jodyn_id ); ?>-q"><div><p><?php echo wp_kses_post( jodyn_faqs_fill( $jodyn_q[1] ) ); ?></p></div></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( $jodyn_p['related'] ) : ?>
	<section class="jd-pg-rel" aria-labelledby="jd-pg-rel-title">
		<div class="jd-container">
			<h2 id="jd-pg-rel-title" data-jd-lines><?php esc_html_e( 'Related Programmes', 'jodyn' ); ?></h2>
			<div class="jd-pg-rel__grid" data-jd-stagger>
				<?php foreach ( $jodyn_p['related'] as $jodyn_r ) : ?>
					<a class="jd-pg-card" href="<?php echo esc_url( $jodyn_r['details'] ); ?>">
						<span class="jd-pg-card__k"><?php echo esc_html( $jodyn_r['area_short'] ); ?></span>
						<b><?php echo esc_html( $jodyn_r['title'] ); ?></b>
						<span class="jd-pg-card__m"><?php echo $jodyn_i['cal']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $jodyn_r['upcoming'] ? $jodyn_r['date_full'] : __( 'New dates coming soon', 'jodyn' ) ); ?></span>
						<?php if ( $jodyn_r['upcoming'] ) : ?>
							<span class="jd-pg-card__m"><?php echo $jodyn_i['pin']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $jodyn_r['format'] ); ?></span>
						<?php endif; ?>
						<span class="jd-pg-card__go"><?php esc_html_e( 'View Programme', 'jodyn' ); ?><?php echo jodyn_arrow_swap(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
