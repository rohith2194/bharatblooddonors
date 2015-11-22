<?php
//Start session
session_start();
date_default_timezone_set( 'Asia/Kolkata' );
$pin=$mobileError=$nameError=$pinError=$name=$mobile=$bgError=$bg='';
$landmark=$landError='';

//Check whether the session variable SESS_MEMBER_ID is present or not
if(!isset($_SESSION['sess_user_pid']) || (trim($_SESSION['sess_user_pid']) == '')) {
    echo "<script type='text/javascript'>alert(\"Please Login to continue\");</script>";
	header("location:./blood_bank_login.php");
	exit();
}
        if($_SESSION["client"]=="general")
        {
                header("location:./dashboard.php");
        }
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
                require_once './functions.php';
                $be=$pe=$ne=$le=$me=true;
                $pin=$_POST["pin"];
                if(empty($pin))
                {
                        $pinError="Please Enter your PIN CODE";
                }
                else
                {
                        $pin=check_data($pin);
                        $pin=intval($pin);
                        if((is_int($pin)) && (strlen($_POST['pin'])===6))
                        {
                            $pe=false;
                        }
                        else
                        {
                            $pinError="Please Enter Valid PIN CODE";
                        }
                }
                if(empty($_POST["bg"]))
                {
                        $bgError="Please Choose one of listed Blood Groups";
                }
                else
                {
                        $bg=$_POST["bg"];
                        if($bg=="a+" or $bg=="b+" or $bg=="o+" or $bg=="ab+" or $bg=="a-" or $bg=="b-" or $bg=="o-" or $bg=="ab-")
                        {
                                $be=false;
                        }
                }
                $mobile=check_data($_POST["mobile"]);
                if(empty($_POST["mobile"]))
                {

                        $mobileError="Please Enter Mobile Number";
                }
                else
                {
                        $mobile=intval($mobile);
                        if(is_int($mobile) and (strlen($_POST["mobile"])==10))
                        {
                                $me=false;
                        }
                        else
                        {
                                $mobileError="Please Enter Valid Mobile Number";
                        }
                        $mobile=check_data($_POST["mobile"]);
                }
                if(empty($_POST["name"]))
                {
                        $nameError="Name Field Required";
                }
                else
                {
                        $name=check_data($_POST["name"]);
                        if (!preg_match("/^[a-zA-Z ]*$/",$name))
                        {
                                $nameError = "Only letters and white space allowed";
                        }
                        else if(strlen($name)>16)
                        {
                                $nameError ="Please Shorten the name field";
                        }
                        else
                        {
                                $ne=false;
                        }
                }
                if(empty($_POST["landmark"]))
                {
                        $landError="Please Provide Landmark";
                }
                else
                {
                        $landmark=check_data($_POST["landmark"]);
                        if(strlen($landmark)>35)
                        {
                                $landError="Please shorten Landmark";
                        }
                        else
                        {
                                $le=false;
                        }
                }

                if(!($pe or $be or $me or $le or $ne))
                {
                        //data validation over now we need to search data
                        require_once './config.php';
                        $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
                        $donars=mysqli_query($con,"SELECT DISTINCT mobile, nextsmsdate FROM donars WHERE pin='$pin' AND bg='$bg' AND mobileverified='Y'");

                        $bg=conv_text($bg);
                        //echo $bg;
                        $message="*Bharat blood Donors*Blood Group:$bg Contact Details:- Name:$name Mobile:$mobile PIN:$pin Landmark:$landmark";
                        $count=0;
                        $numbers="";
                        while($row=mysqli_fetch_array($donars))
                        {
                                if((strtotime(date('Y-m-d'))-strtotime($row['nextsmsdate'])<0))
                                {
                                        continue;
                                }
                                //send msgs here with
                                $numbers=$numbers.$row['mobile'].",";
                                $count++;

                        }
                        //echo $numbers;
                        if($count>0)
                        {
                                send_sms($numbers,$message);
                                $bankid=$_SESSION['sess_user_pid'];
                                mysqli_query($con,"INSERT INTO `messageinfo`(`pid`, `bankid`, `time`, `numberofmsgs`, `bg`, `pin`) VALUES (NULL,$bankid,CURRENT_TIMESTAMP,$count,'$bg','$pin')");
                                echo "<script typ='text/javascript'>alert(\"We had sent messages to $count donars. Hope You get blood in soon \");</script>";
                        }
                        else
                        {
                                echo "<script typ='text/javascript'>alert(\"Sorry to say this we don't $bg donars in $pin  \");</script>";
                        }

                }
        }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='./css/bootstrap.min.css' rel='stylesheet' type='text/css'>
