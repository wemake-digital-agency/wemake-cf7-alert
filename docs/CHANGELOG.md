# Wemake CF7 Alert - Changelog

Releases and other changes, newest on top. Every entry: What, Why, Verify, Rollback, Task.
Versions before 2.18.3 have no entries here; see the git history.

## 2026-11-25 - 2.19: updates through the Wemake update server

What:
- Shared update client (`inc/class-wemake-update-client.php`, started for every request in `wemake_cf7_alert.php`) and the `Update URI` header. Removed the GitHub updater (`inc/admin_update_plugin_github.php`), the old updater (`inc/admin_update.php`, `inc/action.php`, `assets/js/admin.js`, the `wmcfa_language` script in `inc/admin.php`). Version 2.19.
- `inc/action.php` turned PHP error display on for every AJAX request of the site (`WMCFA_AJAX_DEBUG`); the file and the constant are gone.
- `inc/frontend.php`: removed the old updater's maintenance mode (the whole site showed "Briefly unavailable" while `wmcfa_maintenance_on` was set).
- `assets/css/admin.css`, `admin.sass`: removed the rules for `#wemake-cf7-alert-update` that hid WordPress' own update text on the Plugins screen.
- Release workflow `.github/workflows/release.yml` (tag-based, draft first, asset `wemake-cf7-alert.zip`) instead of `build-release.yml`. README rewritten, docs added.
Why: Decision 2026-11-25 "Updates through the Wemake update server, old copies included".
Verify: After the tag `v2.19` the GitHub release has `wemake-cf7-alert.zip` with the top folder `wemake-cf7-alert/`. Tested locally (WordPress 7.0, PHP 8.3, the update server code with `wemake-cf7-alert` in both lists): the real 2.18 old updater got 2.19 from `update.php` (main file last in the zip), removed its old files, the plugin stayed active; 2.18.3 and 2.2 with their GitHub updater installed 2.19 from the release zip; 2.19 updated to 2.19.1 through the update server; with `wmcfa_maintenance_on` = 1 the front end renders normally. PHP lint on 7.4 and 8.3.
Rollback: Publish a new release with the previous code and a higher version; on the update server remove `wemake-cf7-alert` from `WM_UPDATE_FROM_GITHUB` and `WM_CLIENT_UPDATES`.
Task: https://task.nexfield.day/t/1f244bcb-0313-55f0-8ec7-53ab75b7acfb

## 2.18.3 - required checkboxes in the popup

What: Popup validates required CF7 checkboxes (consent / terms) and shows *"אישור קבלת פרסומים ועדכונים הוא חובה"*. Before, checkboxes were skipped and the popup was empty (found on wemake.co.il/acs-plugin).
Why: Empty error popup on forms with a required consent checkbox.
Verify: Submit a form with the required checkbox unchecked: the popup shows the text.
Rollback: Not needed.
Task: -
