/**
 * Builds dist/jodyn-articles.xml from content/articles/*.md: one WordPress import file (WXR) with
 * every article, its category, summary and featured photo, ready for Tools → Import → WordPress
 * on the live site. Usage: npm run articles
 *
 * Markdown it understands (content/articles/README.md): ## and ### headings, paragraphs, - and
 * 1. lists, **bold**, [links](url), a "> **Related …**" quote (the green box with a button) and a
 * final "## Key takeaways" list (the takeaways box). Photos come from media/insights on GitHub.
 */
const fs = require('fs');
const path = require('path');

const ROOT = path.join(__dirname, '..');
const DIR = path.join(ROOT, 'content', 'articles');
const OUT = path.join(ROOT, 'dist', 'jodyn-articles.xml');
const SITE = 'https://jodynconsulting.com';
const PHOTOS = 'https://raw.githubusercontent.com/Fagbayibo/jodyn-consulting-wp-theme/main/media/insights/';
// Newest first, all dated before the articles already on the live site, so those stay on top.
const FIRST_DATE = new Date(Date.UTC(2026, 9, 28, 9, 0, 0));
const DAYS_APART = 4;

const esc = (s) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
const cdata = (s) => '<![CDATA[' + s.replace(/]]>/g, ']]]]><![CDATA[>') + ']]>';

function inline(text) {
	return esc(text)
		.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
		.replace(/\[([^\]]+)\]\(([^)]+)\)/g, (m, label, url) => {
			const outside = /^https?:\/\//.test(url) && !url.startsWith(SITE);
			return `<a href="${url}"${outside ? ' target="_blank" rel="noreferrer noopener"' : ''}>${label}</a>`;
		});
}

const para = (html) => `<!-- wp:paragraph --><p>${html}</p><!-- /wp:paragraph -->`;
const heading = (level, text) =>
	level === 2
		? `<!-- wp:heading --><h2 class="wp-block-heading">${inline(text)}</h2><!-- /wp:heading -->`
		: `<!-- wp:heading {"level":${level}} --><h${level} class="wp-block-heading">${inline(text)}</h${level}><!-- /wp:heading -->`;
function list(items, ordered) {
	const tag = ordered ? 'ol' : 'ul';
	const lis = items.map((i) => `<!-- wp:list-item --><li>${inline(i)}</li><!-- /wp:list-item -->`).join('');
	return `<!-- wp:list${ordered ? ' {"ordered":true}' : ''} --><${tag} class="wp-block-list">${lis}</${tag}><!-- /wp:list -->`;
}

