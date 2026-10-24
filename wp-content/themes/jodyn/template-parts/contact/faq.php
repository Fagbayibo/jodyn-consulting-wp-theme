<?php
/**
 * Contact · Before You Reach Out (copy section 6, quick answers).
 *
 * White with light particles, between the forest form and the forest footer: a centred heading
 * over a narrow accordion of outlined rows (native <details>, so it opens without JavaScript);
 * the first answer starts open. The response time comes from Customize → Contact & social.
 * Copy: content/pages/contact.md, section 6 (approved; the free consultation answer is still
 * a placeholder to confirm before launch).
 *
 * @package JoDyn
 */

$jodyn_answers = array(
	array(
		__( 'How quickly will you respond?', 'jodyn' ),
		/* translators: %s: response time, e.g. one working day. */
		sprintf( __( 'We respond to all enquiries within %s. Training registrations are confirmed by email immediately.', 'jodyn' ), jodyn_response_time() ),
	),
	array(
		__( 'Can we meet in person?', 'jodyn' ),
		__( 'Yes. We are happy to meet at your office or ours, or online if that is more convenient. Simply mention your preference in your message.', 'jodyn' ),
	),
	array(
		__( 'Is the first consultation free?', 'jodyn' ),
		__( '[Yes. Our initial consultation is free, with no obligation. It helps us understand your needs and recommend the right next step.]', 'jodyn' ),
	),
);
?>
<section class="jd-ct-faq" aria-labelledby="jd-ct-faq-title" data-jd-particles="light" data-jd-keep-out=".jd-ct-faq__title, .jd-ct-faq__list">
	<div class="jd-particles" aria-hidden="true"><canvas></canvas></div>
	<div class="jd-container jd-ct-faq__in">
		<h2 class="jd-ct-faq__title" id="jd-ct-faq-title" data-jd-lines>Before You <em>Reach Out</em></h2>
		<div class="jd-ct-faq__list" data-jd-stagger>
			<?php foreach ( $jodyn_answers as $jodyn_i => $jodyn_qa ) : ?>
				<details class="jd-ct-faq__item"<?php echo 0 === $jodyn_i ? ' open' : ''; ?>>
					<summary><span><?php echo esc_html( $jodyn_qa[0] ); ?></span><span class="jd-ct-faq__pm" aria-hidden="true"><i></i><i></i></span></summary>
					<div class="jd-ct-faq__a"><p><?php echo esc_html( $jodyn_qa[1] ); ?></p></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
