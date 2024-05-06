<?php
require 'authentication.php'; 

if(isset($_SESSION['admin_id'])){
  $user_id = $_SESSION['admin_id'];
  $user_name = $_SESSION['admin_name'];
  $security_key = $_SESSION['security_key'];
  if ($user_id != NULL && $security_key != NULL) {
    header('Location: task-info.php');
  }
}

if(isset($_POST['login_btn'])){
 $info = $obj_admin->admin_login_check($_POST);
}

$page_name="Login";
include("include/head.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyLang</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .login-container {
            margin-top: 200px;
        }
        .login-card {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #ffffff;
        }
        .login-title {
            text-align: center;
            margin-bottom: 50px;
        }
        .login-btn {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card login-card">
            <div class="card-body">
                <h2 class="login-title">EasyLang</h2>
                <form action="" method="POST">
                    <?php if(isset($info)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $info; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Username" name="username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="admin_password" required>
                    </div>
                    <button type="submit" name="login_btn" class="btn btn-primary login-btn">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

