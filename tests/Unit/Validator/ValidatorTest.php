<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Exceptions\RuleValidationException;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MaxRule as ArrayMaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Array\MinRule as ArrayMinRule;
use N1ebieski\KSEFClient\Validator\Rules\Date\AfterRule;
use N1ebieski\KSEFClient\Validator\Rules\Date\BeforeRule;
use N1ebieski\KSEFClient\Validator\Rules\Date\MaxRangeRule;
use N1ebieski\KSEFClient\Validator\Rules\Date\TimezoneRule;
use N1ebieski\KSEFClient\Validator\Rules\Directory\ExistsRule as DirectoryExistsRule;
use N1ebieski\KSEFClient\Validator\Rules\File\ExistsRule as FileExistsRule;
use N1ebieski\KSEFClient\Validator\Rules\File\ExtensionsRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\DecimalRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\MaxDigitsRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\MaxRule as NumberMaxRule;
use N1ebieski\KSEFClient\Validator\Rules\Number\MinRule as NumberMinRule;
use N1ebieski\KSEFClient\Validator\Rules\String\ClassExistsRule;
use N1ebieski\KSEFClient\Validator\Rules\String\CountryRule;
use N1ebieski\KSEFClient\Validator\Rules\String\CountryUERule;
use N1ebieski\KSEFClient\Validator\Rules\String\CurrencyRule;
use N1ebieski\KSEFClient\Validator\Rules\String\EmailRule;
use N1ebieski\KSEFClient\Validator\Rules\String\MaxBytesRule;
use N1ebieski\KSEFClient\Validator\Rules\String\MaxRule as StringMaxRule;
use N1ebieski\KSEFClient\Validator\Rules\String\MinBytesRule;
use N1ebieski\KSEFClient\Validator\Rules\String\MinRule as StringMinRule;
use N1ebieski\KSEFClient\Validator\Rules\String\RegexRule;
use N1ebieski\KSEFClient\Validator\Rules\String\UrlRule;
use N1ebieski\KSEFClient\Validator\Rules\Utility\RequiredRule;
use N1ebieski\KSEFClient\Validator\Rules\Xml\SchemaRule;
use N1ebieski\KSEFClient\Validator\Validator;
use N1ebieski\KSEFClient\ValueObjects\SchemaPath;

dataset('rules', [
    'afterRule' => [
        'afterRule',
        new DateTimeImmutable(),
        [new AfterRule(new DateTimeImmutable('+1 day'))]
    ],
    'arrayMaxRule' => [
        'arrayMaxRule',
        [1, 2, 3],
        [new ArrayMaxRule(2)]
    ],
    'arrayMinRule' => [
        'arrayMinRule',
        [],
        [new ArrayMinRule(1)]
    ],
    'beforeRule' => [
        'beforeRule',
        new DateTimeImmutable('+2 days'),
        [new BeforeRule(new DateTimeImmutable('+1 day'))]
    ],
    'maxRangeRule' => [
        'maxRangeRule',
        new DateTimeImmutable('2024-01-01 00:00:00'),
        [new MaxRangeRule(new DateTimeImmutable('2024-03-12 00:00:00'), 2)]
    ],
    'timezoneRule' => [
        'timezoneRule',
        new DateTimeImmutable('now', new DateTimeZone('UTC')),
        [new TimezoneRule(['Europe/Warsaw'])]
    ],
    'directoryExistsRule' => [
        'directoryExistsRule',
        __DIR__ . '/' . uniqid('missing-dir', true),
        [new DirectoryExistsRule()]
    ],
    'fileExistsRule' => [
        'fileExistsRule',
        __DIR__ . '/' . uniqid('missing-file', true) . '.txt',
        [new FileExistsRule()]
    ],
    'fileExtensionsRule' => [
        'fileExtensionsRule',
        '/tmp/document.pdf',
        [new ExtensionsRule(['xml'])]
    ],
    'numberDecimalRule' => [
        'numberDecimalRule',
        '12.3',
        [new DecimalRule(2, 4)]
    ],
    'numberMaxDigitsRule' => [
        'numberMaxDigitsRule',
        '12345',
        [new MaxDigitsRule(4)]
    ],
    'numberMaxRule' => [
        'numberMaxRule',
        11.0,
        [new NumberMaxRule(10.0)]
    ],
    'numberMinRule' => [
        'numberMinRule',
        4.0,
        [new NumberMinRule(5.0)]
    ],
    'stringClassExistsRule' => [
        'stringClassExistsRule',
        'NonExisting\\ClassName',
        [new ClassExistsRule()]
    ],
    'stringCountryRule' => [
        'stringCountryRule',
        'ZZ',
        [new CountryRule()]
    ],
    'stringCountryUERule' => [
        'stringCountryUERule',
        'US',
        [new CountryUERule()]
    ],
    'stringCurrencyRule' => [
        'stringCurrencyRule',
        'XYZ',
        [new CurrencyRule()]
    ],
    'stringEmailRule' => [
        'stringEmailRule',
        'invalid-email',
        [new EmailRule()]
    ],
    'stringMaxBytesRule' => [
        'stringMaxBytesRule',
        'abcd',
        [new MaxBytesRule(3)]
    ],
    'stringMaxRule' => [
        'stringMaxRule',
        'abcd',
        [new StringMaxRule(3)]
    ],
    'stringMinBytesRule' => [
        'stringMinBytesRule',
        'ab',
        [new MinBytesRule(3)]
    ],
    'stringMinRule' => [
        'stringMinRule',
        'ab',
        [new StringMinRule(3)]
    ],
    'stringRegexRule' => [
        'stringRegexRule',
        'abc',
        [new RegexRule('/^\\d+$/')]
    ],
    'stringUrlRule' => [
        'stringUrlRule',
        'notaurl',
        [new UrlRule()]
    ],
    'utilityRequiredRule' => [
        'utilityRequiredRule',
        '',
        [new RequiredRule()]
    ],
    'xmlSchemaRule' => [
        'xmlSchemaRule',
        '<?xml version="1.0" encoding="UTF-8"?><Invalid></Invalid>',
        [
            new SchemaRule(
                new SchemaPath(Utility::basePath('resources/xsd/authv2.xsd'))
            )
        ]
    ]
]);

test('test validation rules without attribute', function (string $attribute, mixed $value, array $rules): void {
    /** @var array<int, AbstractRule> $rules */
    expect(fn () => Validator::validate(
        $value,
        rules: $rules
    ))->toThrow(function (RuleValidationException $exception): bool {
        expect($exception)->toHaveProperties(['message', 'context']);
        expect($exception->context)->toHaveKeys(['message', 'values']);

        expect($exception->context['message'])->not->toBeEmpty();
        expect($exception->context['values'])->toBeArray();

        return true;
    });
})->with('rules');

test('test validation rules with attribute', function (string $attribute, mixed $value, array $rules): void {
    /** @var array<int, AbstractRule> $rules */
    expect(fn () => Validator::validate(
        values: [
            $attribute => $value,
        ],
        rules: [
            $attribute => $rules,
        ]
    ))->toThrow(function (RuleValidationException $exception) use ($attribute): bool {
        expect($exception)->toHaveProperties(['message', 'context']);
        expect($exception->context)->toHaveKeys(['message', 'values']);

        expect($exception->context['message'])->not->toBeEmpty();
        expect($exception->context['values'])->toContain($attribute);

        return true;
    });
})->with('rules');
