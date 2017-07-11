# ITB_CDM
-----

--NOTE: admin login to website:       username: fiona       password: password


## Enhancements

1. Twig templating
...twig/twig added to composer.json
...Twig templates created:
..*Base template - Removes need for header, nav and footer PHP templates.
..*News page.
..*Login form.
..*Login error message.
..*Registration form.
..*Dashboard page.
..*Admin page.
..*Welcome message shown upon successful registration.
..*Website Landing page.

2. Validation
...User registration form is successfully validated before database record is created.

3. Unit Tests
...phpunit/phpunit added to composer.json
...phpunit.xml configured to run tests and generate a test coverage report.
...Coverage report is found in Test/coverage/ folder.
...Unit Tests added for:
...User class : 100% coverage
...UserRepository class : 100% coverage
...*Placement class : 100% coverage
...*PlacementRepository class : 100% coverage

4. Logging
...monolog/monolog added to composer.json
...php-console/php-console added to composer.json
...Logging added to debug routing process.

5. PHPDocumentor
...Installed phpdoc/phpdoc using Composer.
...Created basic documentation in docs/ folder.
...Commands:
..*composer exec "phpdoc -d ./src -t ./docs"

