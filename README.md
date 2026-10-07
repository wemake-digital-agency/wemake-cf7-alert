# wemake-cf7-alert

WordPress plugin by Wemake that shows Contact Form 7 results (sent / validation errors / spam) in a popup instead of the default inline messages.

Sites update this plugin **from GitHub releases** of this repository.

## How updates work

`inc/admin_update_plugin_github.php` checks
`https://api.github.com/repos/wemake-digital-agency/wemake-cf7-alert/releases/latest`
and offers an update in WP Admin when the release tag (without the leading `v`) is greater than the installed version (`version_compare(tag, current, '>')`).

So:

- The repository must be **public** while sites update (the updater calls the GitHub API without a token).
- The **newest published release** is what sites get, and its tag must be a **higher version** than what is installed.
- Never edit plugin files directly on a site — the next update overwrites them. Fix it here and release.

## Making a release

1. Commit and push the change to `main`.
2. Pick the next tag. It must be greater than the current one, compared as versions:
   `2.18.3` → `v2.18.4` (fix) or `v2.19.0` (feature).
   Careful: `v2.2` is **lower** than `v2.18.x` (`2 < 18`), so never go back to a two-part `v2.x` tag.
3. Go to [Releases](https://github.com/wemake-digital-agency/wemake-cf7-alert/releases) → **Draft a new release**:
   - **Choose a tag** → type the new tag (e.g. `v2.18.4`) → *Create new tag on publish*, target `main`.
   - **Release title**, e.g. `v2.18.4 — short summary`.
   - **Release notes**: what changed and why (link the task / page if relevant).
   - **Publish release**.
4. The [`build-release.yml`](.github/workflows/build-release.yml) workflow then runs automatically (watch it in [Actions](https://github.com/wemake-digital-agency/wemake-cf7-alert/actions)):
   - sets `Version:` and `WMCFA_PLUGIN_VERSION` in `wemake_cf7_alert.php` to the tag number and commits it to `main` (`Update plugin version to … [skip ci]`);
   - builds `wemake-cf7-alert-<tag>.zip` and attaches it to the release.
5. Pull `main` locally afterwards — the workflow pushed a version commit.

Do not bump the version in `wemake_cf7_alert.php` by hand — the workflow does it from the tag.

## Updating a site

1. WP Admin → Plugins → **Wemake CF7 Alert** → *Update now* (may take a while to appear because of update caching; *Dashboard → Updates → Check again* forces a check).
   Or upload the release zip manually: Plugins → Add New → Upload Plugin → *Replace current with uploaded*.
2. Clear the page cache (WP Rocket / LiteSpeed): `assets/js/frontend.js` is enqueued without a version query string.
3. Check a form on the site: submit with an empty required field / unchecked required checkbox — the popup must show the error text.

## Changelog

- **2.18.3** — Popup validates required CF7 checkboxes (consent / terms) and shows *"אישור קבלת פרסומים ועדכונים הוא חובה"*. Before, checkboxes were skipped and the popup was empty (found on wemake.co.il/acs-plugin).
