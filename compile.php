<?php
/**
 * HINT: The php.ini setting phar.readonly must be set to 0.
 *
 * Execute like this: php -d phar.readonly=0 compile.php
 */

$pathToPhar = __DIR__ . '/bin/query-builder.phar';
$pathToSrc = __DIR__ . '/src';

// cleanup
if (file_exists($pathToPhar)) {
    unlink($pathToPhar);
}

// create phar
$phar = new Phar($pathToPhar);
$phar->buildFromDirectory($pathToSrc);
$phar->setStub(<<<ENDSTUB
<?php
Phar::mapPhar('query-builder.phar');
include 'phar://query-builder.phar/QueryBuilderFactory.php';
__HALT_COMPILER();
ENDSTUB);

echo "Phar successfully created." . PHP_EOL;
