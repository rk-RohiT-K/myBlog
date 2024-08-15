<?php 
require_once '../functions.php';


$users=xiu_fetch_all('select * from users');


 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- -->
      <div class="row">
<!--  
        </div> -->
        <div class="col-md-12">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="btn-delete" class="btn btn-danger btn-sm" href="/admin/user-delete.php" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center" width="80">头像</th>
                <th class="text-center">邮箱</th>
                <th class="text-center">别名</th>
                <th class="text-center">昵称</th>
                <th class="text-center">权限</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $item): ?>
                <tr class="text-center" >
                <td class="text-center"><input data-id="<?php echo $item['id']; ?>" type="checkbox"></td>
                <td class="text-center"><img class="avatar" src="<?php echo $item['avatar']; ?>"></td>
                <td><?php echo $item['email']; ?></td>
                <td><?php echo $item['slug']; ?></td>
                <td><?php echo $item['nickname']; ?></td>
                <td><?php echo $item['root']=='root'? '管理员' : '用户'; ?></td>
                <td class="text-center">
                  <a href="/admin/user-delete.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>        
              <?php endforeach ?>
                
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php $current_page='users' ?>
   <?php include 'inc/sidebar.php'; ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $(function($){
       var tbodyChecks=$('tbody input');
       var $btnDelete=$("#btn-delete");
       var allChecks=[];
       tbodyChecks.on('change',function() {
         var $id=$(this).data('id');
         if ($(this).prop('checked')) {
            allChecks.includes($id) || allChecks.push($id);
         }else {
           allChecks.splice(allChecks.indexOf($id),1);
         }
         allChecks.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut();
         $btnDelete.prop('search','?id='+allChecks);
       });
       $('thead input').on('change',function() {
        var checked=$(this).prop('checked');
        tbodyChecks.prop('checked',checked).trigger('change');
       });





    })



  </script>
  <script>NProgress.done()</script>
</body>
</html>
