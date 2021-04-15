.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: dependency-install
dependency-install:  ## Install all dependency with composer
	composer install
	composer bin all install

.PHONY: dependency-purge
dependency-purge:  ## Remove all dependency
	rm -fR vendor
	rm -fR tools/*/vendor

.PHONY: coding-standards
coding-standards:  ## Verify code style issues with easy coding standard
	vendor/bin/ecs check --verbose

.PHONY: static-code-analysis
static-code-analysis:  ## Runs a static code analysis with phpstan/phpstan and vimeo/psalm
	vendor/bin/phpstan analyse --memory-limit=-1
	vendor/bin/psalm --config=psalm.xml --diff --show-info=false --stats --threads=4

.PHONY: psalm
psalm:  # Runs a static analysis (vimeo/psalm)
	vendor/bin/psalm --config=psalm.xml --diff --show-info=true --stats --threads=4

.PHONY: stan
stan:  # Runs a static analysis (phpstan/phpstan)
	vendor/bin/phpstan analyse --memory-limit=-1


.PHONY: tests
tests: ## Runs tests with phpunit/phpunit
	vendor/bin/phpunit --testsuite unit,integration,acceptance,support,phpunit-extension

.PHONY: code-coverage
code-coverage:  ## Collects coverage from running unit tests with phpunit/phpunit
	vendor/bin/phpunit --testsuite unit,integration,acceptance --coverage-text --coverage-html var/coverage/

.PHONY: mutation-tests
mutation-tests: vendor ## Runs mutation tests with infection/infection
	mkdir -p .var/infection
	vendor/bin/infection --configuration=infection.json.dist

.PHONY: clear-cache
clear-cache:  ## Clean all cache (phpunit)
	rm -fR var/.phpunit.cache
	vendor/bin/phpstan clear-result-cache
	vendor/bin/psalm --clear-cache