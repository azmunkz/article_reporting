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
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['range'] = [
      '#type' => 'select',
      '#title' => $this->t('Filter by'),
      '#options' => [
        'day' => $this->t('Day'),
        'week' => $this->t('Week'),
        'month' => $this->t('Month'),
        'year' => $this->t('Year'),
      ],
      '#default_value' => $form_state->getValue('range', 'month'),
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
    $form_state->setRedirect('article_reporting.dashboard', ['range' => $range]);
  }

}
