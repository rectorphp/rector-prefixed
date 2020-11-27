<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\EventListener;

@\trigger_error(\sprintf('The "%s" class is deprecated since Symfony 4.3 and will be removed in 5.0, use LocaleAwareListener instead.', \_PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\EventListener\TranslatorListener::class), \E_USER_DEPRECATED);
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use _PhpScoperbd5d0c5f7638\Symfony\Component\HttpFoundation\Request;
use _PhpScoperbd5d0c5f7638\Symfony\Component\HttpFoundation\RequestStack;
use _PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use _PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\Event\GetResponseEvent;
use _PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\KernelEvents;
use _PhpScoperbd5d0c5f7638\Symfony\Component\Translation\TranslatorInterface;
use _PhpScoperbd5d0c5f7638\Symfony\Contracts\Translation\LocaleAwareInterface;
/**
 * Synchronizes the locale between the request and the translator.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @deprecated since Symfony 4.3, use LocaleAwareListener instead
 */
class TranslatorListener implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    private $translator;
    private $requestStack;
    /**
     * @param LocaleAwareInterface $translator
     */
    public function __construct($translator, \_PhpScoperbd5d0c5f7638\Symfony\Component\HttpFoundation\RequestStack $requestStack)
    {
        if (!$translator instanceof \_PhpScoperbd5d0c5f7638\Symfony\Component\Translation\TranslatorInterface && !$translator instanceof \_PhpScoperbd5d0c5f7638\Symfony\Contracts\Translation\LocaleAwareInterface) {
            throw new \TypeError(\sprintf('Argument 1 passed to "%s()" must be an instance of "%s", "%s" given.', __METHOD__, \_PhpScoperbd5d0c5f7638\Symfony\Contracts\Translation\LocaleAwareInterface::class, \is_object($translator) ? \get_class($translator) : \gettype($translator)));
        }
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }
    public function onKernelRequest(\_PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\Event\GetResponseEvent $event)
    {
        $this->setLocale($event->getRequest());
    }
    public function onKernelFinishRequest(\_PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\Event\FinishRequestEvent $event)
    {
        if (null === ($parentRequest = $this->requestStack->getParentRequest())) {
            return;
        }
        $this->setLocale($parentRequest);
    }
    public static function getSubscribedEvents()
    {
        return [
            // must be registered after the Locale listener
            \_PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\KernelEvents::REQUEST => [['onKernelRequest', 10]],
            \_PhpScoperbd5d0c5f7638\Symfony\Component\HttpKernel\KernelEvents::FINISH_REQUEST => [['onKernelFinishRequest', 0]],
        ];
    }
    private function setLocale(\_PhpScoperbd5d0c5f7638\Symfony\Component\HttpFoundation\Request $request)
    {
        try {
            $this->translator->setLocale($request->getLocale());
        } catch (\InvalidArgumentException $e) {
            $this->translator->setLocale($request->getDefaultLocale());
        }
    }
}