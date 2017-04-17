    //年月ドロップダウンリストの初期値の設定
    var dt = new Date();
    var year = dt.getFullYear();
    var month = dt.getMonth() + 1;
    for (var i=0; i<12; i++) {
        taeget = year + '/' + ('0' + month).slice(-2); 
        $('#targetDate').append($('<option>').html(taeget).val(taeget.replace('/','')));
        month -= 1;
        if (month == 0) {
            month = 12;
            year -= 1;
        }
    }

    //初期値の設定
    $('#targetDate').val($('#searchValTargetDate').text());
    if ($('#searchValTargetDateStart').text() == $('#searchValTargetDate').text()) {
        $('#referHistory').prop('checked', false);
    } else {
        $('#referHistory').prop('checked', true);
    }
    $('#itemName').val($('#searchValItemName').text());
    if ($('#searchValAggregateType').text() == 'detail') {
        $('#aggregateTypeDetail').prop('checked', true);
    } else {
        $('#aggregateTypeAggrigate').prop('checked', true);
    }


    // 検索条件を変更した場合は再検索
    $('#targetDate, #referHistory, #itemName, #aggregateTypeDetail, #aggregateTypeAggrigate').on('change', function(){
        $('#search').click();
    });


    //日付の開始と終了が同じ場合、または明細の場合、グラフは非表示
    if ($('#aggregateTypeAggrigate').prop('checked') && $('#referHistory').prop('checked')) {
        $('#chartContainer').show();
        $('#tableContainer').css('margin-top', '450');
    } else {
        $('#chartContainer').hide();
        $('#tableContainer').css('margin-top', '0');
    }


    //テーブルの値による書式設定
    var data = [];
    var chartData = [];
    var chartDataA = [];
    var chartDataB = [];
    var chartDataC = [];
    var chartDataD = [];
    var chartDataE = [];
    var chartDataF = [];
    var chartDataG = [];
    var chartDataH = [];
    var chartDataI = [];
    var chartDataJ = [];
    var dataPlot = [];
    var tr = $("table tbody tr");
    //全行を取得
    for (var i=0, l=tr.length; i<l; i++) {
        var cells = tr.eq(i).children();
        //1行目から順に列を取得
        for (var j=0, m=cells.length; j<m; j++) {
            if (typeof data[i] == "undefined") {
                data[i] = [];
            }
            //i行目j列の文字列を取得し、格納
            data[i][j] = cells.eq(j).text();
        }
        //グラフ用の値
        //chartData[chartData.length] = { label: data[i][3],   y: parseInt(data[i][2].replace(',','')) };
        //chartDataA[i-1] = { label: data[i][3],   y: parseInt(data[i][2].replace(',','')) };
        //alert(data[i][1] + ',' + data[i][3] + ',' + data[i][2].replace(',',''));

        var itemName = data[i][3];
        switch (itemName) {
            case '二輪':
                chartDataA.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
                break;
            case '交際費':
                chartDataB.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
                break;
            case '仕事':
                chartDataC.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
                break;
            case '本':
                chartDataD.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
                break;
            case '生活':
                chartDataE.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
                break;
            case '趣味':
                chartDataF.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
                break;
            case '通信費':
                chartDataG.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
                break;
            case '食費':
                chartDataH.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
                break;
            case '山・岩':
                chartDataI.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
                break;
            default:
                chartDataJ.push({ label: data[i][1],   y: parseInt(data[i][2].replace(',','')) });
        }
    }
    //dataPlot.push(chartDataA_All);
    //dataPlot.push(chartDataB_All);
    var dataPlot = [{
    type: 'spline',
    legendText: '二輪',
    showInLegend: true,
    dataPoints: chartDataA
    },
    {
    type: 'spline',
    legendText: '交際費',
    showInLegend: true,
    dataPoints: chartDataB
    },
    {
    type: 'spline',
    legendText: '仕事',
    showInLegend: true,
    dataPoints: chartDataC
    },
    {
    type: 'spline',
    legendText: '本',
    showInLegend: true,
    dataPoints: chartDataD
    },
    {
    type: 'spline',
    legendText: '生活',
    showInLegend: true,
    dataPoints: chartDataE
    },
    {
    type: 'spline',
    legendText: '趣味',
    showInLegend: true,
    dataPoints: chartDataF
    },
    {
    type: 'spline',
    legendText: '通信費',
    showInLegend: true,
    dataPoints: chartDataG
    },
    {
    type: 'spline',
    legendText: '食費',
    showInLegend: true,
    dataPoints: chartDataH
    },
    {
    type: 'spline',
    legendText: '山・岩',
    showInLegend: true,
    dataPoints: chartDataI
    },
    {
    type: 'spline',
    legendText: 'その他',
    showInLegend: true,
    dataPoints: chartDataJ
    }
    ]

    //チャートの生成
    var chart = new CanvasJS.Chart("chartContainer", {
        theme: 'theme2',
        //width: 420,
        //height: 300,
        // data: [{
        //     type: 'spline',
        //     dataPoints: dataPlot
        // }]
        data: dataPlot  
    });
    chart.render();


    //テーブルの書式設定と日本語化
    var data = [];
    var tr = $("table tr");
    //全行を取得
    for (var i=0, l=tr.length; i<l; i++) {
        var cells = tr.eq(i).children();
        //1行目から順にth、td問わず列を取得
        for (var j=0, m=cells.length; j<m; j++) {
            if (typeof data[i] == "undefined") {
                data[i] = [];
            }
            //i行目j列の文字列を取得し、格納
            data[i][j] = cells.eq(j).text();
        }
        //収入の場合、行全体を青色
        if (data[i][0] == '収入') {
            $('table tr').eq(i).css('backgroundColor','#D3EDFB');
        }
        //金額が予算を超える場合、行全体を赤文字
        if (data[i][5].replace(',','') > 0 && data[i][6].replace(',','') < 0) {
            $('table tr').eq(i).css('color','red');
        }
    }
    //dataTables
    $.extend( $.fn.dataTable.defaults, { 
        language: {
            url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json"
        } 
    });
    jQuery(function($){
        $("#datatable").dataTable({
            stateSave: true,
            scrollX: true,
            //scrollY: 200
            displayLength: 100,
        });
    });