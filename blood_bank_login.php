<?php
    session_start();
    date_default_timezone_set( 'Asia/Kolkata' );
    if(isset($_SESSION['sess_user_pid']) and (trim($_SESSION['sess_user_pid']) !== '')) {
        header("location:./blood_bank_send_sms.php");
        exit();
    }
    $username='';
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        ob_start();
        //session_start();
        $username=$_POST['username'];
        $password=$_POST['password'];

        require_once './config.php';
        $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);

        $username=mysqli_real_escape_string($con,$username);

        $search_user=mysqli_query($con,"SELECT `pid`,`bloodbname`,`password`,`falsecount` FROM `members` WHERE `email`='$username' AND subscribed='Y' ");
        if($search_user)
        {
                $data=mysqli_fetch_array($search_user);
                if($data['falsecount']<25)
                {
                        $true_password=$data['password'];
                        if((mysqli_num_rows($search_user)==1 ) and (password_verify ($password , $true_password )))
                        {
                                mysqli_query($con,"UPDATE members SET lastlogin=CURRENT_TIMESTAMP,falsecount=0 WHERE pid=".$data['pid']);
                                session_regenerate_id();
                                $_SESSION['sess_user_pid']=$data['pid'];
                                $_SESSION['sess_username']=$username;
                                $_SESSION['sess_bloodbname']=$data['bloodbname'];
                                $_SESSION['client']="blood_bank";
                                session_write_close();
                                header('Location:./blood_bank_send_sms.php');
                        }
                        else
                        {
                                mysqli_query($con,"UPDATE members SET falsecount=falsecount+1 WHERE pid=".$data['pid']);
                            echo "<script typ='text/javascript'>alert(\"Invalid Username and Password combination\");</script>";
                        }
                }
                else
                {
                        echo "<script typ='text/javascript'>alert(\"Too many false attempts please re-verify your mail-id\");</script>";
                }
        }
        else
        {
                 echo "<script typ='text/javascript'>alert(\"Invalid Username and Password combination\");</script>";
        }
    }
 ?>
<?php
    include 'header.php';
 ?>
<head>
  <link href='./css/bootstrap.min.css' rel='stylesheet' type='text/css'>
  <link href="default.css" rel="stylesheet" type="text/css" media="all" />
  <link href="fonts.css" rel="stylesheet" type="text/css" media="all" />
</head>
<br>
<body>
  <div class='container'>
    <div class='panel panel-primary dialog-panel'>
      <div class='panel-heading'>
        <h5>Blood Bank's Login</h5>
      </div>
      <div class='panel-body'>
        <form class='form-horizontal' role='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
         <div class='form-group'>
           <label class='control-label col-md-2 col-md-offset-2'>E-Mail</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control' placeholder='Your mail Address' type='email' name="username" value=<?php echo $username;?> >
                </div>
               </div>
              </div>
            </div>
        <div class='form-group'>
           <label class='control-label col-md-2 col-md-offset-2'>Password</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control' placeholder='Password' type='password' name="password" >
                </div>
               </div>
              </div>
            </div>
        <div class='form-group'>
            <div class='col-md-offset-4 col-md-3'>
              <button class='btn-lg btn-primary' type='submit'>Sign in</button>
            </div>
          </div>
          </form>
          <a href='./blood_bank_forgot_password.php'><button class='icon icon-warning-sign'  style="background:#82BF56;margin-left:34%;padding:8px; font-size:17px; color:white; border-radius:8px;">Forgot Password</button></a>
          <div class='col-md-offset-8 col-md-3'>
          <div class='col-md-20'>
              <a href="./login.php"><button class='btn-lg btn-danger' style='float:right'>Donors's Login</button></a>
          </div>
        </div>
        </div>
       </div>
      </div>
</body>
<?php
        include 'footer.php';
 ?>
