.DEFAULT_GOAL := help

.PHONY: help
help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

.PHONY: pint
pint: ## Run Pint code style fixer
	@export XDEBUG_MODE=off
	@$(CURDIR)/vendor/bin/pint --parallel
	@unset XDEBUG_MODE

.PHONY: test-pint
test-pint: ## Run Pint code style fixer in test mode
	@export XDEBUG_MODE=off
	@$(CURDIR)/vendor/bin/pint --test --parallel
	@unset XDEBUG_MODE=off

.PHONY: rector
rector: ## Run Rector
	@$(CURDIR)/vendor/bin/rector process

.PHONY: test-rector
test-rector: ## Run Rector in test mode
	@$(CURDIR)/vendor/bin/rector process --dry-run

.PHONY: phpstan
phpstan: ## Run PHPStan
	@$(CURDIR)/vendor/bin/phpstan analyse --ansi --memory-limit=2G

.PHONY: phpstan-module
phpstan-module: ## Run PHPStan on a specific folder
	@folder=$(if $(word 2,$(MAKECMDGOALS)),$(word 2,$(MAKECMDGOALS)),app); \
	$(CURDIR)/vendor/bin/phpstan analyse app-modules/$$folder  --memory-limit=2G

.PHONY: test-phpstan
test-phpstan: ## Run PHPStan in test mode
	@$(CURDIR)/vendor/bin/phpstan analyse --ansi --memory-limit=2G

.PHONY: format
format: rector pint ## Run Pint and Rector and try to fixes the source code

.PHONY: check
check: test-rector test-pint test-phpstan ## Run Pint, PHPStan with Rector in dry-run mode

.PHONY: test
test: ## Run all tests
	@$(CURDIR)/vendor/bin/pest --parallel --compact

.PHONY: test-unit
test-unit: ## Run unit tests
	@$(CURDIR)/vendor/bin/pest --parallel --compact --group=unit

.PHONY: test-feature
test-feature: ## Run feature tests
	@$(CURDIR)/vendor/bin/pest --parallel --compact --group=feature

.PHONY: test-browser
test-browser: ## Run browser tests
	@$(CURDIR)/vendor/bin/pest --parallel --compact --group=browser
