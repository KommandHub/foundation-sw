# Kommandhub Foundation Plugin for Shopware 6

[![License: Proprietary](https://img.shields.io/badge/License-Proprietary-red.svg)](LICENSE)
[![Shopware](https://img.shields.io/badge/Shopware-6.6%20%7C%206.7-blue)](https://shopware.com)

The **Kommandhub Foundation** plugin provides common shared logic, abstract classes, and events for all Kommandhub Shopware plugins. It ensures consistency across payment integrations like Paystack and Flutterwave.

Developed with ❤️ by [Kommandhub Limited](https://kommandhub.com)

---

# Table of Contents

* [Requirements](#requirements)
* [Installation](#installation)
* [Development & Testing](#development--testing)
* [License](#license)

---

# Requirements

* **Shopware**: `~6.6.0` or `~6.7.0`
* **PHP**: `^8.2` (inside Docker) / `^8.4` (local)
* **Composer**

---

# Installation

## Via Composer (Recommended)

```bash
composer require kommandhub/foundation-sw
bin/console plugin:refresh
bin/console plugin:install --activate KommandhubFoundationSW
bin/console cache:clear
```

---

# Development & Testing

To ensure a consistent environment, tests and development tools should be run inside the project's Docker container.

### 1. Setup Development Environment

Follow these steps to prepare your environment:

```bash
# Navigate to the plugin directory
cd custom/plugins/KommandhubFoundationSW

# Start the Docker containers
make up

# Enter the container shell
make shell

# Prepare the test environment (inside the shell)
make prepare
```

### 2. Available Development Commands

Once the environment is prepared, you can execute the following commands (either inside the shell or via `make` from the host):

#### Run PHPUnit Tests
```bash
make test
```

#### Run Tests with Coverage
```bash
make test-coverage
```

#### Static Analysis (PHPStan)
```bash
make analyse
```

#### Code Style (PHP-CS-Fixer)
```bash
make cs
make cs-fix
```

---

# License

This project is licensed under a **Proprietary** license.
See the `composer.json` for details.
