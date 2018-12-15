<?php
/**
 * @file
 * Contains \Drupal\dataimport\Controller\ImportController.
 */

namespace Drupal\dataimport\Controller;

use Drupal;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Component\Utility\Unicode;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Component\Utility\Unicode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Datetime;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Controller\ControllerBase;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateMessageInterface;
use Drupal\migrate\MigrateMessage;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate_plus\Entity\Migration;
use Drupal\migrate_plus\Entity\MigrationGroup;
use Drupal\migrate_plus\Plugin\MigrationConfigEntityPluginManager;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;
use Drupal\migrate_tools\Controller\MigrationController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * ImportController.
 */
class ImportController extends ControllerBase {

  public function importlink() {
    $migration_instance = \Drupal::service('plugin.manager.migration')->createInstance('dataimport_migrate');
    $executable = new MigrateExecutable($migration_instance, new MigrateMessage());
    try {
      $migration_status = $executable->import();
    }
    catch (\Exception $e) {
      \Drupal::logger('migrate_drupal_ui')->error($e->getMessage());
      $migration_status = MigrationInterface::RESULT_FAILED;
    }
    if ($migration_status) {
      drupal_set_message($this->t('Import Successful'));
    }
    else {
      drupal_set_message($migration_status, 'error');
    }
  }
}

