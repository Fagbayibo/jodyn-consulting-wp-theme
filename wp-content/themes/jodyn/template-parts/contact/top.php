<?php
/**
 * Contact · Hero and Ways to reach us (copy sections 1 and 2).
 *
 * White with light particles, under the solid header: a centred heading and intro, then the three
 * ways to reach us as outlined cards with a lime top edge that lift on hover. Each card: a forest
 * icon tile, the line's title and text, its email and
 * phone (Customize → Contact & social; each line falls back to the main details), and Send a
 * message, which opens the form below with that enquiry type selected (assets/js/contact.js).
 * Copy: content/pages/contact.md, sections 1 and 2 (approved).
 *
 * @package JoDyn
 */

$jodyn_doors = array(
	'advisory' => array( 'compass', 'Discuss strategy, systems audit, tax compliance, people and culture, or performance improvement.' ),
	'training' => array( 'cap', 'Register for a programme, request in-house training or design a custom programme.' ),
	'general'  => array( 'chat', 'Partnerships, media, facilitator applications and everything else.' ),
);
?>
<section class="jd-ct-top" aria-labelledby="jd-ct-title" data-jd-particles="light" data-jd-keep-out=".jd-ct-top__copy > *, .jd-ct-top__list">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>
	<div class="jd-container">
		<div class="jd-ct-top__hero">
			<div class="jd-ct-top__copy">
				<p class="jd-eyebrow" data-jd-rise>Contact us</p>
				<h1 class="jd-ct-top__title" id="jd-ct-title" data-jd-lines>Let's Start a <em>Conversation</em></h1>
				<p class="jd-ct-top__text" data-jd-rise="0.15">Whether you need strategic advice, a training programme for your team or simply have a question, we would love to hear from you. Reach out, and a member of our team will respond promptly.</p>
			</div>
		</div>

		<div class="jd-ct-top__doors">
			<h2 class="screen-reader-text">Ways to reach us</h2>
			<ul class="jd-ct-top__list" data-jd-stagger>
				<?php foreach ( jodyn_contact_lines() as $jodyn_key => $jodyn_title ) : ?>
					<?php $jodyn_line = jodyn_contact_line( $jodyn_key ); ?>
					<li class="jd-ct-top__card">
						<span class="jd-ct-top__ico" aria-hidden="true"><?php echo jodyn_icon( $jodyn_doors[ $jodyn_key ][0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<h3><?php echo esc_html( $jodyn_title ); ?></h3>
						<p><?php echo esc_html( $jodyn_doors[ $jodyn_key ][1] ); ?></p>
						<ul class="jd-ct-top__lines">
							<li><?php echo jodyn_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php if ( $jodyn_line['email'] ) : ?><a href="mailto:<?php echo esc_attr( $jodyn_line['email'] ); ?>"><?php echo esc_html( $jodyn_line['email'] ); ?></a><?php else : ?><span>[info@jodynconsulting.com]</span><?php endif; ?></li>
							<li><?php echo jodyn_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php if ( $jodyn_line['tel'] ) : ?><a href="tel:<?php echo esc_attr( $jodyn_line['tel'] ); ?>"><?php echo esc_html( $jodyn_line['phone'] ); ?></a><?php else : ?><span><?php echo esc_html( $jodyn_line['phone'] ? $jodyn_line['phone'] : '[phone number]' ); ?></span><?php endif; ?></li>
						</ul>
						<a class="jd-ct-top__send" href="<?php echo esc_url( add_query_arg( 'enquiry', $jodyn_key, get_permalink() ) . '#contact-form' ); ?>" data-jd-enquiry="<?php echo esc_attr( $jodyn_key ); ?>">Send a message<span class="screen-reader-text">: <?php echo esc_html( $jodyn_title ); ?></span><?php echo jodyn_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
