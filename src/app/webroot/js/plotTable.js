function calculateYears(minDate, maxDate) { 
  return maxDate.getFullYear() - minDate.getFullYear();
}

function isValidDate(d) {
	if ( Object.prototype.toString.call(d) !== "[object Date]" )
	  return false;
	return !isNaN(d.getTime());
      }
      
function parseDate(input) {
    var parts = input.split('/');
    // new Date(year, month [, date [, hours[, minutes[, seconds[, ms]]]]])
    month = parts[1];
    if( month == 0 )
  {
    month = 11;
  }
  else
  {
    month--;
  }
    return new Date(parts[2], month, parts[0]); // months are 0-based
}


function curveDisplay(chartId, curveId, status)
	{
		var chartVarName = chartId + '_chartPlot';
		var chart = window[chartVarName];
		if(status == undefined)
		{
			status = !chart.series[curveId].show;
		}
		
		$('#control_'+chartId+'_'+curveId).prop('checked',status);
		chart.series[curveId].show = status;
		chart.replot();
	}
  
  
  function toggleAll(chartId)
  {
	var chartVarName = chartId + '_chartPlot';
	var chart = window[chartVarName];
	var status = $('#toggle_'+chartId).prop('checked');
	var checkBox = $('#controlChart_'+chartId+' input.curveControl');
	
	checkBox.each(function(index, item){
	    $(item).prop('checked',status);
	});	
	
	for (key in chart.series) {
		chart.series[key].show = status;
	}
	
	chart.replot();
  }
  
  
  $(document).ready(function(){
    $('.chartDiv').each(function(index, item){
      
      $(item).bind("contextmenu",function(e){
                            var chartVarName = $(item).attr('id') + '_chartPlot';
			    window[chartVarName].resetZoom();
                            return false;
            
                    });
    });
  });
  
  
  
  
   function histogram(tableId, chartId, interactive)
  {
    data = {};
	data['plotData'] = {};
	data['labels'] = {};
	data['display'] = {};
	minDate = new Date();
        maxDate = new Date();
	interactive = $('#'+tableId).css('display') !== 'none';
	
    row=0;
    $('#'+tableId+' tr.plot').each(function(index, item){
      if(row > 0) //skip header
      {
        if(!interactive || $(item).css('display') !== 'none')
        {
          key = $(item).find('.date').text();
          dte = parseDate(key);
          
          if( isValidDate(dte) )
          {
            if(dte < minDate)
            {
              minDate = dte;
            }
            
            if(dte > maxDate)
            {
              maxDate = dte;
            }
            //key = dte.getFullYear() + '-' + dte.getMonth() + '-' + dte.getDate();
			// for each item with the curve classe
			$(item).find('td').each(function( index ) {
				var classNames = $(this).attr('class');
				if(classNames != undefined)
				{
					classes = classNames.split(' ');
					var pattern = /^curve_(\S*)$/
					// foreach classes
					var nbClasses = classes.length;
					for (var x=0; x < nbClasses; x++ ) {
						var res = classes[x].match(pattern);
						if(res != null )
						{
							var y = res[1];
							if(data['plotData'][y] == undefined)
							{
								data['plotData'][y] = {};
								labelItem = $('#'+tableId+'Legend').find('td.label_'+classes[x]);
								htmlLabel = labelItem.html();
								if( htmlLabel != undefined)
								{
									data['labels'][y] = htmlLabel;
								}
							}
							tmpData = parseFloat($(this).html());
                                                        if(!isNaN(tmpData))
                                                        {
                                                          if(data['plotData'][y][dte] == undefined)
                                                          {
                                                                  data['plotData'][y][dte] = 0;
                                                          }
                                                          
                                                          data['display'][y] = !($(this).hasClass('noDisplay'));

                                                          data['plotData'][y][dte] += tmpData;
                                                        }
						}
					} // foreach classes
				}
			}); // foreach find
          }
        }
      }
	  else
	  {
		$(item).find('th').each(function( index ) {
			var classNames = $(this).attr('class');
				if(classNames != undefined)
				{
					classes = classNames.split(' ');
					var pattern = /^label_curve_(\S*)$/
					// foreach classes
					var nbClasses = classes.length;
					for (var x=0; x < nbClasses; x++ ) {
						var res = classes[x].match(pattern);
						if(res != null )
						{
							var y = res[1];
							data['labels'][y] = $(this).html();
						}
					}
// 					data['display'][y] = !($(this).hasClass('noDisplay'));
				}
		});
	  }
      row++;
    });

	

	plotData = [];
	var seriesLabel = [];
	var displayCurves = [];
	for (var x in data['plotData'] ) {
		var tmpData = [];
		for(y in data['plotData'][x])
		{
			tmpData.push([y, data['plotData'][x][y]]);
		}
		seriesLabel.push({'label':data['labels'][x]});
		displayCurves.push(data['display'][x])
		plotData.push(tmpData);
	}

  
  
    var chartVarName = chartId + '_chartPlot';
    if(window[chartVarName]  != undefined)
    {
      window[chartVarName].destroy();
    }
    
    
    if(plotData.length == 0)
    {
	$('#'+chartId+'Container').hide();
      return false;
    }
    nbYears = calculateYears(minDate,maxDate);
    
    vertical_lines = [];
    
    for(i=0; i< nbYears+1; i++)
    {
      vertical_lines.push(
            {
                                verticalLine:
                                {
                                    name:'test'+i,
                                    x : new Date((minDate.getFullYear()+i)+'-01-01').getTime(),
                                    lineWidth: 5,
                                    color: 'rgba(255, 0, 0,0.45)',
                                    shadow: false,
                                    lineCap : 'butt'
                                }
                            }   
        
      );
    }

    
//     console.log(plotData);
    window[chartVarName] = jQuery.jqplot (chartId, plotData,
    {
      title: 'Histogram',
      seriesDefaults: {
        //renderer: $.jqplot.BarRenderer,
        rendererOptions: {
          fillToZero: true,
          barWidth:5,
        },
        pointLabels: { show: true }
      },
      height: 500,
      width: 600,
      series: seriesLabel,
      axes:{
        xaxis:{
          label:'Date',
          renderer:$.jqplot.DateAxisRenderer,
          numberTicks:5,
          tickOptions: {
               formatString: '%d/%m/%y'
          }
        },
        yaxis:{
          label:'Count',
		  min:0,
          pad: 1.05,
        }
      },
       highlighter: {
        show: true,
        sizeAdjust: 7.5
      },
      cursor:{
	      showVerticalLine:true,
	      showHorizontalLine:true,
              show: true,
              followMouse: true,
              zoom:true,
              height: 200,
              width: 300,
              showTooltip:false,
      },
      legend: {
              show: true,
              placement:'e'
      },
	   canvasOverlay: {
                show: true,
                objects: vertical_lines                  
            } 
    }
    
    );
	
	html = '<div id="controlChart_'+chartId+'" ><div class="alert alert-info">Clic gauche pour zoomer, clic droit pour dézoomer<br/>Cochez/décochez les cases ci-dessous pour afficher/masquer la courbe souhaitée</div><ul class="controlChart">';
	var found = false;
	var i = 0;
	for(var x in window[chartVarName].series)
	{
		label = window[chartVarName].series[x]['label'];
		$('#'+chartId).parent().find('.control').html('');
		
		var checked = '';
		if(displayCurves[x])
		{
		    checked = 'checked="checked"';
		}
		
		if( label != undefined )
		{
			found = true;
			html += '<li><input id="control_'+chartId+'_'+x+'" class="curveControl" type="checkbox" '+checked+' onchange="curveDisplay(\''+chartId+'\', '+x+')" /> <label>'+label+'</label></li>';
		}
		
		window[chartVarName].series[x].show = displayCurves[x];
	  i++;
	}
	if(found)
	{
		html += '<li><input type="checkbox" checked="checked" id="toggle_'+chartId+'" onchange="toggleAll(\''+chartId+'\')" /> <label>Tout cocher</label></li>';
	}
	html += '<ul></div>';
	$('#'+chartId).parent().find('.control').html(html);
	window[chartVarName].replot();
// 	for(i=0; i< displayCurves.length; i++)
// 	{
// 	  status = displayCurves[i];
// 	  $('#control_'+chartId+'_'+i).prop('checked',false);
// 	  console.log($('#control_'+chartId+'_'+i).prop('checked'));
// 	  window[chartVarName].series[i].show = status;
// 	  console.log(i + ' ' + window[chartVarName].series[i].show);
// 	}
	
//  	toggleAll(chartVarName);
    
    return false;
  }