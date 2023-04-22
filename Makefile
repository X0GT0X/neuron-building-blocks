cs-fix:
	vendor/bin/php-cs-fixer fix -v --allow-risky=yes

php-stan:
	vendor/bin/phpstan analyse

php-stan-baseline:
	vendor/bin/phpstan analyse --generate-baseline

test:
	bin/phpunit
