# Open questions before launch

Every gap left while building the site, in one place (2026-10-27, after the last page). Each line says where the answer goes. Once a detail is entered in **Customize**, the site uses it everywhere at once; the copy files (content/pages/*.md) only need updating where the wording itself changes.

---

## 1. Questions for the client

### Services and offers
- [ ] **Is the first consultation free?** The Contact page and the FAQs say "Yes… free, with no obligation" in brackets until confirmed. *(contact.md section 6, faqs.md General)*
- [ ] **Does JoDyn also do statutory financial audits?** The FAQ says a systems audit is not one; confirm whether JoDyn offers both. *(systems-audit.md FAQs, faqs.md)*
- [ ] **Which taxes does JoDyn handle?** The page names company income tax, VAT, PAYE and withholding tax. Add any others (personal income tax, capital gains tax, stamp duties…). *(tax-consultancy.md, faqs.md)*

### Training: money and rules
- [ ] **Group discount on the registration total.** The form says "Group discount applies: X% off", but the total on the form, the saved registration and the confirmation email show the full fee for every participant. Should the total take the discount off, or should the email say the discount is applied on the invoice?
- [ ] **How can people pay?** Bank transfer only, or online payment too? *(training.md FAQs → faqs.md Training)*
- [ ] **Group discount:** what percentage, and from how many participants (currently [X]% and [three])? → **Customize → Training calendar** (Group discount, Group size). *(training-calendar.md section 5, faqs.md, Terms §4)*
- [ ] **Cancellations and refunds** (Terms §5, and the Training FAQ must use the same numbers):
  - notice needed for a refund: [10] working days?
  - a full refund, or a refund less a ₦X administrative fee?
  - late cancellations: not refundable but transferable to a future programme within 12 months?
- [ ] **Fees:** shown inclusive or exclusive of tax? *(Terms §4)*
- [ ] **Payment deadline:** before the start date, or within X days of registering? *(Terms §4)*
- [ ] **Certificates:** minimum attendance, e.g. 80%? *(Terms §8)*

### Contact and response
- [x] **Phone number:** +234 803 726 6806 (received 2026-10-28). Setup version 7 enters it in Customize → Contact & social while that field is empty.
- [ ] **Office address:** received as "2C Crocxley Drive, Cardogan Place, Lagos" (2026-10-28) and entered the same way. Confirm the spelling (Croxley? Cadogan?) and the area (e.g. Ikoyi, Lekki).
- [x] **WhatsApp:** the same number, +234 803 726 6806 (confirmed 2026-10-28). Setup version 7 enters it too, which shows the Chat on WhatsApp button.
- [x] **Response time** for enquiries: 48 to 72 hours (client, 2026-10-30) → **Customize → Contact & social → Response time**. Used on the Contact page, the FAQs and the FAQs footer card.
- [ ] **One email and phone for everything, or separate ones** for Advisory/Audit/Tax, Training and General enquiries? → **Customize → Contact & social** (each line's email and phone are optional and fall back to the main ones).
- [ ] **A separate training email** (e.g. training@jodynconsulting.com)? → **Customize → Training registration → Training team email**.

### Policies
- [ ] **Effective date** for the Privacy Policy, Terms of Use and Cookie Policy.
- [ ] **Registered business name and RC/BN number.** *(Privacy §1, Terms §1)*
- [ ] **Website address:** confirm www.jodynconsulting.com. *(Terms, opening paragraph)*
- [ ] **How long records are kept:** enquiries [two] years, registrations [six] years, engagement records [six] years. *(Privacy §6)* The site doesn't delete old form entries on its own yet: once these are confirmed, either the team deletes old ones under Enquiries, Registrations and Calendar downloads, or the theme can do it automatically.
- [ ] **Data requests:** answered within [30] days? *(Privacy §9)* Tools → Export Personal Data and Erase Personal Data now cover the three forms: erasing deletes someone's enquiries and calendar downloads and keeps their registrations for the accounting records (to delete by hand if wanted).
- [x] **Analytics:** Google Analytics (GA4) G-TKHX1VKV4J, entered by setup version 9 (2026-10-30). The cookie banner shows, and Analytics loads only after Accept (checked live: page views reach Google). Google's "Set up a Google tag" checker says "not detected" because it never clicks Accept; that is expected, so don't paste Google's snippet into the site. Switching to Google's advanced consent mode would clear the warning, but Google would then get cookie-free signals from every visitor and the Cookie Policy would need rewording; kept as is.
- [ ] **A Nigerian lawyer reviews all three policies** before launch (the client's drafts say they are not legal advice).

---

## 2. Details to enter in Customize

Appearance → Customize. Empty fields are hidden or show their [placeholder] until filled.

| Section | Fields |
|---|---|
| Contact & social | Office address (filled, see above) · Phone (filled) · Email · WhatsApp (filled) · Office hours · Response time · Privacy email (optional) · each enquiry line's email and phone (optional) · LinkedIn, Instagram, Facebook, X, YouTube links |
| Training registration | Training team email · Bank name · Account name · Account number · Days before the programme that joining instructions are sent |
| Training calendar | Calendar PDF · Group discount (%) · Group size for the discount |
| Analytics | Google Analytics Measurement ID |
| Header | Button label and link (already "Contact us") |
| Home hero | Hero film (already set) |

Also in the admin: **Programmes**, the dates, fees, format and venue for each run (only runs with a date appear on the calendar).

---

## 3. Wording we wrote: client to confirm

- [ ] **Systems Audit page:** the whole page (no client original). *(systems-audit.md)*
- [ ] **Tax Consultancy page:** the whole page (no client original). *(tax-consultancy.md)*
- [ ] **Home, Why JoDyn:** "Independent and Confidential". *(home.md section 7)*
- [ ] **About, Our story:** the two added gap lines on controls and tax. *(about.md section 2)*
- [ ] **Privacy Policy:**
  - "Engagement information"
  - sharing with tax authorities
  - the paragraph on transfers outside Nigeria
  - how long engagement records are kept
  - Google Analytics named as a provider (Google Fonts no longer is: the font is served from the website)
- [ ] **Cookie Policy:** the whole page.
- [ ] **Cookie banner:** the text, and the "Cookies" label.
- [ ] **Legal pages:**
  - the help card questions ("Questions about your data?" and the others)
  - the one-line descriptions under "Our other policies"
- [ ] **FAQs:**
  - the hero line
  - the "Still Have a Question?" footer card
  - the merged General answers (confidentiality, small businesses)
  - "Search the questions" and the no-results message

---

## 4. Content still to come

- [ ] **Insights articles** (800–1,500 words each). The sample posts on the dev site are placeholders. 42 suggested titles, six for each topic category, are in [blog-titles.md](blog-titles.md).
- [ ] **Training calendar PDF** for the download.
- [ ] **Leadership photos** for an About page leadership section (low priority; left out until there are real photos).

---

## 5. Before launch (technical)

- [x] **Email sending (SMTP plugin).** Done 2026-10-30: WP Mail SMTP (free) sends through the cPanel mailbox info@jodynconsulting.com (Other SMTP, mail.jodynconsulting.com, SSL 465, Force From Email and Name). The test email reached the Gmail inbox. SPF, DKIM and DMARC were already set by the host (Truehost).
- [ ] **Bank details for registrations** → **Customize → Training registration** (bank name, account name, account number, days before the programme for joining instructions). The confirmation email shows placeholders until they are filled.
- [ ] **Test the real forms on the live site:** one contact form message and one registration, sent from a Gmail address. Each should arrive at info@, the sender should get the "Thank you" or registration email, and the entry under Enquiries or Registrations should say it was emailed.
- [ ] **Social links** → **Customize → Contact & social** once the client sends them; each footer icon shows when its link is filled.
- [ ] **info@ in Gmail:** cPanel Forwarder from info@ to the client's Gmail, plus Gmail → Settings → Accounts → "Send mail as" info@ (mail.jodynconsulting.com, 465, SSL).
- [ ] **Calendar download by email?** The download starts on the page; no copy is emailed and the team isn't notified (each download is listed under Calendar downloads). Decide whether either is wanted.
- [ ] **SEO** (plan of 2026-10-30). Already live: the site is open to search engines, the sitemap (/wp-sitemap.xml) is listed in robots.txt, and every page has a title and canonical link. Still to do:
  - **Theme (one release; recommended over a plugin):** print each page's meta title and description from the SEO blocks in the copy files, link previews for WhatsApp and LinkedIn (Open Graph), and structured data for Google (the business with its Lagos address and phone, articles, programme dates). The theme steps aside if Yoast or Rank Math is installed later. Say "build the SEO".
  - **Google Search Console:** add jodynconsulting.com, verify with the DNS record in cPanel, submit wp-sitemap.xml.
  - **Google Business Profile** for the Lagos office (Maps, and searches such as "consulting firm in Lagos"). Needs the confirmed address spelling.
  - *(Optional)* **Bing Webmaster Tools**, imported from Search Console.
  - **Ongoing:** two articles a month, each linking to a programme or service, shared on LinkedIn.
- [ ] **After updating a site, open any admin page once.** The setup then catches up: version 5 creates the FAQs page and swaps the old Careers menu item for FAQs, version 6 writes the example programme, version 7 fills in the phone, WhatsApp and address and sets the site to Lagos time (if it was still on UTC).
- [ ] **Hosting:** PHP 8.0 or newer (8.2+ recommended), WordPress 6.4 or newer, and outgoing email through SMTP. Cloudflare or a host's proxy in front of the site is fine: the forms' spam limit counts each visitor's real address.
- [ ] **GitHub account:** turn on two-factor sign-in. Sites update themselves from its releases, so whoever controls the account controls the theme.
- [ ] **Editable wording (later).** The FAQs, the three policies and the footer cards are written into the theme, so changing them needs a developer and a release. Decide with the client whether they should become editable in WordPress.
