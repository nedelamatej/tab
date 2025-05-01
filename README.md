# Tranim App Backend (TAB)

## About The Project

Backend part of softball pitch trajectories modeling and analysis application.

- Backend URL: [tranim.nede.cz/api](https://tranim.nede.cz/api)
- Backend API Documentation: [tranim.nede.cz/api/doc](https://tranim.nede.cz/api/doc)
- Backend Doxygen Documentation: [tranim.nede.cz/be/doc](https://tranim.nede.cz/be/doc)
- Changelog: [CHANGELOG.md](./CHANGELOG.md)
- License: [LICENSE.md](./LICENSE.md)

## Table Of Contents
[About The Project](#about-the-project)\
[Installation](#installation)\
[Directory Structure](#directory-structure)\
[Makefile Targets](#makefile-targets)\
[Built With](#built-with)\
[Conventions](#conventions)\
[License](#license)\
[Acknowledgments](#acknowledgments)\
[Contact](#contact)

## Installation

Clone the repository, install Composer dependencies, edit the environment variables and run the database setup script.

```bash
git clone https://github.com/nedelamatej/tab.git && cd tab # clone the repository
composer install # install Composer dependencies
vi .env # edit the environment variables
```

## Directory Structure

```bash
tab
├── config                            # Symfony configuration files
├── doc                               # Doxygen documentation
├── public                            # public files
├── src                               # source code
│   ├── Controller                    # Symfony controllers
│   │   ├── PitchController.php
│   │   └── ...
│   ├── Entity                        # Doctrine entities
│   │   ├── PitchEntity.php
│   │   └── ...
│   ├── Repository                    # Doctrine repositories
│   │   ├── PitchRepository.php
│   │   └── ...
│   └── Service                       # Symfony services
│       ├── DifferentialEquations     # differential equations classes
│       ├── DistanceMetric            # distance metric classes
│       ├── NeighborhoodSearch        # neighborhood search classes
│       ├── NumericalMethod           # numerical method classes
│       ├── BernsteinPolynomial.php   # Bernstein polynomial class
│       ├── DeCasteljausAlgorithm.php # de Casteljau's algorithm class
│       └── PitchService.php          # pitch service class
├── var
│   ├── cache                         # Symfony cache
│   └── log                           # Symfony logs
├── vendor                            # Composer dependencies
├── .env                              # environment variables
├── CHANGELOG.md                      # changelog
├── composer.json                     # Composer configuration file
├── dbDown.sql                        # database teardown script
├── dbUp.sql                          # database setup script
├── LICENSE.md                        # license
└── README.md                         # this file
```

## Makefile Targets

```bash
make install                          # install Composer dependencies
make clean                            # remove all generated files
make doc                              # generate Doxygen documentation
```

## Built With

[![Symfony][symfony]][symfony-url]\
[![PHP][php]][php-url]\
[![Doctrine][doctrine]][doctrine-url]\
[![MySQL][mysql]][mysql-url]\
[![Doxygen][doxygen]][doxygen-url]

## Conventions

All notable changes to this project are documented in [changelog](./CHANGELOG.md), the format is based on [Keep a Changelog](https://keepachangelog.com/).\
This project adheres to both [Conventional Commits](https://www.conventionalcommits.org/) and [Semantic Versioning](https://semver.org/).

## License

This project is licensed under the terms of the [MIT license](./LICENSE.md).

## Acknowledgments

This project is part of a bachelor's thesis:

- **Modelování a analýza trajektorií softballového nadhozu**
- University: Vysoké učení technické v Brně
- Faculty: Fakulta informačních technologií
- Department: Ústav počítačové grafiky a multimédií
- Author: Matěj Neděla ([nedela.matej@gmail.com](mailto:nedela.matej@gmail.com))
- Supervisor: Ing. Tomáš Milet, Ph.D. ([imilet@fit.vut.cz](mailto:imilet@fit.vut.cz))

## Contact

[![Name][name]][name-url]\
[![Email][email]][email-url]\
[![GitHub][github]][github-url]

[symfony]: https://img.shields.io/badge/symfony-000000?style=for-the-badge&logo=symfony&logoColor=white
[symfony-url]: https://symfony.org/
[php]: https://img.shields.io/badge/php-777BB4?style=for-the-badge&logo=php&logoColor=white
[php-url]: https://php.net/
[doctrine]: https://img.shields.io/badge/doctrine-FC6A31?style=for-the-badge&logo=doctrine&logoColor=white
[doctrine-url]: https://www.doctrine-project.org/
[mysql]: https://img.shields.io/badge/mysql-4479A1?style=for-the-badge&logo=mysql&logoColor=white
[mysql-url]: https://mysql.com/
[doxygen]: https://img.shields.io/badge/doxygen-2C4AA8?style=for-the-badge&logo=doxygen&logoColor=white
[doxygen-url]: https://www.doxygen.nl/

[name]: https://img.shields.io/badge/Matěj_Neděla-241F31?style=for-the-badge&logo=gnometerminal&logoColor=white
[name-url]: https://nede.cz/
[email]: https://img.shields.io/badge/nedela@nede.cz-EA4335?style=for-the-badge&logo=gmail&logoColor=white
[email-url]: mailto:nedela@nede.cz
[github]: https://img.shields.io/badge/github-181717?style=for-the-badge&logo=github&logoColor=white
[github-url]: https://github.com/nedelamatej/
