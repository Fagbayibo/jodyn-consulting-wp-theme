<?php
/**
 * FAQs (slug: faqs, /faqs/).
 *
 * Lime hero with search, then the five topics as cards on soft green:
 * template-parts/faqs/page.php. Footer card "Still Have a Question?" (inc/faqs.php).
 * Copy: content/pages/faqs.md (approved).
 *
 * @package JoDyn
 */

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/faqs/page' ); ?>

</main>
<?php
get_footer();
