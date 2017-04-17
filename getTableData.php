<?php

function getTableData($host, $user, $password, $db, $aggregateType, $targetDateStart, $targetDate, $itemName){

    // mysqliクラスのオブジェクトを作成
    $mysqli = new mysqli($host, $user, $password, $db);
    if ($mysqli->connect_error) {
        echo $mysqli->connect_error;
        exit();
    } else {
        $mysqli->set_charset("utf8");
    }

    //SQL文の作成
    if ($aggregateType == 'detail') {
        //詳細
        $sql = "SELECT
                    BalanceOfPayment
                    ,TargetDate
                    ,replace(replace(AmountOfMoney,'￥',''),',','') as AmountOfMoney
                    ,ItemName
                    ,0 as BudgetAmount
                    ,0 as DifferenceFromBudget
                    ,BreakdownName
                    ,Remarks
                from
                    data
                where
                    DATE_FORMAT(TargetDate, '%Y%m') between ? and ? ";
        if ($itemName == '') {
            $sql = $sql." and '' = ? ";
        } else {
            $sql = $sql." and ItemName = ? ";
        }
    } else {
        //項目別集計
        $sql = "SELECT
                    da1.BalanceOfPayment
                    ,DATE_FORMAT(da1.TargetDate, '%Y/%m') as TargetDate
                    ,sum(replace(replace(ifnull(da1.AmountOfMoney,0),'￥',''),',','')) as sum_AmountOfMoney
                    ,da1.ItemName
                    ,bu1.BudgetAmount
                    ,bu1.BudgetAmount - sum(replace(replace(ifnull(da1.AmountOfMoney,0),'￥',''),',','')) as DifferenceFromBudget
                    ,'' as BreakdownName
                    ,'' as Remarks
                FROM
                    budget bu1
                    left join data da1 on da1.ItemName = bu1.ItemName
                WHERE
                    DATE_FORMAT(TargetDate, '%Y%m') between ? and ? ";
        if ($itemName == '') {
            $sql = $sql." and '' = ? ";
        } else {
            $sql = $sql." and bu1.ItemName = ? ";
        }
        $sql = $sql."GROUP BY
                        da1.BalanceOfPayment
                        ,DATE_FORMAT(da1.TargetDate, '%Y/%m') 
                        ,da1.ItemName ";
    }
    //echo "<br />$sql";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sss", $targetDateStart, $targetDate, $itemName);
        $stmt->execute();
        $stmt->bind_result($BalanceOfPayment
                        ,$TargetDate
                        ,$AmountOfMoney
                        ,$ItemName
                        ,$BudgetAmount
                        ,$DifferenceFromBudget
                        ,$BreakdownName
                        ,$Remarks);

        $row = array();
        $rows = array();
        while ($stmt->fetch()) {
            //取得した値を配列に格納
            $row = array('BalanceOfPayment' => $BalanceOfPayment
                        ,'TargetDate' => $TargetDate
                        ,'AmountOfMoney' => $AmountOfMoney
                        ,'ItemName' => $ItemName
                        ,'BudgetAmount' => $BudgetAmount
                        ,'BreakdownName' => $BreakdownName
                        ,'DifferenceFromBudget' => $DifferenceFromBudget
                        ,'Remarks' => $Remarks);
            array_push($rows, $row);
        }
        $stmt->close();
    }
    // DB接続を閉じる
    $mysqli->close();

    return $rows;

}