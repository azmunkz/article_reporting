<?php

namespace Drupal\article_reporting\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\AdminContext;
use Drupal\Core\Url;

/**
 * Subscribes to admin requests and checks if Scheduler is enabled.
 */
class SchedulerCheckSubscriber implements EventSubscriberInterface {

  protected $moduleHandler;
  protected $messenger;
  protected $adminContext;

  public function __construct(ModuleHandlerInterface $module_handler, MessengerInterface $messenger, AdminContext $admin_context) {
    $this->moduleHandler = $module_handler;
    $this->messenger = $messenger;
    $this->adminContext = $admin_context;
  }

  public function onKernelRequest(RequestEvent $event) {
    // Only show on admin routes
    if ($this->adminContext->isAdminRoute() && !$this->moduleHandler->moduleExists('scheduler')) {
      $link = Url::fromRoute('system.modules_list', [], ['absolute' => TRUE])->toString() . '#module-scheduler';

      $this->messenger->addWarning(t('The <strong>Scheduler</strong> module is not enabled. Article Reporting will fallback to created date. <a href=":link"><strong>Enable Scheduler Module</strong></a>', [
        ':link' => $link,
      ]));
    }
  }

  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => ['onKernelRequest'],
    ];
  }

}
