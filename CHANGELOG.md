# Changelog

All notable changes to this package are documented in this file. Versions
follow [Semantic Versioning](https://semver.org). New entries are generated from
commit messages by [dry-ci](https://github.com/TallieuTallieu/dry-ci); past
entries may be edited by hand.

## 3.2.7 - 2026-05-06

### Features

- Support dry 4: `tallieutallieu/dry` is now required as `^3.0.0|^4.0.0` and `tallieutallieu/oak` as `^3.0.0`

## 3.2.6 - 2026-01-05

### Breaking changes

- Require `tallieutallieu/dry`, `tallieutallieu/oak` and `tallieutallieu/dry-dbi` 3.x (previously any version); upgrade `tallieutallieu/dry-dbi` to 3.x if your project is on an older version

### Other changes

- Migrate `RedirectLogRepository` to the dry-dbi API

## 3.2.5 - 2025-07-03

No notable changes.

## 3.2.4 - 2025-07-02

### Fixes

- Handle an empty result when counting the redirect log, so the log cleanup no longer fails

## 3.2.3 - 2025-05-26

### Other changes

- Sort redirects in the admin by creation date, newest first

## 3.2.2 - 2025-05-21

### Other changes

- Allow any version of `tallieutallieu/dry`, `tallieutallieu/oak` and `tallieutallieu/dry-dbi`

## 3.2.1 - 2025-05-21

### Other changes

- Allow more versions of `tallieutallieu/dry` (`>v3.0.0` instead of `^v3.1.0`)

## 3.2.0 - 2025-05-19

### Breaking changes

- New migration `UpdateRedirectTableAddUniqueSourcePath` adds a unique index on `redirects_redirect.source_path`; remove duplicate source paths, then run the migrations after upgrading

### Features

- Make the redirect source path unique; the admin form now handles duplicate source paths
- Add `tallieutallieu/dry-dbi` as a dependency

## 3.1.2 - 2025-05-19

### Fixes

- Fix the offset in the query that cleans up the redirect log

## 3.1.1 - 2025-05-19

### Features

- Clean up the redirect log automatically, keeping the latest 20,000 entries ([sc-6108](https://app.shortcut.com/tallieu--tallieu/story/6108))
- Add a "delete all" action to the redirect log admin ([sc-6108](https://app.shortcut.com/tallieu--tallieu/story/6108))

### Fixes

- Register redirect routes with a trailing slash ([sc-6108](https://app.shortcut.com/tallieu--tallieu/story/6108))

## 3.1.0 - 2025-05-19

### Breaking changes

- Require `tallieutallieu/dry` `^v3.1.0`; the package now targets dry v3
- Route parameters in a source path (e.g. `{id}`) no longer match `/`; redirects whose parameter spans several path segments stop matching ([sc-6108](https://app.shortcut.com/tallieu--tallieu/story/6108))

### Other changes

- Add a README with installation and usage instructions

## 3.0.1 - 2025-05-19

### Breaking changes

- Require `tallieutallieu/oak` `dev-php8.2` instead of `^1.0.6`

## 3.0.0 - 2025-05-19

### Breaking changes

- The package is published as `tallieutallieu/dry-redirects` (was `dietervyncke/dry-redirects`) and depends on `tallieutallieu/oak` instead of `reinvanoyen/oak`; update the package name in your `composer.json`

### Other changes

- First release tagged for dry v3 ("DRY3 version"); the code is the same as 0.1.0

## Earlier history

- **0.0.1** (2020-08-20): Initial release as `dietervyncke/dry-redirects`: admin managers for redirects and the redirect log, 301/302/404 redirects with `{parameter}` substitution, a `RouteWasHit` event that logs each hit, and revisions for the `redirects_redirect` and `redirects_redirect_log` tables.
- **0.0.2** (2020-08-27): Exceptions thrown while registering redirect routes at boot are caught instead of breaking the application.
- **2021-03-23** (unreleased): The repository moved to Tallieu & Tallieu; the package was renamed to `tallieutallieu/dry-redirects` and switched to `tallieutallieu/oak`.
- **0.1.0** (2025-05-19): First release under the new name ("TNT version one"); the same commit is tagged 3.0.0.
- **0.1.1** – **1.0.0** (2025-05-19 – 2025-07-03): Parallel releases from the `dry-v1` branch with redirect-log auto-cleanup (0.1.1), the cleanup query fix (0.1.2), and in 1.0.0 the null-count fix, newest-first sorting, any `tallieutallieu/oak` version and a `tallieutallieu/dry-dbi` dependency. These changes were merged into 3.x in 3.1.2 and 3.2.5.

See the git tags before 3.0.0 for the full history.
