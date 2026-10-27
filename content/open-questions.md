# Open questions before launch

Every gap left while building the site, in one place (2026-10-27, after the last page). Each line says where the answer goes. Once a detail is entered in **Customize**, the site uses it everywhere at once; the copy files (content/pages/*.md) only need updating where the wording itself changes.

---

## 1. Questions for the client

### Services and offers
- [ ] **Is the first consultation free?** The Contact page and the FAQs say "Yes… free, with no obligation" in brackets until confirmed. *(contact.md section 6, faqs.md General)*
- [ ] **Does JoDyn also do statutory financial audits?** The FAQ says a systems audit is not one; confirm whether JoDyn offers both. *(systems-audit.md FAQs, faqs.md)*
- [ ] **Which taxes does JoDyn handle?** The page names company income tax, VAT, PAYE and withholding tax. Add any others (personal income tax, capital gains tax, stamp duties…). *(tax-consultancy.md, faqs.md)*

### Training: money and rules
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
- [ ] **Response time** for enquiries: [one working day]? → **Customize → Contact & social → Response time**. Used on the Contact page, the FAQs and the FAQs footer card.
- [ ] **One email and phone for everything, or separate ones** for Advisory/Audit/Tax, Training and General enquiries? → **Customize → Contact & social** (each line's email and phone are optional and fall back to the main ones).
- [ ] **A separate training email** (e.g. training@jodynconsulting.com)? → **Customize → Training registration → Training team email**.

### Policies
- [ ] **Effective date** for the Privacy Policy, Terms of Use and Cookie Policy.
- [ ] **Registered business name and RC/BN number.** *(Privacy §1, Terms §1)*
- [ ] **Website address:** confirm www.jodynconsulting.com. *(Terms, opening paragraph)*
- [ ] **How long records are kept:** enquiries [two] years, registrations [six] years, engagement records [six] years. *(Privacy §6)*
- [ ] **Data requests:** answered within [30] days? *(Privacy §9)*
- [ ] **Analytics:** confirm Google Analytics (GA4), then enter the Measurement ID (G-…) → **Customize → Analytics**. The cookie banner and the footer "Cookie settings" link appear once it is set.
- [ ] **A Nigerian lawyer reviews all three policies** before launch (the client's drafts say they are not legal advice).

---

## 2. Details to enter in Customize

Appearance → Customize. Empty fields are hidden or show their [placeholder] until filled.

| Section | Fields |
|---|---|
| Contact & social | Office address · Phone · Email · WhatsApp · Office hours · Response time · Privacy email (optional) · each enquiry line's email and phone (optional) · LinkedIn, Instagram, Facebook, X, YouTube links |
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
  - Google Fonts and Google Analytics named as providers
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

- [ ] **Insights articles** (800–1,500 words each). The three sample posts on the dev site are placeholders.
- [ ] **Training calendar PDF** for the download.
- [ ] **Leadership photos** for an About page leadership section (low priority; left out until there are real photos).

---

## 5. Before launch (technical)

- [ ] **Email sending (SMTP plugin).** Without it, registration confirmations, enquiry notifications and the emailed calendar copy may not arrive. A plugin such as WP Mail SMTP with the domain's mailbox (Zoho) fixes this.
- [ ] **SEO titles and descriptions.** Each copy file has an SEO block, but the theme does not print them yet. Decide: an SEO plugin (Yoast or Rank Math, then paste them in), or have the theme print them.
- [ ] **Release 0.1.7.** The legal pages, cookie banner and FAQs are pushed but not released yet.
- [ ] **After updating a site, open any admin page once.** Setup version 5 then creates the FAQs page and swaps the old Careers menu item for FAQs.
