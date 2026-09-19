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
        \Rector\Carbon\Rector\MethodCall\DateTimeMethodCallToCarbonRector::class,
        \Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector::class,
        \Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class,
        \Rector\Carbon\Rector\New_\DateTimeInstanceToCarbonRector::class,
        \Rector\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector::class => [
            __DIR__ . '/src/Validator/Rules/Number/NipRule.php'
        ],
        \Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector::class => [
            __DIR__ . '/src/Testing/Fixtures/Requests/AbstractResponseFixture.php',
            __DIR__ . '/src/Testing/Fixtures/DTOs/Requests/Sessions/AbstractFakturaFixture.php'
        ],
        \Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector::class,
        \Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector::class => [
            __DIR__ . '/src/Factories/EncryptedTokenFactory.php',
            __DIR__ . '/src/Actions/SignDocument/SignDocumentHandler.php',
            __DIR__ . '/src/Factories/EncryptedKeyFactory.php'
        ],
        \Rector\DowngradePhp82\Rector\FunctionLike\DowngradeStandaloneNullTrueFalseReturnTypeRector::class,
        \Rector\DowngradePhp84\Rector\ClassMethod\DowngradeDeprecatedAttributeRector::class
    ])
    ->withComposerBased(phpunit: true)
    ->withImportNames(removeUnusedImports: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        carbon: true,
        phpunitCodeQuality: true
    )
    ->withDowngradeSets(php81: true)
    ->withPhpSets(php81: true);
