<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['send'])){

$subject = $_POST['subject'];
$message = $_POST['message'];

mysqli_query($conn,"
INSERT INTO messages (user_id,subject,message)
VALUES ('$user_id','$subject','$message')
");

$success = "Message sent to admin successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Contact Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ===== GLOBAL ===== */

body{
margin:0;
background:#E5E5E5;
font-family:'Inter','Segoe UI',sans-serif;
}

/* ===== MAIN LAYOUT ===== */

.main-container{
display:flex;
}

/* ===== CONTENT ===== */

.content{
margin-left:240px;
width:calc(100% - 240px);
padding:30px;
}

/* ===== PAGE HEADER ===== */

.page-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.page-header h3{
font-weight:600;
}

/* ===== CARD ===== */

.contact-card{
border:none;
border-radius:18px;
background:#FFFFFF;
box-shadow:0 10px 30px rgba(0,0,0,0.08);
padding:30px;
max-width:600px;
margin:auto;
}

/* ===== INPUT ===== */

.form-control{
border-radius:8px;
padding:10px;
border:1px solid #d1d5db;
}

.form-control:focus{
border-color:#FCA311;
box-shadow:0 0 0 2px rgba(252,163,17,0.15);
}

/* ===== BUTTON ===== */

.btn-send{
background:#FCA311;
border:none;
color:#000;
font-weight:600;
border-radius:8px;
padding:10px 18px;
transition:0.25s;
}

.btn-send:hover{
background:#ffb733;
transform:translateY(-1px);
}

/* ===== ALERT ===== */

.alert{
max-width:600px;
margin:auto;
margin-bottom:20px;
}

/* ===== RESPONSIVE ===== */

@media(max-width:992px){

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

<div class="page-header">

<h3>
<i class="fa-solid fa-envelope"></i>
Contact Admin
</h3>

</div>


<?php if(isset($success)){ ?>

<div class="alert alert-success">
<?php echo $success; ?>
</div>

<?php } ?>


<div class="contact-card">

<form method="POST">

<div class="mb-3">

<label class="form-label">Subject</label>

<input type="text"
name="subject"
class="form-control"
placeholder="Enter subject"
required>

</div>


<div class="mb-3">

<label class="form-label">Message</label>

<textarea name="message"
class="form-control"
rows="5"
placeholder="Write your message to admin..."
required></textarea>

</div>


<button class="btn btn-send" name="send">

<i class="fa-solid fa-paper-plane"></i>
Send Message

</button>

</form>

</div>


</div>
</div>

</body>
</html>