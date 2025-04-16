<?php
$action = "";
$user_id = "";
if(isset($_GET['action']) && isset($_GET['emp_id']))
{
  global $wpdb ;
  if ($_GET['action'] =="edit")
  {
    $action = $_GET['action'];
    $user_id = $_GET['emp_id'];
  }
  if ($_GET['action'] =="show")
  {
    $action = $_GET['action'];
    $user_id = $_GET['emp_id'];
  }
  $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}student_data WHERE id = $user_id"),ARRAY_A);
}
if ($_SERVER['REQUEST_METHOD'] =="POST" && isset($_POST['btn_submit']))
{
  global $wpdb;
  $name = sanitize_text_field($_POST['name']);
  $email = sanitize_text_field($_POST['email']);
  $age = sanitize_text_field($_POST['age']);
  $gender = sanitize_text_field($_POST['gender']);

  $wpdb->insert("{$wpdb->prefix}student_data",array(
    'name' =>$name,
    'email'=>$email,
    'age'=>$age,
    'gender'=>$gender
  ));
  // wp_redirect(admin_url('admin.php?page=custompage12'));
  // exit;
  echo "Record is successfully enter";
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
 
<div class="container">
  <h2><?php if($action == "show"){echo 'Show User';}?>Form</h2>
  <div class="row">
    <div class="col-md-8">
    <div class="panel panel-primary">
    <div class="panel-heading">Panel Heading
    </div>
        <form action="<?php echo $_SERVER['PHP_SELF'] ;?>?page=custompage" method="POST" id='jquery-validation'>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" value="<?php if ($action =='show'){ echo $data['name'];} ?>"  <?php if ($action == 'show') echo 'readonly'; ?> class="form-control" required id="name" placeholder="Enter name" name="name">
            </div>
            <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" value="<?php if ($action =='show'){ echo $data['email'];} ?>"  <?php if ($action == 'show') echo 'readonly'; ?>  class="form-control" required id="email" placeholder="Enter email" name="email">
            </div>
            <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" class="form-control" value="<?php if ($action =='show'){ echo $data['age'];} ?>"  <?php if ($action == 'show') echo 'readonly'; ?>  required id="age" placeholder="Enter age" name="age">
            </div>
            <div class="form-group">
            <label for="email">Gender:</label>
            <select class="form-control" required id="gender" name="gender" <?php if($action == "show"){echo 'disabled';}?>>
              <option><?php echo $data['gender']?></option>
              <option value="Male">Male</option>
              <option value="Female">FeMale</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary" <?php if($action == "show"){echo 'disabled';}?> name="btn_submit">Submit</button>
        </form>
    </div>
    </div>
  </div>
</div>

</body>
</html>
