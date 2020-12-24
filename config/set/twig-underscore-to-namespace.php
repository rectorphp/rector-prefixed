<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74;

use _PhpScoperb75b35f52b74\Rector\Generic\ValueObject\PseudoNamespaceToNamespace;
use _PhpScoperb75b35f52b74\Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector;
use _PhpScoperb75b35f52b74\Rector\Renaming\Rector\Name\RenameClassRector;
use _PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScoperb75b35f52b74\Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\_PhpScoperb75b35f52b74\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScoperb75b35f52b74\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\_PhpScoperb75b35f52b74\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['Twig_LoaderInterface' => '_PhpScoperb75b35f52b74\\Twig\\Loader\\LoaderInterface', 'Twig_Extension_StringLoader' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\StringLoaderExtension', 'Twig_Extension_Optimizer' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\OptimizerExtension', 'Twig_Extension_Debug' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\DebugExtension', 'Twig_Extension_Sandbox' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\SandboxExtension', 'Twig_Extension_Profiler' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\ProfilerExtension', 'Twig_Extension_Escaper' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\EscaperExtension', 'Twig_Extension_Staging' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\StagingExtension', 'Twig_Extension_Core' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\CoreExtension', 'Twig_Node' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Node', 'Twig_NodeVisitor_Optimizer' => '_PhpScoperb75b35f52b74\\Twig\\NodeVisitor\\OptimizerNodeVisitor', 'Twig_NodeVisitor_SafeAnalysis' => '_PhpScoperb75b35f52b74\\Twig\\NodeVisitor\\SafeAnalysisNodeVisitor', 'Twig_NodeVisitor_Sandbox' => '_PhpScoperb75b35f52b74\\Twig\\NodeVisitor\\SandboxNodeVisitor', 'Twig_NodeVisitor_Escaper' => '_PhpScoperb75b35f52b74\\Twig\\NodeVisitor\\EscaperNodeVisitor', 'Twig_SimpleFunction' => '_PhpScoperb75b35f52b74\\Twig\\TwigFunction', 'Twig_Function' => '_PhpScoperb75b35f52b74\\Twig\\TwigFunction', 'Twig_Error_Syntax' => '_PhpScoperb75b35f52b74\\Twig\\Error\\SyntaxError', 'Twig_Error_Loader' => '_PhpScoperb75b35f52b74\\Twig\\Error\\LoaderError', 'Twig_Error_Runtime' => '_PhpScoperb75b35f52b74\\Twig\\Error\\RuntimeError', 'Twig_TokenParser' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\AbstractTokenParser', 'Twig_TokenParserInterface' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\TokenParserInterface', 'Twig_CacheInterface' => '_PhpScoperb75b35f52b74\\Twig\\Cache\\CacheInterface', 'Twig_NodeVisitorInterface' => '_PhpScoperb75b35f52b74\\Twig\\NodeVisitor\\NodeVisitorInterface', 'Twig_Profiler_NodeVisitor_Profiler' => '_PhpScoperb75b35f52b74\\Twig\\Profiler\\NodeVisitor\\ProfilerNodeVisitor', 'Twig_Profiler_Dumper_Text' => '_PhpScoperb75b35f52b74\\Twig\\Profiler\\Dumper\\TextDumper', 'Twig_Profiler_Dumper_Base' => '_PhpScoperb75b35f52b74\\Twig\\Profiler\\Dumper\\BaseDumper', 'Twig_Profiler_Dumper_Blackfire' => '_PhpScoperb75b35f52b74\\Twig\\Profiler\\Dumper\\BlackfireDumper', 'Twig_Profiler_Dumper_Html' => '_PhpScoperb75b35f52b74\\Twig\\Profiler\\Dumper\\HtmlDumper', 'Twig_Profiler_Node_LeaveProfile' => '_PhpScoperb75b35f52b74\\Twig\\Profiler\\Node\\LeaveProfileNode', 'Twig_Profiler_Node_EnterProfile' => '_PhpScoperb75b35f52b74\\Twig\\Profiler\\Node\\EnterProfileNode', 'Twig_Error' => '_PhpScoperb75b35f52b74\\Twig\\Error\\Error', 'Twig_ExistsLoaderInterface' => '_PhpScoperb75b35f52b74\\Twig\\Loader\\ExistsLoaderInterface', 'Twig_SimpleTest' => '_PhpScoperb75b35f52b74\\Twig\\TwigTest', 'Twig_Test' => '_PhpScoperb75b35f52b74\\Twig\\TwigTest', 'Twig_FactoryRuntimeLoader' => '_PhpScoperb75b35f52b74\\Twig\\RuntimeLoader\\FactoryRuntimeLoader', 'Twig_NodeOutputInterface' => '_PhpScoperb75b35f52b74\\Twig\\Node\\NodeOutputInterface', 'Twig_SimpleFilter' => '_PhpScoperb75b35f52b74\\Twig\\TwigFilter', 'Twig_Filter' => '_PhpScoperb75b35f52b74\\Twig\\TwigFilter', 'Twig_Loader_Chain' => '_PhpScoperb75b35f52b74\\Twig\\Loader\\ChainLoader', 'Twig_Loader_Array' => '_PhpScoperb75b35f52b74\\Twig\\Loader\\ArrayLoader', 'Twig_Loader_Filesystem' => '_PhpScoperb75b35f52b74\\Twig\\Loader\\FilesystemLoader', 'Twig_Cache_Null' => '_PhpScoperb75b35f52b74\\Twig\\Cache\\NullCache', 'Twig_Cache_Filesystem' => '_PhpScoperb75b35f52b74\\Twig\\Cache\\FilesystemCache', 'Twig_NodeCaptureInterface' => '_PhpScoperb75b35f52b74\\Twig\\Node\\NodeCaptureInterface', 'Twig_Extension' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\AbstractExtension', 'Twig_TokenParser_Macro' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\MacroTokenParser', 'Twig_TokenParser_Embed' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\EmbedTokenParser', 'Twig_TokenParser_Do' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\DoTokenParser', 'Twig_TokenParser_From' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\FromTokenParser', 'Twig_TokenParser_Extends' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\ExtendsTokenParser', 'Twig_TokenParser_Set' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\SetTokenParser', 'Twig_TokenParser_Sandbox' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\SandboxTokenParser', 'Twig_TokenParser_AutoEscape' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\AutoEscapeTokenParser', 'Twig_TokenParser_With' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\WithTokenParser', 'Twig_TokenParser_Include' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\IncludeTokenParser', 'Twig_TokenParser_Block' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\BlockTokenParser', 'Twig_TokenParser_Filter' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\FilterTokenParser', 'Twig_TokenParser_If' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\IfTokenParser', 'Twig_TokenParser_For' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\ForTokenParser', 'Twig_TokenParser_Flush' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\FlushTokenParser', 'Twig_TokenParser_Spaceless' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\SpacelessTokenParser', 'Twig_TokenParser_Use' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\UseTokenParser', 'Twig_TokenParser_Import' => '_PhpScoperb75b35f52b74\\Twig\\TokenParser\\ImportTokenParser', 'Twig_ContainerRuntimeLoader' => '_PhpScoperb75b35f52b74\\Twig\\RuntimeLoader\\ContainerRuntimeLoader', 'Twig_SourceContextLoaderInterface' => '_PhpScoperb75b35f52b74\\Twig\\Loader\\SourceContextLoaderInterface', 'Twig_NodeTraverser' => '_PhpScoperb75b35f52b74\\Twig\\NodeTraverser', 'Twig_ExtensionInterface' => '_PhpScoperb75b35f52b74\\Twig\\Extension\\ExtensionInterface', 'Twig_Node_Macro' => '_PhpScoperb75b35f52b74\\Twig\\Node\\MacroNode', 'Twig_Node_Embed' => '_PhpScoperb75b35f52b74\\Twig\\Node\\EmbedNode', 'Twig_Node_Do' => '_PhpScoperb75b35f52b74\\Twig\\Node\\DoNode', 'Twig_Node_Text' => '_PhpScoperb75b35f52b74\\Twig\\Node\\TextNode', 'Twig_Node_Set' => '_PhpScoperb75b35f52b74\\Twig\\Node\\SetNode', 'Twig_Node_Sandbox' => '_PhpScoperb75b35f52b74\\Twig\\Node\\SandboxNode', 'Twig_Node_AutoEscape' => '_PhpScoperb75b35f52b74\\Twig\\Node\\AutoEscapeNode', 'Twig_Node_With' => '_PhpScoperb75b35f52b74\\Twig\\Node\\WithNode', 'Twig_Node_Include' => '_PhpScoperb75b35f52b74\\Twig\\Node\\IncludeNode', 'Twig_Node_Print' => '_PhpScoperb75b35f52b74\\Twig\\Node\\PrintNode', 'Twig_Node_Block' => '_PhpScoperb75b35f52b74\\Twig\\Node\\BlockNode', 'Twig_Node_Expression_MethodCall' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\MethodCallExpression', 'Twig_Node_Expression_Unary_Pos' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Unary\\PosUnary', 'Twig_Node_Expression_Unary_Not' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Unary\\NotUnary', 'Twig_Node_Expression_Unary_Neg' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Unary\\NegUnary', 'Twig_Node_Expression_GetAttr' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\GetAttrExpression', 'Twig_Node_Expression_Function' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\FunctionExpression', 'Twig_Node_Expression_Binary_Power' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\PowerBinary', 'Twig_Node_Expression_Binary_In' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\InBinary', 'Twig_Node_Expression_Binary_BitwiseXor' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\BitwiseXorBinary', 'Twig_Node_Expression_Binary_Concat' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\ConcatBinary', 'Twig_Node_Expression_Binary_NotEqual' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\NotEqualBinary', 'Twig_Node_Expression_Binary_Less' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\LessBinary', 'Twig_Node_Expression_Binary_And' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\AndBinary', 'Twig_Node_Expression_Binary_GreaterEqual' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\GreaterEqualBinary', 'Twig_Node_Expression_Binary_Mod' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\ModBinary', 'Twig_Node_Expression_Binary_NotIn' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\NotInBinary', 'Twig_Node_Expression_Binary_Add' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\AddBinary', 'Twig_Node_Expression_Binary_Matches' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\MatchesBinary', 'Twig_Node_Expression_Binary_EndsWith' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\EndsWithBinary', 'Twig_Node_Expression_Binary_FloorDiv' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\FloorDivBinary', 'Twig_Node_Expression_Binary_StartsWith' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\StartsWithBinary', 'Twig_Node_Expression_Binary_LessEqual' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\LessEqualBinary', 'Twig_Node_Expression_Binary_Equal' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\EqualBinary', 'Twig_Node_Expression_Binary_BitwiseAnd' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\BitwiseAndBinary', 'Twig_Node_Expression_Binary_Mul' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\MulBinary', 'Twig_Node_Expression_Binary_Range' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\RangeBinary', 'Twig_Node_Expression_Binary_Or' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\OrBinary', 'Twig_Node_Expression_Binary_Greater' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\GreaterBinary', 'Twig_Node_Expression_Binary_Div' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\DivBinary', 'Twig_Node_Expression_Binary_BitwiseOr' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\BitwiseOrBinary', 'Twig_Node_Expression_Binary_Sub' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\SubBinary', 'Twig_Node_Expression_Test_Even' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Test\\EvenTest', 'Twig_Node_Expression_Test_Defined' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Test\\DefinedTest', 'Twig_Node_Expression_Test_Sameas' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Test\\SameasTest', 'Twig_Node_Expression_Test_Odd' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Test\\OddTest', 'Twig_Node_Expression_Test_Constant' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Test\\ConstantTest', 'Twig_Node_Expression_Test_Null' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Test\\NullTest', 'Twig_Node_Expression_Test_Divisibleby' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Test\\DivisiblebyTest', 'Twig_Node_Expression_Array' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\ArrayExpression', 'Twig_Node_Expression_Binary' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Binary\\AbstractBinary', 'Twig_Node_Expression_Constant' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\ConstantExpression', 'Twig_Node_Expression_Parent' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\ParentExpression', 'Twig_Node_Expression_Test' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\TestExpression', 'Twig_Node_Expression_Filter_Default' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Filter\\DefaultFilter', 'Twig_Node_Expression_Filter' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\FilterExpression', 'Twig_Node_Expression_BlockReference' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\BlockReferenceExpression', 'Twig_Node_Expression_NullCoalesce' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\NullCoalesceExpression', 'Twig_Node_Expression_Name' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\NameExpression', 'Twig_Node_Expression_TempName' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\TempNameExpression', 'Twig_Node_Expression_Call' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\CallExpression', 'Twig_Node_Expression_Unary' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\Unary\\AbstractUnary', 'Twig_Node_Expression_AssignName' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\AssignNameExpression', 'Twig_Node_Expression_Conditional' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\ConditionalExpression', 'Twig_Node_CheckSecurity' => '_PhpScoperb75b35f52b74\\Twig\\Node\\CheckSecurityNode', 'Twig_Node_Expression' => '_PhpScoperb75b35f52b74\\Twig\\Node\\Expression\\AbstractExpression', 'Twig_Node_ForLoop' => '_PhpScoperb75b35f52b74\\Twig\\Node\\ForLoopNode', 'Twig_Node_If' => '_PhpScoperb75b35f52b74\\Twig\\Node\\IfNode', 'Twig_Node_For' => '_PhpScoperb75b35f52b74\\Twig\\Node\\ForNode', 'Twig_Node_BlockReference' => '_PhpScoperb75b35f52b74\\Twig\\Node\\BlockReferenceNode', 'Twig_Node_Flush' => '_PhpScoperb75b35f52b74\\Twig\\Node\\FlushNode', 'Twig_Node_Body' => '_PhpScoperb75b35f52b74\\Twig\\Node\\BodyNode', 'Twig_Node_Spaceless' => '_PhpScoperb75b35f52b74\\Twig\\Node\\SpacelessNode', 'Twig_Node_Import' => '_PhpScoperb75b35f52b74\\Twig\\Node\\ImportNode', 'Twig_Node_SandboxedPrint' => '_PhpScoperb75b35f52b74\\Twig\\Node\\SandboxedPrintNode', 'Twig_Node_Module' => '_PhpScoperb75b35f52b74\\Twig\\Node\\ModuleNode', 'Twig_RuntimeLoaderInterface' => '_PhpScoperb75b35f52b74\\Twig\\RuntimeLoader\\RuntimeLoaderInterface', 'Twig_BaseNodeVisitor' => '_PhpScoperb75b35f52b74\\Twig\\NodeVisitor\\AbstractNodeVisitor', 'Twig_Extensions_Extension_Text' => '_PhpScoperb75b35f52b74\\Twig\\Extensions\\TextExtension', 'Twig_Extensions_Extension_Array' => '_PhpScoperb75b35f52b74\\Twig\\Extensions\\ArrayExtension', 'Twig_Extensions_Extension_Date' => '_PhpScoperb75b35f52b74\\Twig\\Extensions\\DateExtension', 'Twig_Extensions_Extension_I18n' => '_PhpScoperb75b35f52b74\\Twig\\Extensions\\I18nExtension', 'Twig_Extensions_Extension_Intl' => '_PhpScoperb75b35f52b74\\Twig\\Extensions\\IntlExtension', 'Twig_Extensions_TokenParser_Trans' => '_PhpScoperb75b35f52b74\\Twig\\Extensions\\TokenParser\\TransTokenParser', 'Twig_Extensions_Node_Trans' => '_PhpScoperb75b35f52b74\\Twig\\Extensions\\Node\\TransNode']]]);
    $services->set(\_PhpScoperb75b35f52b74\Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector::class)->call('configure', [[\_PhpScoperb75b35f52b74\Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector::NAMESPACE_PREFIXES_WITH_EXCLUDED_CLASSES => \_PhpScoperb75b35f52b74\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \_PhpScoperb75b35f52b74\Rector\Generic\ValueObject\PseudoNamespaceToNamespace('Twig_')])]]);
};
