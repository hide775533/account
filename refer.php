<?php
//エラー出力強制
ini_set( 'display_errors', 1 ); // エラーを画面に表示(1を0にすると画面上にはエラーは出ない)
error_reporting( E_ALL );


// フォームからの値の受け取りと保持
$targetDate = $_GET['targetDate'];
$referHistory = $_GET['referHistory'];
$itemName = $_GET['itemName'];
$aggregateType = $_GET['aggregateType'];

//変数宣言
$user = 'root';
$password = 'root';
$db = 'account';
$host = 'localhost';
$port = 3306;
$regString = (int)substr($targetDate,3,1)-1;
$targetDateStart = $referHistory != '1' ? $targetDate : substr_replace($targetDate, $regString, 3, 1);
?>


<html>
<head>
    <title>account</title>
    <link href="site.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.css"/> 
</head>
<body>

<?php
//ヘッダー
include('menu.html');
?>

<!--検索条件-->
<h2>検索条件</h2>
<form action='refer.php' method='get'>
    日付
    <select id='targetDate' name='targetDate'></select>
    <input id='referHistory' type="checkbox" name="referHistory" value="1">過去１年分の履歴を参照
    <br/>
    項目名
    <input id='itemName' type='text' name='itemName'>
    <br/>
    出力タイプ
    <input id='aggregateTypeAggrigate' type="radio" name="aggregateType" value="aggrigate" checked>集計
    <input id='aggregateTypeDetail' type="radio" name="aggregateType" value="detail">詳細
    <br/>
    <button id='search' type='submit' class='notDisplay'>検索</button>
</form>


<!--検索結果-->
<h2>検索結果</h2>


<?php
//検索条件
echo "";
echo "日付：<span id='searchValTargetDateStart'>".$targetDateStart."</span>";
echo "〜<span id='searchValTargetDate'>".$targetDate."</span>";
echo "、項目名：<span id='searchValItemName'>".$itemName."</span>";
echo "、出力タイプ：<span id='searchValAggregateType'>".$aggregateType."</span>";
?>


<!--グラフ-->
<div id="chartContainer"></div>


<!--データ-->
<div id="tableContainer">
<table id="datatable" class="table table-bordered">
    <thead>
        <tr>
            <th>収支</th>
            <th>日付</th>
            <th>金額</th>
            <th>項目名</th>
            <th>比率</th>
            <th>予算額</th>
            <th>予算額との差</th>
            <th>内訳</th> 
            <th>備考</th>
        </tr>
    </thead>
<?php

//データの取得
require "getTableData.php";
$rows = array();
$rows = getTableData($host, $user, $password, $db, $aggregateType ,$targetDateStart, $targetDate, $itemName);


//合計金額の計算
$AmountOfMoneyTotal = 0;
$BudgetAmountTotal = 0;
$DifferenceFromBudgetTotal = 0;
foreach ($rows as $value) {
    $AmountOfMoneyTotal += $value['AmountOfMoney'];
    $BudgetAmountTotal += $value['BudgetAmount'];
}
$DifferenceFromBudgetTotal = $BudgetAmountTotal - $AmountOfMoneyTotal;


//データ行の出力
echo "<tbody>";
foreach ($rows as $value) {
    //比率の計算
    $raito = round($value['AmountOfMoney'] / $AmountOfMoneyTotal * 100,0);
    $raitoVisual = "<label>".$raito."%<progress value=".$raito." max='40'><span>".$raito."</span>%</progress></label>";
    //表示
    echo "<tr>";
        echo "<td>".$value['BalanceOfPayment']."</td>";
        echo "<td>".$value['TargetDate']."</td>";
        echo "<td class='number'>".number_format($value['AmountOfMoney'])."</td>";
        echo "<td>".$value['ItemName']."</td>";
        echo "<td class='number'>".$raitoVisual."</td>"; 
        echo "<td class='number'>".number_format($value['BudgetAmount'])."</td>";
        echo "<td class='number'>".number_format($value['DifferenceFromBudget'])."</td>";
        echo "<td>".$value['BreakdownName']."</td>";
        echo "<td>".$value['Remarks']."</td>"; 
    echo "</tr>";
}
echo "</tbody>";


?>
    <!--フッター-->
    <tfoot>
        <tr>
            <td>合計</td>
            <td></td>
            <td class='number'><?= number_format($AmountOfMoneyTotal) ?></td>
            <td></td>
            <td></td>
            <td class='number'><?= number_format($BudgetAmountTotal) ?></td>
            <td class='number'><?= number_format($DifferenceFromBudgetTotal) ?></td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>

</table>
</div>
</body>
</html>



<!--js-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js"></script>
<script src="https://cdn.datatables.net/t/bs-3.3.6/jqc-1.12.0,dt-1.10.11/datatables.min.js"></script>
<script src="js/refer.js"></script>
