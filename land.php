<?php

        date_default_timezone_set('Asia/Kolkata');
        $d=strtotime("October 13, 2014, 5:30 pm");
        $present=date("Y-m-d H:i:s");
        $present=strtotime($present);


        $countd=$d-$present;
 ?>
<html>
	<head>

		<link rel="stylesheet" href="./css/flipclock.css">

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

		<script src="./js/flipclock.js"></script>
		<style>
		body
		{
                           height:100%;
                           width:100%;
                           background-image:url(images/nitw.jpg);/*your background image*/
                           background-repeat:no-repeat;/*we want to have one single image not a repeated one*/
                           background-size:cover;/*this sets the image to fullscreen covering the whole screen*/
                           filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.images/nitw.jpg',sizingMethod='scale');
                           -ms-filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='images/nitw.jpg',sizingMethod='scale')";
                }
                </style>
	</head>

	<body>
	        <a href="http://www.nitw.ac.in/nitw/index.php/student-welfare/clubs-and-committees/336" target="_blank"><img src="images/YRC.png" width=150 height=150 style="text-align:center;margin-left: auto;margin-right: auto;text-align: center;display: table-cell;vertical-align: middle"></img></a>
	        <h3 style="text-align:center;font-style:italic;color:white;">Launching</h3>
	        <img src="images/bbd.png"  style="text-align:center;margin-left: auto;margin-right: auto;text-align: center;display: table-cell;vertical-align: middle"></img>
                <h3 style="text-align:center;font-style:italic;color:white;">in</h3>
                <div class="clock"></div>

                <script type="text/javascript">
                        var clock = $('.clock').FlipClock(<?php echo $countd?>, {
                                clockFace: 'DailyCounter',
                                countdown: true
                        });
                </script>
                <h2 style="text-align:center;color:black;text-decoration: underline;">
                        Venue
                </h2>
                <h1 style="text-align:center;color:rgb(255, 255,255);text-decoration: underline;">
                                Civil Department Seminar Hall &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (On 13th October 2014 at 5:30 pm)
                </h1>


        </body>
</html>
