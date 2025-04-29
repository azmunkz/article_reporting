(function ($, Drupal){
  Drupal.behaviors.articleReportingChart = {
    attach: function (context, settings) {
      if ($('#article-reporting-chart', context).once('article-reporting-chart').length) {
        var ctx = document.getElementById('article-reporting-chart').getContext('2d');

        var labels = drupalSettings.article_reporting.labels || [];
        var data = drupalSettings.article_reporting.data || [];

        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: '# of Articles',
              data: data,
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      }
    }
  }
})(jQuery, Drupal, drupalSettings);
