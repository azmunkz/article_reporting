(function ($, Drupal){
  Drupal.behaviors.articleReportingChart = {
    attach: function (context, settings) {
      if ($('#article-reporting-chart', context).once('article-reporting-chart').length) {
        var ctx = document.getElementById('article-reporting-chart').getContext('2d');

        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['Day 1', 'Day 2', 'Day 3'],
            datasets: [{
              label: '# of Articles',
              data: [5, 10, 8],
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
})(jQuery, Drupal);
