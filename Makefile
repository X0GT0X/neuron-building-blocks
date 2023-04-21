cs-fix:
	vendor/bin/php-cs-fixer fix -v --allow-risky=yes

php-stan:
	$(PHP_CONT) vendor/bin/phpstan analyse

php-stan-baseline:
	$(PHP_CONT) vendor/bin/phpstan analyse --generate-baseline
