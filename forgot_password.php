<?php
        session_start();
        if(isset($_GET['mobile']))
        {
                require_once './config.php';
                require_once './functions.php';
                $mobile=check_data($_GET["mobile"]);
                if(empty($_GET["mobile"]))
                {

                        echo "<script>alert(\"Please Enter Mobile Number\");</script>";
                }
                else
                {
                        $mobile=intval($mobile);
                        if(is_int($mobile) and (strlen($_GET["mobile"])==10))
                        {
                                $mobile=check_data($_GET["mobile"]);
                                $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
                                $search=mysqli_query($con,"SELECT emailverified, mobileverified,email,mobile FROM donars WHERE mobile='$mobile' limit 1");
                                if($search)
                                {
                                        if(mysqli_num_rows($search)==1)
                                        {
                                                $otp=generateRandomString(5);
                                                $password=password_hash($otp,PASSWORD_BCRYPT);
                                                $data=mysqli_fetch_array($search);
                                                mysqli_query($con,"UPDATE donars SET password='$password',falsecount=falsecount-1 WHERE mobile='$mobile' ");
                                                if($data['emailverified']=='Y')
                                                {
                                                        $message_body="Your New Password to login with us is ".$otp." . Please Change your password after login";
                                                        mail($data['email'],"Account-Recovery-Noreply ",$message_body,"From: admin@bharatblooddonors.in");
                                                        echo "<script>
                                                                if(confirm('Password Sent to Your E-mail'))
                                                                {
                                                                        window.location.assign(\"./login.php\")
                                                                }
                                                                else
                                                                {
                                                                        window.location.assign(\"./login.php\")
                                                                }


                                                        </script>";
                                                }
                                                else
                                                {
                                                        //send password here to mobile
                                                        $mobile=$data['mobile'];
                                                        $sms_message="Your OTP to verify mobilenumber is $otp Please verify your mobile number.contact admin@bharatblooddonors.in for any queries";
                                                        send_sms($mobile,$sms_message);
                                                         echo "<script>
                                                                if(confirm('Password Sent to Your Mobile'))
                                                                {
                                                                        window.location.assign(\"./login.php\")
                                                                }
                                                                else
                                                                {
                                                                        window.location.assign(\"./login.php\")
                                                                }


                                                        </script>";
                                                }
                                        }
                                        else
                                        {
                                                echo "<script>alert(\"Mobile NOT registered with us\");</script>";
                                        }
                                }

                        }
                        else
                        {
                                echo "<script>alert(\"Please Enter Valid Mobile Number\");</script>";
                        }

                }
        }
        else
        {
?>
<script type="text/javascript">
        var mobile=prompt("Please Enter Your mobile Number");

        if(mobile!=null)
        {
                window.location.assign("./forgot_password.php?mobile="+mobile);
        }
</script>

<?php
        }
 ?>
