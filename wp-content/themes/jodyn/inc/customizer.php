<?php
/**
 * Customizer settings: header button and home hero media.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function jodyn_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'jodyn_header',
		array(
			'title'    => __( 'Header', 'jodyn' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'jodyn_header_cta_label',
		array(
			'default'           => __( 'Contact us', 'jodyn' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'jodyn_header_cta_label',
		array(
			'label'   => __( 'Button label', 'jodyn' ),
			'section' => 'jodyn_header',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'jodyn_header_cta_url',
		array(
			'default'           => home_url( '/contact-us/' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'jodyn_header_cta_url',
		array(
			'label'   => __( 'Button link', 'jodyn' ),
			'section' => 'jodyn_header',
			'type'    => 'url',
		)
	);

	$wp_customize->add_section(
		'jodyn_hero',
		array(
			'title'       => __( 'Home hero', 'jodyn' ),
			'description' => __( 'Background video: muted MP4 (H.264), 1080p, under 6 MB. The poster shows while it loads, and stays for visitors using Data Saver or reduced motion.', 'jodyn' ),
			'priority'    => 31,
		)
	);

	$wp_customize->add_setting(
		'jodyn_hero_video',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'jodyn_hero_video',
			array(
				'label'     => __( 'Background video', 'jodyn' ),
				'section'   => 'jodyn_hero',
				'mime_type' => 'video',
			)
		)
	);

	$wp_customize->add_setting(
		'jodyn_hero_video_mobile',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'jodyn_hero_video_mobile',
			array(
				'label'       => __( 'Background video for phones (optional)', 'jodyn' ),
				'description' => __( 'A lighter 720p copy of the same film, around 3 MB. Phones use it to save data.', 'jodyn' ),
				'section'     => 'jodyn_hero',
				'mime_type'   => 'video',
			)
		)
	);

	$wp_customize->add_setting(
		'jodyn_hero_poster',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'jodyn_hero_poster',
			array(
				'label'   => __( 'Poster image', 'jodyn' ),
				'section' => 'jodyn_hero',
			)
		)
	);
}
add_action( 'customize_register', 'jodyn_customize_register' );
