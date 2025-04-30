<?php

namespace Drupal\article_reporting\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\HttpFoundation\Request;
use Drupal\article_reporting\Form\ArticleReportingFilterForm;

/**
 * Controller for Article Reporting Dashboard.
 */
class ArticleReportingController extends ControllerBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new ArticleReportingController.
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
   * Dashboard page.
   */
  public function dashboard(Request $request) {
    $start_date = $request->query->get('start_date', date('Y-m-01'));
    $end_date = $request->query->get('end_date', date('Y-m-d'));

    $data = $this->fetchArticleCountsByRange($start_date, $end_date);

    // Pass values into form.
    $form = \Drupal::formBuilder()->getForm(ArticleReportingFilterForm::class, $start_date, $end_date);

    return [
      'filter_form' => $form,
      'dashboard' => [
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

  /**
   * Fetch article counts between two dates.
   */

  private function fetchArticleCountsByRange($start_date, $end_date) {
    $start_ts = strtotime($start_date . ' 00:00:00');
    $end_ts = strtotime($end_date . ' 23:59:59');

    $query = $this->database->select('node_field_data', 'n')
      ->fields('n', ['nid', 'created', 'publish_on'])
      ->condition('n.type', 'article')
      ->condition('n.status', 1);

    $results = [];

    $data = $query->execute();

    foreach ($data as $row) {
      // Use publish_on if available, else fallback to created
      $timestamp = (!empty($row->publish_on) && $row->publish_on > 0)
        ? $row->publish_on
        : $row->created;

      if ($timestamp < $start_ts || $timestamp > $end_ts) {
        continue;
      }

      $key = date('Y-m-d', $timestamp);
      if (!isset($results[$key])) {
        $results[$key] = 0;
      }
      $results[$key]++;
    }

    ksort($results);
    return $results;
  }
}
