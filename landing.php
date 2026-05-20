<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Science Education Institute</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:  #ffffff;
            color: #000000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        .landing-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            transform: translateY(-5%);
        }

        .header-container {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 60px;
        }

        .logo {
            width: 85px;
            height: auto;
            filter: drop-shadow(0px 4px 8px rgba(0,0,0,0.5));
        }

        .header-text {
            display: flex;
            flex-direction: column;
        }

        .header-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #000000;
            letter-spacing: 0.5px;
        }

        .header-subtitle {
            margin: 5px 0 0 0;
            font-size: 20px;
            font-weight: 600;
            color: #00A5EF;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        
        .roles-container {
            display: grid;
            grid-template-columns: 260px 260px; 
            gap: 60px;
            justify-content: center;
        }

        .role-card {
            background: rgba(10, 30, 55, 0.45); 
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center; 
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            
          
            height: 280px; 
            box-sizing: border-box;
        }

        .role-card:hover {
            transform: translateY(-10px);
            background: rgba(10, 30, 55, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .role-icon {
            width: 110px;
            height: 110px;
            margin-bottom: 25px;
            filter: drop-shadow(0px 4px 6px rgba(0,0,0,0.4));
            transition: transform 0.3s ease;
            object-fit: contain;
        }

        .role-card:hover .role-icon {
            transform: scale(1.05);
        }

        .role-title {
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-align: center;
            line-height: 1.3;
        }

        .admin-text {
            color: #f1c40f; 
        }

        .custodian-text {
            color: #00A5EF; 
        }

       
        @media (max-width: 768px) {
            .roles-container {
                grid-template-columns: 260px; 
            }
            .header-container {
                flex-direction: column;
                text-align: center;
                margin-bottom: 40px;
            }
        }
    </style>
</head>
<body>

    <div class="landing-wrapper">
        <div class="header-container">
            <img src="/logbook/assets/img/logo.png" alt="SEI Logo" class="logo">
            <div class="header-text">
                <h1 class="header-title">Science Education Institute</h1>
                <h2 class="header-subtitle">Logbook System</h2>
            </div>
        </div>

   
        <div class="roles-container">
            
            <a href="/logbook/login.php?role=superadmin" class="role-card">
                <img src="/logbook/assets/img/superadmin.png" alt="Super Admin" class="role-icon">
                <span class="role-title admin-text">ADMIN</span>
            </a>

            <a href="/logbook/login.php?role=admin" class="role-card">
                <img src="/logbook/assets/img/admin1.png" alt="Records Custodian" class="role-icon">
                <span class="role-title custodian-text">RECORDS<br>CUSTODIAN</span>
            </a>

        </div>
    </div>

</body>
</html>