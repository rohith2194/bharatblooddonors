<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
         session_start();
        if(isset($_SESSION['sess_user_pid']) and (trim($_SESSION['sess_user_pid']) !== ''))
        {
                require_once './config.php';
                require_once './functions.php';
                $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
                $search=mysqli_query($con,"SELECT otp,mobile,mobileverified FROM donars WHERE pid=".$_SESSION['sess_user_pid']." limit 1");
                $data=mysqli_fetch_array($search);
                if($data['mobileverified']=='N')
                {
                        $otp=$data['otp'];
                        $mobile=$data['mobile'];
                        $sms_message="Your OTP to verify mobilenumber is $otp Please verify your mobile number.contact admin@bharathblooddonors.in for any queries";
                        send_sms($mobile,$sms_message);
                        mysqli_close($con);
			//echo "OTP sent to mobile";
                        header("Location:./dashboard.php");
                }
                else
                {
                        echo "<script typ='text/javascript'>alert(\"mobile has already verified Thankyou \");</script>";
                }

        }
        else
        {
                header("Location: ./login.php");
        }
 ?>