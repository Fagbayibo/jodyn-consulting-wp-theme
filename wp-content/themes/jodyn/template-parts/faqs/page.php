<?php
/**
 * FAQs page (option D).
 *
 * Lime hero: the heading beside the intro and a search box that filters the questions as you
 * type. Then each topic as a white card on soft green, in two columns (one on tablets and phones),
 * with its questions as an accordion: one answer open at a time.
 * Search and accordion: assets/js/faqs.js. The lime footer card follows (inc/faqs.php).
 * Copy: content/pages/faqs.md (approved); wording in inc/faqs-content.php.
 *
 * @package JoDyn
 */

$jodyn_faqs = jodyn_faqs_content();
?>
<header class="jd-fq-hero">
	<div class="jd-container jd-fq-hero__grid">
		<div>
			<p class="jd-eyebrow" data-jd-rise><?php echo esc_html( $jodyn_faqs['eyebrow'] ); ?></p>
			<h1 class="jd-fq-title" data-jd-lines><?php echo esc_html( $jodyn_faqs['title'] ); ?></h1>
		</div>
		<div class="jd-fq-hero__side">
			<p class="jd-fq-intro" data-jd-rise="0.1"><?php echo esc_html( $jodyn_faqs['intro'] ); ?></p>
			<form class="jd-fq-search" role="search" data-jd-rise="0.2" data-jd-fq-form>
				<label class="screen-reader-text" for="jd-fq-search"><?php esc_html_e( 'Search the questions', 'jodyn' ); ?></label>
				<?php echo jodyn_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<input id="jd-fq-search" type="search" placeholder="<?php esc_attr_e( 'Search the questions', 'jodyn' ); ?>" autocomplete="off" data-jd-fq-search>
			</form>
		</div>
	</div>
</header>

<section class="jd-fq-body" aria-label="<?php esc_attr_e( 'Questions', 'jodyn' ); ?>">
	<div class="jd-container">
		<p class="screen-reader-text" role="status" data-jd-fq-status></p>
		<div class="jd-fq-grid" data-jd-stagger>
			<?php foreach ( $jodyn_faqs['groups'] as $jodyn_g ) : ?>
				<section class="jd-fq-card" id="<?php echo esc_attr( $jodyn_g['id'] ); ?>" aria-labelledby="<?php echo esc_attr( $jodyn_g['id'] ); ?>-title" data-jd-fq-group>
					<h2 class="jd-fq-card__title" id="<?php echo esc_attr( $jodyn_g['id'] ); ?>-title">
						<span class="jd-fq-card__icon"><?php echo jodyn_icon( $jodyn_g['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php echo esc_html( $jodyn_g['name'] ); ?>
						<small><?php echo esc_html( count( $jodyn_g['items'] ) ); ?></small>
					</h2>
					<div class="jd-fq-list">
						<?php foreach ( $jodyn_g['items'] as $jodyn_n => $jodyn_item ) : ?>
							<?php $jodyn_id = $jodyn_g['id'] . '-' . ( $jodyn_n + 1 ); ?>
							<div class="jd-fq-item" data-jd-fq-item>
								<h3 class="jd-fq-q">
									<button type="button" id="<?php echo esc_attr( $jodyn_id ); ?>-q" aria-expanded="false" aria-controls="<?php echo esc_attr( $jodyn_id ); ?>" data-jd-fq-btn>
										<span><?php echo esc_html( $jodyn_item[0] ); ?></span>
										<i class="jd-fq-ic" aria-hidden="true"></i>
									</button>
								</h3>
								<div class="jd-fq-a" id="<?php echo esc_attr( $jodyn_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $jodyn_id ); ?>-q">
									<div><p><?php echo wp_kses_post( jodyn_faqs_fill( $jodyn_item[1] ) ); ?></p></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endforeach; ?>
		</div>
		<p class="jd-fq-empty" data-jd-fq-empty hidden>
			<?php
			printf(
				/* translators: %s: link to the contact page. */
				esc_html__( 'No questions match your search. %s and we will help.', 'jodyn' ),
				'<a href="' . esc_url( home_url( '/contact-us/' ) ) . '">' . esc_html__( 'Send us a message', 'jodyn' ) . '</a>'
			);
			?>
		</p>
	</div>
</section>
