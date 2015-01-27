<?php
define('MAIN',realpath('../'));
include MAIN.'/includes/config.php';
include MAIN.'/includes/class.admin.php';
include MAIN.'/includes/resize-class.php';

$admin = new Admin();


$msg = "";
// checking users for login
check_admin();

$email = "";
$pass = "";


if(isset($_POST["addcountry"]))
 {
 				
		
		
				$country = isset($_POST["country"])?$_POST["country"]:"";
				$country_short = isset($_POST["country_short"])?$_POST["country_short"]:"";
				$date_time = date("Y-m-d H:i:s");
				
				$msg = "";
				$file_name = "";
				if ($_FILES['icon'] )
					{
						$dir = "../../sec/sec-web/images/";
						$newName = rand(1,9999)."_".time();
						
						$ext = "";
						
							
							 $ext = "";
							 $ext = checkDot($_FILES['icon']["name"]);
							
							 if(chkExt($ext))
							  {
								if(move_uploaded_file($_FILES['icon']["tmp_name"],$dir.$newName.$ext))
								 {
									$image_upload = 1;
									// *** 1) Initialise / load image
									$resizeObj = new resize($dir.$newName.$ext);
									// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
									$resizeObj -> resizeImage(16, 16, 'auto');
									// *** 3) Save image
									$resizeObj -> saveImage($dir."small_".$newName.$ext, 100);
									
									$resizeObj = new resize($dir.$newName.$ext);
									
									$resizeObj -> resizeImage(32, 32, 'auto');
									// *** 3) Save image
									$resizeObj -> saveImage($dir."large_".$newName.$ext, 100);
									
									
									unlink($dir.$newName.$ext);
									unlink($dir.$old_img);
									$file_name = $newName.$ext;
									
								 }
								else
								 {
									 $mss .= "Sorry! Error uploading image.";
								 }
							  }
							 else
							  {
								  $mss .= "Sorry! the system does not support image format;";
							  }
						 
					}
				//$a = new Thumbnail($_FILES["icon"]["tmp_name"],16,16,$small);
				//$a = new Thumbnail($_FILES["icon"]["tmp_name"],32,32,$large);
				
					$arrValue = array (	
					        "country" => $country,
							"country_short" => $country_short,
							"icon" => $file_name,
							"status" => 1							
							);
	
					 $ins_id = $admin->Insert(TBLCON, $arrValue);
					 if($ins_id)
					  {				
							    header("Location:country.php?msg=".base64_encode(1)); 
					 }
					 else
					  {
					   $msg =   $admin->display_msg('Country has been not added succresfully!',false);
					  }
			
			
			
		 
 }				 


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>:::: Admin  Control Panel ::::</title>
<link href="main.css" rel="stylesheet" type="text/css" />
<link href="../css/silver.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/lib/prototype.js"></script>
<script type="text/javascript" src="../js/lib/jquery.js"></script>
<script type="text/javascript" src="js/default.js"></script>
<script type="text/javascript" src="../js/admin/members.js"></script>



</head>

<body>
<!-- CONATINER -->

<div class="container">
<!-- HEADER  -->
	<div>
	 
		     <div class="header_menu">
             
             <div align="right" >
			 <?php include('top.php');?>
			 </div></div>
			 
			 <!-- Button -->
			 <div id="menu">
				<?php include('header.php')?>
		  </div>
			 <!-- END Button -->
	 
	</div>
<!-- END HEADER  -->

<!-- BANNER -->
<div class="banner">
  <div class="hea_ico" ></div>
</div>
<!-- END BANNER -->

<!-- MID  -->
  <div class="mid"> 
    <!-- LEFT MENU -->
    <!-- END LEFT MENU -->
    <!-- RIGHT OBJECTS -->
    <div>
      
          <span class="h1_box_2">
        <div class="all_box"> <br/>
        Add Country</div> 
          </span>
          <br />
          <div id="msg" align="center"><?php echo $msg;?></div>
<div id="outer_div"  ></div>
        <div id="err" class="error" style="display:none"></div>
<div id="success" class="success" style="display:none"></div>
    <form name="form_member" id="form_member" action="" method="post" enctype="multipart/form-data" >
	 <input type="hidden" name="path" id="path" value="<?php echo FULL_PATH?>" />
      <table width="100%" height="28" cellpadding="1" cellspacing="1">
        <tr>
          <td width="166" class="tb_h3" >Country Name:</td>
          <td width="673" ><span class="tb_h3">
            <input name="country" type="text" class="sign_inp" id="country" style="width:400px;" />
          </span></td>
        </tr>
		
		
        <tr>
          <td height="17"><span class="tb_h3">Country Short Name:</span></td>
          <td><span class="tb_h3">
          <input name="country_short" type="text" class="sign_inp" id="country_short" style="width:400px;" /></span></td>
        </tr>
        <tr>
          <td height="17"><span class="tb_h3">Country Icon:</span></td>
          <td  >&nbsp;<input type="file" name="icon" id="icon" class="sign_inp" /></td>
        </tr>
        <tr>
          <td height="17">&nbsp;</td>
          <td class="tb_h3" >&nbsp;</td>
        </tr>
        <tr>
          <td height="17">&nbsp;</td>
          <td class="tb_h3" ><input type="submit" name="addcountry" id="register" value="Add Country"  />
            <input type="button" name="button" id="button" value="Back" onclick="javascript:window.location.href='country.php'" /></td>
        </tr>
      </table>
      </form>
    </div>
    <!-- RIGHT OBJECTS -->
</div>

<!-- END MID  -->

<!-- FOOTER -->
<div class="footer_mid"><?php include ("footer.php");?></div>
<!-- END FOOTER -->

</div>
<!-- END CONATINER -->

<div id="theFormDiv"></div>

</body>
</html>

