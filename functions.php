<?php
        function  check_data($data)
        {
                $data=trim($data);
                $data=stripslashes($data);
                $data=htmlspecialchars($data);
                return $data;
        }
        function generateRandomString($length = 10)
        {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';
                for ($i = 0; $i < $length; $i++)
                {
                        $randomString .= $characters[rand(0, strlen($characters) - 1)];
                }
                return $randomString;
        }

        function send_sms($to,$message)
        {
                $message_body=str_replace(" ","+",$message);
                $url="http://my.b2bsms.co.in/API/WebSMS/Http/v1.0a/index.php?username=tarunapi&password=bloodb2b&sender=BBDNRS&to="."$to"."&message="."$message_body"."&reqid=1&format={json|text}&route_id=3&msgtype=unicode";
                file_get_contents($url);
        }
        function conv_text($bg)
        {
                $length=strlen($bg);
                $type=$bg[$length-1];
                if($type=='+')
                {
                        $add=' pos';
                }
                else
                {
                        $add=' neg';
                }
                if($length==2)
                {
                        $result=$bg[0].$add;
                }
                else if($length==3)
                {
                        $result=$bg[0].$bg[1].$add;
                }
                $result=strtoupper($result);
                return $result;
        }
 ?>