<?php

/* @noinspection ALL */
// @formatter:off
// phpcs:ignoreFile

namespace Pest {
    /**
     * @template TValue
     * @mixin Mixins\Expectation<TValue>
     * @mixin Arch\PendingArchExpectation<TValue>
     * @method void toBeFixture(array<int|string, mixed> $data, ?object $object = null)
     * @method void toBeExceptionFixture(array<int|string, mixed> $data)
     * @method void toBeArrayWithoutObjectsRecursively()
     */
    final class Expectation
    {
    }
}

namespace Pest\Arch {
    /**
     * @template TValue
     * @mixin \Pest\Expectation<TValue>
     * @method \Pest\Expectations\OppositeExpectation<TValue> not()
     * @method PendingArchExpectation<TValue> toHaveReadonlyProperties()
     */
    final class PendingArchExpectation
    {
    }
}
