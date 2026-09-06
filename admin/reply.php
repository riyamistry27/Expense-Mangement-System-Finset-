<?php

$id = $_GET['id'];

if(isset($_POST['reply'])){

$reply = $_POST['reply_text'];

mysqli_query($conn,"
UPDATE messages
SET admin_reply='$reply',
status='replied',
replied_at=NOW()
WHERE id='$id'
");

/* Create notification */

$msg = "Admin replied to your message";

mysqli_query($conn,"
INSERT INTO notifications (user_id,message)
SELECT user_id,'$msg'
FROM messages WHERE id='$id'
");

header("Location: messages.php");
}
?>