<?php
/**
 * The three policies' wording: Privacy Policy, Terms of Use and Cookie Policy.
 *
 * Copy: content/pages/legal.md, Parts 1 to 3 (approved). Each section is array( id, title, html );
 * the id is the section's anchor (Terms section 5 is #cancellations, the registration form's
 * refund link). Tokens in braces are filled by jodyn_legal_fill() (inc/legal.php): contact
 * details from Customize, or the [placeholder] until they are set, and the other policies' URLs.
 * Square-bracketed text is a placeholder the client still has to decide.
 *
 * @package JoDyn
 */

/**
 * One policy's wording.
 *
 * @param string $slug privacy-policy, terms or cookie-policy.
 * @return array{title: string, date: string, intro: string[], sections: array<int, array{id: string, title: string, html: string}>}|null
 */
function jodyn_legal_content( $slug ) {
	$policies = array(
		'privacy-policy' => array(
			'title' => 'Privacy Policy',
			'date' => '[DD Month YYYY]',
			'intro' => array(
				'JoDyn Advisory and Consulting ("JoDyn", "we", "us" or "our") respects your privacy and is committed to protecting your personal data. This Privacy Policy explains what personal data we collect when you visit our website, contact us, register for our programmes or use our services, how we use it, and the rights you have in relation to it.',
				'We process personal data in line with the Nigeria Data Protection Act 2023 (NDPA) and other applicable data protection laws.',
			),
			'sections' => array(
				array(
					'id' => 'who-we-are',
					'title' => 'Who We Are',
					'html' => '<p>JoDyn Advisory and Consulting is the data controller responsible for your personal data. You can contact us about this policy or your personal data at:</p><ul><li><b>Registered name:</b> [full registered business name, RC/BN number]</li><li><b>Address:</b> {address}</li><li><b>Email:</b> {privacy_email}</li><li><b>Phone:</b> {phone}</li></ul>',
				),
				array(
					'id' => 'personal-data-we-collect',
					'title' => 'Personal Data We Collect',
					'html' => '<h3>Information you give us</h3><ul><li><b>Contact and enquiry details:</b> your name, email address, phone number, organisation, job title and the content of your message when you contact us or request a consultation.</li><li><b>Programme registration details:</b> the details of participants and the person registering, the programme selected, invoicing contact details and any special requirements you tell us about, such as accessibility or dietary needs.</li><li><b>Payment information:</b> proof of payment and transaction references. We do not collect or store bank card details on our website.</li><li><b>Engagement information:</b> documents, records and financial information you share with us during an advisory, systems audit or tax engagement.</li><li><b>Newsletter and download requests:</b> your name, email address and organisation when you subscribe to updates or download our training calendar.</li><li><b>Facilitator applications:</b> your name, contact details, professional background and any CV you choose to send us.</li><li><b>Feedback and assessments:</b> programme evaluations, assessment responses and feedback you provide during or after a programme.</li></ul><h3>Information collected automatically</h3><p>When you visit our website, we may automatically collect technical information such as your IP address, browser type, device type, the pages you visit and the time spent on them. This is collected through cookies and similar technologies (see <a href="#cookies">Section 8</a> and our <a href="{cookie_url}">Cookie Policy</a>).</p>',
				),
				array(
					'id' => 'how-we-use-your-personal-data',
					'title' => 'How We Use Your Personal Data',
					'html' => '<p>We use your personal data to:</p><ul><li>respond to your enquiries and consultation requests;</li><li>process programme registrations, confirm payments and send joining instructions;</li><li>deliver training programmes, including issuing certificates of participation;</li><li>carry out advisory, systems audit and tax engagements, including preparing filings and corresponding with tax authorities on your behalf when you ask us to;</li><li>send you newsletters, programme updates and insights, where you have agreed to receive them;</li><li>evaluate and improve our programmes, services and website;</li><li>manage our relationship with clients, participants and facilitators; and</li><li>comply with our legal, tax and regulatory obligations.</li></ul>',
				),
				array(
					'id' => 'our-lawful-basis-for-processing',
					'title' => 'Our Lawful Basis for Processing',
					'html' => '<p>We only process your personal data where we have a lawful basis to do so under the NDPA. Depending on the purpose, this will be:</p><ul><li><b>Consent:</b> for example, when you subscribe to our newsletter. You can withdraw your consent at any time.</li><li><b>Contract:</b> where processing is necessary to register you for a programme or deliver services you or your organisation have requested.</li><li><b>Legal obligation:</b> where we must keep records for tax, accounting or other legal purposes.</li><li><b>Legitimate interests:</b> for example, improving our services and responding to business enquiries, where these interests are not overridden by your rights and freedoms.</li></ul>',
				),
				array(
					'id' => 'who-we-share-your-data-with',
					'title' => 'Who We Share Your Data With',
					'html' => '<p>We do not sell or rent your personal data. We share it only where necessary, with:</p><ul><li><b>Service providers:</b> who help us operate our business, such as our website hosting provider, email provider (Zoho), newsletter platform, website analytics provider (Google Analytics) and website font provider (Google Fonts). They may only use your data to provide services to us.</li><li><b>Facilitators and partners:</b> who deliver programmes on our behalf, limited to the participant information they need to deliver the programme.</li><li><b>Your organisation:</b> where your organisation registered you for a programme, we may share attendance, completion and evaluation information with them.</li><li><b>Tax authorities:</b> where you have engaged us to file returns or respond to them on your behalf.</li><li><b>Authorities:</b> where we are required to by law, regulation or a valid legal request.</li></ul><p>Some of these providers store data outside Nigeria. Where they do, we make sure your data is protected in line with the NDPA\'s rules on transfers outside Nigeria.</p>',
				),
				array(
					'id' => 'how-long-we-keep-your-data',
					'title' => 'How Long We Keep Your Data',
					'html' => '<p>We keep personal data only for as long as necessary for the purposes described in this policy, including to meet legal, accounting and reporting requirements. As a general guide:</p><ul><li>enquiry records are kept for up to [two] years after our last contact;</li><li>programme registration and participation records are kept for up to [six] years to support certificate verification and our financial records;</li><li>engagement records are kept for up to [six] years after the engagement ends, to meet professional and tax record-keeping requirements; and</li><li>newsletter subscriber data is kept until you unsubscribe.</li></ul><p>When data is no longer needed, we securely delete or anonymise it.</p>',
				),
				array(
					'id' => 'how-we-protect-your-data',
					'title' => 'How We Protect Your Data',
					'html' => '<p>We use appropriate technical and organisational measures to protect your personal data against unauthorised access, loss or misuse. These include an encrypted (SSL) website connection, password-protected systems, restricted access to personal data, and regular backups. While we take every reasonable precaution, no method of transmission over the internet is completely secure.</p>',
				),
				array(
					'id' => 'cookies',
					'title' => 'Cookies',
					'html' => '<p>Our website uses cookies, which are small text files stored on your device, to make the site work properly, remember your preferences and help us understand how visitors use the site. Analytics cookies are only set if you accept them. Our <a href="{cookie_url}">Cookie Policy</a> explains which cookies we use and how to control them.</p><p>You can change your choice at any time using the <a href="{cookie_url}#how-to-control-cookies" data-jd-cookie-settings>Cookie settings</a> link at the bottom of every page, or control and delete cookies through your browser settings. Blocking some cookies may affect how the website works.</p>',
				),
				array(
					'id' => 'your-rights',
					'title' => 'Your Rights',
					'html' => '<p>Under the Nigeria Data Protection Act 2023, you have the right to:</p><ul><li>be informed about how your personal data is used;</li><li>access the personal data we hold about you;</li><li>have inaccurate or incomplete data corrected;</li><li>have your data deleted, where there is no good reason for us to keep it;</li><li>restrict or object to certain processing, including direct marketing;</li><li>withdraw your consent at any time, where we rely on consent;</li><li>receive your data in a commonly used format and have it transferred to another organisation (data portability); and</li><li>lodge a complaint with the Nigeria Data Protection Commission (NDPC).</li></ul><p>To exercise any of these rights, contact us at {privacy_email}. We will respond within [30] days. You can unsubscribe from our emails at any time using the link at the bottom of every email.</p>',
				),
				array(
					'id' => 'childrens-privacy',
					'title' => 'Children\'s Privacy',
					'html' => '<p>Our website and services are intended for adults and organisations. We do not knowingly collect personal data from anyone under 18 years of age.</p>',
				),
				array(
					'id' => 'links-to-other-websites',
					'title' => 'Links to Other Websites',
					'html' => '<p>Our website may contain links to other websites. We are not responsible for the privacy practices of those websites and encourage you to read their privacy policies.</p>',
				),
				array(
					'id' => 'changes-to-this-policy',
					'title' => 'Changes to This Policy',
					'html' => '<p>We may update this Privacy Policy from time to time. The latest version will always be available on this page, with the effective date shown at the top.</p>',
				),
				array(
					'id' => 'contact-us',
					'title' => 'Contact Us',
					'html' => '<p>If you have any questions about this Privacy Policy or how we handle your personal data, please contact us at {privacy_email} or {phone}.</p>',
				),
			),
		),
		'terms' => array(
			'title' => 'Terms of Use',
			'date' => '[DD Month YYYY]',
			'intro' => array(
				'These Terms of Use govern your use of the JoDyn Advisory and Consulting website at [www.jodynconsulting.com] and your registration for our training programmes. By using this website or registering for a programme, you agree to these terms. If you do not agree, please do not use the website.',
			),
			'sections' => array(
				array(
					'id' => 'about-us',
					'title' => 'About Us',
					'html' => '<p>This website is operated by JoDyn Advisory and Consulting [RC/BN number], [office address, Nigeria]. References to "JoDyn", "we", "us" or "our" mean JoDyn Advisory and Consulting.</p>',
				),
				array(
					'id' => 'use-of-the-website',
					'title' => 'Use of the Website',
					'html' => '<p>You may use this website for lawful purposes only. You agree not to:</p><ul><li>use the website in any way that breaches applicable laws or regulations;</li><li>submit false or misleading information through our forms;</li><li>attempt to gain unauthorised access to the website, its server or any connected system; or</li><li>copy, reproduce or distribute website content for commercial purposes without our written permission.</li></ul><p>We aim to keep the information on this website accurate and up to date, but we do not guarantee that it is complete or error-free. Programme details, dates and fees may change.</p>',
				),
				array(
					'id' => 'intellectual-property',
					'title' => 'Intellectual Property',
					'html' => '<p>All content on this website, including text, graphics, the JoDyn name and logo, and all programme materials provided during our training, is owned by or licensed to JoDyn Advisory and Consulting and is protected by intellectual property laws. Programme materials are provided for the personal use of participants and their organisations and may not be reproduced, shared or used to deliver training without our written permission.</p>',
				),
				array(
					'id' => 'programme-registration-and-payment',
					'title' => 'Programme Registration and Payment',
					'html' => '<ul><li>A registration is received when you complete the registration form and confirmed once full payment is received.</li><li>Fees are shown in Nigerian Naira (₦) and are [inclusive / exclusive] of applicable taxes.</li><li>Payment must be received [before the programme start date / within X days of registration]. We may release unpaid seats to other participants.</li><li>Group discounts apply only where the stated number of participants from the same organisation register for the same programme and pay together.</li></ul>',
				),
				array(
					'id' => 'cancellations',
					'title' => 'Cancellations, Transfers and Refunds',
					'html' => '<h3>Cancellation by participants</h3><ul><li>Cancellations received in writing at least [10] working days before the programme start date qualify for [a full refund / a refund less a ₦X administrative fee].</li><li>Cancellations received less than [10] working days before the start date [are not refundable, but the fee may be transferred to a future programme within 12 months].</li><li>Participants who do not attend without notice are not entitled to a refund.</li></ul><h3>Substitutions</h3><p>You may send a substitute participant from the same organisation at no extra cost, provided you notify us before the programme starts.</p><h3>Changes by JoDyn</h3><p>We reserve the right to change a programme\'s date, venue, format or facilitator, or to cancel a programme if necessary. If we cancel a programme, you may choose a full refund or a transfer to a future date. We are not responsible for travel or accommodation costs you incur.</p>',
				),
				array(
					'id' => 'online-programmes',
					'title' => 'Online Programmes',
					'html' => '<ul><li>Participants are responsible for having a suitable device and a stable internet connection.</li><li>Joining links are for the registered participant only and must not be shared.</li><li>Sessions may not be recorded by participants without our permission. Where we record a session, participants will be informed in advance.</li></ul>',
				),
				array(
					'id' => 'participant-conduct',
					'title' => 'Participant Conduct',
					'html' => '<p>We expect all participants to treat facilitators and fellow participants with respect. We may remove any participant whose behaviour disrupts a programme, without a refund.</p>',
				),
				array(
					'id' => 'certificates',
					'title' => 'Certificates',
					'html' => '<p>Certificates of participation are issued to participants who attend [at least 80%] of a programme. A certificate confirms participation and does not represent a professional qualification unless expressly stated.</p>',
				),
				array(
					'id' => 'advisory-systems-audit-and-tax-services',
					'title' => 'Advisory, Systems Audit and Tax Services',
					'html' => '<p>Advisory, systems audit and tax engagements are governed by a separate written proposal or engagement letter, which will set out the scope, deliverables, timelines and fees. These Terms of Use do not create an engagement.</p>',
				),
				array(
					'id' => 'limitation-of-liability',
					'title' => 'Limitation of Liability',
					'html' => '<p>Our training, advisory, systems audit and tax services are provided with reasonable skill and care. However, the outcomes of applying the knowledge, tools or recommendations we provide depend on many factors outside our control. To the extent permitted by law, JoDyn is not liable for any indirect or consequential loss arising from the use of this website or our services, and our total liability for any programme is limited to the fee paid for that programme.</p>',
				),
				array(
					'id' => 'links-to-other-websites',
					'title' => 'Links to Other Websites',
					'html' => '<p>This website may link to third-party websites. We have no control over their content and are not responsible for them.</p>',
				),
				array(
					'id' => 'privacy',
					'title' => 'Privacy',
					'html' => '<p>Your use of this website is also governed by our <a href="{privacy_url}">Privacy Policy</a> and <a href="{cookie_url}">Cookie Policy</a>, which explain how we handle your personal data.</p>',
				),
				array(
					'id' => 'governing-law',
					'title' => 'Governing Law',
					'html' => '<p>These terms are governed by the laws of the Federal Republic of Nigeria. Any dispute will be subject to the jurisdiction of the courts of Nigeria.</p>',
				),
				array(
					'id' => 'changes-to-these-terms',
					'title' => 'Changes to These Terms',
					'html' => '<p>We may update these terms from time to time. The current version will always be available on this page.</p>',
				),
				array(
					'id' => 'contact-us',
					'title' => 'Contact Us',
					'html' => '<p>For questions about these terms, contact us at {email} or {phone}.</p>',
				),
			),
		),
		'cookie-policy' => array(
			'title' => 'Cookie Policy',
			'date' => '[DD Month YYYY]',
			'intro' => array(
				'This Cookie Policy explains how JoDyn Advisory and Consulting ("JoDyn", "we", "us" or "our") uses cookies and similar technologies on our website, and how you can control them. It should be read with our <a href="{privacy_url}">Privacy Policy</a>.',
			),
			'sections' => array(
				array(
					'id' => 'what-cookies-are',
					'title' => 'What Cookies Are',
					'html' => '<p>Cookies are small text files that a website stores on your device when you visit it. They let the website work properly, remember your choices and, in some cases, understand how visitors use it. Similar technologies, such as your browser\'s local storage, work in much the same way, and this policy covers them too.</p>',
				),
				array(
					'id' => 'the-cookies-we-use',
					'title' => 'The Cookies We Use',
					'html' => '<p>We keep cookies to a minimum.</p><h3>Essential cookies</h3><p>These are needed for the website to work and are always on.</p><ul><li><b>jodyn_consent:</b> remembers whether you accepted or declined analytics cookies, so we do not ask again on every page. Kept for 12 months.</li><li><b>WordPress sign-in cookies:</b> set only when our staff sign in to manage the site. Visitors who browse the site, register for a programme or send us a message do not receive them.</li></ul><h3>Analytics cookies</h3><p>With your consent, we use Google Analytics to understand which pages are visited, how long visitors stay and how they found us, so we can improve the site. Google Analytics collects information such as the pages you visit, your browser and device type and your approximate location. It does not tell us who you are.</p><ul><li><b>_ga:</b> distinguishes one visitor from another. Kept for 2 years.</li><li><b>_ga_[ID]:</b> keeps track of your visit. Kept for 2 years.</li></ul><p>These cookies are only set if you choose Accept in our cookie banner. If you choose Essential only, Google Analytics does not load at all.</p>',
				),
				array(
					'id' => 'third-party-services',
					'title' => 'Third-Party Services',
					'html' => '<p>Our website loads its font from Google Fonts. To deliver the font, Google receives your IP address and browser details. Google Fonts does not set cookies. You can read how Google handles this information in <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google\'s privacy policy</a>.</p><p>Links to our social media pages take you to those platforms, which have their own cookie policies.</p>',
				),
				array(
					'id' => 'how-to-control-cookies',
					'title' => 'How to Control Cookies',
					'html' => '<p>You can change your choice at any time using the <a href="{cookie_url}#how-to-control-cookies" data-jd-cookie-settings>Cookie settings</a> link at the bottom of every page. If you withdraw your consent, Google Analytics stops loading from your next page view.</p><p>You can also view, block or delete cookies through your browser settings. Most browsers let you block cookies from particular websites or from third parties. Blocking essential cookies may stop parts of the website from working.</p>',
				),
				array(
					'id' => 'changes-to-this-policy',
					'title' => 'Changes to This Policy',
					'html' => '<p>We may update this Cookie Policy when the cookies we use change. The latest version will always be available on this page, with the effective date shown at the top.</p>',
				),
				array(
					'id' => 'contact-us',
					'title' => 'Contact Us',
					'html' => '<p>If you have any questions about our use of cookies, please contact us at {privacy_email} or {phone}.</p>',
				),
			),
		),
	);
	return isset( $policies[ $slug ] ) ? $policies[ $slug ] : null;
}
