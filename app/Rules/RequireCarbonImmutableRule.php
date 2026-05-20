<?php

namespace App\Rules;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

class RequireCarbonImmutableRule implements Rule
{
    public function getNodeType(): string
    {
        return FullyQualified::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        // 检查代码中是否直接使用了可变的 Carbon\Carbon 类
        if($node->toString() === 'Carbon\Carbon'){
            // 如果发现，则抛出一条构建错误
            return [
                RuleErrorBuilder::message('团队规范：请使用 Carbon\CarbonImmutable 代替 Carbon\Carbon，以避免时间状态被意外修改产生 Bug。')->build()
            ];
        }

        return [];
    }
}
