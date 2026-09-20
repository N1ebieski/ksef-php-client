<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests;

use N1ebieski\KSEFClient\Actions\AbstractAction;
use N1ebieski\KSEFClient\Actions\AbstractHandler;
use N1ebieski\KSEFClient\Actions\ConvertEcdsaDerToRaw\ConvertEcdsaDerToRawHandler;
use N1ebieski\KSEFClient\Contracts\EnumInterface;
use N1ebieski\KSEFClient\Exceptions\AbstractException;
use N1ebieski\KSEFClient\Exceptions\ExceptionHandler;
use N1ebieski\KSEFClient\Exceptions\HttpClient\ClientException;
use N1ebieski\KSEFClient\Exceptions\HttpClient\Exception;
use N1ebieski\KSEFClient\Exceptions\HttpClient\ServerException;
use N1ebieski\KSEFClient\Exceptions\RuleValidationException;
use N1ebieski\KSEFClient\Factories\AbstractFactory;
use N1ebieski\KSEFClient\HttpClient\Adapters\AbstractAdapter;
use N1ebieski\KSEFClient\Requests\AbstractHandler as RequestAbstractHandler;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\AbstractValueObject;
use N1ebieski\KSEFClient\Validator\Rules\AbstractRule;
use N1ebieski\KSEFClient\ValueObjects\Requests\XmlNamespace;
use PHPUnit\Architecture\Elements\ObjectDescription;

function onlyClasses(ObjectDescription $object): bool
{
    return ! class_exists($object->name)
        || $object->reflectionClass->isAbstract()
        || enum_exists($object->name);
}

function excludeSuffix(ObjectDescription $object, string $suffix): bool
{
    return ! class_exists($object->name)
        || $object->reflectionClass->isAbstract()
        || ! str_ends_with($object->name, $suffix);
}

arch()->preset()->php();
arch()->preset()->security()->ignoring(['tempnam', 'uniqid']);

arch()->expect('N1ebieski\KSEFClient')->toBeCasedCorrectly()->ignoring('N1ebieski\KSEFClient\Tests');
arch()->expect('N1ebieski\KSEFClient')->toBeFinal()
    ->ignoring([
        RuleValidationException::class,
        ClientException::class,
        Exception::class,
        ServerException::class
    ])
    ->mergeExcludeCallbacks([
        onlyClasses(...)
    ]);
arch()->expect('N1ebieski\KSEFClient')->toUseStrictTypes();
arch()->expect('N1ebieski\KSEFClient')->toUseStrictEquality()->ignoring([ConvertEcdsaDerToRawHandler::class]);

arch()->expect('N1ebieski\KSEFClient\Contracts')->toBeInterfaces();

arch()->expect('N1ebieski\KSEFClient\Contracts\Resources')
    ->toOnlyUse([
        'N1ebieski\KSEFClient\Contracts',
        'N1ebieski\KSEFClient\ValueObjects',
        'N1ebieski\KSEFClient\Requests',
    ]);

arch()->expect('N1ebieski\KSEFClient\Support\Concerns')->toBeTraits();

arch()->expect('N1ebieski\KSEFClient\Actions')
    ->extending(AbstractHandler::class)
    ->not()->toHavePublicMethodsBesides(['handle', '__construct']);

arch()->expect('N1ebieski\KSEFClient\Actions')
    ->toExtend(AbstractHandler::class)
    ->mergeExcludeCallbacks([
        fn (ObjectDescription $object) => excludeSuffix($object, 'Handler'),
    ]);

arch()->expect('N1ebieski\KSEFClient\Actions')
    ->classes()
    ->extending(AbstractHandler::class)
    ->toHaveSuffix('Handler');

arch()->expect('N1ebieski\KSEFClient\Actions')
    ->toExtend(AbstractAction::class)
    ->mergeExcludeCallbacks([
        fn (ObjectDescription $object) => excludeSuffix($object, 'Action'),
    ]);

arch()->expect('N1ebieski\KSEFClient\Actions')
    ->classes()
    ->extending(AbstractAction::class)
    ->toHaveSuffix('Action');

