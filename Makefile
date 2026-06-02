.SILENT:
.PHONY: help up up-quick down build wait-for-container \
	prepare install-test-deps configure-tests install-plugin \
	load-fixtures reload-fixtures refresh-plugin \
	test test-coverage cs cs-fix analyse shell logs status \
	restart clean clean-all info

## Colors
COLOR_RESET=\033[0m
COLOR_INFO=\033[32m
COLOR_COMMENT=\033[33m

## Configuration
CONTAINER_NAME ?= kommandhub-foundation-plugin
PLUGIN_NAME ?= KommandhubFoundationSW

PLUGIN_REL_PATH := custom/plugins/$(PLUGIN_NAME)
PLUGIN_DIR := $(PLUGIN_REL_PATH)

## Determine plugin and project paths
PLUGIN_ABS_PATH := $(shell cd $(dir $(lastword $(MAKEFILE_LIST))) && pwd)
PROJECT_ROOT := $(shell cd $(PLUGIN_ABS_PATH)/../../.. && pwd)

## Detect whether Make is executed inside the container
IS_DOCKER := $(shell test -f /.dockerenv && echo true || echo false)

## Docker wrapper
DOCKER_RUN := $(if $(filter true,$(IS_DOCKER)),,docker exec $(CONTAINER_NAME))

## Container paths
CONTAINER_PLUGIN_DIR := /var/www/html/$(PLUGIN_REL_PATH)

## Shared command flags
COMPOSER_FLAGS := --no-interaction --optimize-autoloader --no-scripts

PHPUNIT := PROJECT_ROOT=/var/www/html /var/www/html/vendor/bin/phpunit
PHP_CS_FIXER := PROJECT_ROOT=/var/www/html /var/www/html/vendor/bin/php-cs-fixer
PHPSTAN := PROJECT_ROOT=/var/www/html /var/www/html/vendor/bin/phpstan

help:
	@echo "Available commands:"
	@echo ""
	@echo "Environment"
	@echo "  make up                - Start containers and wait until ready"
	@echo "  make up-quick          - Start containers only"
	@echo "  make down              - Stop containers"
	@echo "  make build             - Rebuild containers"
	@echo "  make restart           - Restart containers"
	@echo ""
	@echo "Plugin Setup"
	@echo "  make prepare           - Full test environment setup"
	@echo "  make install-test-deps - Install test dependencies"
	@echo "  make configure-tests   - Copy test configuration"
	@echo "  make install-plugin    - Install and activate plugin"
	@echo "  make refresh-plugin    - Refresh and reactivate plugin"
	@echo "  make load-fixtures     - Load fixtures"
	@echo "  make reload-fixtures   - Reload fixtures"
	@echo ""
	@echo "Quality"
	@echo "  make test             - Run PHPUnit tests"
	@echo "  make test-coverage    - Run tests with coverage"
	@echo "  make cs               - Run PHP-CS-Fixer checks"
	@echo "  make cs-fix           - Fix coding standards"
	@echo "  make analyse          - Run PHPStan"
	@echo ""
	@echo "Utilities"
	@echo "  make shell            - Open shell in container"
	@echo "  make logs             - Follow container logs"
	@echo "  make status           - Show container status"
	@echo "  make clean            - Remove local build artifacts"
	@echo "  make clean-all        - Remove build artifacts and containers"
	@echo "  make info             - Show current configuration"
	@echo ""
	@echo "Configuration:"
	@echo "  CONTAINER_NAME=$(CONTAINER_NAME)"
	@echo "  PLUGIN_NAME=$(PLUGIN_NAME)"
	@echo "  PLUGIN_ABS_PATH=$(PLUGIN_ABS_PATH)"
	@echo "  PROJECT_ROOT=$(PROJECT_ROOT)"
	@echo "  Running in container: $(IS_DOCKER)"

up: up-quick wait-for-container
	@echo "✅ Container $(CONTAINER_NAME) is ready!"

up-quick:
	@echo "🚀 Starting Docker containers..."
	docker-compose up -d --build

wait-for-container:
	@echo "⏳ Waiting for container $(CONTAINER_NAME)..."
	@for i in 1 2 3 4 5; do \
		if docker ps --filter name=$(CONTAINER_NAME) --filter status=running | grep -q $(CONTAINER_NAME); then \
			echo "✅ Container is running"; \
			break; \
		fi; \
		echo "Waiting... (attempt $$i/5)"; \
		sleep 5; \
		if [ $$i -eq 5 ]; then \
			echo "❌ Container failed to start"; \
			exit 1; \
		fi; \
	done

down:
	@echo "🛑 Stopping containers..."
	docker-compose down -v

build:
	@echo "🏗️ Building containers..."
	docker-compose build

prepare: install-test-deps configure-tests install-plugin load-fixtures
	@echo "✅ Test environment is ready!"

install-test-deps:
	@echo "📦 Installing test dependencies..."

	rm -rf vendor/

	$(DOCKER_RUN) composer require \
		--dev \
		shopware/dev-tools \
		shopware/fixture-bundle \
		$(COMPOSER_FLAGS)

	composer install $(COMPOSER_FLAGS)

configure-tests:
	@echo "⚙️ Copying test configuration..."

	rsync -aq \
		/var/www/html/$(PLUGIN_DIR)/tests/Setup/config/ \
		$(PROJECT_ROOT)/config/ || true

install-plugin:
	@echo "🔌 Refreshing plugins..."

	$(DOCKER_RUN) php bin/console plugin:refresh

	$(DOCKER_RUN) php bin/console plugin:install $(PLUGIN_NAME) --activate || \
	$(DOCKER_RUN) php bin/console plugin:update $(PLUGIN_NAME)

refresh-plugin:
	@echo "♻️ Refreshing plugin..."

	$(DOCKER_RUN) php bin/console plugin:refresh

	$(DOCKER_RUN) php bin/console plugin:deactivate $(PLUGIN_NAME) || true

	$(DOCKER_RUN) php bin/console plugin:activate $(PLUGIN_NAME)

load-fixtures:
	@echo "🌱 Loading fixtures..."

	$(DOCKER_RUN) php bin/console fixture:load --no-interaction

reload-fixtures: load-fixtures

test:
	@echo "🧪 Running PHPUnit tests..."

	$(DOCKER_RUN) bash -c "\
		cd $(CONTAINER_PLUGIN_DIR) && \
		$(PHPUNIT) \
		--testdox \
		--configuration=. \
		--colors=always \
		${FILTER}"

test-coverage:
	@echo "📊 Running PHPUnit with coverage..."

	$(DOCKER_RUN) bash -c "\
		cd $(CONTAINER_PLUGIN_DIR) && \
		$(PHPUNIT) \
		--testdox \
		--coverage-html build/coverage \
		--coverage-text \
		--coverage-clover build/logs/clover.xml \
		--coverage-cobertura build/logs/cobertura.xml \
		--configuration=. \
		--colors=always \
		${FILTER}"

cs:
	@echo "🔍 Running PHP-CS-Fixer checks..."

	$(DOCKER_RUN) bash -c "\
		cd $(CONTAINER_PLUGIN_DIR) && \
		$(PHP_CS_FIXER) fix --dry-run --diff"

cs-fix:
	@echo "🛠 Fixing coding standards..."

	$(DOCKER_RUN) bash -c "\
		cd $(CONTAINER_PLUGIN_DIR) && \
		$(PHP_CS_FIXER) fix"

analyse:
	@echo "🔎 Running PHPStan..."

	$(DOCKER_RUN) bash -c "\
		cd $(CONTAINER_PLUGIN_DIR) && \
		$(PHPSTAN) analyse src -c phpstan.dist.neon --memory-limit=1G"

shell:
	@if [ "$(IS_DOCKER)" = "true" ]; then \
		echo "🐚 Already inside container"; \
		bash; \
	else \
		echo "🐚 Opening shell in $(CONTAINER_NAME)..."; \
		docker exec -it $(CONTAINER_NAME) bash; \
	fi

logs:
	docker-compose logs -f

status:
	docker-compose ps

restart: down up

clean:
	@echo "🧹 Cleaning build artifacts..."

	rm -rf \
		build \
		vendor \
		composer.lock

clean-all: clean
	@echo "🧨 Removing containers, volumes and build artifacts..."

	docker-compose down -v

info:
	@echo "Current Configuration:"
	@echo ""
	@echo "Container Name:      $(CONTAINER_NAME)"
	@echo "Plugin Name:         $(PLUGIN_NAME)"
	@echo "Plugin Directory:    $(PLUGIN_DIR)"
	@echo "Plugin Absolute:     $(PLUGIN_ABS_PATH)"
	@echo "Project Root:        $(PROJECT_ROOT)"
	@echo "Inside Container:    $(IS_DOCKER)"
	@echo ""
	@echo "Running containers:"
	@docker ps --filter name=$(CONTAINER_NAME) 2>/dev/null || echo "Docker not available"