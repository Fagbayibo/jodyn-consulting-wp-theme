# Training calendar

URL: /training/calendar/
Client's original: [source/calendar-register-insights-contact-client-original.md](../source/calendar-register-insights-contact-client-original.md) (Page 1)

**Balance:** a training page, so it is all training. The client's words are kept as written wherever they work. Every change from the original is marked `> Changed:`. Gaps the client must fill are marked `[PLACEHOLDER]`.

**Trimmed from 6 sections to 5** (plus the footer card): the closing call to action becomes the footer's lime card, as on every other page.

---

## SEO
Status: approved

Meta title: Training Calendar | Upcoming Programmes in Nigeria | JoDyn
Meta description: View upcoming leadership, management, HR and professional skills programmes by JoDyn Advisory and Consulting. Register for in-person and online training.
Focus keyword: training calendar Nigeria
Supporting keywords: upcoming training programmes Nigeria; leadership training dates; online training courses Nigeria; professional development courses Lagos

> Changed: URL is /training/calendar/ (client wrote /training-calendar), to sit beside /training/programmes/ and match the links already on the home, Training and footer.

---

## 1. Hero
Status: approved

Eyebrow: Training calendar
Heading: Upcoming Training Programmes
Text: Explore our upcoming open programmes in leadership, management, people and professional skills. Choose a date and format that suits you, and secure your seat today.
Button: Download Training Calendar (PDF) → #download (section 4)

---

## 2. Filter bar
Status: approved

Search placeholder: Search programmes…
Category: All categories, Leadership & Management, Strategy & Execution, HR & People Management, Personal Effectiveness, Customer Service & Business Development, Project Management & Operations
Format: All formats, In-person, Online, Hybrid
Month: All months, then the next 12 months
Button: Clear filters

> Changed: the six categories are the same six areas as the Training programmes page (same programmes, shorter names), so a programme sits in the same place on both pages.

---

## 3. Programme list
Status: approved

Eyebrow: Open programmes
Heading: Find Your Next Programme
Text: All programmes are facilitated by experienced professionals and include course materials, practical tools and a certificate of participation. Seats are limited to keep each session interactive.

Each programme card shows:
- Category tag, e.g. "Leadership & Management"
- Programme title, linked to the programme's own page
- Date and duration, e.g. "18–19 November 2026 · 2 days"
- Format and venue, e.g. "In-person · Ibadan" or "Online · Live via Zoom"
- Fee, e.g. "₦XX,XXX per participant"
- Status badge: "Seats available", "Few seats left" or "Fully booked"
- Buttons: Register Now (registration page, programme already selected) and View Details

Pagination: the list shows a set number of programmes per page, with page numbers and Previous / Next below it. Filtering goes back to page 1.

> Changed (user, 2026-10-24): pagination added.

When no programmes match the filters:
Heading: No programmes match your search right now.
Text: Try changing your filters, or tell us what you are looking for, and we will let you know when it is scheduled. We can also deliver any programme exclusively for your team.
Button: Request a Programme → /contact-us/?enquiry=training

> Build note: each programme in the admin gets two new fields, an end date (for "18–19 November · 2 days") and a seats status (the badge). "Fully booked" turns Register Now off for that run.
> Build note: Register Now goes to the Registration page (next page in this document). Until that page is built it uses the programme's own registration link, as it does now.

---

## 4. Download the calendar
Status: approved

Eyebrow: Plan ahead
Heading: Download Our Training Calendar
Text: Get the full schedule of upcoming programmes, with dates, formats and fees, in one easy-to-share PDF. Ideal for HR and L&D teams planning the year's training.
Fields: Full name *, Email address *, Organisation, Job title
Checkbox: Send me updates on new programmes and insights (optional)
Button: Download Now

> Changed: shown on the page itself, not in a pop-up. The pop-up on the home and Training pages gets the same four fields and checkbox, so every download is recorded the same way.
> Build note: "emails a copy" needs the site to send email. The download starts straight away; the emailed copy works once the live site has email set up (an SMTP plugin). [Confirm with the client]

---

## 5. Group and in-house training
Status: approved

Heading: Training a Team?
Text: Save with group registration, or bring any programme in-house. We can deliver it exclusively for your organisation, adapted to your industry and scheduled around your operations.
- Group discount: [X]% off when [three] or more participants from the same organisation register for the same programme.
- In-house delivery: any programme, at your premises, a venue of your choice or online.
Button: Request In-House Training → /contact-us/?enquiry=training

> Changed: the button goes to the Contact form with Training selected. The client linked it to the request form on the Training page (section 8), which moved to the Contact page.
> [PLACEHOLDER] the discount percentage and the minimum group size.
> Build note: both are set in Customize → Training calendar (Group discount (%) and Group size for the discount). Until then the page shows [X] and [three].

---

## Footer card (closing call to action)
Status: approved

Heading: Can't Find What You Need?
Text: Our team can recommend the right programme for your goals or design one specifically for your organisation.
Button: Talk to Our Training Team → /contact-us/?enquiry=training

> Changed: the client's section 6 becomes the footer's lime card, as on every other page.
