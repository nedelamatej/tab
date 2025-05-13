# Changelog

All notable changes to this project will be documented in this file.

## [1.1.0] - 2025-05-13

### Added

- Added final touches.

### Fixed

- Fixed deployment issues.

## [1.0.0] - 2025-05-05

### Fixed

- Fixed array indexing in `Pitch` controller.

### Changed

- Changed used neighborhood in `Pitch` service.

## [0.3.0] - 2025-04-28

### Added

- Setup `OpenAPI` documentation tool.
- Added `comp` endpoint to `Pitch` controller.
- Added `PitchService` class.
- Added `DeCasteljausAlgorithm` class.

## [0.2.0] - 2025-04-14

### BREAKING CHANGES

- Added required property `code` to `Type` entity.
- Added required relation `country` to `Organization` entity.
- Added required relation `country` to `Event` entity.
- Added required relation `country` to `Pitcher` entity.
- Updated database `setup` and `teardown` scripts.

### Added

- Added `Country` entity, repository and controller.
- Added more `GET` endpoints to `Event` controller.
- Added more `GET` endpoints to `Pitcher` controller.
- Added more `GET` endpoints to `Pitch` controller.
- Installed `cors-bundle` library.

## [0.1.0] - 2025-03-30

### Added

- Added initial set of files.
- Added configuration files (`workspace` and `editorconfig`).
- Setup `Doxygen` documentation tool.
- Added `Type` entity, repository and controller.
- Added `Organization` entity, repository and controller.
- Added `Event` entity, repository and controller.
- Added `Pitcher` entity, repository and controller.
- Added `Pitch` entity, repository and controller.
- Added database `setup` and `teardown` scripts.
- Added differential equations classes (`AbstractEquations`, `BaseballEquations` and `SoftballEquations`).
- Added numerical method classes (`AbstractMethod`, `EulerMethod`, `RungeKutta2Method` and `RungeKutta4Method`).
- Added distance metric classes (`AbstractDistance`, `ChebyshevDistance`, `EuclideanDistance` and `ManhattanDistance`).
- Added neighborhood search classes (`AbstractNeighborhood`, `MooreNeighborhood` and `VonNeumannNeighborhood`).
- Added `BernsteinPolynomial` class.
- Installed `math-php` library.
