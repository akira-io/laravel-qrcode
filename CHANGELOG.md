# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed

- **qrcode:** Corrected `eyeColor()` named arguments from `outterRed`, `outterGreen`, and `outterBlue` to `outerRed`, `outerGreen`, and `outerBlue`; `inner*` now maps to the internal eye color and `outer*` maps to the external eye color.

## [1.2.0](https://github.com/akira-io/laravel-qrcode/compare/1.1.0...v1.2.0) (2026-05-31)

### Bug Fixes

- **qrcode:** Harden package defaults and output behavior ([42a2d94](https://github.com/akira-io/laravel-qrcode/commit/42a2d949e151a3eab1aa6c1f161abb0c4a94bf7d))
- **qrcode:** Guard base path fallback ([5e2119e](https://github.com/akira-io/laravel-qrcode/commit/5e2119e90c87c7e317402d7932df153ea6b2d3b4))


### Features

- **qrcode:** Add crypto payment payloads ([82074d4](https://github.com/akira-io/laravel-qrcode/commit/82074d4bc289563f9dfb02e44133756a8bb03720))
- **qrcode:** Add contact and calendar payloads ([c931f59](https://github.com/akira-io/laravel-qrcode/commit/c931f59df01e7f378ec2f94f9be1c3e3beab9c42))
- **testing:** Add QR payload assertions ([a0978c6](https://github.com/akira-io/laravel-qrcode/commit/a0978c6f5dd287d7762db65112011be4adbfdd81))


### Other

- **deps-dev:** Bump akira/laravel-debugger from 1.3.1 to 1.4.0 ([5400509](https://github.com/akira-io/laravel-qrcode/commit/5400509e096f3162f46a4e7c3f4ddc01542dc0b4))
- **deps-dev:** Bump driftingly/rector-laravel from 2.1.9 to 2.1.12 ([97fbc33](https://github.com/akira-io/laravel-qrcode/commit/97fbc3358e5e33f781aa833d99c61bb0133f9c6e))
- **deps:** Bump bacon/bacon-qr-code from 3.0.3 to 3.0.4 ([5cc5d62](https://github.com/akira-io/laravel-qrcode/commit/5cc5d629d9a1fec99be3881b174173eb981c6837))
- **deps-dev:** Bump rector/rector from 2.3.8 to 2.3.9 ([8b8d255](https://github.com/akira-io/laravel-qrcode/commit/8b8d255b0e8fa655c9503e3f05fd454165e8be38))
- **deps-dev:** Bump phpstan/phpstan from 2.1.40 to 2.1.41 ([ffb1570](https://github.com/akira-io/laravel-qrcode/commit/ffb15703b74062f46c09cee2c5755c204670424b))
- **deps-dev:** Bump laravel/pint from 1.27.1 to 1.29.0 ([e93dc83](https://github.com/akira-io/laravel-qrcode/commit/e93dc839d1b9a431144663ec389a79379601ce97))
- **deps-dev:** Bump pestphp/pest from 4.4.1 to 4.4.2 ([04310d9](https://github.com/akira-io/laravel-qrcode/commit/04310d9de11f3671bd377ef7e2ea54cdcf471c64))
- **deps:** Bump SethCohen/github-releases-to-discord ([dd5d4ab](https://github.com/akira-io/laravel-qrcode/commit/dd5d4ab9e3596efc0afc1fe1882b32e1fd1c35cc))
- **deps-dev:** Bump pestphp/pest-plugin-type-coverage ([4489c0f](https://github.com/akira-io/laravel-qrcode/commit/4489c0fafef478b220aa368d0c741a695c25b288))
- **deps:** Bump bacon/bacon-qr-code from 3.0.4 to 3.1.1 ([ad1ebfe](https://github.com/akira-io/laravel-qrcode/commit/ad1ebfe6d9fe3fff6c3b51550569f55c596288da))
- **deps-dev:** Bump orchestra/testbench from 10.9.0 to 11.1.0 ([558e398](https://github.com/akira-io/laravel-qrcode/commit/558e39803d039e4d1116e95f1eaac713d072bf44))
- **deps-dev:** Bump pestphp/pest from 4.4.3 to 4.7.0 ([312bf6c](https://github.com/akira-io/laravel-qrcode/commit/312bf6cd331b5ceff9e113af69bf43e601e3472d))
- **deps-dev:** Bump peckphp/peck from 0.2.0 to 0.3.0 ([b94ce68](https://github.com/akira-io/laravel-qrcode/commit/b94ce6820196d3fa4a6d42ab2877aa8eca1f69ed))
- **deps-dev:** Bump laravel/pint from 1.29.0 to 1.29.1 ([9e75417](https://github.com/akira-io/laravel-qrcode/commit/9e7541725f074741a5e0839efa7e2c05afa0eff6))
- **deps-dev:** Bump nunomaduro/collision from 8.9.3 to 8.9.4 ([ea3ec2c](https://github.com/akira-io/laravel-qrcode/commit/ea3ec2c9eb42257a07637f55a77555613dd8fc2a))
- **deps-dev:** Bump driftingly/rector-laravel from 2.1.12 to 2.4.0 ([62e7ef3](https://github.com/akira-io/laravel-qrcode/commit/62e7ef3cd760b27b3e404f17ab43f2962ff915ca))
- **deps-dev:** Bump rector/rector from 2.3.9 to 2.4.4 ([20023c5](https://github.com/akira-io/laravel-qrcode/commit/20023c56d03821930b3860aac640d7c90c4988f2))
- **deps-dev:** Bump phpstan/phpstan from 2.1.46 to 2.1.55 ([63ee394](https://github.com/akira-io/laravel-qrcode/commit/63ee394871d246e610e22f601966b7e29f121d70))
- **deps-dev:** Resolve pest 4.7.0 conflicts ([e2a68bb](https://github.com/akira-io/laravel-qrcode/commit/e2a68bbfa274e7a2cf7c24dcf78ee33e997ae009))

## [1.1.0](https://github.com/akira-io/laravel-qrcode/compare/1.0.4...1.1.0) (2026-02-24)

### Features

- Add support for Laravel 13 ([dc0f464](https://github.com/akira-io/laravel-qrcode/commit/dc0f464e6801e9ca7e7c47d65ed82dc2621a3fe9))


### Other

- **deps-dev:** Bump phpstan/phpstan from 2.1.37 to 2.1.38 ([a23c702](https://github.com/akira-io/laravel-qrcode/commit/a23c702488cb624dfc0097bab51ec3da4da68dfe))
- **deps-dev:** Bump pestphp/pest from 4.3.1 to 4.3.2 ([ab9a8ef](https://github.com/akira-io/laravel-qrcode/commit/ab9a8eff340c66d018097450efe52c333410f1e6))
- **deps-dev:** Bump rector/rector from 2.3.4 to 2.3.5 ([f6c0d2f](https://github.com/akira-io/laravel-qrcode/commit/f6c0d2f49f5e129aa1396c90e1d8b4a280a67c83))
- **deps-dev:** Bump laravel/pint from 1.27.0 to 1.27.1 ([d03bc10](https://github.com/akira-io/laravel-qrcode/commit/d03bc10f6c114e2f246fb62c16048e43dcaabc17))
- **deps-dev:** Bump rector/rector from 2.3.5 to 2.3.8 ([c1e75b0](https://github.com/akira-io/laravel-qrcode/commit/c1e75b0a202969a89292ca2ec82af1c123e263c5))
- **deps-dev:** Bump pestphp/pest from 4.3.2 to 4.4.1 ([9eae4b0](https://github.com/akira-io/laravel-qrcode/commit/9eae4b03f7e7ff99df113408f1deeab2d509dab8))
- **deps-dev:** Bump pestphp/pest-plugin-laravel from 4.0.0 to 4.1.0 ([d77938a](https://github.com/akira-io/laravel-qrcode/commit/d77938a5d076a0dfc0a6f48397b7359b3c34c6d5))
- **deps-dev:** Bump nunomaduro/collision from 8.8.3 to 8.9.1 ([e9f9f9b](https://github.com/akira-io/laravel-qrcode/commit/e9f9f9be3f994af627e7bb7c2c8950ccdd4b348c))

## [1.0.4](https://github.com/akira-io/laravel-qrcode/compare/1.0.3...1.0.4) (2026-01-27)

### Other

- **deps-dev:** Bump laravel/pint from 1.26.0 to 1.27.0 ([5b6bb48](https://github.com/akira-io/laravel-qrcode/commit/5b6bb488ae8f229b8c8e762194a92384eb6e8dbd))
- **deps-dev:** Bump pestphp/pest from 4.3.0 to 4.3.1 ([3d8cd39](https://github.com/akira-io/laravel-qrcode/commit/3d8cd398aace4ea5675d433d73c189e5571e0e6c))
- **deps-dev:** Bump orchestra/testbench from 10.8.0 to 10.9.0 ([02b98a8](https://github.com/akira-io/laravel-qrcode/commit/02b98a88032529f44d919a5cfb80b371a62c34e3))
- **deps-dev:** Bump rector/rector from 2.3.0 to 2.3.4 ([3d05aa6](https://github.com/akira-io/laravel-qrcode/commit/3d05aa6d3c71dfbfd0fe38310f493a25cdec6920))

## [1.0.3](https://github.com/akira-io/laravel-qrcode/compare/1.0.2...1.0.3) (2025-12-31)

### Other

- **deps-dev:** Bump driftingly/rector-laravel from 2.1.7 to 2.1.8 ([5c5af2d](https://github.com/akira-io/laravel-qrcode/commit/5c5af2de178224367ea4b35fd1c43dcc4eb4cfbc))
- **deps-dev:** Bump driftingly/rector-laravel from 2.1.8 to 2.1.9 ([d3921e4](https://github.com/akira-io/laravel-qrcode/commit/d3921e40b9f84b9e2d1f6682138e6758e5e00385))
- **deps-dev:** Bump rector/rector from 2.2.14 to 2.3.0 ([28fe81f](https://github.com/akira-io/laravel-qrcode/commit/28fe81f9e82efec714553ab958ee400d483a3d4a))

## [1.0.2](https://github.com/akira-io/laravel-qrcode/compare/1.0.1...1.0.2) (2025-12-16)

### Other

- **deps-dev:** Bump rector/rector from 2.2.11 to 2.2.14 ([67a5488](https://github.com/akira-io/laravel-qrcode/commit/67a54883551d4a466b1834b1f736f04415b51a0d))
- **deps-dev:** Bump driftingly/rector-laravel from 2.1.6 to 2.1.7 ([74a6fa0](https://github.com/akira-io/laravel-qrcode/commit/74a6fa0fce23a9fc1c1bedf38a034c9be009deef))

## [1.0.1](https://github.com/akira-io/laravel-qrcode/compare/1.0.0...1.0.1) (2025-12-08)

### Other

- **deps-dev:** Bump driftingly/rector-laravel from 2.1.5 to 2.1.6 ([774ab6a](https://github.com/akira-io/laravel-qrcode/commit/774ab6a06b06908bb09946a30aa6f0508d9caa69))
- **deps-dev:** Bump peckphp/peck from 0.1.3 to 0.2.0 ([671f44c](https://github.com/akira-io/laravel-qrcode/commit/671f44c056b529f6ff3ee58ed4fdd2b5a08a23ab))
- **deps-dev:** Bump phpstan/phpstan from 2.1.32 to 2.1.33 ([c7e9a13](https://github.com/akira-io/laravel-qrcode/commit/c7e9a13d8ad67826d65e8d2930b193e530dc4e14))

## [1.0.0](https://github.com/akira-io/laravel-qrcode/compare/...1.0.0) (2025-12-02)

### Code Refactoring

- Improve type handling and code clarity in DataTypeMapper and related classes ([3598437](https://github.com/akira-io/laravel-qrcode/commit/359843729166cbcdfc7882337e9b4dc7185d9a23))
- Improve type hints and error handling in QR code generation ([28f33d1](https://github.com/akira-io/laravel-qrcode/commit/28f33d15c91bc9b257e0f6a651189647e910886d))
- Enforce strict types and finalize classes in QR code actions and data types ([41e9e7b](https://github.com/akira-io/laravel-qrcode/commit/41e9e7b23ef556d4dcd8835d78f8438cb7d6f855))


### Features

- Add initial implementation of QR code generator and related features ([c84298d](https://github.com/akira-io/laravel-qrcode/commit/c84298dbfd7e600a82904d453c659d84623194ba))
- Add support for 'text' method in DataTypeMapper with validation ([cb33a58](https://github.com/akira-io/laravel-qrcode/commit/cb33a5835430c5c7f9154a52cb59b02d2dd2fb79))
- Extend QrCode class with additional methods for various QR code types ([a195c1e](https://github.com/akira-io/laravel-qrcode/commit/a195c1ebebb93d7936431fad17a53809e1341989))
- Add base64 encoding for PNG format in QR code generation for HTML display ([3f238e5](https://github.com/akira-io/laravel-qrcode/commit/3f238e52862e23dee90b049d11f5a7b9da81c7b4))
- Add release-it configuration and Composer plugin for version management ([290c79c](https://github.com/akira-io/laravel-qrcode/commit/290c79ce83e853a44ba718f32795016039a72809))
- Add GitHub Actions workflow for releasing to Discord ([b29e313](https://github.com/akira-io/laravel-qrcode/commit/b29e3130ff8123460ddf61fef95319f04d386845))


### Other

- **deps:** Bump actions/checkout from 4 to 5 ([d8f544e](https://github.com/akira-io/laravel-qrcode/commit/d8f544e54c1cfd34a040ddc00e62ffde532d21c7))
- **deps:** Bump actions/checkout from 5 to 6 ([fb4be7d](https://github.com/akira-io/laravel-qrcode/commit/fb4be7dd27e407491ff1fd1740800e4243e7012e))
- **deps-dev:** Bump akira/laravel-debugger from 1.0.1 to 1.3.1 ([b6d6214](https://github.com/akira-io/laravel-qrcode/commit/b6d62142aba9f5f658664eed479f178120682565))
- **deps:** Bump bacon/bacon-qr-code from 3.0.1 to 3.0.3 ([9da57c6](https://github.com/akira-io/laravel-qrcode/commit/9da57c61c34e35d0ae2a6d6a31bbe8c1036e5b7a))
- **deps-dev:** Bump nunomaduro/collision from 8.8.2 to 8.8.3 ([2afd434](https://github.com/akira-io/laravel-qrcode/commit/2afd43450d22b05d3e6cf3dc5a31fdd1e17d27c4))
- **deps-dev:** Bump laravel/pint from 1.25.1 to 1.26.0 ([dc94ead](https://github.com/akira-io/laravel-qrcode/commit/dc94eadf710456b535bc6ca5fa8d9483042b392c))
