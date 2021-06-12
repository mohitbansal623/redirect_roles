<?php

namespace Drupal\redirect_roles\EventSubscriber;

use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;
use Drupal\Core\Access\AccessResult;

/**
 * Event subscriber subscribing to KernelEvents::REQUEST.
 */
class RedirectAnonymousSubscriber implements EventSubscriberInterface {

  /**
   *
   */
  public function __construct() {
    $this->account = \Drupal::currentUser();
  }

  /**
   * This function redirects the anonymous user to login page.
   */
  public function checkAuthStatus(GetResponseEvent $event) {
    $node = \Drupal::routeMatch()->getParameter('node');    
    if ($node instanceof NodeInterface && in_array('content_editor', $this->account->getRoles())) {
      if ($node->getType() == 'page') {
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
      }
    }
  }

  /**
   * Callback function getSubscribedEvents.
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkAuthStatus'];
    return $events;
  }

}
