(function($) {
	"use strict";

	function decimalFormat(nStr)
	{
	  if (decimalFormat == 'dot') {
		 var $decimalDot = '.';
		 var $decimalComma = ',';
	 } else {
		 var $decimalDot = ',';
		 var $decimalComma = '.';
		 }

	   if (currencyPosition == 'left'){
	   var currency_symbol_left = currencySymbol;
	   var currency_symbol_right = '';
	   } else {
	   var currency_symbol_right = currencySymbol;
	   var currency_symbol_left = '';
	   }

	    nStr += '';
	    var x = nStr.split('.');
	    var x1 = x[0];
	    var x2 = x.length > 1 ? $decimalDot + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + $decimalComma + '$2');
	    }
	    return currency_symbol_left + x1 + x2 + currency_symbol_right;
	  }

	  function transparentize(color, opacity) {
				var alpha = opacity === undefined ? 0.5 : 1 - opacity;
				return Color(color).alpha(alpha).rgbString();
			}

	  var init = document.getElementById("Chart").getContext('2d');

	  const gradient = init.createLinearGradient(0, 0, 0, 300);
	                    gradient.addColorStop(0, '#268707');
	                    gradient.addColorStop(1, '#2687072e');

	  const lineOptions = {
	                        pointRadius: 4,
	                        pointHoverRadius: 6,
	                        hitRadius: 5,
	                        pointHoverBorderWidth: 3
	                    }

	  var ChartArea = new Chart(init, {
	      type: 'line',
	      data: {
	          labels: labelChart,
	          datasets: [{
	              label: amount,
	              backgroundColor: gradient,
	              borderColor: '#268707',
	              data: dataFundsChart,
	              borderWidth: 2,
	              fill: true,
	              lineTension: 0.4,
	              ...lineOptions
	          }]
	      },
	      options: {
	          scales: {
	              yAxes: [{
	                  ticks: {
	                    min: 0, // it is for ignoring negative step.
	                     display: true,
	                      maxTicksLimit: 8,
	                      padding: 10,
	                      beginAtZero: true,
	                      callback: function(value, index, values) {
	                        var symbolLeft = currencyPosition == 'left' ? currencySymbol : '';
	                        var symbolRight = currencyPosition == 'right' ? currencySymbol : '';
	                          return  symbolLeft + value + symbolRight;
	                      }
	                  }
	              }],
	              xAxes: [{
	                gridLines: {
	                  display:false
	                },
	                display: true,
	                ticks: {
	                  maxTicksLimit: 15,
	                  padding: 5,
	                }
	              }]
	          },
	          tooltips: {
	            mode: 'index',
	            intersect: false,
	            reverse: true,
	            backgroundColor: '#000',
	            xPadding: 16,
	            yPadding: 16,
	            cornerRadius: 4,
	            caretSize: 7,
	              callbacks: {
	                  label: function(t, d) {
	                      var xLabel = d.datasets[t.datasetIndex].label;
	                      var yLabel = t.yLabel == 0 ? decimalFormat(t.yLabel) : decimalFormat(t.yLabel.toFixed(2));
	                      return xLabel + ': ' + yLabel;
	                  }
	              },
	          },
	          hover: {
	            mode: 'index',
	            intersect: false
	          },
	          legend: {
	              display: false
	          },
	          responsive: true,
	          maintainAspectRatio: false
	      }
	  });

		// Sales last 30 days
		var sales = document.getElementById("ChartSales").getContext('2d');

	  const gradientSales = sales.createLinearGradient(0, 0, 0, 300);
	                    gradientSales.addColorStop(0, '#268707');
	                    gradientSales.addColorStop(1, '#2687072e');

	  const lineOptionsSales = {
	                        pointRadius: 4,
	                        pointHoverRadius: 6,
	                        hitRadius: 5,
	                        pointHoverBorderWidth: 3
	                    }

	  var ChartArea = new Chart(sales, {
	      type: 'bar',
	      data: {
	          labels: labelChart,
	          datasets: [{
	              label: donations,
	              backgroundColor: '#268707',
	              borderColor: '#268707',
	              data: datalastDonations,
	              borderWidth: 2,
	              fill: true,
	              lineTension: 0.4,
	              ...lineOptionsSales
	          }]
	      },
	      options: {
	          scales: {
	              yAxes: [{
	                  ticks: {
	                    min: 0, // it is for ignoring negative step.
	                     display: true,
	                      maxTicksLimit: 8,
	                      padding: 10,
	                      beginAtZero: true,
	                      callback: function(value, index, values) {
	                          return value;
	                      }
	                  }
	              }],
	              xAxes: [{
	                gridLines: {
	                  display:false
	                },
	                display: true,
	                ticks: {
	                  maxTicksLimit: 15,
	                  padding: 5,
	                }
	              }]
	          },
	          tooltips: {
	            mode: 'index',
	            intersect: false,
	            reverse: true,
	            backgroundColor: '#000',
	            xPadding: 16,
	            yPadding: 16,
	            cornerRadius: 4,
	            caretSize: 7,
	              callbacks: {
	                  label: function(t, d) {
	                      var xLabel = d.datasets[t.datasetIndex].label;
	                      var yLabel = t.yLabel;
	                      return xLabel + ': ' + yLabel;
	                  }
	              },
	          },
	          hover: {
	            mode: 'index',
	            intersect: false
	          },
	          legend: {
	              display: false
	          },
	          responsive: true,
	          maintainAspectRatio: false
	      }
	  });

})(jQuery);
