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
    $updatedPackage = $event->getOperation()->getTargetPackage();
    if ($updatedPackage->getName() == 'iqual/iq_barrio') {
      if (file_exists('/var/www/public/themes/custom/iq_barrio/resources/sass/_definitions.scss')) {
        copy('/var/www/public/themes/custom/iq_barrio/resources/sass/_definitions.scss', '/var/www/public/themes/custom/_definitions.scss.tmp');
      }
    }
  }

  /**
   * Post package, moves copy of _definitions.scss file back.
   *
   * @param \Composer\Installer\PackageEvent $event
   *   Event after update.
   */
  public function postPackage(PackageEvent $event) {
    $updatedPackage = $event->getOperation()->getTargetPackage();
    if ($updatedPackage->getName() == 'iqual/iq_barrio') {
      if (file_exists('/var/www/public/themes/custom/_definitions.scss.tmp')) {
        rename('/var/www/public/themes/custom/_definitions.scss.tmp', '/var/www/public/themes/custom/iq_barrio/resources/sass/_definitions.scss');
      }
    }
  }

}
