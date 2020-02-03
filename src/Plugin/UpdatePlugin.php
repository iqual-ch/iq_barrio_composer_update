<?php

namespace iqual\Composer\Plugin;

use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

/**
 * Composer plugin for handling pagedesigner updates.
 */
class UpdatePlugin implements PluginInterface, EventSubscriberInterface, Capable {

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {

  }

  /**
   * {@inheritdoc}
   */
  public function getCapabilities() {
    return [
      'Composer\Plugin\Capability\CommandProvider' => 'DrupalComposer\DrupalScaffold\CommandProvider',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      PackageEvents::PRE_PACKAGE_UPDATE => 'prePackage',
      PackageEvents::POST_PACKAGE_UPDATE => 'postPackage',
    ];
  }

  /**
   * Pre package event, copies _definitions.scss file to /themes/custom folder.
   *
   * @param \Composer\Installer\PackageEvent $event
   *   Event before update.
   */
  public function prePackage(PackageEvent $event) {
    $this->handler->onPrePackageEvent($event);
  }

  /**
   * Post package, moves copy of _definitions.scss file back.
   *
   * @param \Composer\Installer\PackageEvent $event
   *   Event after update.
   */
  public function postPackage(PackageEvent $event) {
    $this->handler->onPostPackageEvent($event);
  }

}
