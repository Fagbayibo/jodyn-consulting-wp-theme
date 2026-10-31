# Insights articles: drafts for approval

Eight launch articles (2026-10-31), written in full from the sample posts on the preview site. Nothing here is on the live site yet. Once the wording is approved, they become one import file for the live site (Tools → Import → WordPress), each with its category, summary and photo.

| # | File | Category | Words (about) | Photo |
|---|---|---|---|---|
| 1 | [01-leadership-training-investment.md](01-leadership-training-investment.md) | Leadership | 910 | leadership-training.jpg |
| 2 | [02-systems-audit-signs.md](02-systems-audit-signs.md) | Systems Audit | 920 | systems-audit-signs.jpg |
| 3 | [03-tax-compliance-checklist.md](03-tax-compliance-checklist.md) | Tax | 1,010 | tax-compliance-checklist.jpg |
| 4 | [04-strategy-to-execution.md](04-strategy-to-execution.md) | Strategy | 830 | strategy-execution.jpg |
| 5 | [05-performance-management-system.md](05-performance-management-system.md) | Performance | 820 | performance-management.jpg |
| 6 | [06-training-needs-analysis.md](06-training-needs-analysis.md) | Learning & Development | 850 | training-needs-analysis.jpg |
| 7 | [07-capacity-building-signs.md](07-capacity-building-signs.md) | People & Culture | 800 | capacity-building.jpg |
| 8 | [08-in-person-vs-online-training.md](08-in-person-vs-online-training.md) | Learning & Development | 750 | in-person-vs-online.jpg |

Left out: **"Our 2027 Training Calendar Is Now Available"**, until the calendar PDF exists.

## Before approving

- **The tax article (3)** must be checked by a tax professional. It deliberately gives no rates, thresholds or deadlines, because those are set by law and change; it names the 2025 tax laws in force from January 2026, which the reviewer should confirm.
- **Links:** each article links to a real programme or service page on the site; article 7 links to article 6, so they go live together.
- **Claims:** no statistics, client stories or numbers are made up. Where an article speaks for JoDyn ("we"), it only describes services already on the site.
- **The leadership article (1)** keeps the client's own opening paragraph word for word.
- **Author:** each article goes out under the WordPress user who imports it. Set the name, job title and one-line bio under Users → Profile first; they show in the author box.

## How the files are written

- The top block holds the title, category, summary (shown on the cards and in Google) and photo.
- `## Heading` lines become the article's sections and its "On this page" list.
- A quoted block starting **Related programme** or **Related service** becomes the green box with a button.
- The final **Key takeaways** list becomes the takeaways box.

## Making the import file

`npm run articles` builds `dist/jodyn-articles.xml` from these files (dev/build-articles.js). Upload it on the live site under Tools → Import → WordPress (install the importer when asked), assign the posts to an existing user and tick "Download and import file attachments". Articles are dated 28 October back to 30 September, so articles already published on the site stay on top. Re-importing skips articles that already exist.
