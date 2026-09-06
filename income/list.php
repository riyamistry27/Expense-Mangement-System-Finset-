


<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM income
          WHERE user_id='$user_id'
          ORDER BY income_date DESC";

$result = mysqli_query($conn, $query);

$incomes = [];
while($row = mysqli_fetch_assoc($result)){
    $incomes[] = $row;
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Income List</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ===== GLOBAL ===== */

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
color:#000;
}

/* ===== MAIN LAYOUT ===== */

.main-container{
display:flex;
}

/* ===== SIDEBAR ===== */

.sidebar{
width:240px;
height:100vh;
background:#14213D;
color:white;
padding:25px 18px;
position:fixed;
left:0;
top:0;
display:flex;
flex-direction:column;
}

/* LOGO */

.logo{
margin-bottom:30px;
font-size:20px;
font-weight:600;
color:#FCA311;
}

/* SIDEBAR LINKS */

.sidebar a{
display:flex;
align-items:center;
gap:10px;
color:#E5E5E5;
padding:12px 14px;
margin:6px 0;
text-decoration:none;
border-radius:8px;
font-size:15px;
transition:all 0.25s ease;
}

.sidebar a:hover{
background:#FCA311;
color:#000;
transform:translateX(4px);
}

.sidebar hr{
border-color:rgba(255,255,255,0.1);
margin:20px 0;
}

/* ===== CONTENT AREA ===== */

.content{
margin-left:240px;
width:calc(100% - 240px);
padding:25px;
}

/* ===== PAGE HEADER ===== */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.page-header h3{
font-weight:600;
margin:0;
}

/* ===== ADD BUTTON ===== */

.btn-add{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
padding:8px 16px;
border-radius:8px;
transition:0.3s;
}

.btn-add:hover{
background:#ffb733;
transform:translateY(-1px);
}

/* ===== CARD ===== */

.card{
border:none;
border-radius:15px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
background:#FFFFFF;
padding:20px;
}

/* ===== TABLE ===== */

.table{
margin-top:10px;
}

.table thead{
background:#f8fafc;
}

.table th{
font-weight:600;
}

.table td{
vertical-align:middle;
}

.table-hover tbody tr:hover{
background:#f1f5f9;
}

/* ===== AMOUNT STYLE ===== */

.amount-income{
color:#16a34a;
font-weight:600;
}

/* ===== ACTION BUTTONS ===== */

.btn-edit{
background:#FCA311;
border:none;
color:#000;
font-size:13px;
padding:6px 12px;
border-radius:6px;
margin-right:5px;
}

.btn-edit:hover{
background:#ffb733;
}

.btn-delete{
background:#ef4444;
border:none;
color:white;
font-size:13px;
padding:6px 12px;
border-radius:6px;
}

.btn-delete:hover{
background:#dc2626;
}

/* ===== RESPONSIVE ===== */

@media(max-width:992px){

.sidebar{
width:200px;
}

.content{
margin-left:200px;
width:calc(100% - 200px);
}

}

</style>


</head>

<body>

<div class="main-container">

    <?php include "../includes/sidebar.php"; ?>

    <div class="content">

     

        <div class="container mt-2">

            <div class="card p-4">

                <div class="page-header">

<h3>Income List</h3>

<a href="add.php" class="btn btn-add">
<i class="fa-solid fa-plus"></i> Add Income
</a>

</div>
       
                <table class="table table-hover">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Source</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

<?php foreach($incomes as $row) { ?>
                        <tr>

                            <td><?php echo $row['id']; ?></td>

                            <td>
                                <?php echo htmlspecialchars($row['source']); ?>
                            </td>

                            <td class="amount-income">
+ ₹ <?php echo number_format($row['amount'],2); ?>
</td>

                            <td>
                                <?php echo $row['income_date']; ?>
                            </td>

                            <td>

                                <a href="edit.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-edit">
                                   Edit
                                </a>

                                <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-delete"
                                   onclick="return confirm('Delete this income?')">
                                   Delete
                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>