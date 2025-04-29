(function ($, Drupal, once, drupalSettings) {
  Drupal.behaviors.articleReportingChart = {
    attach: function (context, settings) {
      once('article-reporting-chart', '#article-reporting-chart', context).forEach(function (element) {
        var ctx = element.getContext('2d');

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
      });
    }
  };
})(jQuery, Drupal, once, drupalSettings);