<title>Send SMS to Donors</title>
</head>
<?php include './header.php'; ?>
<body>
    <div id="page-wrapper">
<div id="page" class="container">
	<div id="content">
		<div class="title">
			<h2>Welcome, <?php echo $_SESSION["sess_bloodbname"] ?></h2>
			<span class="byline">Donate Blood Save Life</span>
		</div>
    </div>
   <div id="sidebar">
       <h2>Here we'll display statistics of corresponding Blood Bank</h2>
   </div>
  </div>
 </div>
  <br>
<div class='container'>
    <div class='panel panel-primary dialog-panel'>
      <div class='panel-heading'>
        <h5>Send SMS's to Registered Donoras'</h5>
      </div>
      <div class='panel-body'>
        <form class='form-horizontal' role='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Conact Name</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Not more than 15 letters' type='text' name="name" value=<?php echo $name; ?>>
                </div>
              </div>
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-lable col-md-6 col-md-4'><?php echo $nameError; ?></label>
                </div>
              </div>
            </div>
          </div>
        <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Mobile</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Contact Mobile Number' type='text' name="mobile" value=<?php echo $mobile;?> >
                </div>
              </div>
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-lable col-md-6 col-md-4'><?php echo $mobileError; ?></label>
                </div>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Pin Code</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='PIN CODE of Location' type='text' name="pin" value=<?php echo $pin;?> >
                </div>
              </div>
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-lable col-md-6 col-md-4'><?php echo $pinError; ?></label>
                </div>
              </div>
            </div>
          </div>

            <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Blood Group</label>
              <div class='col-md-2'>
                <select class='form-control' name="bg">
                <option value="">Select</option>
                        <option value="a+" <?php if($bg=="a+") echo "selected";?>>A +</option>
                        <option value="a-" <?php if($bg=="a-") echo "selected";?>>A -</option>
                        <option value="b+" <?php if($bg=="b+") echo "selected";?>>B +</option>
                        <option value="b-" <?php if($bg=="b-") echo "selected";?>>B -</option>
                        <option value="ab+" <?php if($bg=="ab+") echo "selected";?>>AB +</option>
                        <option value="ab-" <?php if($bg=="ab-") echo "selected";?>>AB -</option>
                        <option value="o+" <?php if($bg=="o+") echo "selected";?>>O +</option>
                        <option value="o-" <?php if($bg=="o-") echo "selected";?>>O -</option>
                  </select>
                </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Landmark</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Not more than 25 letters' type='text' name="landmark" value=<?php echo $landmark; ?>>
                </div>
              </div>
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-lable col-md-6 col-md-4'><?php echo $landError; ?></label>
                </div>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <div class='col-md-offset-4 col-md-3'>
              <button class='btn-lg btn-primary' type='submit'>Send SMS's</button>
            </div>
          </div>
          </form>
        </div>
       </div>
      </div>
     </div>
     <a href='./blood_bank_change_password.php'><button class='icon icon-warning-sign'  style="background:#82BF56;margin-left:42%;padding:8px; font-size:17px; color:white; border-radius:8px;">Change Password</button></a>

</body>
<br></br>
<?php
        include 'footer.php';
 ?>

</html>