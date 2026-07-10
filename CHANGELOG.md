# CHANGELOG

## 2026-07-10

- Refactored how custom helpers are implemented, dropped predefined functions in favor of publishable, project-owned helpers.
- Updated README for this.
- Added Roadmap section to README.

## 2026-07-07

- Added the `setRoute.controllerNamespace` setting.
- Routing for nested entries in matrix fields is now possible.

## 2026-07-06

- Reinit git repository.
- Adopt official Blade support.
- Added set of AI-generated experimental helpers for Blade templates, including docs.
- Added documentation for replacing default Twig functions/filters/tests.
- Updated README
- Support for config-driven Blade customization.
- Added the `anonymousComponentPaths` configuration option.
- Added experimental middleware.

## 2026-06-23

- Updated all Blade/Twig functions to use a `c6b_` prefix to avoid naming conflicts with other libraries.
- Dropped the `laracraft` Twig variable in favor of global functions.

## 2026-06-22
- Added the `asRelativeTime` function to convert timestamps to relative time formats (e.g., "5 minutes ago").

## 2026-06-20

Initial commit.