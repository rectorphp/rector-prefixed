<?php

declare (strict_types=1);
namespace RectorPrefix20210120;

use Rector\Generic\ValueObject\PseudoNamespaceToNamespace;
use Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use RectorPrefix20210120\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\RectorPrefix20210120\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['Twig_LoaderInterface' => 'Twig\\Loader\\LoaderInterface', 'Twig_Extension_StringLoader' => 'Twig\\Extension\\StringLoaderExtension', 'Twig_Extension_Optimizer' => 'Twig\\Extension\\OptimizerExtension', 'Twig_Extension_Debug' => 'Twig\\Extension\\DebugExtension', 'Twig_Extension_Sandbox' => 'Twig\\Extension\\SandboxExtension', 'Twig_Extension_Profiler' => 'Twig\\Extension\\ProfilerExtension', 'Twig_Extension_Escaper' => 'Twig\\Extension\\EscaperExtension', 'Twig_Extension_Staging' => 'Twig\\Extension\\StagingExtension', 'Twig_Extension_Core' => 'Twig\\Extension\\CoreExtension', 'Twig_Node' => 'Twig\\Node\\Node', 'Twig_NodeVisitor_Optimizer' => 'Twig\\NodeVisitor\\OptimizerNodeVisitor', 'Twig_NodeVisitor_SafeAnalysis' => 'Twig\\NodeVisitor\\SafeAnalysisNodeVisitor', 'Twig_NodeVisitor_Sandbox' => 'Twig\\NodeVisitor\\SandboxNodeVisitor', 'Twig_NodeVisitor_Escaper' => 'Twig\\NodeVisitor\\EscaperNodeVisitor', 'Twig_SimpleFunction' => 'Twig\\TwigFunction', 'Twig_Function' => 'Twig\\TwigFunction', 'Twig_Error_Syntax' => 'Twig\\Error\\SyntaxError', 'Twig_Error_Loader' => 'Twig\\Error\\LoaderError', 'Twig_Error_Runtime' => 'Twig\\Error\\RuntimeError', 'Twig_TokenParser' => 'Twig\\TokenParser\\AbstractTokenParser', 'Twig_TokenParserInterface' => 'Twig\\TokenParser\\TokenParserInterface', 'Twig_CacheInterface' => 'Twig\\Cache\\CacheInterface', 'Twig_NodeVisitorInterface' => 'Twig\\NodeVisitor\\NodeVisitorInterface', 'Twig_Profiler_NodeVisitor_Profiler' => 'Twig\\Profiler\\NodeVisitor\\ProfilerNodeVisitor', 'Twig_Profiler_Dumper_Text' => 'Twig\\Profiler\\Dumper\\TextDumper', 'Twig_Profiler_Dumper_Base' => 'Twig\\Profiler\\Dumper\\BaseDumper', 'Twig_Profiler_Dumper_Blackfire' => 'Twig\\Profiler\\Dumper\\BlackfireDumper', 'Twig_Profiler_Dumper_Html' => 'Twig\\Profiler\\Dumper\\HtmlDumper', 'Twig_Profiler_Node_LeaveProfile' => 'Twig\\Profiler\\Node\\LeaveProfileNode', 'Twig_Profiler_Node_EnterProfile' => 'Twig\\Profiler\\Node\\EnterProfileNode', 'Twig_Error' => 'Twig\\Error\\Error', 'Twig_ExistsLoaderInterface' => 'Twig\\Loader\\ExistsLoaderInterface', 'Twig_SimpleTest' => 'Twig\\TwigTest', 'Twig_Test' => 'Twig\\TwigTest', 'Twig_FactoryRuntimeLoader' => 'Twig\\RuntimeLoader\\FactoryRuntimeLoader', 'Twig_NodeOutputInterface' => 'Twig\\Node\\NodeOutputInterface', 'Twig_SimpleFilter' => 'Twig\\TwigFilter', 'Twig_Filter' => 'Twig\\TwigFilter', 'Twig_Loader_Chain' => 'Twig\\Loader\\ChainLoader', 'Twig_Loader_Array' => 'Twig\\Loader\\ArrayLoader', 'Twig_Loader_Filesystem' => 'Twig\\Loader\\FilesystemLoader', 'Twig_Cache_Null' => 'Twig\\Cache\\NullCache', 'Twig_Cache_Filesystem' => 'Twig\\Cache\\FilesystemCache', 'Twig_NodeCaptureInterface' => 'Twig\\Node\\NodeCaptureInterface', 'Twig_Extension' => 'Twig\\Extension\\AbstractExtension', 'Twig_TokenParser_Macro' => 'Twig\\TokenParser\\MacroTokenParser', 'Twig_TokenParser_Embed' => 'Twig\\TokenParser\\EmbedTokenParser', 'Twig_TokenParser_Do' => 'Twig\\TokenParser\\DoTokenParser', 'Twig_TokenParser_From' => 'Twig\\TokenParser\\FromTokenParser', 'Twig_TokenParser_Extends' => 'Twig\\TokenParser\\ExtendsTokenParser', 'Twig_TokenParser_Set' => 'Twig\\TokenParser\\SetTokenParser', 'Twig_TokenParser_Sandbox' => 'Twig\\TokenParser\\SandboxTokenParser', 'Twig_TokenParser_AutoEscape' => 'Twig\\TokenParser\\AutoEscapeTokenParser', 'Twig_TokenParser_With' => 'Twig\\TokenParser\\WithTokenParser', 'Twig_TokenParser_Include' => 'Twig\\TokenParser\\IncludeTokenParser', 'Twig_TokenParser_Block' => 'Twig\\TokenParser\\BlockTokenParser', 'Twig_TokenParser_Filter' => 'Twig\\TokenParser\\FilterTokenParser', 'Twig_TokenParser_If' => 'Twig\\TokenParser\\IfTokenParser', 'Twig_TokenParser_For' => 'Twig\\TokenParser\\ForTokenParser', 'Twig_TokenParser_Flush' => 'Twig\\TokenParser\\FlushTokenParser', 'Twig_TokenParser_Spaceless' => 'Twig\\TokenParser\\SpacelessTokenParser', 'Twig_TokenParser_Use' => 'Twig\\TokenParser\\UseTokenParser', 'Twig_TokenParser_Import' => 'Twig\\TokenParser\\ImportTokenParser', 'Twig_ContainerRuntimeLoader' => 'Twig\\RuntimeLoader\\ContainerRuntimeLoader', 'Twig_SourceContextLoaderInterface' => 'Twig\\Loader\\SourceContextLoaderInterface', 'Twig_NodeTraverser' => 'Twig\\NodeTraverser', 'Twig_ExtensionInterface' => 'Twig\\Extension\\ExtensionInterface', 'Twig_Node_Macro' => 'Twig\\Node\\MacroNode', 'Twig_Node_Embed' => 'Twig\\Node\\EmbedNode', 'Twig_Node_Do' => 'Twig\\Node\\DoNode', 'Twig_Node_Text' => 'Twig\\Node\\TextNode', 'Twig_Node_Set' => 'Twig\\Node\\SetNode', 'Twig_Node_Sandbox' => 'Twig\\Node\\SandboxNode', 'Twig_Node_AutoEscape' => 'Twig\\Node\\AutoEscapeNode', 'Twig_Node_With' => 'Twig\\Node\\WithNode', 'Twig_Node_Include' => 'Twig\\Node\\IncludeNode', 'Twig_Node_Print' => 'Twig\\Node\\PrintNode', 'Twig_Node_Block' => 'Twig\\Node\\BlockNode', 'Twig_Node_Expression_MethodCall' => 'Twig\\Node\\Expression\\MethodCallExpression', 'Twig_Node_Expression_Unary_Pos' => 'Twig\\Node\\Expression\\Unary\\PosUnary', 'Twig_Node_Expression_Unary_Not' => 'Twig\\Node\\Expression\\Unary\\NotUnary', 'Twig_Node_Expression_Unary_Neg' => 'Twig\\Node\\Expression\\Unary\\NegUnary', 'Twig_Node_Expression_GetAttr' => 'Twig\\Node\\Expression\\GetAttrExpression', 'Twig_Node_Expression_Function' => 'Twig\\Node\\Expression\\FunctionExpression', 'Twig_Node_Expression_Binary_Power' => 'Twig\\Node\\Expression\\Binary\\PowerBinary', 'Twig_Node_Expression_Binary_In' => 'Twig\\Node\\Expression\\Binary\\InBinary', 'Twig_Node_Expression_Binary_BitwiseXor' => 'Twig\\Node\\Expression\\Binary\\BitwiseXorBinary', 'Twig_Node_Expression_Binary_Concat' => 'Twig\\Node\\Expression\\Binary\\ConcatBinary', 'Twig_Node_Expression_Binary_NotEqual' => 'Twig\\Node\\Expression\\Binary\\NotEqualBinary', 'Twig_Node_Expression_Binary_Less' => 'Twig\\Node\\Expression\\Binary\\LessBinary', 'Twig_Node_Expression_Binary_And' => 'Twig\\Node\\Expression\\Binary\\AndBinary', 'Twig_Node_Expression_Binary_GreaterEqual' => 'Twig\\Node\\Expression\\Binary\\GreaterEqualBinary', 'Twig_Node_Expression_Binary_Mod' => 'Twig\\Node\\Expression\\Binary\\ModBinary', 'Twig_Node_Expression_Binary_NotIn' => 'Twig\\Node\\Expression\\Binary\\NotInBinary', 'Twig_Node_Expression_Binary_Add' => 'Twig\\Node\\Expression\\Binary\\AddBinary', 'Twig_Node_Expression_Binary_Matches' => 'Twig\\Node\\Expression\\Binary\\MatchesBinary', 'Twig_Node_Expression_Binary_EndsWith' => 'Twig\\Node\\Expression\\Binary\\EndsWithBinary', 'Twig_Node_Expression_Binary_FloorDiv' => 'Twig\\Node\\Expression\\Binary\\FloorDivBinary', 'Twig_Node_Expression_Binary_StartsWith' => 'Twig\\Node\\Expression\\Binary\\StartsWithBinary', 'Twig_Node_Expression_Binary_LessEqual' => 'Twig\\Node\\Expression\\Binary\\LessEqualBinary', 'Twig_Node_Expression_Binary_Equal' => 'Twig\\Node\\Expression\\Binary\\EqualBinary', 'Twig_Node_Expression_Binary_BitwiseAnd' => 'Twig\\Node\\Expression\\Binary\\BitwiseAndBinary', 'Twig_Node_Expression_Binary_Mul' => 'Twig\\Node\\Expression\\Binary\\MulBinary', 'Twig_Node_Expression_Binary_Range' => 'Twig\\Node\\Expression\\Binary\\RangeBinary', 'Twig_Node_Expression_Binary_Or' => 'Twig\\Node\\Expression\\Binary\\OrBinary', 'Twig_Node_Expression_Binary_Greater' => 'Twig\\Node\\Expression\\Binary\\GreaterBinary', 'Twig_Node_Expression_Binary_Div' => 'Twig\\Node\\Expression\\Binary\\DivBinary', 'Twig_Node_Expression_Binary_BitwiseOr' => 'Twig\\Node\\Expression\\Binary\\BitwiseOrBinary', 'Twig_Node_Expression_Binary_Sub' => 'Twig\\Node\\Expression\\Binary\\SubBinary', 'Twig_Node_Expression_Test_Even' => 'Twig\\Node\\Expression\\Test\\EvenTest', 'Twig_Node_Expression_Test_Defined' => 'Twig\\Node\\Expression\\Test\\DefinedTest', 'Twig_Node_Expression_Test_Sameas' => 'Twig\\Node\\Expression\\Test\\SameasTest', 'Twig_Node_Expression_Test_Odd' => 'Twig\\Node\\Expression\\Test\\OddTest', 'Twig_Node_Expression_Test_Constant' => 'Twig\\Node\\Expression\\Test\\ConstantTest', 'Twig_Node_Expression_Test_Null' => 'Twig\\Node\\Expression\\Test\\NullTest', 'Twig_Node_Expression_Test_Divisibleby' => 'Twig\\Node\\Expression\\Test\\DivisiblebyTest', 'Twig_Node_Expression_Array' => 'Twig\\Node\\Expression\\ArrayExpression', 'Twig_Node_Expression_Binary' => 'Twig\\Node\\Expression\\Binary\\AbstractBinary', 'Twig_Node_Expression_Constant' => 'Twig\\Node\\Expression\\ConstantExpression', 'Twig_Node_Expression_Parent' => 'Twig\\Node\\Expression\\ParentExpression', 'Twig_Node_Expression_Test' => 'Twig\\Node\\Expression\\TestExpression', 'Twig_Node_Expression_Filter_Default' => 'Twig\\Node\\Expression\\Filter\\DefaultFilter', 'Twig_Node_Expression_Filter' => 'Twig\\Node\\Expression\\FilterExpression', 'Twig_Node_Expression_BlockReference' => 'Twig\\Node\\Expression\\BlockReferenceExpression', 'Twig_Node_Expression_NullCoalesce' => 'Twig\\Node\\Expression\\NullCoalesceExpression', 'Twig_Node_Expression_Name' => 'Twig\\Node\\Expression\\NameExpression', 'Twig_Node_Expression_TempName' => 'Twig\\Node\\Expression\\TempNameExpression', 'Twig_Node_Expression_Call' => 'Twig\\Node\\Expression\\CallExpression', 'Twig_Node_Expression_Unary' => 'Twig\\Node\\Expression\\Unary\\AbstractUnary', 'Twig_Node_Expression_AssignName' => 'Twig\\Node\\Expression\\AssignNameExpression', 'Twig_Node_Expression_Conditional' => 'Twig\\Node\\Expression\\ConditionalExpression', 'Twig_Node_CheckSecurity' => 'Twig\\Node\\CheckSecurityNode', 'Twig_Node_Expression' => 'Twig\\Node\\Expression\\AbstractExpression', 'Twig_Node_ForLoop' => 'Twig\\Node\\ForLoopNode', 'Twig_Node_If' => 'Twig\\Node\\IfNode', 'Twig_Node_For' => 'Twig\\Node\\ForNode', 'Twig_Node_BlockReference' => 'Twig\\Node\\BlockReferenceNode', 'Twig_Node_Flush' => 'Twig\\Node\\FlushNode', 'Twig_Node_Body' => 'Twig\\Node\\BodyNode', 'Twig_Node_Spaceless' => 'Twig\\Node\\SpacelessNode', 'Twig_Node_Import' => 'Twig\\Node\\ImportNode', 'Twig_Node_SandboxedPrint' => 'Twig\\Node\\SandboxedPrintNode', 'Twig_Node_Module' => 'Twig\\Node\\ModuleNode', 'Twig_RuntimeLoaderInterface' => 'Twig\\RuntimeLoader\\RuntimeLoaderInterface', 'Twig_BaseNodeVisitor' => 'Twig\\NodeVisitor\\AbstractNodeVisitor', 'Twig_Extensions_Extension_Text' => 'Twig\\Extensions\\TextExtension', 'Twig_Extensions_Extension_Array' => 'Twig\\Extensions\\ArrayExtension', 'Twig_Extensions_Extension_Date' => 'Twig\\Extensions\\DateExtension', 'Twig_Extensions_Extension_I18n' => 'Twig\\Extensions\\I18nExtension', 'Twig_Extensions_Extension_Intl' => 'Twig\\Extensions\\IntlExtension', 'Twig_Extensions_TokenParser_Trans' => 'Twig\\Extensions\\TokenParser\\TransTokenParser', 'Twig_Extensions_Node_Trans' => 'Twig\\Extensions\\Node\\TransNode']]]);
    $services->set(\Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector::class)->call('configure', [[\Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector::NAMESPACE_PREFIXES_WITH_EXCLUDED_CLASSES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\PseudoNamespaceToNamespace('Twig_')])]]);
};
