<?php 
require_once '../config.php';

session_start();
function login(){
  if (empty($_POST['email'])) {
    $GLOBALS['message']='no email';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['message']='no password';
    return;
  }
  $email=$_POST['email'];
  $password=$_POST['password'];



 $conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
  if (!$conn) {
    exit('<h1>error code1</h1>');
  }
  $query=mysqli_query($conn,"select * from users where email = '{$email}' limit 1; ");

  if ( !$query) {
     $GLOBALS['message']='query not found';
    return;
  }
  $user=mysqli_fetch_assoc($query);
  if (!$user) {
    $GLOBALS['message']='Message1';
    return;
  }
  if ($user['password']!==$password) {
    $GLOBALS['message']='password not match';
    return;
  }
  //$_SESSION['is_logged_in']=true;
  $_SESSION['current_login_user']=$user;


  if ($user['root']=='root') {
     header('Location: /admin/index.php');
  }else{
      $GLOBALS['message']='not admin';
  }
 
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  login();
}
if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['action']) && $_GET['action']==='logout') {
   if (!empty($_SESSION['current_login_user'])) {
     session_unset($_SESSION['current_login_user']);
   } 
}

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
</head>
<body>
  <div class="login">

    <form class="login-wrap <?php echo isset($message) ? 'shake animated' : '' ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate autocomplete="off">
      <img class="avatar" src="/static/assets/img/default.png">
      <?php if (isset($message)): ?>
         <div class="alert alert-danger">
        <strong>错误！<?php echo $message ?></strong> 
      </div>
      <?php endif ?>
     
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo isset($_POST['email'])? $_POST['email'] :''  ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password-md"  type="password" class="form-control" placeholder="密码">
         <input type="hidden" id="password" name="password">
      </div>
      <button class="btn btn-primary btn-block" id="button">登 录</button>
    </form>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.min.js"></script>
  <script src="/static/assets/vendors/jquery/md5.js"></script>
  <script>
    $(function($){

    $('#email').on('blur',function(){
         var emailFormat=/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/
         var value=$(this).val();
         if (!value || !emailFormat.test(value)) {
           return;
        }  

    $.get('/admin/api/avator.php',{ email : value }, function(res) {

      if (!res) return;

      $('.avatar').fadeOut( function() {
        $(this).on('load',function() {
         $(this).fadeIn();
        }).attr('src',res);
      });;
      
    });

 });
    $('#button').on('click',function(){

    //var password_md=document.getElementById('password-md');

    // var password=document.getElementById('password');
    var passwordOld=document.getElementById('password-md');
    var passwordNew=document.getElementById('password');
    console.log(passwordOld.value);
    // set password
     passwordNew.value = hex_md5(passwordOld.value);
    });
   // var www=hex_md5("123")
   // console.log(www);
    });
  </script>
</body>
</html>
