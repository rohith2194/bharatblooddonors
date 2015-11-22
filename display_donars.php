<?php
	//error_reporting(E_ALL); 
	//ini_set('display_errors', 1);  
        session_start();
        $request=false;
        $pinError=$bgError=$pin=$bg='';
        if($_SERVER["REQUEST_METHOD"]=="POST")
        {
            $request=true;
        }
?>

<head>
  <link href='./css/bootstrap.min.css' rel='stylesheet' type='text/css'>
  <link href='./css/fontscss.css' rel='stylesheet' type='text/css'>
  <link href="default.css" rel="stylesheet" type="text/css" media="all" />
  <link href="fonts.css" rel="stylesheet" type="text/css" media="all" />
  <link href='./css/table.css' rel='stylesheet' type='text/css'>

</head>
<?php
    include 'header.php';
 ?>
 <body>
 <div class='container'>
    <div class='panel panel-primary dialog-panel'>
      <div class='panel-heading'>
        <h5>Donors Search</h5>
      </div>
      <div class='panel-body'>
        <form class='form-horizontal' role='form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
	<CENTER><a class="hover" href="http://www.mapsofindia.com/pincode/" target="_blank" style="text-decoration: underline">Clik Here to Know the PIN CODES </a></CENTER><br>
		
        <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-2'>Pin Code</label>
            <div class='col-md-4'>
              <div class='col-md-4'>
                <div class='form-group internal'>
                  <input class='form-control'  placeholder='6-digit PIN CODE' type='text' name="pin" value=<?php echo $pin;?> >
                </div>
              </div>
              <div class='form-group'>
            <label class='control-label col-md-2 col-md-offset-0'>Blood Group</label>
              <div class='col-md-4'>
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
            </div>
          </div>
          <div class='form-group'>
            <div class='col-md-offset-4 col-md-3'>
              <button class='btn-lg btn-primary' type='submit'>Search</button>
            </div>
          </div>
          </form>
        </div>

    <?php
        if($request)
        {
                require_once './functions.php';
                $be=$pe=true;
                $pin=$_POST["pin"];
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
                if($pe or $be)
                {
                    if($pe)
                    {
                        echo "<script type= text/javascript> alert('$pinError')</script>";
                    }
                    if($be)
                    {
                        echo "<script type= text/javascript> alert('$bgError')</script>";
                    }
                }
                else
                {
                        require_once './config.php';
                        $con=mysqli_connect(HOST,USER,PASSWORD,DATABASE);
                        $search=mysqli_query($con,"SELECT DISTINCT mobile ,name,nextsmsdate  FROM donars WHERE pin='$pin' AND bg='$bg' AND subscribed='Y' AND display=1 AND mobileverified='Y'");

                        if(mysqli_num_rows($search)==0)
                        {
                                echo "<script type= text/javascript> alert('Sorry to say this but we donot have data of Donors in $pin')</script>";
                        }
                        else
                        {?>
                                        <table>
                                          <thead>
                                            <tr>
                                              <th>Mobile ( PIN CODE:<?php echo $pin ?> )</th>
                                              <th>Name ( Blood Group:<?php echo $bg ?> )</th>
                                            </tr>
                                          </thead>
                                          <tbody>

                                        <?php while($data=mysqli_fetch_array($search)){
                                                //echo strtotime(date('Y-m-d'))-strtotime($data['nextsmsdate']);
                                                if((strtotime(date('Y-m-d'))-strtotime($data['nextsmsdate']))<0)
                                                {
                                                        continue;
                                                }
                                        ?>
                                            <tr>
                                            <td><strong><?php echo $data['mobile'] ?></strong></td>
                                            <td><strong><?php echo $data['name'] ?></strong></td>
                                            </tr>
                                        <?php }
                        mysqli_close($con);
                        }
                }
        }
?>

           </div>
        </div>

</body>
</html>