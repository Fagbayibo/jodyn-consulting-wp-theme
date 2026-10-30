<?php
/**
 * Search and sharing: page titles, meta descriptions, link previews (Open Graph) and structured
 * data for Google (JSON-LD).
 *
 * - Pages use the approved titles and descriptions from the SEO block of each copy file
 *   (content/pages/*.md), by page path.
 * - Articles use their own title and summary (excerpt); programmes follow programme.md's SEO
 *   pattern, "[Programme Title] Training in Nigeria | JoDyn".
 * - Every page describes the business (name, Lagos address, phone, email, social profiles);
 *   articles add an Article, programmes a Course and, while a run is scheduled, an Event with its
 *   dates, venue and fee.
 * - Shared links show the article's or programme's photo, or the JoDyn share card
 *   (assets/img/share.jpg).
 * - The sitemap leaves out author pages and the two pages that redirect (Services, Training).
 *
 * If an SEO plugin (Yoast, Rank Math, All in One SEO, SEOPress, The SEO Framework) is active,
 * the theme prints none of this, so nothing is doubled.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether an SEO plugin is handling titles, descriptions and structured data.
 *
 * @return bool
 */
function jodyn_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' );
}

/**
 * Approved titles and descriptions by page path ('' is the home page, 'insights' the posts page).
 *
 * @return array path => array( title, description )
 */
function jodyn_seo_pages() {
	return array(
		''                         => array( 'JoDyn Advisory & Consulting | Business Advisory, Audit, Tax & Training', 'Business advisory, systems audit, tax consultancy and corporate training for organisations in Nigeria. Expert guidance and practical programmes from JoDyn Advisory and Consulting.' ),
		'about-us'                 => array( 'About Us | JoDyn Advisory and Consulting, Nigeria', 'Learn about JoDyn Advisory and Consulting: our mission, values and approach to business advisory, systems audit, tax consultancy and corporate training in Nigeria.' ),
		'services/advisory'        => array( 'Business Advisory Services in Nigeria | JoDyn Advisory', 'Strategy, organisational transformation, people and culture, and performance advisory for organisations in Nigeria. Practical advice that turns plans into results.' ),
		'services/systems-audit'   => array( 'Systems Audit Services in Nigeria | JoDyn Advisory', 'Independent reviews of internal controls, processes, IT systems and governance for organisations in Nigeria. See where the risks are and fix them before they become costly.' ),
		'services/tax-consultancy' => array( 'Tax Consultancy Services in Nigeria | JoDyn Advisory', 'Tax compliance and filing, tax planning, tax health checks and support with tax authorities for organisations in Nigeria. Stay compliant and avoid costly surprises.' ),
		'training/programmes'      => array( 'Corporate Training Programmes in Nigeria | JoDyn Advisory', 'Leadership, management, HR and soft skills training for organisations and professionals in Nigeria. In-house, open, online and hybrid programmes by JoDyn.' ),
		'training/calendar'        => array( 'Training Calendar | Upcoming Programmes in Nigeria | JoDyn', 'View upcoming leadership, management, HR and professional skills programmes by JoDyn Advisory and Consulting. Register for in-person and online training.' ),
		'register'                 => array( 'Register for a Programme | JoDyn Advisory and Consulting', 'Register for an upcoming JoDyn training programme. Complete the short form to secure your seat and receive payment and joining details.' ),
		'insights'                 => array( 'Insights on Strategy, Tax, Leadership and People | JoDyn Advisory', 'Practical articles, guides and case studies on strategy, systems audit, tax, leadership and workforce development for organisations and professionals in Nigeria.' ),
		'faqs'                     => array( 'FAQs | JoDyn Advisory and Consulting', 'Answers to common questions about JoDyn\'s advisory, systems audit, tax consultancy and training services: fees, timelines, registration, payment and certificates.' ),
		'contact-us'               => array( 'Contact Us | JoDyn Advisory and Consulting, Nigeria', 'Contact JoDyn Advisory and Consulting for strategy advisory, systems audit, tax consultancy and corporate training. Call, email or send us a message today.' ),
		'privacy-policy'           => array( 'Privacy Policy | JoDyn Advisory and Consulting', 'How JoDyn Advisory and Consulting collects, uses and protects your personal data, and your rights under the Nigeria Data Protection Act 2023.' ),
		'terms'                    => array( 'Terms of Use | JoDyn Advisory and Consulting', 'Terms governing the use of the JoDyn Advisory and Consulting website, registration for our training programmes and our advisory, audit and tax services.' ),
		'cookie-policy'            => array( 'Cookie Policy | JoDyn Advisory and Consulting', 'Which cookies the JoDyn Advisory and Consulting website uses, why we use them and how you can control them in your browser.' ),
	);
}

