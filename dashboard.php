<?php
session_start();
date_default_timezone_set( 'Asia/Kolkata' );
if(!isset($_SESSION['sess_user_pid']) || (trim($_SESSION['sess_user_pid']) == '')) {
    echo "<script type='text/javascript'>alert(\"Please Login to continue\");</script>";
	header("location:./login.php");
	exit();
}

        if($_SESSION["client"]=="blood_bank")
        {
                header("location:./blood_bank_send_sms.php");
        }
        require_once './config.php';
        require_once './functions.php';
        $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);


        if($_SERVER['REQUEST_METHOD']=="POST")
        {
                if(!empty($_POST["pin"]))
                {
                        $pin=check_data($_POST["pin"]);
                        $pin=intval($pin);
                        if((is_int($pin)) && (strlen($_POST['pin'])===6))
                        {
                                mysqli_query($con,"UPDATE donars SET pin='$pin' WHERE pid=".$_SESSION['sess_user_pid']);
                        }
                        else
                        {
                                echo "<script type=\"text/javascript\"> alert(\"Please Enter Valid PinCode\")</script>";
                        }

                }
                if(!empty($_POST["password"]))
                {
                        $password=check_data($_POST["password"]);
                        $password=password_hash($password,PASSWORD_BCRYPT);
                        mysqli_query($con,"UPDATE donars SET password='$password' WHERE pid=".$_SESSION['sess_user_pid']);
                }
                if(!empty($_POST["date"]))
                {
                        $date=$_POST['date'];
                        $date= date("Y-m-d",strtotime($date."+90 days"));
                        mysqli_query($con,"UPDATE donars SET nextsmsdate='$date' WHERE pid=".$_SESSION["sess_user_pid"]);
                        //echo "UPDATE donars SET nextsmsdate=".$date." WHERE pid=".$_SESSION["sess_user_pid"];
                        //echo "<script type=\"text/javascript\"> alert($q)</script>";
                }

        }
?>

<head>
        <link href='./css/bootstrap.min.css' rel='stylesheet' type='text/css'>
        <link href="default.css" rel="stylesheet" type="text/css" media="all" />
        <link href="fonts.css" rel="stylesheet" type="text/css" media="all" />
        <link href='./css/datepicker.min.css' rel='stylesheet' type='text/css'>
        <script src='./js/jquery.min.js' type='text/javascript'></script>
        <script src='./js/bootstrap-datepicker.min.js' type='text/javascript'></script>

</head>
<style>.indent-small {
  margin-left: 5px;
}
.form-group.internal {
  margin-bottom: 0;
}
.dialog-panel {
  margin: 10px;
}
.datepicker-dropdown {
  z-index: 150 !important;
}
.panel-body {
  background: #e5e5e5;
  /* Old browsers */
  background: -moz-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
  /* FF3.6+ */
  background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #e5e5e5), color-stop(100%, #ffffff));
  /* Chrome,Safari4+ */
  background: -webkit-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
  /* Chrome10+,Safari5.1+ */
  background: -o-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
  /* Opera 12+ */
  background: -ms-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
  /* IE10+ */
  background: radial-gradient(ellipse at center, #e5e5e5 0%, #ffffff 100%);
  /* W3C */
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e5e5e5', endColorstr='#ffffff', GradientType=1);
  /* IE6-9 fallback on horizontal gradient */
  font: 600 15px "Open Sans", Arial, sans-serif;
}
label.control-label {
  font-weight: 600;
  color: #777;
}
</style>
<?php
        include_once './header.php';
 ?>
<?php
        $verified=mysqli_query($con,"SELECT mobileverified,emailverified FROM donars WHERE pid=".$_SESSION['sess_user_pid']);
        $data=mysqli_fetch_array($verified);
        if($data['emailverified']=='N')
        {
                 echo "<script>alert(\"Your mail has not yet verified.\");</script>";
                 echo "<a href='./resend_confirmation_mail.php'><button class='icon icon-warning-sign'  style=\"background:#82BF56;margin-left:42%;padding:8px; font-size:17px; color:white; border-radius:8px;\">Resend Confirmation Link</button></a>";
        }
 ?>
<br>

<body>

<div id="page-wrapper">
<div id="page" class="container">
	<div id="content">
		<div class="title">
			<h3>Welcome , <?php echo $_SESSION['sess_name']; ?></h3>
			<h4>Donate Blood Save Life<h4>


<?php

        if($data['mobileverified']=='N')
        {
                unverified();
        }
        else
        {
                verified();
        }
 ?>
 <?php
        function unverified()
        {
 ?>

 <div class='container'>
    <div class='panel panel-primary dialog-panel'>
      <div class='panel-heading'>
        <h5>You Haven't verified Your Mobile Number Please Verify</h5>
      </div>
      <div class='panel-body'>
        <form class='form-horizontal' role='form' action="./verify.php" method="post">
        <div class='form-group'>
           <label class='control-label col-md-2 col-md-offset-2'>Your One time Password</label>
            <div class='col-md-4'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control' placeholder='Enter OTP' type='password' name="otp" >
                </div>
               </div>
              </div>
            </div>
        <div class='form-group'>
            <div class='col-md-offset-4 col-md-3'>
              <button class='btn-lg btn-primary' type='submit'>Verify</button>
            </div>
          </div>
          </form>
        <div class='col-md-offset-8 col-md-3'>
          <div class='col-md-20'>
              <a href="./resend_otp.php"><button class='btn-lg btn-danger' style='float:right'>Resend OTP</button></a>
          </div>
        </div>
        </div>
       </div>
      </div>
<?php }//ending of unverified function ?>
<?php
        function verified()
        {
                $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
                $search=mysqli_query($con,"SELECT pin from donars where pid=".$_SESSION['sess_user_pid']." limit 1");
                $temp=mysqli_fetch_array($search);
                $pin=$temp['pin'];

 ?>
<div class='container'>
    <div class='panel panel-primary dialog-panel'>
      <div class='panel-heading'>
        <h4>Update Your Details</h4>
      </div>
      <div class='panel-body'>
        <form class='form-horizontal' role='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Pin Code</label>
            <div class='col-md-6'>
              <div class='col-md-6'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Your 6 digit PIN CODE' type='text' name="pin" value=<?php echo $pin;?> >
                </div>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Password</label>
            <div class='col-md-6'>
              <div class='col-md-6'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Password' type='password' name="password">
                </div>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2' for='id_checkin'>Last Donation Date</label>
            <div class='col-md-8'>
              <div class='col-md-3'>
                <div class='form-group internal input-group'>
                  <input class='form-control datepicker' id='id_checkin' name="date">
                  <span class='input-group-addon'>
                    <i class='glyphicon glyphicon-calendar'></i>
                  </span>
                </div>
              </div>
             </div>
           </div>
          <div class='form-group'>
            <div class='col-md-offset-4 col-md-3'>
              <button class='btn-lg btn-primary' type='submit'>Update</button>
            </div>
          </div>
          </form>
          </div>
          </div>
        </div>

 <?php mysqli_close($con);} //end of verified function ?>

 		</div>
	</div>
</div>
</div>
</body>
<br></br>
<script style='display: none;'>var __links = document.querySelectorAll('a');function __linkClick(e) { parent.window.postMessage(this.href, '*');} ;for (var i = 0, l = __links.length; i < l; i++) {if ( __links[i].getAttribute('target') == '_blank' ) { __links[i].addEventListener('click', __linkClick, false);}}</script>

<script>$(document).ready(function() {
  $('.datepicker').datepicker();
});
</script>

<?php
        include 'footer.php';
 ?>

