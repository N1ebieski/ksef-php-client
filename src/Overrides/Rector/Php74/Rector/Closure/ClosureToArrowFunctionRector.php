<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Overrides\Rector\Php74\Rector\Closure;

use PhpParser\Node;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector as BaseClosureToArrowFunctionRector;
use Rector\Rector\AbstractRector;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ClosureToArrowFunctionRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(private readonly BaseClosureToArrowFunctionRector $baseClosureToArrowFunctionRector)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return $this->baseClosureToArrowFunctionRector->getRuleDefinition();
    }

    public function getNodeTypes(): array
    {
        return $this->baseClosureToArrowFunctionRector->getNodeTypes();
    }

    public function refactor(Node $node): ?Node
    {
        $parent = $this->baseClosureToArrowFunctionRector->refactor($node);

        $comments = $node->stmts[0]->getAttribute(AttributeKey::COMMENTS) ?? [];

        if ($comments !== []) {
            return null;
        }

        return $parent;
    }

    public function provideMinPhpVersion(): int
    {
        return $this->baseClosureToArrowFunctionRector->provideMinPhpVersion();
    }
}
