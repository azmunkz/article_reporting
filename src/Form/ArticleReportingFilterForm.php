<?php

namespace  Drupal\article_reporting\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a filter form for Article Reporting
 */
class ArticleReportingFilterForm extends FormBase{

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'article_reporting_filter_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $start_date = NULL, $end_date = NULL) {

    $start_date = $start_date ?: date('Y-m-01');
    $end_date = $end_date ?: date('Y-m-d');

    $form['start_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Start Date'),
      '#default_value' => $start_date,
      '#required' => TRUE
    ];

    $form['end_date'] = [
      '#type' => 'date',
      '#title' => $this->t('End Date'),
      '#default_value' => $end_date,
      '#required' => TRUE
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions'] = [
      '#type' => 'submit',
      '#value' => $this->t('Apply filter'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm ( array &$form, FormStateInterface $form_state) {
    // Redirect with query parameters.
    $range = $form_state->getValue('range');
    //$form_state->setRedirect('article_reporting.dashboard', ['range' => $range]);
    $form_state->setRedirect('article_reporting.dashboard', [], [
      'query' => [
        'start_date' => $form_state->getValue('start_date'),
        'end_date' => $form_state->getValue('end_date')
      ],
    ]);
  }

}