/**
 * The site name as plain text ("JoDyn Advisory & Consulting", not "&amp;").
 *
 * @return string
 */
function jodyn_seo_site_name() {
	return wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
}

/**
 * Plain text cut to about $max characters, at a sentence end where one fits, else a word break.
 *
 * @param string $text  Text or HTML.
 * @param int    $max   Characters.
 * @param float  $whole Shortest share of $max that whole sentences may fill before a word break is used instead.
 * @return string
 */
function jodyn_seo_trim( $text, $max = 160, $whole = 0.5 ) {
	$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( (string) $text ) ) ) );
	if ( mb_strlen( $text ) <= $max ) {
		return $text;
	}
	// Whole sentences where they fit, so the description doesn't stop mid-thought.
	$kept = '';
	foreach ( preg_split( '/(?<=[.!?])\s+/u', $text ) as $sentence ) {
		$next = trim( $kept . ' ' . $sentence );
		if ( mb_strlen( $next ) > $max ) {
			break;
		}
		$kept = $next;
	}
	if ( mb_strlen( $kept ) >= $max * $whole ) {
		return $kept;
	}
	$cut = mb_substr( $text, 0, $max - 1 );
	$cut = preg_replace( '/\s+\S*$/u', '', $cut );
	return rtrim( $cut, " ,;:.–-" ) . '…';
}

/**
 * The programme's description, after programme.md's pattern:
 * "[Short summary]. [In-person in the city / Online]. Register now."
 *
 * @param WP_Post $post Programme.
 * @return string
 */
function jodyn_seo_programme_description( $post ) {
	$data  = jodyn_programme_data( $post );
	$where = '';
	if ( $data['upcoming'] && get_post_meta( $post->ID, '_jodyn_format', true ) ) {
		$venue = $data['venue'];
		if ( 'online' === $data['format_key'] ) {
			$where = __( 'Online.', 'jodyn' );
		} elseif ( 'hybrid' === $data['format_key'] ) {
			/* translators: %s: city. */
			$where = $venue ? sprintf( __( 'In %s and online.', 'jodyn' ), $venue ) : __( 'In-person and online.', 'jodyn' );
		} else {
			/* translators: %s: city. */
			$where = $venue ? sprintf( __( 'In-person in %s.', 'jodyn' ), $venue ) : __( 'In-person.', 'jodyn' );
		}
	}
	$end     = trim( $where . ( $data['open'] ? ' ' . __( 'Register now.', 'jodyn' ) : '' ) );
	$summary = jodyn_seo_trim( $data['summary'], 158 - mb_strlen( $end ), 0.3 );
	if ( $summary && $end && ! preg_match( '/[.!?…]$/u', $summary ) ) {
		$summary .= '.';
	}
	return trim( $summary . ' ' . $end );
}

/**
 * Title, description, image and type for the current page. Empty title or description means
 * WordPress's own (titles) or none (descriptions).
 *
 * @return array{title: string, description: string, image: int, type: string, url: string}
 */
