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
    ->withConfiguredRule(\Pest\Rector\Rules\ChainExpectCallsRector::class, [
        'merge_different_variables' => false,
    ])
    ->withSkip([
        \Rector\Php82\Rector\Class_\ReadOnlyClassRector::class,
        \Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector::class,
        \Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class,
        \Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector::class => [
            __DIR__ . '/src/Testing/Fixtures/Requests/AbstractResponseFixture.php',
            __DIR__ . '/src/Testing/Fixtures/DTOs/Requests/Sessions/AbstractFakturaFixture.php'
        ],
        \Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector::class,
        \Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector::class => [
            __DIR__ . '/src/Actions/SignDocument/SignDocumentHandler.php'
        ],
        \Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector::class => [
            __DIR__ . '/tests/Arch.php'
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
        phpunitCodeQuality: true
    )
    ->withSets([
        \Pest\Rector\Set\PestSetList::CODING_STYLE,
    ])
    ->withPhpSets(php84: true);