/** The "> **Related programme**" quote: the green box with a button. */
function callout(lines) {
	const inner = [];
	for (const line of lines) {
		let m;
		if ((m = line.match(/^\*\*(.+)\*\*$/))) inner.push(para(inline(m[1])));
		else if ((m = line.match(/^###\s+(.+)$/))) inner.push(heading(3, m[1]));
		else if ((m = line.match(/^\[([^\]]+)\]\(([^)]+)\)$/)))
			inner.push(`<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="${m[2]}">${esc(m[1])}</a></div><!-- /wp:button --></div><!-- /wp:buttons -->`);
		else if (line) inner.push(para(inline(line)));
	}
	return `<!-- wp:group {"className":"is-style-jodyn-callout","layout":{"type":"constrained"}} --><div class="wp-block-group is-style-jodyn-callout">${inner.join('')}</div><!-- /wp:group -->`;
}

function takeaways(items) {
	return `<!-- wp:group {"className":"is-style-jodyn-takeaways","layout":{"type":"constrained"}} --><div class="wp-block-group is-style-jodyn-takeaways">${heading(3, 'Key takeaways')}${list(items, false)}</div><!-- /wp:group -->`;
}

function toBlocks(md) {
	const lines = md.split(/\r?\n/);
	const out = [];
	for (let i = 0; i < lines.length; ) {
		const line = lines[i];
		if (!line.trim()) { i++; continue; }
		let m;
		if (/^>/.test(line)) {
			const quote = [];
			while (i < lines.length && /^>/.test(lines[i])) quote.push(lines[i++].replace(/^>\s?/, '').trim());
			out.push(callout(quote));
		} else if (/^## Key takeaways\s*$/.test(line)) {
			i++;
			const items = [];
			while (i < lines.length && (!lines[i].trim() || /^- /.test(lines[i]))) {
				if (lines[i].trim()) items.push(lines[i].slice(2));
				i++;
			}
			out.push(takeaways(items));
		} else if ((m = line.match(/^(#{2,3})\s+(.+)$/))) {
			out.push(heading(m[1].length, m[2]));
			i++;
		} else if (/^(- |\d+\. )/.test(line)) {
			const ordered = /^\d+\. /.test(line);
			const items = [];
			while (i < lines.length && (ordered ? /^\d+\. / : /^- /).test(lines[i])) items.push(lines[i++].replace(/^(- |\d+\. )/, ''));
			out.push(list(items, ordered));
		} else {
			const text = [];
			while (i < lines.length && lines[i].trim() && !/^(#|>|- |\d+\. )/.test(lines[i])) text.push(lines[i++].trim());
			out.push(para(inline(text.join(' '))));
		}
	}
	return out.join('\n\n');
}

function parse(file) {
	const raw = fs.readFileSync(path.join(DIR, file), 'utf8');
	const m = raw.match(/^---\r?\n([\s\S]*?)\r?\n---\r?\n([\s\S]*)$/);
	if (!m) throw new Error(`${file}: no front matter`);
	const meta = {};
	for (const line of m[1].split(/\r?\n/)) {
		const kv = line.match(/^(\w+):\s*(.*)$/);
		if (kv) meta[kv[1]] = kv[2].replace(/^"(.*)"$/, '$1');
	}
	for (const key of ['title', 'slug', 'category', 'excerpt', 'photo']) if (!meta[key]) throw new Error(`${file}: no ${key}`);
	if (!CATEGORIES[meta.category]) throw new Error(`${file}: unknown category ${meta.category}`);
	return { ...meta, body: toBlocks(m[2]) };
}

// Category names as on the site (inc/insights.php), in case one has to be created.
const CATEGORIES = { strategy: 'Strategy', 'systems-audit': 'Systems Audit', tax: 'Tax', 'people-culture': 'People & Culture', performance: 'Performance', leadership: 'Leadership', 'learning-development': 'Learning & Development', 'case-studies': 'Case Studies', 'news-events': 'News & Events' };

const stamp = (d) => d.toISOString().slice(0, 19).replace('T', ' ');
const files = fs.readdirSync(DIR).filter((f) => /^\d+-.+\.md$/.test(f)).sort();
const items = [];
files.forEach((file, n) => {
	const a = parse(file);
	const postId = 9001 + n * 2;
	const photoId = postId + 1;
	const date = new Date(FIRST_DATE.getTime() - n * DAYS_APART * 86400000);
	const photoName = a.photo.replace(/\.[a-z]+$/, '');
	items.push(`	<item>
		<title>${cdata(a.title)}</title>
		<dc:creator>${cdata('jodyn')}</dc:creator>
		<content:encoded>${cdata(a.body)}</content:encoded>
		<excerpt:encoded>${cdata(a.excerpt)}</excerpt:encoded>
		<wp:post_id>${postId}</wp:post_id>
		<wp:post_date>${cdata(stamp(new Date(date.getTime() + 3600000)))}</wp:post_date>
		<wp:post_date_gmt>${cdata(stamp(date))}</wp:post_date_gmt>
		<wp:comment_status>${cdata('closed')}</wp:comment_status>
		<wp:ping_status>${cdata('closed')}</wp:ping_status>
		<wp:post_name>${cdata(a.slug)}</wp:post_name>
		<wp:status>${cdata('publish')}</wp:status>
		<wp:post_parent>0</wp:post_parent>
		<wp:menu_order>0</wp:menu_order>
		<wp:post_type>${cdata('post')}</wp:post_type>
		<wp:is_sticky>0</wp:is_sticky>
		<category domain="category" nicename="${a.category}">${cdata(CATEGORIES[a.category] || a.category)}</category>
		<wp:postmeta><wp:meta_key>${cdata('_thumbnail_id')}</wp:meta_key><wp:meta_value>${cdata(String(photoId))}</wp:meta_value></wp:postmeta>
	</item>
	<item>
		<title>${cdata(photoName)}</title>
		<dc:creator>${cdata('jodyn')}</dc:creator>
		<content:encoded>${cdata('')}</content:encoded>
		<excerpt:encoded>${cdata('')}</excerpt:encoded>
		<wp:post_id>${photoId}</wp:post_id>
		<wp:post_date>${cdata(stamp(new Date(date.getTime() + 3600000)))}</wp:post_date>
		<wp:post_date_gmt>${cdata(stamp(date))}</wp:post_date_gmt>
		<wp:post_name>${cdata(photoName)}</wp:post_name>
		<wp:status>${cdata('inherit')}</wp:status>
		<wp:post_parent>${postId}</wp:post_parent>
		<wp:post_type>${cdata('attachment')}</wp:post_type>
		<wp:attachment_url>${cdata(PHOTOS + a.photo)}</wp:attachment_url>
		<wp:postmeta><wp:meta_key>${cdata('_wp_attachment_image_alt')}</wp:meta_key><wp:meta_value>${cdata(a.title)}</wp:meta_value></wp:postmeta>
	</item>`);
	console.log(`${file} → ${a.slug} (${a.category}, ${stamp(date).slice(0, 10)})`);
});

const xml = `<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<title>JoDyn Advisory &amp; Consulting</title>
	<link>${SITE}</link>
	<description>Insights articles</description>
	<language>en-GB</language>
	<wp:wxr_version>1.2</wp:wxr_version>
	<wp:base_site_url>${SITE}</wp:base_site_url>
	<wp:base_blog_url>${SITE}</wp:base_blog_url>
	<wp:author><wp:author_id>1</wp:author_id><wp:author_login>${cdata('jodyn')}</wp:author_login><wp:author_email>${cdata('info@jodynconsulting.com')}</wp:author_email><wp:author_display_name>${cdata('JoDyn')}</wp:author_display_name><wp:author_first_name>${cdata('')}</wp:author_first_name><wp:author_last_name>${cdata('')}</wp:author_last_name></wp:author>
${items.join('\n')}
</channel>
</rss>
`;
fs.mkdirSync(path.dirname(OUT), { recursive: true });
fs.writeFileSync(OUT, xml);
console.log(`\n${files.length} articles → ${path.relative(ROOT, OUT)} (${Math.round(xml.length / 1024)} KB)`);
