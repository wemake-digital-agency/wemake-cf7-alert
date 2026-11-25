# wemake-cf7-alert

WordPress plugin by Wemake that shows Contact Form 7 results (sent / validation errors / spam) in a popup instead of the default inline messages.

- **Requires:** PHP 5.6.40+ (checked by the plugin).
- The repository is **public**: copies 2.18.1-2.18.3 and 2.2 read its releases to update.

This README is the living reference: updates, releases, updating a site. Source of truth is the code; update this file
in the same change.

## Docs

- `README.md` (this file) - updates, releases, updating a site.
- [docs/DECISIONS.md](docs/DECISIONS.md) - decisions and who made them, newest on top.
- [docs/CHANGELOG.md](docs/CHANGELOG.md) - releases and changes with Verify and Rollback, newest on top.
- [CLAUDE.md](CLAUDE.md) - rules for Claude in this repository.

## How updates work

From 2.19 the plugin updates through the Wemake update server and the standard WordPress updater (Dashboard > Updates,
the Plugins screen, WordPress auto-updates). It asks `https://repository.wemake.co.il/check.php` when WordPress reads its
update list (at most once per 12 hours, "Check again" skips the cache); the server reads the latest GitHub release of
this repository and returns a signed download link to `wemake-cf7-alert.zip`.

- Client: `inc/class-wemake-update-client.php` (`Wemake_Update_Client_1_0`), started in `wemake_cf7_alert.php` for every
  request (also cron). The canonical copy lives in `wemake-digital-agency/wemake-repository` (`client/`); copy it from
  there, don't edit it here.
- The `Update URI` header (`wemake_cf7_alert.php`) keeps WordPress from offering a wordpress.org plugin with the same slug.
- Nothing is replaced unless the whole zip arrived intact and holds `wemake_cf7_alert.php` with a plugin header;
  otherwise the installed version stays. After an update the plugin keeps its installed folder name.
- The server answers only while `wemake-cf7-alert` is in `WM_CLIENT_UPDATES` (`wemake-repository`, `public_html/inc/github.php`).
- Sites whose host blocks outgoing requests to `repository.wemake.co.il` get no updates: install new versions there by hand.

Older copies reach the new version by themselves, each in its own way:

| Installed version | How it gets the latest release |
|---|---|
| 2.18.1 - 2.18.3, 2.2 | Its own GitHub updater (`inc/admin_update_plugin_github.php`, removed in 2.19) reads `releases/latest` and offers the first `.zip` asset; the admin updates it from Dashboard > Updates (on the Plugins screen the notice text is hidden by that version's CSS) or WordPress auto-updates it. `2.2` is a lower version than `2.18.x` (`2 < 18`), so those copies are offered every release. |
| 2.13 - 2.18 | Its old updater asks `repository.wemake.co.il/update.php` from wp-admin (every 5 minutes while an admin is in wp-admin) and shows "update" under the plugin on the Plugins screen; the admin clicks it. The server offers the latest GitHub release as a zip without the top folder while `wemake-cf7-alert` is in `WM_UPDATE_FROM_GITHUB`. That updater downloads the whole zip first, then deletes the plugin files and unpacks the new ones; the server puts `wemake_cf7_alert.php` last in that zip, so an unpack that breaks halfway leaves no main file and WordPress skips the plugin. |
| 1.41 and older | No updater: install by hand. |

After that one step every copy is on 2.19+ and updates through the Wemake update server.

Never edit plugin files directly on a site: the next update overwrites them. Fix it here and release.

## Making a release

1. Bump the version in both places in `wemake_cf7_alert.php`: the `Version:` header and `WMCFA_PLUGIN_VERSION`. Add an
   entry to `docs/CHANGELOG.md`. The new number must be greater than the latest release, compared as versions
   (`2.19` → `2.19.1` fix, `2.20` feature). Never a two-part number lower than the latest (`2.2` is lower than `2.18`).
2. Merge to `main`, then push a tag with the same version: `git tag v2.20 && git push origin v2.20`.
   Don't create the release in the GitHub web UI: it would be public for a minute without `wemake-cf7-alert.zip`, and
   copies 2.18.1-2.18.3 that check in that minute fall back to GitHub's source archive (wrong folder name).
3. The `Release` workflow (`.github/workflows/release.yml`) checks that the tag matches both versions, lints the PHP,
   builds `wemake-cf7-alert.zip` (top folder `wemake-cf7-alert/`, plugin files only), creates the release as a draft with
   the zip and then publishes it as the latest release.
4. Sites see the update within 12 hours (plus at most one hour of server cache), or at once with "Check again".

## Updating a site by hand

1. WP Admin → Dashboard → Updates → **Wemake CF7 Alert** (*Check again* forces a check), or upload the release zip:
   Plugins → Add New → Upload Plugin → *Replace current with uploaded*.
2. Clear the page cache (WP Rocket / LiteSpeed): `assets/js/frontend.js` is enqueued without a version query string.
3. Check a form on the site: submit with an empty required field / unchecked required checkbox - the popup must show the error text.
