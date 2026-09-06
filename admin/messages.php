<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

/* ================= REPLY ================= */

if(isset($_POST['send_reply'])){

$message_id = intval($_POST['message_id']);
$reply = mysqli_real_escape_string($conn,$_POST['reply']);

$update = mysqli_query($conn,"
UPDATE messages
SET admin_reply='$reply',
status='replied',
replied_at=NOW()
WHERE id='$message_id'
");

if($update){

$get_user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT user_id FROM messages WHERE id='$message_id'
"));

$user_id = $get_user['user_id'];

mysqli_query($conn,"
INSERT INTO notifications (user_id,message)
VALUES ('$user_id','Admin replied to your support message')
");

}

header("Location: messages.php");
exit();
}

/* ================= FETCH ================= */

$query = "
SELECT messages.*, users.name, users.email
FROM messages
JOIN users ON messages.user_id = users.id
ORDER BY messages.created_at DESC
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>

<title>User Messages</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
margin:0;
background:#f1f5f9;
font-family:'Inter','Segoe UI',sans-serif;
}

/* LAYOUT */

.main-container{
display:flex;
}

/* SIDEBAR */

.sidebar{
width:240px;
height:100vh;
background:#14213D;
color:white;
padding:25px 18px;
position:fixed;
left:0;
top:0;
}

.sidebar h4{
color:#FCA311;
margin-bottom:25px;
}

.sidebar a{
display:flex;
align-items:center;
gap:10px;
color:#E5E5E5;
padding:12px 14px;
margin:6px 0;
text-decoration:none;
border-radius:8px;
transition:0.2s;
}

.sidebar a:hover{
background:#FCA311;
color:#000;
}

/* CONTENT */

.content{
margin-left:240px;
width:calc(100% - 240px);
padding:30px;
}

/* HEADER */

.topbar{
background:white;
padding:18px 22px;
border-radius:12px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 3px 10px rgba(0,0,0,0.08);
margin-bottom:25px;
}

/* CARD */

.card{
border:none;
border-radius:14px;
box-shadow:0 6px 18px rgba(0,0,0,0.08);
}

/* TABLE */

.table thead{
background:#f8fafc;
}

.table th{
font-weight:600;
}

.message-cell{
max-width:260px;
white-space:nowrap;
overflow:hidden;
text-overflow:ellipsis;
}

.btn-reply{
background:#FCA311;
border:none;
color:#000;
font-weight:500;
}

.btn-reply:hover{
background:#ffb733;
}

.modal-content{
border-radius:14px;
}

</style>

</head>

<body>

<div class="main-container">

<div class="sidebar">

<h4>Admin Panel</h4>

<a href="dashboard.php">
<i class="fa-solid fa-chart-line"></i> Dashboard
</a>

<a href="users.php">
<i class="fa-solid fa-users"></i> Users
</a>

<a href="categories.php">
<i class="fa-solid fa-layer-group"></i> Categories
</a>

<a href="messages.php">
<i class="fa-solid fa-envelope"></i> User Messages
</a>

<a href="logs.php">
<i class="fa-solid fa-file-lines"></i> Audit Logs
</a>

<a href="logout.php" class="text-danger">
<i class="fa-solid fa-right-from-bracket"></i> Logout
</a>

</div>


<div class="content">

<div class="topbar">

<h5>
<i class="fa-solid fa-envelope"></i>
User Support Messages
</h5>

<span class="badge bg-primary">
Total: <?php echo mysqli_num_rows($result); ?>
</span>

</div>


<div class="card p-4">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>
<th>ID</th>
<th>User</th>
<th>Subject</th>
<th>Message</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
<strong><?php echo htmlspecialchars($row['name']); ?></strong>
<br>
<small class="text-muted">
<?php echo htmlspecialchars($row['email']); ?>
</small>
</td>

<td>
<?php echo htmlspecialchars($row['subject']); ?>
</td>

<td class="message-cell">
<?php echo htmlspecialchars($row['message']); ?>
</td>

<td>

<?php if($row['status']=="pending"){ ?>

<span class="badge bg-warning text-dark">
Pending
</span>

<?php }else{ ?>

<span class="badge bg-success">
Replied
</span>

<?php } ?>

</td>

<td>
<?php echo date("d M Y H:i",strtotime($row['created_at'])); ?>
</td>

<td>

<button class="btn btn-sm btn-reply"
data-bs-toggle="modal"
data-bs-target="#replyModal<?php echo $row['id']; ?>">

<i class="fa-solid fa-reply"></i>
Reply

</button>

</td>

</tr>


<div class="modal fade" id="replyModal<?php echo $row['id']; ?>">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">
Reply to <?php echo htmlspecialchars($row['name']); ?>
</h5>

<button class="btn-close" data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

<p class="text-muted">
User Message
</p>

<div class="border rounded p-3 mb-3">
<?php echo htmlspecialchars($row['message']); ?>
</div>

<form method="POST">

<input type="hidden"
name="message_id"
value="<?php echo $row['id']; ?>">

<textarea
name="reply"
class="form-control"
rows="4"
required
placeholder="Write your reply..."></textarea>

<br>

<button type="submit"
name="send_reply"
class="btn btn-success">

<i class="fa-solid fa-paper-plane"></i>
Send Reply

</button>

</form>

</div>

</div>

</div>

</div>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>