arch()->expect('N1ebieski\KSEFClient\Exceptions')
    ->classes()
    ->toExtend(AbstractException::class)
    ->ignoring([
        AbstractException::class,
        ClientException::class,
        ServerException::class,
        ExceptionHandler::class,
    ]);

arch()->expect('N1ebieski\KSEFClient\Exceptions')
    ->classes()
    ->toHaveSuffix('Exception')
    ->ignoring(ExceptionHandler::class);

arch()->expect('N1ebieski\KSEFClient\Factories')
    ->classes()
    ->toExtend(AbstractFactory::class)
    ->ignoring(AbstractFactory::class);

arch()->expect('N1ebieski\KSEFClient\Factories')
    ->classes()
    ->toHaveSuffix('Factory');

arch()->expect('N1ebieski\KSEFClient\HttpClient\Adapters')
    ->classes()
    ->toExtend(AbstractAdapter::class)
    ->ignoring(AbstractAdapter::class);

arch()->expect('N1ebieski\KSEFClient\Requests')
    ->classes()
    ->toExtend(RequestAbstractHandler::class)
    ->mergeExcludeCallbacks([
        fn (ObjectDescription $object) => excludeSuffix($object, 'Handler'),
    ]);

arch()->expect('N1ebieski\KSEFClient\Requests')
    ->classes()
    ->extending(RequestAbstractHandler::class)
    ->toHaveSuffix('Handler');

arch()->expect('N1ebieski\KSEFClient\Requests')
    ->classes()
    ->toExtend(AbstractRequest::class)
    ->mergeExcludeCallbacks([
        fn (ObjectDescription $object) => excludeSuffix($object, 'Request'),
    ]);

arch()->expect('N1ebieski\KSEFClient\Requests')
    ->classes()
    ->extending(AbstractRequest::class)
    ->toHaveSuffix('Request');

arch()->expect('N1ebieski\KSEFClient\Requests')
    ->extending(RequestAbstractHandler::class)
    ->not()
    ->toHavePublicMethodsBesides(['__construct', 'handle']);

arch()->expect('N1ebieski\KSEFClient\Resources')
    ->classes()
    ->not()
    ->toImplementNothing()
    ->ignoring(AbstractResource::class);

arch()->expect('N1ebieski\KSEFClient\Resources')
    ->classes()
    ->toExtend(AbstractResource::class)
    ->ignoring(AbstractResource::class);

arch()->expect('N1ebieski\KSEFClient\Resources')
    ->classes()
    ->toHaveSuffix('Resource');

arch()->expect('N1ebieski\KSEFClient\DTOs')
    ->classes()
    ->toHaveReadonlyProperties();

arch()->expect('N1ebieski\KSEFClient\DTOs')
    ->classes()
    ->toExtend(AbstractDTO::class);

arch()->expect('N1ebieski\KSEFClient\DTOs')
    ->not()
    ->toUse([
        'N1ebieski\KSEFClient\Actions',
        'N1ebieski\KSEFClient\Factories',
        'N1ebieski\KSEFClient\Requests',
        'N1ebieski\KSEFClient\Resources',
        'N1ebieski\KSEFClient\HttpClient',
    ]);

arch()->expect('N1ebieski\KSEFClient\ValueObjects')
    ->classes()
    ->toHaveReadonlyProperties();

arch()->expect('N1ebieski\KSEFClient\ValueObjects')
    ->classes()
    ->toExtend(AbstractValueObject::class);

arch()->expect('N1ebieski\KSEFClient\ValueObjects')
    ->enums()
    ->toImplement(EnumInterface::class)
    ->ignoring(XmlNamespace::class);

arch()->expect('N1ebieski\KSEFClient\ValueObjects')
    ->not()
    ->toUse([
        'N1ebieski\KSEFClient\Actions',
        'N1ebieski\KSEFClient\Factories',
        'N1ebieski\KSEFClient\Requests',
        'N1ebieski\KSEFClient\Resources',
        'N1ebieski\KSEFClient\HttpClient',
    ]);

arch()->expect('N1ebieski\KSEFClient\Validator\Rules')
    ->classes()
    ->toExtend(AbstractRule::class)
    ->ignoring(AbstractRule::class);

arch()->expect('N1ebieski\KSEFClient\Validator\Rules')
    ->classes()
    ->toHaveSuffix('Rule');
