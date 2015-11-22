<?php
        if(isset($_GET['mobile']) and isset($_GET['token']))
        {
                require_once './config.php';
                require_once './functions.php';
                $mobile=check_data($_GET["mobile"]);
                if(empty($_GET["mobile"]))
                {

                        echo "<script>alert(\"Something Went Wrong. We'll investigate this\");</script>";
                }
                else
                {
                        $mobile=intval($mobile);
                        if(is_int($mobile) and (strlen($_GET["mobile"])==10))
                        {
                                $mobile=check_data($_GET["mobile"]);
                                $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
                                $search=mysqli_query($con,"SELECT password FROM donars WHERE mobile='$mobile' limit 1");
                                $data=mysqli_fetch_array($search);
                                $length=strlen($data['password']);
                                $true_token=substr($data['password'],$length-11,$length-1);
                                if($true_token==$_GET['token'])
                                {
                                        mysqli_query($con,"UPDATE donars SET  emailverified='Y' WHERE mobile='$mobile' limit 1");
                                         echo "<script>
                                                                if(confirm('Your E-mail has been verified Thankyou'))
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
                                         echo "<script>
                                                                if(confirm('E-mail has not verified please use resend confirmation link'))
                                                                {
                                                                        window.location.assign(\"./login.php\")
                                                                }
                                                                else
                                                                {
                                                                        window.location.assign(\"./login.php\")
                                                                }


                                                        </script>";
                                }
                                mysqli_close($con);
                        }
                        else
                        {
                                $mobileError="Please Enter Valid Mobile Number";
                        }

                }

        }
        else
        {
                echo "<script>alert(\"Something Went Wrong. We'll investigate this\");</script>";
        }

 ?>