function jodyn_seo_current() {
	static $seo = null;
	if ( null !== $seo ) {
		return $seo;
	}
	$seo   = array(
		'title'       => '',
		'description' => '',
		'image'       => 0,
		'type'        => 'website',
		'url'         => home_url( user_trailingslashit( $GLOBALS['wp']->request ) ),
	);
	$pages = jodyn_seo_pages();
	$path  = null;

	if ( is_front_page() ) {
		$path       = '';
		$seo['url'] = home_url( '/' );
	} elseif ( is_home() ) {
		$path = 'insights';
	} elseif ( is_page() ) {
		$path = get_page_uri( get_queried_object() );
	} elseif ( is_singular( 'jodyn_programme' ) ) {
		$post               = get_queried_object();
		/* translators: %s: programme title. */
		$seo['title']       = sprintf( __( '%s Training in Nigeria | JoDyn', 'jodyn' ), get_the_title( $post ) );
		$seo['description'] = jodyn_seo_programme_description( $post );
		$seo['image']       = get_post_thumbnail_id( $post );
	} elseif ( is_singular( 'post' ) ) {
		$post               = get_queried_object();
		$seo['description'] = jodyn_seo_trim( has_excerpt( $post ) ? $post->post_excerpt : $post->post_content );
		$seo['image']       = has_post_thumbnail( $post ) ? get_post_thumbnail_id( $post ) : jodyn_article_banner_id( $post ); // the card graphic, title included
		$seo['type']        = 'article';
	} elseif ( is_category() ) {
		$seo['description'] = jodyn_seo_trim( term_description() );
	}

	if ( null !== $path && isset( $pages[ $path ] ) ) {
		list( $seo['title'], $seo['description'] ) = $pages[ $path ];
	}
	if ( is_singular() ) {
		$seo['url'] = (string) wp_get_canonical_url();
	}
	if ( $seo['title'] && is_paged() ) {
		/* translators: %d: page number. */
		$seo['title'] .= ' – ' . sprintf( __( 'Page %d', 'jodyn' ), max( 2, (int) get_query_var( 'paged' ) ) );
	}
	return $seo;
}

/**
 * The approved page title, in place of WordPress's "Page – Site name".
 *
 * @param string $title Title so far ('' lets WordPress build it).
 * @return string
 */
function jodyn_seo_title( $title ) {
	if ( jodyn_seo_plugin_active() ) {
		return $title;
	}
	$seo = jodyn_seo_current();
	return $seo['title'] ? esc_html( $seo['title'] ) : $title;
}
add_filter( 'pre_get_document_title', 'jodyn_seo_title', 20 );

/**
 * Description, canonical link where WordPress gives none, and the link preview tags.
 */
