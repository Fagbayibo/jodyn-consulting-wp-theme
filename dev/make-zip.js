/**
 * Builds dist/jodyn.zip, the installable theme.
 *
 * The zip unpacks to a single jodyn/ folder, so WordPress replaces the installed theme in place
 * on update (GitHub's own "Source code" zip unpacks to jodyn-consulting-wp-theme-x.y.z/ and would
 * install as a second theme). archiver writes forward-slash paths; zips made with PowerShell use
 * backslashes, which Linux hosts unpack as flat files with "\" in their names.
 *
 * Usage: npm run zip
 */
const fs = require('fs');
const path = require('path');
const archiver = require('archiver');

const root = path.resolve(__dirname, '..');
const themeDir = path.join(root, 'wp-content', 'themes', 'jodyn');
const distDir = path.join(root, 'dist');
const out = path.join(distDir, 'jodyn.zip');

function themeVersion() {
  const css = fs.readFileSync(path.join(themeDir, 'style.css'), 'utf8');
  const m = /^\s*Version:\s*(\S+)/m.exec(css);
  if (!m) throw new Error('No "Version:" line in wp-content/themes/jodyn/style.css');
  return m[1];
}

function makeZip() {
  fs.mkdirSync(distDir, { recursive: true });
  return new Promise((resolve, reject) => {
    const output = fs.createWriteStream(out);
    const archive = archiver('zip', { zlib: { level: 9 } });
    output.on('close', () => resolve({ file: out, bytes: archive.pointer() }));
    archive.on('warning', reject);
    archive.on('error', reject);
    archive.pipe(output);
    archive.glob('**/*', {
      cwd: themeDir,
      dot: false,
      ignore: ['**/.DS_Store', '**/Thumbs.db', '**/*.map', '**/node_modules/**'],
    }, { prefix: 'jodyn' });
    archive.finalize();
  });
}

module.exports = { makeZip, themeVersion, out };

if (require.main === module) {
  makeZip().then(({ file, bytes }) => {
    console.log(`JoDyn ${themeVersion()} -> ${path.relative(root, file)} (${(bytes / 1048576).toFixed(1)} MB)`);
  }).catch((err) => {
    console.error(err.message || err);
    process.exit(1);
  });
}
