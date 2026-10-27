# Single programme page (template)

URL pattern: /programme/[programme-name]/ · Example: /programme/leadership-essentials-for-new-managers/
Client's original: [source/legal-and-templates-client-original.md](../source/legal-and-templates-client-original.md), Part 3 (Single Programme Template), Sections 1 to 4.

**One template for all 33 programmes.** Each programme fills it from its own fields in the admin (Programmes → Edit). Any section whose field is empty is left out, so a programme with only a title and summary still makes a clean page. The client's words are kept as written. Every change is marked `> Changed:`, new text `> NEW, confirm with client`, and gaps `[PLACEHOLDER]`.

> Build notes (option B, built 2026-10-27): single-jodyn_programme.php and template-parts/programme/page.php; fields in inc/programmes.php ("Programme page" box under the editor); page logic, footer card and menu in inc/programme-page.php; programme.css and programme.js. A programme with no write-up and no date shows just the hero, the facts bar and the shared sections; with a date but no write-up, the details box stands on its own. Undated programmes only show a format once one is chosen. Setup version 6 writes the example into Leadership Essentials for New Managers while its fields are empty. On programme pages the menu marks Training as current (it used to mark Insights).

> Changed: the URL is /programme/[name]/ (the client's says /programmes/[name]). The site already uses /programme/ for these pages, and /training/programmes/ is the programmes overview, so the two don't clash.

---

## Fields in the admin
Status: approved

From the client's Section 1. ✓ = already in the admin; + = added for this page.

| Field | What goes in it | |
|---|---|---|
| Programme title | e.g. Leadership Essentials for New Managers | ✓ |
| Area | One of the six programme areas (the client's "Category") | ✓ |
| Short summary | One or two sentences, used on cards and as the meta description | ✓ (Excerpt) |
| Overview | Two or three paragraphs describing the programme | ✓ (the main editor) |
| Who should attend | One role per line | + |
| Learning outcomes | One outcome per line (the lead-in "By the end of this programme, you will be able to:" is added for you) | + |
| Programme modules | One module per line: Title: short description | + |
| Dates | Start date, end date, time | ✓ |
| Duration | e.g. 2 days | + |
| Format and venue | In-person, Online or Hybrid, and the venue or platform | ✓ |
| Fee | ₦ amount per participant (the group discount note is added from Customize) | ✓ |
| What's included | One item per line | + |
| Facilitator | Optional: name, photo, title and short bio | + |
| Brochure | Optional PDF | + |
| Seats | Seats available / Few seats left / Fully booked | ✓ |
| Programme photo | Landscape, about 1600 × 900 px | ✓ |

> Changed: "Dates and sessions" is one run per programme for now (start date, end date, time), as the calendar already works. A programme that runs again later gets its new date entered when the old one has passed. [PLACEHOLDER] if the client wants several dates on one programme page at once, that is a further change to the calendar.

---

## 1. Hero
Status: approved

Label: [Area], e.g. LEADERSHIP & MANAGEMENT DEVELOPMENT
Heading (H1): [Programme title]
Text: [Short summary]
Details strip: [Date] · [Duration] · [Format, Venue] · ₦[Fee] per participant
Button: Register Now → Registration page, this programme already selected
Button (optional): Download Brochure → the brochure PDF

Programme without a date yet (NEW, confirm with client):
- Details strip: New dates coming soon · [Duration] · [Format]
- Button: Enquire About This Programme → Contact form, Training selected, this programme named

Fully booked (NEW, confirm with client):
- A "Fully booked" tag, and the button becomes: Ask About the Next Date → Contact form, Training selected, this programme named

> NEW, confirm with client: the two cases above. The client's template only covers a programme with open seats. (On the Training page's list, undated programmes offer "Request In-House Training"; on its own page a programme also needs a way for one person to ask about the next date, hence "Enquire About This Programme".)

## 2. Programme Overview
Status: approved

Heading: Programme Overview
Text: [Overview]

## 3. Who Should Attend
Status: approved

Heading: Who Should Attend
List: [Who should attend]

## 4. Learning Outcomes
Status: approved

Heading: Learning Outcomes
Lead-in: By the end of this programme, you will be able to:
List: [Learning outcomes]

## 5. Programme Modules
Status: approved

Heading: Programme Modules
Accordion (click to expand): Module 1: [Title], then its description. One row per module.

## 6. How You Will Learn
Status: approved

The same text on every programme page.

Heading: How You Will Learn
Text: Our programmes combine short expert input with case studies, role plays, group discussions and practical exercises. Participants practise the key skills in a safe, supportive environment and receive guidance from the facilitator.

> Changed: the client's text is for the example programme ("This programme… Participants practise key skills, such as delegating and giving feedback…"). As standard text for all 33 programmes, "This programme" becomes "Our programmes" and the leadership-only example "such as delegating and giving feedback" is removed.

## 7. Programme Details box
Status: approved

Sticky on the right on desktop; at the bottom on phones (client's Section 2).

Heading: Programme Details
Rows: Dates · Duration · Time · Format · Venue · Fee · Includes
Fee line: ₦[Fee] per participant · [X]% group discount for [3]+ participants
Status tag: [Seats available / Few seats left / Fully booked]
Button: Register Now → Registration page, this programme already selected

> Changed: "Time" gets its own row (the client puts it inside Duration: "2 days, [9:00 am to 4:00 pm]"), since time is already its own field.
> Build note: the group discount line comes from Customize → Training calendar, so it is the same on every page; until it is set it shows [X]% and [3].

## 8. Facilitator (optional)
Status: approved

Heading: Your Facilitator
Name, photo, title and a short bio. Left out when no facilitator is set.

> NEW, confirm with client: the heading "Your Facilitator" (the client's template says "Facilitator: Optional profile" with no heading).

## 9. Bring This Programme to Your Team
Status: approved

Heading: Bring This Programme to Your Team
Text: Training several people at once? We can deliver this programme exclusively for your organisation, adapted to your industry, culture and goals.
Button: Request In-House Delivery → Contact form, In-House or Custom Training selected, this programme named

> Changed: the client's example opens with "Developing several new managers at once?" and ends "adapted to your industry, culture and leadership framework", which only fit the leadership example. As standard text: "Training several people at once?" and "culture and goals".
> Changed: the button goes to the Contact form (the in-house request form moved there), not "Training › Programmes, in-house request form".

## 10. FAQs
Status: approved

Standard questions about registration, payment, certificates and refunds (client's Section 2), the same approved answers as the FAQs page:
- How do I register for a programme?
- How do I pay for a programme?
- Will I receive a certificate?
- What is your cancellation and refund policy? (links to the Terms, section 5)

Link: See all FAQs → /faqs/#training

> NEW, confirm with client: the "See all FAQs" link.

## 11. Related Programmes
Status: approved

Heading: Related Programmes
Three cards from the same area: title, date (or "New dates coming soon"), format, View Programme.

> NEW, confirm with client: "View Programme" on the cards.

## Footer card
Status: approved

Heading: Can't Find What You <em>Need?</em>
Text: Our team can recommend the right programme for your goals or design one specifically for your organisation.
Button: Talk to Our Training Team → /contact-us/?enquiry=training

> Changed: not in the client's template (which ends with Related Programmes). Every page ends with the lime card, so this uses the Training Calendar's approved card.

---

## SEO pattern
Status: approved

Meta title: [Programme Title] Training in Nigeria | JoDyn
Meta description: [Short summary]. [In-person in the city / Online]. Register now.
Focus keyword: [programme topic] training Nigeria, e.g. "leadership training for new managers in Nigeria"
Schema: Course or Event schema, so dates and fees can show in Google results

> [PLACEHOLDER] part of the SEO decision on the end list (content/open-questions.md): an SEO plugin, or the theme prints these. The theme can add the Course schema itself either way.

---

## Example: Leadership Essentials for New Managers
Status: approved

The client's fully written example (Section 4). It becomes that programme's content, on the dev site and on new installs, so there is one complete page to show.

Area: Leadership & Management Development
Summary: Make a confident transition from team member to team leader. Learn how to lead former peers, delegate effectively, give feedback and motivate your team to deliver results.
Duration: 2 days
Time: [9:00 am to 4:00 pm]
Format: [In-person, Venue / Online]
Fee: ₦[Fee] per participant

Overview:
Stepping into management for the first time is one of the biggest transitions in any career. The skills that made you excellent at your job are not the same skills you need to lead others. New managers are often expected to deliver results through people from day one, with little preparation for what that really involves.

This practical two-day programme equips new and aspiring managers with the essential skills to lead with confidence. Through real workplace scenarios, practical tools and guided practice, participants learn how to set clear expectations, delegate well, hold productive conversations and build a motivated, accountable team.

Participants leave with a personal leadership action plan and a toolkit they can apply immediately with their teams.

Who should attend:
- Newly promoted supervisors, team leads and managers
- High-potential employees preparing for a leadership role
- Managers with less than two years of people-management experience
- Technical experts who now lead teams

Learning outcomes:
- Understand the shift from individual contributor to manager and what is now expected of you;
- Lead former peers with confidence and credibility;
- Set clear goals and expectations for your team;
- Delegate tasks effectively and follow up without micromanaging;
- Give constructive feedback and handle difficult conversations;
- Motivate team members and build a culture of accountability; and
- Create a personal 90-day leadership action plan.

Modules:
- Module 1: The Manager's Mindset: Understanding your new role, responsibilities and the expectations of your team and leaders.
- Module 2: Leading Former Peers: Building credibility, setting boundaries and earning respect in your new role.
- Module 3: Setting Goals and Expectations: Translating organisational goals into clear team and individual objectives.
- Module 4: Delegation That Works: What to delegate, to whom and how, plus how to follow up without micromanaging.
- Module 5: Feedback and Difficult Conversations: Practical frameworks for giving feedback and addressing underperformance.
- Module 6: Motivating and Developing Your Team: Understanding what drives people and building an engaged, accountable team.
- Module 7: Your Leadership Action Plan: Bringing it all together into a practical 90-day plan.

Includes: Course materials, practical toolkit, certificate of participation, [lunch and refreshments for in-person sessions]

Related programmes (same area): Leading High-Performance Teams · Coaching and Mentoring Skills for Managers · Emotional Intelligence for Leaders

> Build note: the related cards come from the same area automatically, so they show whichever three leadership programmes are next on the calendar; the client's three are all in that area.