function jodyn_seo_head() {
	if ( jodyn_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}
	$seo   = jodyn_seo_current();
	// Shared articles show their own headline; the site name is beside it already (og:site_name).
	$title = $seo['title'] ? $seo['title'] : ( is_singular() ? get_the_title() : wp_get_document_title() );
	$title = html_entity_decode( $title, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$tags  = array();

	if ( $seo['description'] ) {
		$tags[] = array( 'name', 'description', $seo['description'] );
	}
	if ( ( is_home() && ! is_front_page() ) || is_category() ) {
		printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $seo['url'] ) );
	}

	$tags[] = array( 'property', 'og:site_name', jodyn_seo_site_name() );
	$tags[] = array( 'property', 'og:type', $seo['type'] );
	$tags[] = array( 'property', 'og:title', $title );
	if ( $seo['description'] ) {
		$tags[] = array( 'property', 'og:description', $seo['description'] );
	}
	$tags[] = array( 'property', 'og:url', $seo['url'] );

	$image = $seo['image'] ? wp_get_attachment_image_src( $seo['image'], 'full' ) : false;
	if ( $image ) {
		$alt    = trim( (string) get_post_meta( $seo['image'], '_wp_attachment_image_alt', true ) );
		$tags[] = array( 'property', 'og:image', $image[0] );
		$tags[] = array( 'property', 'og:image:width', $image[1] );
		$tags[] = array( 'property', 'og:image:height', $image[2] );
		$tags[] = array( 'property', 'og:image:alt', $alt ? $alt : get_the_title() );
	} else {
		$tags[] = array( 'property', 'og:image', get_template_directory_uri() . '/assets/img/share.jpg' );
		$tags[] = array( 'property', 'og:image:width', 1200 );
		$tags[] = array( 'property', 'og:image:height', 630 );
		$tags[] = array( 'property', 'og:image:alt', jodyn_seo_site_name() );
	}
	if ( 'article' === $seo['type'] ) {
		$tags[] = array( 'property', 'article:published_time', get_the_date( 'c' ) );
		$tags[] = array( 'property', 'article:modified_time', get_the_modified_date( 'c' ) );
	}
	$tags[] = array( 'name', 'twitter:card', 'summary_large_image' );

	foreach ( $tags as $tag ) {
		printf( '<meta %s="%s" content="%s" />' . "\n", $tag[0], esc_attr( $tag[1] ), esc_attr( $tag[2] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $tag[0] is 'name' or 'property'.
	}
}
add_action( 'wp_head', 'jodyn_seo_head', 2 );

/**
 * The business, as Google reads it: a professional service in Lagos.
 *
 * @return array
 */
function jodyn_seo_organization() {
	$home    = home_url( '/' );
	$contact = jodyn_contact();
	$org     = array(
		'@type'      => 'ProfessionalService',
		'@id'        => $home . '#organization',
		'name'       => jodyn_seo_site_name(),
		'url'        => $home,
		'logo'       => array(
			'@type'  => 'ImageObject',
			'url'    => get_template_directory_uri() . '/assets/img/logo-color.png',
			'width'  => 480,
			'height' => 333,
		),
		'image'       => get_template_directory_uri() . '/assets/img/share.jpg',
		'description' => jodyn_seo_pages()[''][1],
		'areaServed' => array(
			'@type' => 'Country',
			'name'  => 'Nigeria',
		),
	);
	if ( $contact['tel'] ) {
		$org['telephone'] = $contact['tel'];
	}
	if ( $contact['email'] ) {
		$org['email'] = $contact['email'];
	}
	// The last line of the address is the city: "2C Crocxley Drive / Cardogan Place / Lagos".
	$lines = array_values( array_filter( array_map( 'trim', preg_split( '/[\r\n]+/', $contact['address'] ) ) ) );
	if ( $lines ) {
		$city           = count( $lines ) > 1 ? array_pop( $lines ) : '';
		$org['address'] = array_filter(
			array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => implode( ', ', $lines ),
				'addressLocality' => $city,
				'addressCountry'  => 'NG',
			)
		);
	}
	$same = wp_list_pluck( jodyn_social_links(), 'url' );
	// Placeholder links (a network's home page, not a profile) say nothing about the business.
	$same = array_values( array_filter( $same, static function ( $url ) {
		return '' !== trim( (string) wp_parse_url( $url, PHP_URL_PATH ), '/' );
	} ) );
	if ( $same ) {
		$org['sameAs'] = $same;
	}
	return $org;
}

/**
 * The programme as a Course and, while a run is scheduled, as an Event.
 *
 * @param WP_Post $post  Programme.
 * @param array   $org   Organisation reference.
 * @param string  $image Photo URL or ''.
 * @return array[]
 */
function jodyn_seo_programme_graph( $post, $org, $image ) {
	$data        = jodyn_programme_data( $post );
	$url         = get_permalink( $post );
	$description = jodyn_seo_trim( $data['summary'] ? $data['summary'] : $post->post_content, 300 );
	$course      = array(
		'@type'       => 'Course',
		'@id'         => $url . '#course',
		'name'        => $data['title'],
		'description' => $description,
		'url'         => $url,
		'provider'    => $org,
	);
	if ( ! $data['upcoming'] ) {
		return array( $course );
	}

	$modes = array(
		'in-person' => array( 'Onsite', 'OfflineEventAttendanceMode' ),
		'online'    => array( 'Online', 'OnlineEventAttendanceMode' ),
		'hybrid'    => array( 'Blended', 'MixedEventAttendanceMode' ),
	);
	$mode  = $modes[ $data['format_key'] ];
	$place = array(
		'@type'   => 'Place',
		'name'    => $data['venue'] ? $data['venue'] : jodyn_seo_site_name(),
		'address' => array_filter(
			array(
				'@type'           => 'PostalAddress',
				'addressLocality' => $data['venue'],
				'addressCountry'  => 'NG',
			)
		),
	);
	$online = array(
		'@type' => 'VirtualLocation',
		'url'   => $url,
	);
	$location = array(
		'Onsite'  => $place,
		'Online'  => $online,
		'Blended' => array( $place, $online ),
	);
	$end = $data['end'] ? $data['end'] : $data['datetime'];

	$course['hasCourseInstance'] = array(
		'@type'      => 'CourseInstance',
		'courseMode' => $mode[0],
		'startDate'  => $data['datetime'],
		'endDate'    => $end,
		'location'   => $location[ $mode[0] ],
	);

	$event = array(
		'@type'               => 'Event',
		'@id'                 => $url . '#event',
		'name'                => $data['title'],
		'description'         => $description,
		'url'                 => $url,
		'startDate'           => $data['datetime'],
		'endDate'             => $end,
		'eventStatus'         => 'https://schema.org/EventScheduled',
		'eventAttendanceMode' => 'https://schema.org/' . $mode[1],
		'location'            => $location[ $mode[0] ],
		'organizer'           => $org,
	);
	if ( $image ) {
		$event['image'] = $image;
	}
	if ( $data['fee_amount'] ) {
		$availability    = array(
			'open' => 'InStock',
			'few'  => 'LimitedAvailability',
			'full' => 'SoldOut',
		);
		$event['offers'] = array(
			'@type'         => 'Offer',
			'price'         => $data['fee_amount'],
			'priceCurrency' => 'NGN',
			'availability'  => 'https://schema.org/' . $availability[ $data['seats'] ],
			'url'           => $data['url'],
		);
	}
	return array( $course, $event );
}

