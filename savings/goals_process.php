<?php
session_start();
include "../config/database.php";

$user_id = $_SESSION['user_id'];

$goal_id = $_POST['goal_id'];
$amount  = $_POST['amount'];

/* ADD CONTRIBUTION */

mysqli_query($conn,"
UPDATE savings_goals
SET saved_amount = saved_amount + '$amount'
WHERE id='$goal_id' AND user_id='$user_id'
");

/* CHECK GOAL STATUS */

$goal = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT goal_name,target_amount,saved_amount
FROM savings_goals
WHERE id='$goal_id' AND user_id='$user_id'
"));

if($goal){

if($goal['saved_amount'] >= $goal['target_amount']){

/* ADD NOTIFICATION */

$message = "🎉 Congratulations! You completed your goal: ".$goal['goal_name'];

mysqli_query($conn,"
INSERT INTO notifications (user_id,message)
VALUES ('$user_id','$message')
");

}

}

echo "success";
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Contribution</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

<h3>Add Contribution</h3>

<form method="POST">

<div class="mb-3">
<label>Amount</label>
<input type="number" step="0.01" name="amount"
class="form-control" required>
</div>

<button class="btn btn-success">Add</button>

</form>

</div>

</body>
</html>