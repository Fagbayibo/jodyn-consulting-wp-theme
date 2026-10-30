/**
 * Publishes a theme update: every WordPress site running the theme offers it under
 * Dashboard > Updates (inc/updater.php) at its next update check.
 *
 * 1. Bump "Version:" in wp-content/themes/jodyn/style.css (e.g. 0.1.0 -> 0.2.0) and commit.
 * 2. npm run release
 *
 * It refuses to run with uncommitted changes or when the tag for that version already exists,
 * then pushes main, builds dist/jodyn.zip and creates the GitHub release vX.Y.Z with the zip
 * attached. The tag must match style.css: that is what the sites compare against.
 */
const { execFileSync } = require('child_process');
const path = require('path');
const { makeZip, themeVersion } = require('./make-zip');

const root = path.resolve(__dirname, '..');
const run = (cmd, ...args) => execFileSync(cmd, args, { cwd: root, encoding: 'utf8', stdio: ['ignore', 'pipe', 'pipe'] }).trim();
const fail = (msg) => { console.error(`\nRelease stopped: ${msg}\n`); process.exit(1); };

(async () => {
  const version = themeVersion();
  if (!/^\d+\.\d+\.\d+$/.test(version)) fail(`style.css Version "${version}" is not X.Y.Z`);
  const tag = `v${version}`;

  if (run('git', 'status', '--porcelain')) fail('there are uncommitted changes. Commit them first.');
  if (run('git', 'branch', '--show-current') !== 'main') fail('switch to the main branch first.');
  if (run('git', 'tag', '-l', tag) || run('git', 'ls-remote', '--tags', 'origin', `refs/tags/${tag}`)) {
    fail(`${tag} already exists. Bump "Version:" in wp-content/themes/jodyn/style.css and commit.`);
  }

  console.log(`Pushing main...`);
  run('git', 'push', 'origin', 'main');

  console.log(`Building the zip...`);
  const { file, bytes } = await makeZip();
  console.log(`  ${path.relative(root, file)} (${(bytes / 1048576).toFixed(1)} MB)`);

  console.log(`Creating release ${tag}...`);
  const url = run('gh', 'release', 'create', tag, file, '--target', 'main', '--title', `JoDyn ${version}`, '--generate-notes');
  run('git', 'fetch', '--tags', '--quiet');

  console.log(`\nReleased ${tag}: ${url}`);
  console.log('Sites offer it within about 5 minutes (Dashboard > Updates or Appearance > Themes); GitHub can take a minute to mark it Latest.');
})().catch((err) => fail((err.stderr || err.message || String(err)).trim()));
