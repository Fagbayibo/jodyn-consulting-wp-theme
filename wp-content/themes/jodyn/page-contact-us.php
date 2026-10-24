<?php
/**
 * Contact page (slug: contact-us, /contact-us/).
 *
 * In page order:
 * Hero and Ways to reach us (centred heading, three lime-topped cards): template-parts/contact/top.php.
 * Form, office details and social links (forest): template-parts/contact/form.php.
 * Before You Reach Out (white, centred accordion): template-parts/contact/faq.php.
 * No footer card on this page (footer.php): the form is the call to action.
 * Copy: content/pages/contact.md (approved).
 *
 * @package JoDyn
 */

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/contact/top' ); ?>

	<?php get_template_part( 'template-parts/contact/form' ); ?>

	<?php get_template_part( 'template-parts/contact/faq' ); ?>

</main>
<?php
get_footer();
