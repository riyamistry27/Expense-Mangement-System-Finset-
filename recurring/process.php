<?php

require_once __DIR__ . "/../config/database.php";
if (!isset($_SESSION['user_id'])) {
    return;
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$result = mysqli_query($conn,"
    SELECT * FROM recurring_transactions
    WHERE user_id='$user_id'
    AND status='active'
    AND next_run <= '$today'
");

while($row = mysqli_fetch_assoc($result)){

    $next_run = $row['next_run'];

    while($next_run <= $today){

        if($row['type'] == 'income'){

            mysqli_query($conn,"
                INSERT INTO income
                (user_id,amount,income_date,source)
                VALUES
                ('$user_id','".$row['amount']."','$next_run','".$row['description']."')
            ");

        }else{

            mysqli_query($conn,"
                INSERT INTO expenses
                (user_id,category_id,amount,expense_date,description)
                VALUES
                ('$user_id','".$row['category_id']."','".$row['amount']."','$next_run','".$row['description']."')
            ");

        }

        if(!empty($row['goal_id'])){
            mysqli_query($conn,"
                UPDATE savings_goals
                SET saved_amount = saved_amount + '".$row['amount']."'
                WHERE id='".$row['goal_id']."'
            ");
        }

        if($row['frequency']=='monthly'){
            $next_run = date('Y-m-d', strtotime("+1 month", strtotime($next_run)));
        }
        elseif($row['frequency']=='weekly'){
            $next_run = date('Y-m-d', strtotime("+1 week", strtotime($next_run)));
        }
        else{
            $next_run = date('Y-m-d', strtotime("+1 year", strtotime($next_run)));
        }

    }

    mysqli_query($conn,"
        UPDATE recurring_transactions
        SET next_run='$next_run'
        WHERE id='".$row['id']."'
    ");
}
?>