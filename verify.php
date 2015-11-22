<?php
session_start();
        if(!isset($_SESSION['sess_user_pid']) || (trim($_SESSION['sess_user_pid']) == ''))
        {
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

        if($_SERVER["REQUEST_METHOD"]=="POST")
        {
                $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
                $true_otp=mysqli_query($con,"SELECT otp FROM donars WHERE pid=".$_SESSION['sess_user_pid']." AND mobileverified='N' ");
                $data=mysqli_fetch_array($true_otp);
                if(($_POST["otp"]==$data['otp']) and ($data['otp']!=''))
                {
                        mysqli_query($con,"UPDATE donars SET mobileverified='Y' WHERE pid=".$_SESSION['sess_user_pid']);
                        //here update password and salt
                        mysqli_close($con);
                        echo "<script>
                                        if(confirm('Your Mobile has been Verified.ThankYou for Registering'))
                                        {
                                                window.location.assign(\"./dashboard.php\")
                                        }
                                        </script>";
                        echo "hi";
                }
                else
                {
                        echo "<script type=\"text/javascript\">
                                if(confirm('OTP did not Match'))
                                {
                                        window.location.assign(\"./dashboard.php\")
                                }
                                </script>";
                        echo "bye";
                }
        }
 ?>
