php-cs:
	# Adding PHP_CS_FIXER_IGNORE_ENV=1 until PHP 8.4 support is added
	PHP_CS_FIXER_IGNORE_ENV=1 php vendor/bin/php-cs-fixer fix -v --allow-risky=yes

php-stan:
	vendor/bin/phpstan analyse

php-stan-baseline:
	vendor/bin/phpstan analyse --generate-baseline

test:
	bin/phpunit
