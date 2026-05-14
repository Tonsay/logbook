<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile - Logbook System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: #0f172a; margin: 0;">

    <div class="profile-card" style="background: #1e293b; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); width: 100%; max-width: 400px; text-align: center;">
        <h2 style="margin-bottom: 20px; color: white; font-family: 'Plus Jakarta Sans', sans-serif;">Update Profile</h2>
        
        <?php if(!empty($message)): ?>
            <div style="padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; 
                 background: <?php echo ($message_type == 'success') ? '#065f46' : '#991b1b'; ?>; 
                 color: white;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php 
     
            $current_pic = $_SESSION['profile_picture'] ?? 'avatar.png';
            
            
            $physical_upload_path = __DIR__ . '/../uploads/' . $current_pic; 
            $default_avatar = 'assets/img/avatar.png';
            
            if ($current_pic !== 'avatar.png' && $current_pic !== 'avatar.jpg' && file_exists($physical_upload_path)) {
             
                $image_path = 'uploads/' . $current_pic;
            } else {
        
                $image_path = $default_avatar;
            }
        ?>
        
        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Profile" 
             style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid #3b82f6; object-fit: cover; margin-bottom: 20px;">

        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_image" accept="image/*" required 
                   style="color: #94a3b8; font-size: 14px; margin-bottom: 20px; width: 100%;">
            
            <button type="submit" class="btn-confirm" style="width: 100%; padding: 12px; border-radius: 8px; cursor: pointer; border: none; background: #3b82f6; color: white; font-weight: 700;">
                Save New Photo
            </button>
        </form>

        <a href="index.php" style="display: block; margin-top: 25px; color: #94a3b8; text-decoration: none; font-size: 14px;">
            ← Back to Dashboard
        </a>
    </div>

</body>
</html>