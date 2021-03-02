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
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(DeclareStrictTypesFixer::class);

    $services->set(FullyQualifiedStrictTypesFixer::class);

    $services->set(NativeFunctionInvocationFixer::class);

    $services->set(NoUnusedImportsFixer::class);

    $services->set(BlankLineAfterNamespaceFixer::class);

    $services->set(StrictComparisonFixer::class);

    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [[
            'syntax' => 'short',
        ]]);

    $services
        ->set(BlankLineBeforeStatementFixer::class)
        ->call(
            'configure',
            [
                [
                    'statements' => ['continue', 'declare', 'return', 'throw', 'try'],
                ],
            ],
        );

    $services
        ->set(OrderedImportsFixer::class)
        ->call(
            'configure',
            [
                [
                    'imports_order' => ['class', 'function', 'const'],
                ],
            ],
        );

    // $services
    //     ->set(PhpdocLineSpanFixer::class)
    //     ->call(
    //         'configure',
    //         [
    //             [
    //                 'const' => 'single',
    //                 'method' => 'single',
    //                 'property' => 'single',
    //             ],
    //         ],
    //     );

    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/badge/src',
        __DIR__ . '/badge/tests',
        __DIR__ . '/ecs.php',
    ]);

    $parameters->set(Option::SETS, [
        SetList::SPACES,
        SetList::ARRAY,
        SetList::DOCBLOCK,
        SetList::NAMESPACES,
        SetList::CONTROL_STRUCTURES,
        SetList::CLEAN_CODE,
        SetList::PSR_12,
    ]);

    $parameters->set(
        'skip',
        [
            BlankLineAfterOpeningTagFixer::class,
        ]
    );
};
