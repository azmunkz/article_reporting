<?php

namespace Drupal\article_reporting\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\HttpFoundation\Request;
use Drupal\article_reporting\Form\ArticleReportingFilterForm;

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
  public function dashboard(Request $request) {
    $range = $request->query->get('range', 'month');

    // Fetch article counts
    $data = $this->fetchArticleCounts($range);

    return [
      'filter_form' => \Drupal::formBuilder()->getForm(ArticleReportingFilterForm::class),
      'dashboard' =>[
        '#theme' => 'article_reporting_dashboard',
        '#attached' => [
          'library' => [
            'article_reporting/chart',
          ],
          'drupalSettings' => [
            'article_reporting' => [
              'labels' => array_keys($data),
              'data' => array_values($data),
            ],
          ],
        ],
      ],
    ];
  }

  private function fetchArticleCounts($range) {
    $query = $this->database->select('node_field_data', 'n')
      ->fields('n', ['created'])
      ->condition('n.type', 'article')
      ->condition('n.status', 1);

    $records = $query->execute()->fetchCol();

    $results = [];

    foreach ($records as $timestamp) {
      switch ($range) {
        case 'day':
          $key = date('Y-m-d', $timestamp);
          break;

        case 'week':
          $key = date('o-W',$timestamp);
          break;

        case 'month':
          $key = date('Y-m', $timestamp);
          break;

        case 'year':
          $key = date('Y', $timestamp);
          break;
      }

      if (!isset($results[$key])) {
        $results[$key] = 0;
      }
      $results[$key]++;

    }

    ksort($results);
    return $results;
  }

}
