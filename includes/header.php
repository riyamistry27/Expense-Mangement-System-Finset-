<?php
$user_name = $_SESSION['name'] ?? "User";
$user_id   = $_SESSION['user_id'] ?? 0;

/* Notification Count */
$notif_count = 0;

if($user_id){
    $result = mysqli_query($conn,"
        SELECT COUNT(*) as total
        FROM notifications
        WHERE user_id='$user_id'
        AND is_read=0
    ");

    if($result){
        $row = mysqli_fetch_assoc($result);
        $notif_count = $row['total'] ?? 0;
    }
}
?>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="topbar">

    <div class="topbar-left">
        <h4>Welcome back, <?php echo $user_name; ?> 👋</h4>
        <small>Manage your finances</small>
    </div>

    <div class="topbar-right">

        <!-- Notification Bell -->
        <a href="/expense-management/notifications/index.php" class="notification">

            <i class="fa-solid fa-bell"></i>

            <?php if($notif_count > 0){ ?>
                <span class="notif-badge">
                    <?php echo $notif_count; ?>
                </span>
            <?php } ?>

        </a>

        <a href="/expense-management/profile.php" class="profile-btn">
            <i class="fa-solid fa-user"></i> Profile
        </a>

    </div>

</div>