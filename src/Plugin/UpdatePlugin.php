<?php

namespace iqual\Composer\Plugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Drupal\Composer\Plugin\Scaffold\CommandProvider as ScaffoldCommandProvider;
use iqual\Composer\Handler;

/**
 * Composer plugin for handling pagedesigner updates.
 */
class UpdatePlugin implements PluginInterface, EventSubscriberInterface, Capable {

  /**
   * Handler.
   *
   * @var \iqual\Composer\Handler
   */
  protected $handler;

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
    $this->handler = new Handler($composer, $io);
  }

  /**
   * {@inheritdoc}
   */
  public function getCapabilities() {
    return [
      CommandProvider::class => ScaffoldCommandProvider::class,
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
