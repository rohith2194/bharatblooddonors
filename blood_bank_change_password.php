<?php
        session_start();
        if(!isset($_SESSION['sess_user_pid']) || (trim($_SESSION['sess_user_pid']) == ''))
        {
                echo "<script>
                                        if(confirm('Please Login to continue'))
                                        {
                                                window.location.assign(\"./blood_bank_login.php\")
                                        }
                                        else
                                        {
                                                window.location.assign(\"./blood_bank_login.php\")
                                        }


                        </script>";
                //header("location:./blood_bank_login.php");
                exit();
        }
        if($_SESSION["client"]=="general")
        {
                header("location:./dashboard.php");// changing to donars profile.
        }

        if($_SERVER['REQUEST_METHOD']=='POST')
        {
                require_once './functions.php';
                require_once './config.php';

                $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);

                //$password=password_hash($_POST['password'],PASSWORD_BCRYPT);

                if(!empty($_POST["password"]))
                {
                        //$password=check_data($_POST["password"]);
                        $password=password_hash($_POST['password'],PASSWORD_BCRYPT);
                        mysqli_query($con,"UPDATE members SET password='$password' WHERE pid=".$_SESSION['sess_user_pid']);
                        echo "<script>
                                        if(confirm('Password Successfully changed'))
                                        {
                                                window.location.assign(\"./blood_bank_send_sms.php\")
                                        }
                                        </script>";

                }
                else
                {
                        echo "<script typ='text/javascript'>alert(\"Please Enter Password\");</script>";
                }
        }

 ?>

<head>
  <link href='./css/bootstrap.min.css' rel='stylesheet' type='text/css'>
</head>
<?php include_once './header.php'; ?>
<div class='container'>
    <div class='panel panel-primary dialog-panel'>
      <div class='panel-heading'>
        <h5>Blood Donation Registration</h5>
      </div>
      <div class='panel-body'>
        <form class='form-horizontal' role='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Name</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Your New Password' type='password' name="password" >
                </div>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <div class='col-md-offset-4 col-md-3'>
              <button class='btn-lg btn-primary' type='submit'>Change Password</button>
            </div>
          </div>
          </form>
           </div>
  </div>
  </div>

  <?php
        include_once './footer.php';
   ?>
