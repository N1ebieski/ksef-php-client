<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var'])
    ->notPath([
        // CS-Fixer cannot handle PHP 8.4 property hooks yet
        'src/Testing/Fixtures/Requests/AbstractResponseFixture.php',
        'src/Testing/Fixtures/Requests/AbstractRequestFixture.php',
        'src/Testing/Fixtures/AbstractFixture.php'
    ]);

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRules([
        '@PSR12' => true,
        'not_operator_with_space' => true,
    ])
    ->setFinder($finder)
;
