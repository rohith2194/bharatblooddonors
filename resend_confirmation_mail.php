<?php
        session_start();
        if(isset($_SESSION['sess_user_pid']) and (trim($_SESSION['sess_user_pid']) !== ''))
        {
                require_once './config.php';
                $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
                $search=mysqli_query($con,"SELECT password,mobile,email FROM donars WHERE pid=".$_SESSION['sess_user_pid']." limit 1");
                $data=mysqli_fetch_array($search);
                $length=strlen($data['password']);
                $token=substr($data['password'],$length-11,$length-1);
                //update message body
                $message_body="Verify your E-mail www.bharathblooddonors.in/mailverify.php?mobile=".$data['mobile']."&token=$token";
                mail($data['email'],"Email Verification ",$message_body,"From: admin@bharathblooddonors.in");
                mysqli_close($con);
                header("Location:./dashboard.php");
        }
        else
        {
                header("Location: ./login.php");
        }
?>
