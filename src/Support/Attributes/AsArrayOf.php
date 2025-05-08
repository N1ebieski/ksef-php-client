<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support\Attributes;

use Attribute;
use N1ebieski\KSEFClient\Support\Attributes\Attribute as BaseAttribute;
use N1ebieski\KSEFClient\Validator\Rules\String\ClassExistsRule;
use N1ebieski\KSEFClient\Validator\Validator;

#[Attribute()]
final readonly class AsArrayOf extends BaseAttribute
{
    public string $class;

    public function __construct(string $class)
    {
        Validator::validate($class, [
            new ClassExistsRule()
        ]);

        $this->class = $class;
    }
}
