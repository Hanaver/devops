<?php

namespace App\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * 重点在这里：添加泛型注释，声明当前 Rule 绑定的具体节点类型
 *
 * @implements Rule<New_>
 */
class RequireCarbonImmutableRule implements Rule
{
    public function getNodeType(): string
    {
        return New_::class;
    }

    /**
     * 有了上面的类注释，PHPStan 在分析这里时，
     * 就会自动知道传进来的 $node 实际上是 PhpParser\Node\Expr\New_ 对象，
     * 因此它不会再因为找不到 $class 属性而报错了。
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if(!$node->class instanceof Node\Name){
            return [];
        }

        $className = $node->class->toString();

        // 检查代码中是否直接使用了可变的 Carbon\Carbon 类
        if($className === 'Carbon\Carbon'){
            // 如果发现，则抛出一条构建错误
            return [
                RuleErrorBuilder::message('团队规范：请使用 Carbon\CarbonImmutable 代替 Carbon\Carbon，以避免时间状态被意外修改产生 Bug。')
                    ->identifier('rule.requireCarbonImmutable')
                    ->build()
            ];
        }

        return [];
    }
}
