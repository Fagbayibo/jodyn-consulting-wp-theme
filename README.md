# JoDyn Advisory & Consulting — WordPress theme

Classic WordPress theme for JoDyn Advisory & Consulting.

## Layout

- `wp-content/themes/jodyn/` — the theme. Zip this folder and upload it under Appearance → Themes.
- `content/pages/` — approved page copy (the source for all on-page text).
- `dev/playground/` — local preview with WordPress Playground (blueprint + seed data).
- `media/` — sample photos and files used only by the local seed.

## Local preview

```sh
npx @wp-playground/cli@latest server --port=9400 \
  --mount=./wp-content/themes/jodyn:/wordpress/wp-content/themes/jodyn \
  --mount=./dev/playground:/wordpress/dev \
  --mount=./media:/wordpress/dev-media \
  --blueprint=./dev/playground/blueprint.json
```

Then open http://127.0.0.1:9400/. (On Git Bash for Windows, prefix the command with `MSYS_NO_PATHCONV=1`.)

## Site settings the client fills in

- **Appearance → Customize → Contact & social:** office address, phone, email, social links (empty fields are hidden).
- **Appearance → Customize → Training calendar:** the calendar PDF behind the download form.
- **Appearance → Customize → Home hero:** background video and poster.
- **Appearance → Menus:** Primary and Footer navigation.
- **Programmes** (admin menu): upcoming training programmes; the next three appear on the home page.