/**
 * Structured data for Google: the business and site on every page, plus the article or
 * programme being viewed.
 */
function jodyn_seo_schema() {
	if ( jodyn_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}
	$home  = home_url( '/' );
	$org   = array( '@id' => $home . '#organization' );
	$graph = array(
		jodyn_seo_organization(),
		array(
			'@type'      => 'WebSite',
			'@id'        => $home . '#website',
			'url'        => $home,
			'name'       => jodyn_seo_site_name(),
			'inLanguage' => get_bloginfo( 'language' ),
			'publisher'  => $org,
		),
	);

	if ( is_singular( array( 'post', 'jodyn_programme' ) ) ) {
		$post  = get_queried_object();
		$image = get_the_post_thumbnail_url( $post, 'full' );
		if ( 'post' === $post->post_type ) {
			$article = array(
				'@type'            => 'Article',
				'@id'              => get_permalink( $post ) . '#article',
				'headline'         => get_the_title( $post ),
				'description'      => jodyn_seo_current()['description'],
				'url'              => get_permalink( $post ),
				'mainEntityOfPage' => get_permalink( $post ),
				'datePublished'    => get_the_date( 'c', $post ),
				'dateModified'     => get_the_modified_date( 'c', $post ),
				'author'           => array(
					'@type' => 'Person',
					'name'  => get_the_author_meta( 'display_name', $post->post_author ),
				),
				'publisher'        => $org,
				'inLanguage'       => get_bloginfo( 'language' ),
			);
			$cat = jodyn_post_category( $post );
			if ( $cat ) {
				$article['articleSection'] = $cat->name;
			}
			if ( $image ) {
				$article['image'] = $image;
			}
			$graph[] = $article;
		} else {
			$graph = array_merge( $graph, jodyn_seo_programme_graph( $post, $org, $image ) );
		}
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
		)
	);
}
add_action( 'wp_head', 'jodyn_seo_schema', 3 );

/**
 * Sitemap: no author pages (they only list articles, and would show the login name).
 */
add_filter(
	'wp_sitemaps_add_provider',
	static function ( $provider, $name ) {
		return 'users' === $name ? false : $provider;
	},
	10,
	2
);

/**
 * Sitemap: leave out Services and Training while they are empty, as they redirect
 * (inc/setup.php) and Google should only be given pages that answer themselves.
 */
add_filter(
	'wp_sitemaps_posts_query_args',
	static function ( $args, $post_type ) {
		if ( 'page' !== $post_type ) {
			return $args;
		}
		$skip = array();
		foreach ( array( 'services', 'training' ) as $path ) {
			$page = get_page_by_path( $path );
			if ( $page && '' === trim( $page->post_content ) ) {
				$skip[] = $page->ID;
			}
		}
		if ( $skip ) {
			$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), $skip );
		}
		return $args;
	},
	10,
	2
);
