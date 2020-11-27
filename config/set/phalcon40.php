<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use Rector\Generic\Rector\StaticCall\SwapClassMethodArgumentsRector;
use Rector\Generic\ValueObject\SwapClassMethodArguments;
use Rector\Phalcon\Rector\Assign\FlashWithCssClassesToExtraCallRector;
use Rector\Phalcon\Rector\Assign\NewApplicationToToFactoryWithDefaultContainerRector;
use Rector\Phalcon\Rector\MethodCall\AddRequestToHandleMethodCallRector;
use Rector\Renaming\Rector\ConstFetch\RenameConstantRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
# https://docs.phalcon.io/4.0/en/upgrade#general-notes
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    # !!! be careful not to run this twice, since it swaps arguments back and forth
    # see https://github.com/rectorphp/rector/issues/2408#issue-534441142
    $services->set(\Rector\Generic\Rector\StaticCall\SwapClassMethodArgumentsRector::class)->call('configure', [[\Rector\Generic\Rector\StaticCall\SwapClassMethodArgumentsRector::ARGUMENT_SWAPS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\SwapClassMethodArguments('_PhpScoperbd5d0c5f7638\\Phalcon\\Model', 'assign', [0, 2, 1])])]]);
    # for class renames is better - https://docs.phalcon.io/4.0/en/upgrade#cheat-sheet
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\Adapter' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\Adapter\\AbstractAdapter', '_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\Resource' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\Component', '_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\ResourceInterface' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\ComponentInterface', '_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\ResourceAware' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\ComponentAware', '_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\ResourceInterface' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\AssetInterface', '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\MessageInterface' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Messages\\MessageInterface', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\MessageInterface' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Messages\\MessageInterface', '_PhpScoperbd5d0c5f7638\\Phalcon\\Annotations\\Adapter' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Annotations\\Adapter\\AbstractAdapter', '_PhpScoperbd5d0c5f7638\\Phalcon\\Annotations\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Annotations\\AnnotationsFactory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Application' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Application\\AbstractApplication', '_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\Resource' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\Asset', '_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\Resource\\Css' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\Asset\\Css', '_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\Resource\\Js' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\Asset\\Js', '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Backend' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache', '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Backend\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\AdapterFactory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Backend\\Apcu' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Adapter\\Apcu', '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Backend\\File' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Adapter\\Stream', '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Backend\\Libmemcached' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Adapter\\Libmemcached', '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Backend\\Memory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Adapter\\Memory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Backend\\Redis' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Adapter\\Redis', '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Exception' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Cache\\Exception\\Exception', '_PhpScoperbd5d0c5f7638\\Phalcon\\Config\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Config\\ConfigFactory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Db' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Db\\AbstractDb', '_PhpScoperbd5d0c5f7638\\Phalcon\\Db\\Adapter' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Db\\Adapter\\AbstractAdapter', '_PhpScoperbd5d0c5f7638\\Phalcon\\Db\\Adapter\\Pdo' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Db\\Adapter\\Pdo\\AbstractPdo', '_PhpScoperbd5d0c5f7638\\Phalcon\\Db\\Adapter\\Pdo\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Db\\Adapter\\PdoFactory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Dispatcher' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Dispatcher\\AbstractDispatcher', '_PhpScoperbd5d0c5f7638\\Phalcon\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Factory\\AbstractFactory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Flash' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Flash\\AbstractFlash', '_PhpScoperbd5d0c5f7638\\Phalcon\\Forms\\Element' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Forms\\Element\\AbstractElement', '_PhpScoperbd5d0c5f7638\\Phalcon\\Image\\Adapter' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Image\\Adapter\\AbstractAdapter', '_PhpScoperbd5d0c5f7638\\Phalcon\\Image\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Image\\ImageFactory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\Adapter' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\Adapter\\AbstractAdapter', '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\Adapter\\Blackhole' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\Adapter\\Noop', '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\Adapter\\File' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\Adapter\\Stream', '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\LoggerFactory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\Formatter' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Logger\\Formatter\\AbstractFormatter', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Collection' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Collection', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Collection\\Exception' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Collection\\Exception', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Message' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Messages\\Message', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\MetaData\\Files' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\MetaData\\Stream', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\Email' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\Email', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\Exclusionin' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\ExclusionIn', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\Inclusionin' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\InclusionIn', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\Ip' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\Ip', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\Numericality' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\Numericality', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\PresenceOf' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\PresenceOf', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\Regex' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\Regex', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\StringLength' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\StringLength', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\Uniqueness' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\Uniqueness', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Validator\\Url' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator\\Url', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Url' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Url', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Url\\Exception' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Url\\Exception', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\User\\Component' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Di\\Injectable', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\User\\Module' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Di\\Injectable', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\User\\Plugin' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Di\\Injectable', '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\View\\Engine' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\View\\Engine\\AbstractEngine', '_PhpScoperbd5d0c5f7638\\Phalcon\\Paginator\\Adapter' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Paginator\\Adapter\\AbstractAdapter', '_PhpScoperbd5d0c5f7638\\Phalcon\\Paginator\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Paginator\\PaginatorFactory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Session\\Adapter' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Session\\Adapter\\AbstractAdapter', '_PhpScoperbd5d0c5f7638\\Phalcon\\Session\\Adapter\\Files' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Session\\Adapter\\Stream', '_PhpScoperbd5d0c5f7638\\Phalcon\\Session\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Session\\Manager', '_PhpScoperbd5d0c5f7638\\Phalcon\\Translate\\Adapter' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Translate\\Adapter\\AbstractAdapter', '_PhpScoperbd5d0c5f7638\\Phalcon\\Translate\\Factory' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Translate\\TranslateFactory', '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\CombinedFieldsValidator' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\AbstractCombinedFieldsValidator', '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Message' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Messages\\Message', '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Message\\Group' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Messages\\Messages', '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\Validator' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Validation\\AbstractValidator', '_PhpScoperbd5d0c5f7638\\Phalcon\\Text' => '_PhpScoperbd5d0c5f7638\\Phalcon\\Helper\\Str', '_PhpScoperbd5d0c5f7638\\Phalcon\\Session\\AdapterInterface' => 'SessionHandlerInterface']]]);
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\AdapterInterface', 'isResource', 'isComponent'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\AdapterInterface', 'addResource', 'addComponent'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\AdapterInterface', 'addResourceAccess', 'addComponentAccess'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\AdapterInterface', 'dropResourceAccess', 'dropComponentAccess'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\AdapterInterface', 'getActiveResource', 'getActiveComponent'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\AdapterInterface', 'getResources', 'getComponents'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\Adapter\\Memory', 'isResource', 'isComponent'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\Adapter\\Memory', 'addResource', 'addComponent'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\Adapter\\Memory', 'addResourceAccess', 'addComponentAccess'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\Adapter\\Memory', 'dropResourceAccess', 'dropComponentAccess'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Acl\\Adapter\\Memory', 'getResources', 'getComponents'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Cli\\Console', 'addModules', 'registerModules'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Dispatcher', 'setModelBinding', 'setModelBinder'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\Manager', 'addResource', 'addAsset'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\Manager', 'addResourceByType', 'addAssetByType'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Assets\\Manager', 'collectionResourcesByType', 'collectionAssetsByType'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Http\\RequestInterface', 'isSecureRequest', 'isSecure'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Http\\RequestInterface', 'isSoapRequested', 'isSoap'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Paginator', 'getPaginate', 'paginate'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoperbd5d0c5f7638\\Phalcon\\Mvc\\Model\\Criteria', 'order', 'orderBy')])]]);
    $services->set(\Rector\Renaming\Rector\ConstFetch\RenameConstantRector::class)->call('configure', [[\Rector\Renaming\Rector\ConstFetch\RenameConstantRector::OLD_TO_NEW_CONSTANTS => ['FILTER_SPECIAL_CHARS' => 'FILTER_SPECIAL', 'FILTER_ALPHANUM' => 'FILTER_ALNUM']]]);
    $services->set(\Rector\Phalcon\Rector\Assign\FlashWithCssClassesToExtraCallRector::class);
    $services->set(\Rector\Phalcon\Rector\Assign\NewApplicationToToFactoryWithDefaultContainerRector::class);
    $services->set(\Rector\Phalcon\Rector\MethodCall\AddRequestToHandleMethodCallRector::class);
};