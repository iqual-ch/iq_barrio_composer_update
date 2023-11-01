<?php

namespace iqual\Composer;

use Composer\Composer;
use Composer\Installer\PackageEvent;
use Composer\IO\IOInterface;

/**
 * Core class of the plugin, contains all logic which files should be fetched.
 */
class Handler {

  final public const PRE_DRUPAL_SCAFFOLD_CMD = 'pre-drupal-scaffold-cmd';
  final public const POST_DRUPAL_SCAFFOLD_CMD = 'post-drupal-scaffold-cmd';

  /**
   * Composer instance.
   *
   * @var \Composer\Composer
   */
  protected $composer;

  /**
   * IO Interface.
   *
   * @var \Composer\IO\IOInterface
   */
  protected $io;

  /**
   * Handler constructor.
   *
   * @param \Composer\Composer $composer
   *   Composer instance.
   * @param \Composer\IO\IOInterface $io
   *   IO Interface.
   */
  public function __construct(Composer $composer, IOInterface $io) {
    $this->composer = $composer;
    $this->io = $io;
  }

  /**
   * Pre package event, copies _definitions.scss file to /themes/custom folder.
   *
   * @param \Composer\Installer\PackageEvent $event
   *   Event before update.
   */
  public function onPrePackageEvent(PackageEvent $event) {
    $updatedPackage = $event->getOperation()->getTargetPackage();
    if ($updatedPackage->getName() == 'iqual/iq_barrio') {
      $installPath = $this->composer->getInstallationManager()->getInstallPath($updatedPackage);
      $tmpPath = str_replace('/iq_barrio', '', $installPath);
      if ($installPath && file_exists($installPath . '/resources/sass/_definitions.scss')) {
        copy($installPath . '/resources/sass/_definitions.scss', $tmpPath . '/_definitions.scss.tmp');
      }
    }
  }

  /**
   * Post package, moves copy of _definitions.scss file back.
   *
   * @param \Composer\Installer\PackageEvent $event
   *   Event after update.
   */
  public function onPostPackageEvent(PackageEvent $event) {

    $updatedPackage = $event->getOperation()->getTargetPackage();
    if ($updatedPackage->getName() == 'iqual/iq_barrio') {
      $installPath = $this->composer->getInstallationManager()->getInstallPath($updatedPackage);
      $tmpPath = str_replace('/iq_barrio', '', $installPath);
      if ($installPath && file_exists($installPath . '/resources/sass/_definitions.scss')) {
        rename($tmpPath . '/_definitions.scss.tmp', $installPath . '/resources/sass/_definitions.scss');
      }
    }
  }

}
