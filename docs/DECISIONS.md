# Wemake CF7 Alert - Decisions

Newest on top. Never delete an entry; mark replaced ones `Status: superseded by <date> <title>`.

## 2026-11-25 - Updates through the Wemake update server, old copies included

Decided by: Vitalii
Context: Copies updated in three ways: 2.18.1-2.18.3 and 2.2 from the latest GitHub release (their own GitHub updater), 2.13-2.18 through the old repository.wemake.co.il updater (an admin's click on the Plugins screen), 1.41 and older not at all. Same plan as wemake-acs 1.63 the same day.
Decision: From 2.19 the plugin ships the shared Wemake update client (`inc/class-wemake-update-client.php`) with the `Update URI` header; the GitHub updater and the old updater are removed. Releases are built by `.github/workflows/release.yml` from a `v*` tag, published only after `wemake-cf7-alert.zip` is attached. The update server offers the latest release to old copies too: 2.18.1-2.18.3 and 2.2 through their GitHub updater, 2.13-2.18 through `update.php` (`WM_UPDATE_FROM_GITHUB`). Every copy must be able to update by itself. WordPress auto-updates are not forced on.
Why: One safe update path for all copies; installs recorded on the update server.
Affects: The repository stays public (copies 2.18.1-2.18.3 read its releases). Every release needs a tag that matches the `Version` header and `WMCFA_PLUGIN_VERSION`, greater than the latest as a version. Removing `wemake-cf7-alert` from `WM_UPDATE_FROM_GITHUB` or `WM_CLIENT_UPDATES` stops updates for old copies or for 2.19+. The `wmcfa_l` option checks are left as they are.
Status: active
Task: https://task.nexfield.day/t/1f244bcb-0313-55f0-8ec7-53ab75b7acfb
