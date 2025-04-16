<?php
global $wpdb ;
$result_data = $wpdb->get_results("select * from {$wpdb->prefix}student_data",ARRAY_A);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['delete_name'])) 
  {
      $wpdb->delete( "{$wpdb->prefix}student_data",array('id' => $_POST['delete_name']));
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<body>

<div class="container">
<h2>All User</h2>
<div class="row">
    <div class="col-md-10">
    <div class="panel panel-primary">
    <div class="panel-heading">All Employee
    </div>
    <table  id="example">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if($result_data > 0)
      {
        foreach($result_data as $value)
        {
          ?>
          <tr>
        <td><?php echo $value['id']?></td>
        <td><?php echo $value['name']?></td>
        <td><?php echo $value['email']?></td>
        <td><?php echo $value['age']?></td>
        <td><?php echo ucfirst($value['gender'])?></td>
        <td>
          <a href="admin.php?page=custompage&action=edit&emp_id=<?php echo $value['id']?>" class="btn btn-primary btn-sm" href="">Edit</a>
          <a href="admin.php?page=custompage&action=show&emp_id=<?php echo $value['id']?>" class="btn btn-info btn-sm" href="">Show</a>
          <form style="display:inline;"  id="deleteform-<?php echo $value['id']; ?>" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=custompage12">
            <input type="hidden" name="delete_name" value="<?php echo $value['id']; ?>">
          </form>
          <a href="#" onclick="if(confirm('Are you sure you want to delete?')) { document.getElementById('deleteform-<?php echo $value['id']; ?>').submit(); } return false;" class="btn btn-danger btn-sm">Delete</a>
        </td>
      </tr>
          <?php
        }

      }else{
        echo "NO employee found";
      }
      ?>
    </tbody>
  </table>
    </div>
    </div>
</div>
</div>
</body>
</html>
