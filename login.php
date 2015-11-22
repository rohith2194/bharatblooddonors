<?php
        session_start();
        date_default_timezone_set( 'Asia/Kolkata' );
        if(isset($_SESSION['sess_user_pid']) and (trim($_SESSION['sess_user_pid']) !== ''))
        {
                if($_SESSION["client"]=="blood_bank")
                {
                        header("location:./blood_bank_send_sms.php");
                }
                else if($_SESSION["client"]=="general")
                {
                        header("location:./dashboard.php");
                }
                else
                {
                        header("location:./index.php");
                }
                exit();
        }

        $username='';
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
                ob_start();
                $username=$_POST['username'];
                $password=$_POST['password'];
                require_once './config.php';

                $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);

                $username=check_data($username);

                $username=mysqli_real_escape_string($con,$username);

                $search_user=mysqli_query($con,"SELECT `pid`,`mobile`,`password`,`name`,`falsecount` FROM `donars` WHERE `mobile`='$username' AND subscribed='Y'");
                $data=mysqli_fetch_array($search_user);
                if(mysqli_num_rows($search_user)!=0)
                {
                        if($data['falsecount']<30)
                        {
                                $true_password=$data['password'];
                                if(password_verify ($password , $true_password ))
                                {
                                        mysqli_query($con,"UPDATE donars SET lastlogin=CURRENT_TIMESTAMP,falsecount=0 WHERE pid=".$data['pid']);
                                        session_regenerate_id();
                                        $_SESSION['sess_user_pid']=$data['pid'];
                                        $_SESSION['sess_user_name']=$username;
                                        $_SESSION['sess_name']=$data['name'];
                                        $_SESSION['client']='general';
                                        session_write_close();
                                        header('Location:./dashboard.php');

                                }
                                else
                                {
                                        mysqli_query($con,"UPDATE donars SET falsecount=falsecount+1 WHERE pid=".$data['pid']);
                                        echo "<script typ='text/javascript'>alert(\"Invalid Username and Password combination\");</script>";
                                }
                        }
                        else
                        {
                                $check=mysqli_query($con,"SELECT mobileverified FROM donars WHERE mobile='$username'");
                                $data=mysqli_fetch_array($check);
                                if($data['mobileverified']=='Y')
                                {
                                        //$otp=rand(10000,99999);
                                        //mysqli_query($con,"UPDATE donars SET mobileverified='N',otp=$otp WHERE mobile='$username'");
                                        //send msg to the user to inform that it's attach with otp verification'
                                        echo "<script typ='text/javascript'>alert(\"User Account is blocked for too many invalid passwords Reverify Your Number to get access\");</script>";
                                }
                        }
                }
                else
                {
                        echo "<script typ='text/javascript'>alert(\" $username is not registered with us\");</script>";
                }
                mysqli_close($con);
        }

        function  check_data($data){
                $data=trim($data);
                $data=stripslashes($data);
                $data=htmlspecialchars($data);
                return $data;
        }
 ?>

<?php
        include './header.php';
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
        <h5>Donors Login</h5>
      </div>
      <div class='panel-body'>
        <form class='form-horizontal' role='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
         <div class='form-group'>
           <label class='control-label col-md-2 col-md-offset-2'>Mobile</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control' placeholder='Your Mobile Number' type='text' name="username" value=<?php echo $username;?> >
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
            <div class='col-md-offset-4 col-md-6'>
              <button class='btn-lg btn-primary'  type='submit'>Sign in</button>

            </div>

          </div>

          </form>
          <a href='./signup.php'><button  style="background:#A21B3B;margin-left:34%;padding:10px; font-size:17px; color:white; border-radius:8px;">Register</button></a>
          <a href='./forgot_password.php'><button class='icon icon-warning-sign'  style="background:#82BF56;margin-left:2%;padding:8px; font-size:17px; color:white; border-radius:8px;">Forgot Password</button></a>
        <div class='col-md-offset-8 col-md-3'>
          <div class='col-md-20'>
              <a href="./blood_bank_login.php"><button class='btn-lg btn-danger' style='float:right'> Blood Bank's Login</button></a>
          </div>
        </div>
        </div>
       </div>
      </div>
</body>
<br></br>
<?php
        include 'footer.php';
 ?>
