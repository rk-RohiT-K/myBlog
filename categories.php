<?php 
require_once '../functions.php';

if (!empty($_GET['id'])) {
  $current_edit_category=xiu_fetch_one('select * from categories where id='.$_GET['id']);
}

function add_category(){
   if (empty($_POST['name']) || empty($_POST['slug'])) {
    $GLOBALS['success']= false ;
    $GLOBALS['message']='Please fill in the form completely';
    return ;
   }

   $name=$_POST['name'];
   $slug=$_POST['slug'];


   $rows=xiu_execute("insert into categories values(null,'{$slug}','{$name}');");

   $GLOBALS['success']= $rows <=0 ? false : true;
    $GLOBALS['message']= $rows <=0 ?'Add failed' : 'Add Successful';
}
function edit_category(){
  global $current_edit_category;
    if (empty($_POST['name']) || empty($_POST['slug'])) {
    $GLOBALS['success']= false ;
    $GLOBALS['message']='PLease fill in the form completely';
    return ;
   }
  $id = $current_edit_category['id'];
   $name=empty($_POST['name']) ? $current_edit_category['name'] : $_POST['name'];
   $current_edit_category['name']=$name;
   $slug=empty($_POST['slug']) ? $current_edit_category['slug'] : $_POST['slug'];
   $current_edit_category['slug']=$slug;
   $rows=xiu_execute("update categories set slug='{$slug}' , name='{$name}' where id={$id};");
   $GLOBALS['success']= $rows <=0 ? false : true;
    $GLOBALS['message']= $rows <=0 ?'Update failed' : 'Update Successful';
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  //Once the form submits the request and doesn't submit the ID through the URL, it means the data is added
  if (empty($_GET['id'])) {
   add_category();
  }else{
    edit_category();
  }
  
}

$categories=xiu_fetch_all('select * from categories;');





 ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- Display when there is an error message -->
      <?php if (isset($message)): ?>
       <?php if ($success): ?>
          <div class="alert alert-success">
        <strong>Success！</strong><?php echo $message ?>
      </div>
      <?php else: ?>
          <div class="alert alert-danger">
        <strong>Failed！</strong><?php echo $message ?>
      </div>
       <?php endif ?>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
           <?php if (isset($current_edit_category)): ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $current_edit_category['id']; ?>" method="post" novalidate>
            <h2>Edit《 <?php echo $current_edit_category['name']; ?> 》</h2>
            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="CategoryName" value="<?php echo $current_edit_category['name'];?> ">
            </div>
            <div class="form-group">
              <label for="slug">Alias</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $current_edit_category['slug']; ?>">
              <p class="help-block">https://chenyu.io/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">Keep</button>
            </div>
          </form>
           <?php else: ?>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate>
            <h2>Add New Category</h2>
            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="CategoryName">
            </div>
            <div class="form-group">
              <label for="slug">Alias</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://chenyu.io/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">Add</button>
            </div>
          </form>
           
            <?php endif ?>
          
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a  id="btn_delete" class="btn btn-danger btn-sm" href="/admin/category-delete.php" style="display: none">Batch Deletion</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>Name</th>
                <th>Slug</th>
                <th class="text-center" width="100">Operate</th>
              </tr>
            </thead>
            <tbody>
             
             <?php foreach ($categories as $item): ?>
                <tr>
                <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['slug']; ?></td>
                <td class="text-center">
                  <a href="/admin/categories.php?id=<?php echo $item['id']; ?>" class="btn btn-info btn-xs">Edit</a>
                  <a href="/admin/category-delete.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">Delete</a>
                </td>
              </tr>
             <?php endforeach ?>
             
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php $current_page='categories' ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $(function($){
       //When the selected state of any CheckBox in the table changes
       var $tBodyCheckBoxs= $('tbody input');
       var $btnDelete=$('#btn_delete');
       var allCheckeds= [] ;
       $tBodyCheckBoxs.on('change', function() {
        var id=$(this).data('id');

        if ($(this).prop('checked')) {
             allCheckeds.includes(id) || allCheckeds.push(id);
            
        }else {
          allCheckeds.splice(allCheckeds.indexOf(id),1);
        }
      allCheckeds.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut();
      $btnDelete.prop('search','?id='+allCheckeds);
       });
         //Select all or none
       $('thead input').on('change',function(){
         var checked=$(this).prop('checked');

         $tBodyCheckBoxs.prop('checked',checked).trigger('change')


       })
     });
 
   

  </script>
  <script>NProgress.done()</script>
</body>
</html>
