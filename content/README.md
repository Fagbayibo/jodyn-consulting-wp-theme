# Site copy

One file per page. This is the single source of truth for every word on the site: nothing gets built until its section's copy is here and approved.

## How it works

1. Paste the client's copy under each section heading (or drop the .docx in this folder and it gets split into these files).
2. Before a section is designed, its copy is pulled from here and shown for approval.
3. Once approved, mark the section `Status: approved`.

## Format

```md
## Section name
Status: draft | approved

Eyebrow: Short label above the heading
Heading: The main line
Text: Supporting paragraph(s)
Button: Label → /link/
```

Use only the fields a section needs. Leave a section out entirely if the page doesn't have it, or add new ones in the order they appear on the page.

## Pages

- [home.md](pages/home.md) — rebalanced to ~60% advisory, audit and tax / 40% training; the client's original is in [source/](source/)
- [about.md](pages/about.md) — About us, trimmed to 7 sections and rebalanced to the four service lines
- [advisory.md](pages/advisory.md) — Advisory service page (/services/advisory/), 6 sections (FAQs moved to the FAQs page); five advisory services including Learning & Development Advisory
- [training.md](pages/training.md) — Training programmes (/training/programmes/), 6 sections; request form and FAQs moved out
- [training-calendar.md](pages/training-calendar.md) — Training calendar (/training/calendar/), 5 sections; client copy for Registration, Insights, Contact and the legal pages is in [source/](source/)
- [registration.md](pages/registration.md) — Registration (/register/), hero, how it works, form, on-screen confirmation and confirmation email
- [contact.md](pages/contact.md) — Contact (/contact-us/), hero, ways to reach us, form, office details, follow us, quick answers
- [systems-audit.md](pages/systems-audit.md) — Systems Audit service page (/services/systems-audit/), 6 sections; no client original, written from the confirmed home page scope (FAQs go to the FAQs page)
- [tax-consultancy.md](pages/tax-consultancy.md) — Tax Consultancy service page (/services/tax-consultancy/), 6 sections; no client original, written from the confirmed home page scope (FAQs go to the FAQs page)
- [legal.md](pages/legal.md) — Privacy Policy (/privacy-policy/), Terms of Use (/terms/) and Cookie Policy (/cookie-policy/), one shared template; the Cookie Policy is new
- [programme.md](pages/programme.md) — Single programme page (/programme/[name]/), one template filled from each programme's fields; the client's Leadership Essentials example
- [faqs.md](pages/faqs.md) — FAQs (/faqs/), every page's FAQs together, grouped by topic; replaces Careers in the header menu
- [insights.md](pages/insights.md) — Insights (/insights/), the blog: hero, featured article, categories, article grid, launch articles and the single article template

Other pages are added here as the client sends their copy.
