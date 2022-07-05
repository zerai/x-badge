<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\NamespaceNotation\BlankLineAfterNamespaceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (EcsConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/badge/src',
        __DIR__ . '/badge/tests',
        __DIR__ . '/ecs.php',
    ]);

    $ecsConfig->skip(
        [
            BlankLineAfterOpeningTagFixer::class,
        ]
    );

    $ecsConfig->rule(DeclareStrictTypesFixer::class);

    $ecsConfig->rule(FullyQualifiedStrictTypesFixer::class);

    $ecsConfig->rule(NativeFunctionInvocationFixer::class);

    $ecsConfig->rule(NoUnusedImportsFixer::class);

    $ecsConfig->rule(BlankLineAfterNamespaceFixer::class);

    $ecsConfig->rule(StrictComparisonFixer::class);

    $ecsConfig->ruleWithConfiguration(
        ArraySyntaxFixer::class,
        [
            'syntax' => 'short',
        ]
    );

    $ecsConfig->ruleWithConfiguration(
        BlankLineBeforeStatementFixer::class,
        [
            'statements' => ['continue', 'declare', 'return', 'throw', 'try'],
        ],
    );

    $ecsConfig->ruleWithConfiguration(
        OrderedImportsFixer::class,
        [
            'imports_order' => ['class', 'function', 'const'],
        ]
    );

    $ecsConfig->ruleWithConfiguration(
        PhpdocLineSpanFixer::class,
        [
            [
                'const' => 'single',
                'method' => 'single',
                'property' => 'single',
            ],

        ]
    );

    $ecsConfig->sets([
        SetList::SPACES,
        SetList::ARRAY,
        SetList::DOCBLOCK,
        SetList::NAMESPACES,
        SetList::CONTROL_STRUCTURES,
        SetList::CLEAN_CODE,
        SetList::PSR_12,
    ]);
};
