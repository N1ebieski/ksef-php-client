<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Support;

use Closure;
use N1ebieski\KSEFClient\Contracts\PipeInterface;
use N1ebieski\KSEFClient\Contracts\PipelineInterface;

final class Pipeline implements PipelineInterface
{
    /** @var array<int, PipeInterface> */
    private array $pipes = [];

    private string $method = 'handle';

    public function through(array $pipes): self
    {
        $this->pipes = $pipes;

        return $this;
    }

    public function pipe(PipeInterface $pipe): self
    {
        $this->pipes[] = $pipe;

        return $this;
    }

    public function via(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function process(mixed $traveler): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->carry(),
            fn (mixed $traveler): mixed => $traveler
        );

        return $pipeline($traveler); //@phpstan-ignore-line
    }

    private function carry(): Closure
    {
        return fn (Closure $stack, PipeInterface $pipe): Closure => fn (mixed $traveler) => $pipe->{$this->method}($traveler, $stack);
    }
}
