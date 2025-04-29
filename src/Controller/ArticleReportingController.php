<?php

namespace Drupal\article_reporting\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

/**
 * Controller for Article Reporting Dashboard
 */
class ArticleReportingController extends ControllerBase{

  /**
   * Database connection
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new ArticleReportingController
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Returns the Article Reporting Dashbpard page.
   */
  public function dashboard() {
    return [
      '#theme' => 'article_reporting_dashboard',
      '#attached' => [
        'library' => [
          'article_reporting/chart',
        ]
      ]
    ];
  }

}
