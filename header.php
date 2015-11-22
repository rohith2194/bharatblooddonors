<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Bharat Blood Donors</title>
<meta name="keywords" content="Bharat Blood Donors,Blood Donors Online,Send SMS to Blood Donors,Blood Donors In India, Indian Blood Donors, Urgent Blood Required" />
<meta name="description" content="Contact Blood Donors through our website. Whenever blood required our registered blood banks have access to send SMS to Donors registered with us in their respective area.Our website is a bridge between recipent and Donors." />
<link href="./css/fontscss.css" rel="stylesheet" />
<link href="default.css" rel="stylesheet" type="text/css" media="all" />
<link href="fonts.css" rel="stylesheet" type="text/css" media="all" />

<!--[if IE 6]><link href="default_ie6.css" rel="stylesheet" type="text/css" /><![endif]-->

</head>
<br>
<body>

<a href="http://www.nitw.ac.in/nitw/index.php/student-welfare/clubs-and-committees/336" target="_blank"><img src="images/yrc_trans.png" width=200 height=200 style="position:fixed;float:left;float:top;"></img></a>


<div id="logo">
	<h1>
	        <a href="./index.php" class="icon icon-group"><span>Bharat Blood Donors</span></a>
        </h1>
	<h3 style="text-align:center;color:black;"><b>A Bridge between blood donors and RECIPIENTS.</b></h3>

</div>

<div id="header">
	<div id="menu" class="container">
		<ul>
			<li class="current_page_item"><a href="./index.php" accesskey="1" title="Bharat Blood Donors"  class="icon  icon-home">Homepage</a></li>

			<?php
                                if(isset($_SESSION['sess_user_pid']) and (trim($_SESSION['sess_user_pid']) !== ''))
                                {
			 ?>
                        <li><a href="./dashboard.php" accesskey="1" title="Profile" >My Profile</a></li>
                        <?php
                                }
                                else
                                {
                        ?>
			<li><a href="./signup.php" accesskey="1" title="Register To Donate">Register</a></li>
			<?php
                                }
                        ?>
			<li><a href="./display_donars.php" accesskey="2" title="Search For Donors" class="icon icon-search">Donors Search</a></li>
			<li><a href="./index.php#why_donation" accesskey="3" title="Why I need to Donate" class="icon icon-question-sign">Why Donation</a></li>
			<li><a href="./how_we_work.php" accesskey="4" title="How we Work">How we Work..?</a></li>
			<?php
                                if(isset($_SESSION['sess_user_pid']) and (trim($_SESSION['sess_user_pid']) !== ''))
                                {
			 ?>
                        <li><a href="./logout.php" accesskey="5" title="Login" class="icon icon-signout">Logout</a></li>
                        <?php
                                }
                                else
                                {
                        ?>
			<li><a href="./login.php" accesskey="5" title="Login" class="icon icon-signin">Login</a></li>
			<?php
                                }
                        ?>
                        <li><a href="http://www.nitw.ac.in/nitw/index.php/student-welfare/clubs-and-committees/336" target="_blank" accesskey="6" title="Youth Red Cross NITW" >YRC NITW</a></li>

		</ul>
	</div>
</div>
<br>
</body>