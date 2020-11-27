<?php

declare (strict_types=1);
namespace Rector\NetteKdyby\ValueObject;

final class NetteEventToContributeEventClass
{
    /**
     * @var string[]
     * @see https://github.com/contributte/event-dispatcher-extra/tree/master/src/Event
     */
    public const PROPERTY_TO_EVENT_CLASS = [
        // application
        'Nette\\Application\\Application::onShutdown' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Application\\ShutdownEvent',
        'Nette\\Application\\Application::onStartup' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Application\\StartupEvent',
        'Nette\\Application\\Application::onError' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Application\\ErrorEvent',
        'Nette\\Application\\Application::onPresenter' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Application\\PresenterEvent',
        'Nette\\Application\\Application::onRequest' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Application\\RequestEvent',
        'Nette\\Application\\Application::onResponse' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Application\\ResponseEvent',
        // presenter
        'Nette\\Application\\UI\\Presenter::onStartup' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Application\\PresenterShutdownEvent',
        'Nette\\Application\\UI\\Presenter::onShutdown' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Application\\PresenterStartupEvent',
        // nette/security
        'Nette\\Security\\User::onLoggedIn' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Security\\LoggedInEvent',
        'Nette\\Security\\User::onLoggedOut' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Security\\LoggedOutEvent',
        // latte
        'Latte\\Engine::onCompile' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Latte\\LatteCompileEvent',
        'Nette\\Bridges\\ApplicationLatte\\TemplateFactory::onCreate' => '_PhpScoperbd5d0c5f7638\\Contributte\\Events\\Extra\\Event\\Latte\\TemplateCreateEvent',
    ];
}