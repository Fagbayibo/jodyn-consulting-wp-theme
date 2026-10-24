<?php
/**
 * Registration page (slug: register, /register/). Every Register Now button lands here.
 *
 * In page order:
 * Hero and How registration works (the four steps as one ticket): template-parts/register/top.php.
 * Form, programme summary and thank-you message: template-parts/register/form.php.
 * No footer card on this page: the form is the call to action.
 * Copy: content/pages/registration.md (approved).
 *
 * @package JoDyn
 */

// The programme pages should rank on Google, not the form (registration.md, SEO).
add_filter( 'wp_robots', 'wp_robots_no_robots' );

get_header();
?>
<main id="main" class="jd-main jd-main--flush">

	<?php get_template_part( 'template-parts/register/top' ); ?>

	<?php get_template_part( 'template-parts/register/form' ); ?>

</main>
<?php
get_footer();
