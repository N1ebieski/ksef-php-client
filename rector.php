<?php

declare(strict_types=1);

return \Rector\Config\RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withRules([
        \N1ebieski\KSEFClient\Overrides\Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class,
    ])
    ->withSkip([
        \Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector::class,
        \Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class,
        \Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector::class => [
            __DIR__ . '/src/Testing/Fixtures/Requests/AbstractResponseFixture.php',
            __DIR__ . '/src/Testing/Fixtures/DTOs/Requests/Sessions/AbstractFakturaFixture.php'
        ],
        \Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector::class,
        \Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector::class => [
            __DIR__ . '/src/Actions/SignDocument/SignDocumentHandler.php'
        ]
    ])
    ->withComposerBased(phpunit: true)
    ->withImportNames(removeUnusedImports: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        carbon: false,
        phpunitCodeQuality: true
    )
    ->withPhpSets(php84: true);
