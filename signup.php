<?php
//checking user login and redirecting
        session_start();

        if(isset($_SESSION['sess_user_pid']) and (trim($_SESSION['sess_user_pid']) !== ''))
        {
                header("location:./dashboard.php");
                exit();
        }
        //date_default_timezone_set( 'Asia/Kolkata' );

//initialization of variables
$nameError=$emailError=$bgError=$ageError=$mobileError=$genderError=$pinError=$alert_Error=$tError=$passError="";
$ne=$ee=$be=$ae=$me=$te=$pe=$ge=$pase=$captchae=true;
$age=$mobile=$pin=$email=$bg=$age=$name=$terms=$gender=$password=$rollno=$clg="";
	date_default_timezone_set( 'Asia/Kolkata' );
        if($_SERVER["REQUEST_METHOD"]=="POST")
        {
                require_once './config.php';
                require_once './functions.php';
                if(empty($_POST["name"]))
                {
                        $nameError="Name Field Required";
                }
                else
                {
                        $name=check_data($_POST["name"]);
                        if (!preg_match("/^[a-zA-Z ]*$/",$name))
                        {
                                $nameErr = "Only letters and white space allowed";
                        }
                        else
                        {
                                $ne=false;
                        }
                }
                if(empty($_POST["password"]))
                {
                        $passError="Password Required";
                }
                else
                {
                        $password=check_data($_POST["password"]);
                        $pase=false;
                }

                if(empty($_POST["email"]))
                {
                        $emailError="Please Enter an E-Mail Id";
                }
                else
                {
                        $email=check_data($_POST["email"]);
                        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
                        {
                                $emailErr = "Invalid email format";
                        }
                        else
                        {
                                $ee=false;
                        }
                }
                if(empty($_POST["age"]))
                {
                        $ageError="Please Enter Your Age";
                }
                else
                {
                        $age=check_data($_POST["age"]);
                        $age=intval($age);
                        if($age>=18 and $age<=60)
                        {
                                $ae=false;
                        }
                        else
                        {
                                $ageError="Only 18-60 Years are Eligible for blood Donation";
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
                $gender=$_POST["gender"];
                if(empty($_POST["gender"]))
                {
                        $genderError="Please Choose Your Gender from list above";
                }
                else
                {
                        if($gender=="male" or $gender=="female")
                        {
                                if($gender=="male")
                                {
                                        $gender_ins="M";
                                }
                                else
                                {
                                        $gender_ins="F";
                                }
                                $ge=false;
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
                $pin=$_POST["pin"];
                $pin=check_data($pin);
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
                $display=isset($_POST["display"]);
                if(!isset($_POST["terms"]))
                {
                        $tError="You MUST Accept Terms &Conditions";
                }
                else
                {
                        $te=false;
                }
                $rollno=htmlspecialchars(stripslashes(check_data($_POST["rollno"])));
                $clg=htmlspecialchars(check_data($_POST["clg"]));
                if(!($te or $me or $ge or $ne or $pe or $ee or $ae or $be or $pase))
                {
						$cap_resp=$_POST["g-recaptcha-response"];
						$url="https://www.google.com/recaptcha/api/siteverify?secret=6LeIFf8SAAAAAMPOMN1ZmJbyU8CKMHi1b-wJ6J9O&response="."$cap_resp";
						$response=file_get_contents($url);
						$response=json_decode($response);
						if($response->{'success'}=="true")
						{
                        	$con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
							if($con)
							{
									$duplicate=mysqli_query($con,"SELECT `pid` from `donars` WHERE `mobile`='$mobile' limit 1");
									if(mysqli_num_rows($duplicate)==0)
									{
											$otp=rand(10000,99999);
											$password=password_hash($password,PASSWORD_BCRYPT);
											$emai_hash=password_hash($otp,PASSWORD_BCRYPT);
											$today = date("Y-m-d");
											//echo $today;
											$query="INSERT INTO `donars`(`pid`, `pin`, `name`, `mobile`, `email`, `bg`, `gender`, `age`, `display`, `registeredtime`, `totaldonations`, `mobileverified`, `emailverified`, `password`,`nextsmsdate`, `falsecount`, `lastlogin`, `subscribed`, `otp`, `rollno`, `clg`) VALUES (NULL,'$pin','$name','$mobile','$email','$bg','$gender','$age','$display','$today',0,'N','N','$password',CURRENT_TIMESTAMP,0,CURRENT_TIMESTAMP,'Y','$otp','$rollno','$clg')";
											$ins=mysqli_query($con,$query);
											$getinfo=mysqli_query($con,"SELECT `pid`,`name` from `donars` WHERE `mobile`='$mobile'");
											$data=mysqli_fetch_array($getinfo);
											$token=substr($password,strlen($password)-11,strlen($password)-1);
											$message_body="Verify you email www.bharatblooddonors.in/mailverify.php?mobile=$mobile&token=$token";
											mail($email,"Email Verification ",$message_body,"From: admin@bharatblooddonors.in");
											mysqli_close($con);
											$sms_message="Your OTP to verify Mobile Number is $otp . For any queries, we are at bbd.yrc.nitw@gmail.com . We appreciate your noble task. SHARE US - HELP US.:) ";
											send_sms($mobile,$sms_message);
											ob_start();
											session_regenerate_id();
											$_SESSION['sess_user_pid']=$data['pid'];
											$_SESSION['sess_user_name']=$data['name'];
											$_SESSION['sess_name']=$data['name'];
											$_SESSION['client']='general';
											session_write_close();
										 header("Location:./dashboard.php");
									}
                                else
                                {
                                        echo "<script>
                                        if(confirm('$mobile already exist in our database pleas login to do any modifications'))
                                        {
                                                window.location.assign(\"./login.php\")
                                        }
                                        </script>";
                                }
                        }
					}
					else
					{
						$alert_Error="Captcha NOT matched";
					}

                }
                else
                {
						$alert_Error="There are missing fields in form";
                }

     }

 ?>

<!DOCTYPE html>
<head>
  <link href='./css/bootstrap.min.css' rel='stylesheet' type='text/css'>
  <link href='./css/bootstrap-switch.css' rel='stylesheet' type='text/css'>
  <script src='./js/jquery.min.js' type='text/javascript'></script>
  <script src='./js/bootstrap.min.js' type='text/javascript'></script>
  <script src='./js/bootstrap-switch.min.js' type='text/javascript'></script>
   <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<?php
    include 'header.php';
 ?>

<body>
        <div id="featured-wrapper">
	<div id="horizontal" class="container">
	        <ul>
	                <h4><li class="icon icon-check">We Donot spam</li></h4>
	                <h4><li class="icon icon-check">We respect Your Privacy.</li></h4>
	                <h4><li class="icon icon-check">We Don't Store your original password.</li></h4>
	                <h4><li class="icon icon-check">It's Completly Free of Cost.</li></h4>
	                <h4><li class="icon icon-check">Only registered blood Donors only have access to send sms, So we expect Donors get SMS only when there is true necessity.</li></h4>
	        </ul>
        </div>
        </div>
<br>
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
                  <input class='form-control'  placeholder='Your Name' type='text' name="name" value=<?php echo $name; ?>>
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
            <label class='control-label col-md-2 col-md-offset-2'>Age</label>
            <div class='col-md-8'>
              <div class='col-md-2'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Your Age' type='text' name="age" value=<?php echo $age; ?>>
                </div>
              </div>
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-lable col-md-6 col-md-4'><?php echo $ageError; ?></label>
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
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-label col-md-6 col-md-offset-5'><?php echo $bgError; ?></label>
                </div>
              </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>E-Mail</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control' placeholder='mail@domain.com' type='text' name="email" value=<?php echo $email;?> >
                </div>
              </div>
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-lable col-md-6 col-md-4'><?php echo $emailError; ?></label>
                </div>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Mobile</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Your 10 digit mobile Number' type='text' name="mobile" value=<?php echo $mobile;?> >
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
              <div class='col-md-2'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Your 6 digit PIN CODE' type='text' name="pin" value=<?php echo $pin;?> >
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
            <label class='control-label col-md-2 col-md-offset-2'>Password</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Password' type='password' name="password" value=<?php echo $password;?> >
                </div>
              </div>
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-lable col-md-6 col-md-4'><?php echo $passError; ?></label>
                </div>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Gender</label>
              <div class='col-md-2'>
                <select class='form-control' name="gender">
                <option value="">Select</option>
                        <option value="male" <?php if($gender=="male") echo "selected";?>> MALE</option>
                        <option value="female" <?php if($gender=="female") echo "selected";?>> FEMALE</option>
                  </select>
                </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-4 col-md-offset'>Roll No(If you are from NITW)</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Optional if you aren not NITW student' type='text' name="rollno" value=<?php echo $rollno;?> >
                </div>
              </div>
            </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>College Name</label>
            <div class='col-md-8'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='Optional College Name' type='text' name="clg" value=<?php echo $clg;?> >
                </div>
              </div>
             </div>
          </div>


          <div class='form-group'>
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-label col-md-6 col-md-offset-5'><?php echo $genderError; ?></label>
                </div>
              </div>
          </div>
          <div class='form-group'>
            <label class='control-label col-md-4 col-md-offset' >Display Mobile Number (What's this?) </label>
            <div class='col-md-4'>
              <div class='make-switch' data-off-label='NO' data-on-label='YES'>
                <input name="display" type='checkbox' value='accept_display' checked <?php if(isset($_POST['display'])) {echo "checked";}?> >
              </div>
              <b><i><h5 style="text-align:center;">If you opted for no still you'll get SMS. But your info(Mobile & Name) will not be displayed on our website.</h5></i></b>
            </div>
          </div>

          <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2' >I</label>
            <div class='col-md-1'>
              <div class='make-switch' data-off-label='Do Not' data-on-label='Do'>
                <input name="terms" type='checkbox' value='checked' <?php if(isset($_POST['terms'])) echo "checked";?> >
              </div>
            </div>
            <label class='control-lable col-md-offset-1 col-md-offset' style='float:left'>Accept <a href="./terms_conditions.php">Terms & Conditions</a></label>
          </div>
           <div class='form-group'>
              <div class='col-md-8 indent-small'>
                <div class='form-group internal'>
                  <label class='control-label col-md-6 col-md-offset-5'><?php echo $tError; ?></label>
                </div>
              </div>
          </div>
          <ul>
                <h5><li class="icon icon-check" style="margin-left:25%;">My hemoglobin is not less than 12.5 grams</li></h5>
                <h5><li class="icon icon-check" style="margin-left:25%;">I am free from acute respiratory diseases and skin diseases</li></h5>
                <h5><li class="icon icon-check" style="margin-left:25%;">I do not carry any disease transmissible by blood transfusion</li></h5>
                <h5><li class="icon icon-check" style="margin-left:25%;">I am not under medication for Malaria / Tuberculosis / Diabetes / Fits / Convulsions</li></h5>
                <h5><li class="icon icon-warning-sign" style="margin-left:25%;">I have not suffered from <b>Hepatitis B, C</b></li></h5>
                <h5><li class="icon icon-warning-sign" style="margin-left:25%;">I have not suffered from <b>AIDS</b></li></h5>
                <h5><li class="icon icon-warning-sign" style="margin-left:25%;">I have not suffered from <b>Cancer</b></li></h5>
                <h5><li class="icon icon-warning-sign" style="margin-left:25%;">I have not suffered from <b>Kidney disease</b></li></h5>
                <h5><li class="icon icon-warning-sign" style="margin-left:25%;">I have not suffered from <b>Heart disease</b></li></h5>
                <h5><li class="icon icon-warning-sign" style="text-align:center;"> Please consult your physician to check for eligibility.</li></h5>
                <br>
	<div class='form-group'>
    	<div class='col-md-offset-4 col-md-3'>
    		<div class="g-recaptcha" data-sitekey="6LeIFf8SAAAAABJqNKT0vU8DySANvQa5GLmJWeas" style="text-align:center;"></div>
             </div>
          </div>
          <div class='form-group'>
            <div class='col-md-offset-4 col-md-3'>
              <button class='btn-lg btn-primary' type='submit'>Register</button>
            </div>
          </div>
          </form>
      </div>
  </div>
  </div>
  </div>
<br></br>
<?php
	if($alert_Error!="")
	{
		echo "<script>alert(\"$alert_Error\")</script>";
	}
?>
<?php
        include 'footer.php';
 ?>
</body>