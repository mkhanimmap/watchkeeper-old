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


if(isset($_POST["editmembers"]))
 {
				$id = isset($_POST["id"])?base64_decode($_POST["id"]):"";
				$country = isset($_POST["country"])?$_POST["country"]:"";
				$country_short = isset($_POST["country_short"])?$_POST["country_short"]:"";
				
				$old_img = isset($_REQUEST["old_img"])?$_REQUEST["old_img"]:"";
				$icon = $old_img;
		  
		   if ($_FILES['icon'])
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
									
									// *** 1) Initialise / load image
									$resizeObj = new resize($dir.$newName.$ext);
									// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
									$resizeObj -> resizeImage(32, 32, 'auto');
									// *** 3) Save image
									$resizeObj -> saveImage($dir."large_".$newName.$ext, 100);
								
									$icon = $newName.$ext;
									
									unlink($dir.$newName.$ext);
									if(file_exists($dir.$old_img) and !empty($old_img))
										unlink($dir.$old_img);
									
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
				
				
				$arrValue = array (	
					         	"country" => $country,
								"country_short" => $country_short,
								"icon" => $icon
								
							);
				$where =  "id = ".$id;			
			
			$msg = array("Record Update Successfully","Error in updating");
			$val = $admin->update_tbl(TBLCON,$where ,$arrValue, $msg);
		
			// showing the msg of the query
			$msg = $admin->show_msg($val);
			  
	}				 

$id = isset($_GET["id"])?base64_decode($_GET["id"]):"";
if($id !="")
{
	$sql = "select * from ".TBLCON." where id =".$id;
	$row = $admin->RunQuerySingleObj($sql);
	if(!empty($row))
	{
		$country = !empty($row->country)?$row->country:"";
		$country_short = !empty($row->country_short)?$row->country_short:"";
		$icon = !empty($row->icon)?$row->icon:"";
						
	}
   else
	{
		header("location:country.php?msg=".base64_encode(6)); 
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
	 
		     <div class="header_menu"><div align="right" >
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
  <div class="hea_ico"></div>
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
        Update Country</div> </span>
          <br />
          <div id="msg" align="center"><?php echo $msg;?></div>
<div id="outer_div"  ></div>
        <div id="err" class="error" style="display:none"></div>
<div id="success" class="success" style="display:none"></div>
    <form name="form_member" id="form_member" action="" method="post" enctype="multipart/form-data" >
      <input type="hidden" name="old_img" id="old_img" value="<?php echo $icon;?>" />
      <table width="100%" height="28" cellpadding="1" cellspacing="1">
        <tr>
          <td width="166" class="tb_h3" >Country Name:</td>
          <td width="673" ><span class="tb_h3">
            <input name="country" type="text" class="sign_inp" id="country" style="width:400px;" value="<?php echo $country;?>" />
          </span></td>
        </tr>
		
        <tr>
          <td height="17"><span class="tb_h3">Country Short Name:</span></td>
          <td><span class="tb_h3">
          <input name="country_short" type="text" class="sign_inp" id="country_short" style="width:400px;" value="<?php echo $country_short;?>" /></span></td>
        </tur>
        <tr>
          <td height="17"><span class="tb_h3">Country Icon:</span></td>
          <td  >&nbsp;<input type="file" name="icon" id="icon" class="sign_inp" />&nbsp;&nbsp;&nbsp;&nbsp;
                              <?php
                               if(file_exists("../../sec/sec-web/images/large_".$icon) and !empty($icon))
							    {
									?>
                                    <img src="../../sec/sec-web/images/large_<?php echo $icon?>">
                                    <?php
							    }
							  ?></td>
        </tr>
		
        <tr>
          <td height="17">&nbsp;</td>
          <td class="tb_h3" >&nbsp;</td>
        </tr>
        <tr>
          <td height="17">&nbsp;<input type="hidden" name="id" value="<?php echo base64_encode($id);?>" /></td>
          <td class="tb_h3" ><input type="submit" name="editmembers" id="editmembers" value="Update"  />
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

