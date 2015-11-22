<?php
        if(($_SERVER['REQUEST_METHOD']=='GET') and isset($_GET['email']) )
        {
                require_once './config.php';
                require_once './functions.php';
                if(empty($_GET["email"]))
                {
                        echo "<script>alert(\"Please Enter E-mail Id\");</script>";
                }
                else
                {
                        $email=check_data($_GET["email"]);
                        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
                        {
                                echo "<script>alert(\"Invalid E-mail Id\");</script>";
                        }
                        else
                        {
                                 $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
                                 $search=mysqli_query($con,"SELECT password from members where email='$email' limit 1");
                                 if($search)
                                 {
                                        if(mysqli_num_rows($search)==1)
                                        {
                                                $otp=generateRandomString(5);
                                                $password=password_hash($otp,PASSWORD_BCRYPT);
                                                $data=mysqli_fetch_array($search);
                                                mysqli_query($con,"UPDATE members SET password='$password' WHERE email='$email'");
                                                //email the password to members
                                                $message_body="Your New Password to login with us is ".$otp." . Please Change your password after login";
                                                mail($email,"Account-Recovery-No reply ",$message_body,"From: admin@bharatblooddonors.in");
                                                mysqli_close($con);
                                                echo "<script>alert(\"Password sent to your E-mail (check spam if you have not received) \");</script>";
                                        }
                                        else
                                        {
                                                echo "<script>alert(\"E-Mail id not found\");</script>";
                                        }
                                }
                                else
                                {
                                        echo "<script>alert(\"Something Went Wrong\");</script>";

                                }
                        }
                }
        }
        else
        {
 ?>

<script type="text/javascript">
        var email=prompt("Please Enter Your E-mail Id");

        if(email!=null)
        {
                window.location.assign("./blood_bank_forgot_password.php?email="+email);
        }
</script>
<?php
        }
 ?>
