(function () {

    "use strict";

    var atual = 0;
    var evtSource = new EventSource('sse/event?atual=' + atual);
    var chart;

    var chartListener = function (event) {

        $(".chart-main").each(function () {
            var el = $(this);

            var json = JSON.parse(event.data);

            json.yaxis.labels.formatter = function (value) {
                return (value === null) ? "" : value.toLocaleString("pt-BR", {
                    minimumFractionDigits: json.extra.decimals,
                    maximumFractionDigits: json.extra.decimals
                }) + " " + json.extra.unit;
            };

            json.tooltip.x.formatter = function (value, {series, seriesIndex, dataPointIndex, w}) {
                return json.extra.tooltip.title[dataPointIndex];
            };

            json.tooltip.y.formatter = function (value) {
                return (value === null) ? "" : value.toLocaleString("pt-BR", {
                    minimumFractionDigits: json.extra.tooltip.decimals,
                    maximumFractionDigits: json.extra.tooltip.decimals
                }) + " " + json.extra.unit;
            };

            $(".total").html(json.extra.custom.period);

            $(".card-unidade").each(function (i) {
                $(this).find('.last-unidade').html(json.extra.units.last[$(this).data("unidade")] + ' <small>L</small>');
                $(this).find('.total-unidade').html(json.extra.units.total[$(this).data("unidade")] + ' <small>L</small>');
                $(this).find('.status').children().remove();
                $(this).find('.status').append('<i class="card-body-online float-right fas fa-circle status" title=""></i>');
                $(this).find('.status').children().addClass(json.extra.units.status[$(this).data("unidade")])
            });

            if (json.hasOwnProperty('extra')) {
                if (json.extra.hasOwnProperty('footer')) {
                    el.parent().parent().parent().children().remove(".card-footer");
                    el.parent().parent().parent().append(json.extra.footer);
                }
            }

            if (chart) {
                chart.updateOptions(json);
            } else {
                chart = new ApexCharts(el[0], json);
                chart.render();
            }
        });
    }

    var timestampListener = function (event) {

        var atual = event.data;

        evtSource.removeEventListener('chart', chartListener);
        evtSource.removeEventListener('timestamp', timestampListener);

        evtSource.close();
        evtSource = null;

        setTimeout(function () {

            evtSource = new EventSource('sse/event?atual=' + atual);

            evtSource.addEventListener('chart', chartListener, false);
            evtSource.addEventListener('timestamp', timestampListener, false);

        }, 300000);

    }

    evtSource.addEventListener('chart', chartListener, false);
    evtSource.addEventListener('timestamp', timestampListener, false);

    $(".flip").flip({
        trigger: 'manual'
    });

    setInterval( function () {
        $(".flip").each(function(i){
            let el = $(this);
            setTimeout(function () {
                el.flip('toggle');
            }, 100 * i);
        });
    }, 300000 );

}.apply(this, [jQuery]));