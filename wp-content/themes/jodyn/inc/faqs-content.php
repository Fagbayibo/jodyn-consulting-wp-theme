<?php
/**
 * The FAQs page's wording: the hero and the questions in five topics.
 *
 * Copy: content/pages/faqs.md (approved). Each topic has an id (its anchor), a name, a
 * jodyn_icon() name and its questions as array( question, answer ), with a third 'programme' on
 * the four that every programme page repeats (inc/programme-page.php). In the answers,
 * {url:/path/} becomes a link on this site and {response} the response time from Customize
 * (inc/faqs.php). Square-bracketed text is a placeholder the client still has to confirm.
 *
 * @package JoDyn
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The FAQs page's wording.
 *
 * @return array{eyebrow: string, title: string, intro: string, groups: array<int, array{id: string, name: string, icon: string, items: array<int, string[]>}>}
 */
function jodyn_faqs_content() {
	return array(
		'eyebrow' => 'FAQs',
		'title' => 'Frequently Asked Questions',
		'intro' => 'Answers to the questions we hear most about our advisory, systems audit, tax and training services. Can\'t find what you need? Send us a message and we will help.',
		'groups' => array(
			array(
				'id' => 'general',
				'name' => 'General',
				'icon' => 'chat',
				'items' => array(
					array( 'How do we get started?', 'Simply <a href="{url:/contact-us/}">book a consultation or send us a message</a>. We will schedule a conversation to understand your needs, then recommend the right approach and next steps.' ),
					array( 'Is the first consultation free?', '[Yes. Our initial consultation is free, with no obligation. It helps us understand your needs and recommend the right next step.]' ),
					array( 'Do you work with small and growing businesses?', 'Yes. We work with organisations of all sizes, from SMEs putting structures in place to larger organisations and public institutions, and scale our support to fit your stage and resources.' ),
					array( 'Will our information be kept confidential?', 'Absolutely. All information you share with us, including financial records, is treated with strict confidentiality and used only for the work you have asked us to do. We are happy to sign a non-disclosure agreement where required.' ),
					array( 'How quickly will you respond?', 'We respond to all enquiries within {response}. Training registrations are confirmed by email immediately.' ),
				),
			),
			array(
				'id' => 'advisory',
				'name' => 'Advisory',
				'icon' => 'compass',
				'items' => array(
					array( 'How long does an advisory engagement take?', 'It depends on the scope. A focused workshop or strategy retreat can be completed in one to three days, while a full strategy, restructuring or HR systems project typically runs over several weeks or months. We agree on timelines with you before any work begins.' ),
					array( 'How much do your advisory services cost?', 'Every engagement is scoped to your needs, so fees depend on the size, complexity and duration of the work. After an initial conversation, we provide a clear proposal with the scope, deliverables, timeline and fees.' ),
					array( 'Can advisory sessions be held online?', 'Yes. Many parts of our engagements, including interviews, workshops and progress reviews, can be delivered online. We also offer in-person and hybrid options depending on your preference.' ),
				),
			),
			array(
				'id' => 'systems-audit',
				'name' => 'Systems Audit',
				'icon' => 'search',
				'items' => array(
					array( 'Is a systems audit the same as a statutory financial audit?', 'No. A statutory audit gives an opinion on your financial statements. A systems audit looks at how your processes, controls and systems work, so you can reduce risk and run more efficiently. [confirm whether JoDyn also offers statutory financial audits.]' ),
					array( 'How long does a systems audit take?', 'It depends on the scope and the size of your organisation. A focused review of one area can take one to two weeks, while a wider review takes several weeks. We agree the timeline before any work begins.' ),
					array( 'How much does an audit cost?', 'Fees depend on the scope, size and complexity of the review. After an initial conversation, we send a clear proposal with the scope, deliverables, timeline and fees.' ),
					array( 'Will the audit disrupt our work?', 'We plan interviews and testing around your team\'s schedule and keep requests to what the review needs.' ),
					array( 'Can you help us fix the issues you find?', 'Yes. Our advisory and training teams can help you put the recommendations in place, or you can implement them yourselves using the action plan.' ),
				),
			),
			array(
				'id' => 'tax-consultancy',
				'name' => 'Tax Consultancy',
				'icon' => 'receipt',
				'items' => array(
					array( 'Which taxes can you help with?', 'We support company income tax, VAT, PAYE and withholding tax, and advise on other taxes that apply to your organisation. [confirm the full list of taxes JoDyn handles, for example personal income tax for individuals, capital gains tax or stamp duties.]' ),
					array( 'Can you file our returns for us?', 'Yes. We can prepare and file returns on your behalf, or review returns prepared by your team before they are filed.' ),
					array( 'How much do your tax services cost?', 'Fees depend on the service and the size of your organisation. Routine filing can be agreed as a fixed monthly or annual fee; other work is scoped and quoted before it begins.' ),
					array( 'We have received a tax assessment. What should we do?', 'Do not ignore it, and note the deadline for responding. Contact us as soon as possible so we can review it and help you respond in time.' ),
				),
			),
			array(
				'id' => 'training',
				'name' => 'Training',
				'icon' => 'cap',
				'items' => array(
					array( 'How do I register for a programme?', 'Choose a programme from <a href="{url:/training/calendar/}">our training calendar</a> and click Register Now. Complete the short registration form, and you will receive a confirmation email with payment details and programme information.', 'programme' ),
					array( 'How do I pay for a programme?', 'Payment can be made by bank transfer [or online payment]. Payment details are included in your registration confirmation. Your seat is secured once payment is received.', 'programme' ),
					array( 'Do you offer discounts for groups?', 'Yes. Organisations registering [three] or more participants for the same open programme qualify for a group discount. For larger groups, an in-house programme is often more cost-effective. <a href="{url:/contact-us/?enquiry=training}">Contact us</a> for details.' ),
					array( 'Can a programme be customised for my organisation?', 'Yes. Any of our programmes can be adapted to your industry, goals and team, and we can design fully bespoke programmes from scratch. <a href="{url:/contact-us/?enquiry=custom-training}">Tell us about your team</a> to get started.' ),
					array( 'What do I need to join an online programme?', 'A laptop or desktop computer with a stable internet connection, a webcam and a microphone. Joining details are sent before the programme, and our team is available to help with any technical questions.' ),
					array( 'Will I receive a certificate?', 'Yes. Every participant who completes a programme receives a certificate of participation from JoDyn Advisory and Consulting.', 'programme' ),
					array( 'What is your cancellation and refund policy?', '[Cancellations made at least X working days before the programme start date qualify for a full refund or transfer to a future programme. Substitute participants are welcome at no extra cost.] The full policy is in our <a href="{url:/terms/#cancellations}">Terms of Use</a>.', 'programme' ),
				),
			),
		),
	);
}
