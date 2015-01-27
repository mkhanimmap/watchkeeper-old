<?php
include('checkSession.php');
include 'dbconnect.php';

//die();
if(isset($_POST["contact_form"]))
 {
	$date = isset($_REQUEST["date"])?$_REQUEST["date"]:"";
$time = isset($_REQUEST["time"])?$_REQUEST["time"]:"";
$countries = isset($_REQUEST["countries"])?$_REQUEST["countries"]:"";
$location = isset($_REQUEST["location"])?$_REQUEST["location"]:"";
$message = isset($_REQUEST["message"])?$_REQUEST["message"]:"";
$incidenttype = isset($_REQUEST["incidenttype"])?$_REQUEST["incidenttype"]:"";
$lat = isset($_REQUEST["lat"])?$_REQUEST["lat"]:"";
$lng = isset($_REQUEST["lng"])?$_REQUEST["lng"]:""; 
$id = isset($_REQUEST["id"])?$_REQUEST["id"]:"";

if ($_REQUEST['id']!=''){
	if ($_REQUEST['state']=='del'){	
		$query = "delete from \"incidentEvents\" where id=".$_REQUEST['id'];
	} else {
		if ($_REQUEST['lat']!=''){
			$query = "update \"incidentEvents\" set date='".$_REQUEST['date']."', time='".$_REQUEST['time']."', country='".$_REQUEST['countries']."', incidenttype='".$_REQUEST['incidenttype']."',location='".$_REQUEST['location']."', \"desc\"='".addslashes($_REQUEST['message'])."', the_geom=GeomFromText('POINT(".$_REQUEST['lng']." ".$_REQUEST['lat'].")', 4326) where id=".$_REQUEST['id'];
		} else {
			$query = "update \"incidentEvents\" set date='".$_REQUEST['date']."', time='".$_REQUEST['time']."', country='".$_REQUEST['countries']."', incidenttype='".$_REQUEST['incidenttype']."',location='".$_REQUEST['location']."', \"desc\"='".addslashes($_REQUEST['message'])."' where id=".$_REQUEST['id'];
		}	
	}
} else {
	if ($lat!=''){
		$query = "insert into \"incidentEvents\"(date, time, country, incidenttype, location, \"desc\", the_geom) values('".$date."','".$time."','".$countries."','".$incidenttype."','".$location."','".addslashes($message)."', GeomFromText('POINT(".$lng." ".$lat.")', 4326))";
	} else {
		$query = "insert into \"incidentEvents\"(date, time, country, incidenttype, location, \"desc\") values('".$date."','".$time."','".$countries."','".$incidenttype."','".$location."','".addslashes($message)."')";
	}	
}

if ((isset($_REQUEST['checkbox-2'])) && ($_REQUEST['countries']=='PAK') && ($_REQUEST['state']!='del')){
	$unique_id =  date("YmdHis").rand(1,999999);
	$query .= ";
	insert into sms_outbound(ref_id, msisdn_to, message, user_id, group_id, subgroup_id, unique_id, status, type, date_time)
	select '', cell, '".addslashes($_REQUEST['message'])."', id, 3, 0, '$unique_id', 0, 'm', now() from sms_user where email !='' and country_id = 3 and cell !='00000000000';";
}


echo $query;
die();

$res=pg_query($db, $query);
pg_close($db);	

header("Location: gallery.php");
/* Make sure that code below does not get executed when we redirect. */
exit;
	 
 }



$id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
$db = getDB();
$flg =  isset($_REQUEST['flg'])?$_REQUEST['flg']:"";
$date = '';
$time = '';
$country = '';
$location = '';
$message = '';
$incidenttype = '';
$lng='';
$lat='';

if (($id!='') && ($flg!='map')){
	$queryData = "select *, x(the_geom) as lng, y(the_geom) as lat from \"incidentEvents\" where id=".$id;
	$result=pg_query($db, $queryData);
	$row = pg_fetch_array($result);
	$date = $row['date'];
	$time = $row['time'];
	$country = $row['country'];
	$location = $row['location'];
	$message = $row['desc'];
	$incidenttype = $row['incidenttype'];
	$lng=$row['lng'];
	$lat=$row['lat'];
} else if (isset($_REQUEST['lng'])){
	$date = $_REQUEST['date'];
	$time = $_REQUEST['time'];
	$country = $_REQUEST['countries'];
	$location = $_REQUEST['location'];
	$message = $_REQUEST['message'];
	$incidenttype = $_REQUEST['incidenttype'];
	$lng=$_REQUEST['lng'];
	$lat=$_REQUEST['lat'];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div data-role="page" data-add-back-btn="true" id="addalert-page">
			<div data-role="header" id="header">
				<h1>Manage Alert</h1>
			</div><!-- /header -->
			
			
	<div data-role="content">  
		<form class="contact_form" action="" method="post" name="contact_form" id="contact_form" enctype="multipart/form-data">
			<fieldset data-role="fieldcontain">  
				<label for="date">Date:</label>
				<input id="date" name="date" type="date" placeholder="" required <?php if ($date!=''){echo "value='$date'";}?>/>
			</fieldset>
			<fieldset data-role="fieldcontain">  	
	        	<label for="time">Time:</label>
	        	<input id="time" name="time" type="time" required <?php if ($time!=''){echo "value='$time'";}?>/>
			</fieldset>
		    <?php
		        // include 'dbconnect.php';
		        $query = "select country, country_short from sms_country where status=1";
				$res=pg_query($db, $query);
		    ?>
		    <fieldset data-role="fieldcontain"> 
		    	<label for="country"  class="select">Country:</label>
		       
				<select name="countries" id="countries">
						<?php
							while ($row = pg_fetch_array($res)){
								if ($country==$row['country_short']){
									echo '<option selected value= "'.$row['country_short'].'">'.$row['country'].'</option>';
								} else {  
									echo '<option value= "'.$row['country_short'].'">'.$row['country'].'</option>';
								}		
							}
							
						?>
				</select>		
		    </fieldset>
		    <fieldset data-role="fieldcontain"> 
		        <label for="location">Location:</label>
		        <input id="location" name="location" type="text"  placeholder="" required <?php if ($location!=''){echo "value='$location'";}?>/>
		   </fieldset>
		   <fieldset data-role="fieldcontain"> 
				<label for="message">Message:</label>
				<textarea id="message" name="message" cols="40" rows="6"  required ><?php if ($message!=''){echo "$message";}?></textarea>
			</fieldset>
			<fieldset data-role="fieldcontain"> 
		    	<label for="incidenttype"  class="select">Incident Type:</label>
				<select name="incidenttype" id="incidenttype">
					<option <?php if ($incidenttype=='Army operation'){echo ' selected ';}?> value='Army operation'>Army operation</option>
					<option <?php if ($incidenttype=='Demonstration'){echo ' selected ';}?> value='Demonstration'>Demonstration</option>
					<option <?php if ($incidenttype=='Human remains find'){echo ' selected ';}?> value='Human remains find'>Human remains find</option>
					<option <?php if ($incidenttype=='IED explosion'){echo ' selected ';}?> value='IED explosion'>IED explosion</option>
					<option <?php if ($incidenttype=='IED find'){echo ' selected ';}?> value='IED find'>IED find</option>
					<option <?php if ($incidenttype=='IED threat'){echo ' selected ';}?> value='IED threat'>IED threat</option>
					<option <?php if ($incidenttype=='Kidnapping'){echo ' selected ';}?> value='Kidnapping'>Kidnapping</option>
					<option <?php if ($incidenttype=='Police operation'){echo ' selected ';}?> value='Police operation'>Police operation</option>
					<option <?php if ($incidenttype=='Rioting'){echo ' selected ';}?> value='Rioting'>Rioting</option>
					<option <?php if ($incidenttype=='Robbery'){echo ' selected ';}?> value='Robbery'>Robbery</option>
					<option <?php if ($incidenttype=='Security Forces operation'){echo ' selected ';}?> value='Security Forces operation'>Security Forces operation</option>
					<option <?php if ($incidenttype=='Shooting'){echo ' selected ';}?> value='Shooting'>Shooting</option>
					<option <?php if ($incidenttype=='Shooting (insurgency)'){echo ' selected ';}?> value='Shooting (insurgency)'>Shooting (insurgency)</option>
					<option <?php if ($incidenttype=='Shooting (political)'){echo ' selected ';}?> value='Shooting (political)'>Shooting (political)</option>
					<option <?php if ($incidenttype=='Vehicle hijacking'){echo ' selected ';}?> value='Vehicle hijacking'>Vehicle hijacking</option>
					<option <?php if ($incidenttype=='Weapons cache find'){echo ' selected ';}?> value='Weapons cache find'>Weapons cache find</option>
					<option <?php if ($incidenttype=='Others'){echo ' selected ';}?> value='Others'>Others</option>
				</select>		
		    </fieldset>
			<ul id="listview" data-role="listview" data-inset="true" data-theme="c" data-dividertheme="a">
				<li><a id="getCoord" href="#"><?php if ((isset($_REQUEST['lng'])) || ($lng!='')){echo "Modify Coordinate";} else {echo "Get Coordinate";} ?></a></li>
			</ul>
		    <fieldset data-role="controlgroup">
			   <input type="checkbox" name="checkbox-2" id="checkbox-2" class="custom" />
			   <label for="checkbox-2">Send SMS</label>
		    </fieldset>
		    <input type="hidden" id="lat" name="lat" value="<?php echo $lat;?>"/>
		    <input type="hidden" id="lng" name="lng" value="<?php echo $lng;?>"/>
		    <input type="hidden" id="id" name="id" value="<?php echo $id;?>"/>
		    <button class="submit" type="submit">Submit Form</button>
		</form>
	</div>

			
	      <div data-role="footer" data-theme="a">
            <div class="ui-bar">
                        
           	 <a href="saveEvent.php?state=del&id=<?php echo $id;?>" data-role="button" data-icon="arrow-u" data-theme="a" style="float:right;">Delete</a> 
           
           </div>
          </div>
          <!-- /Footer --> 

		</div>
</body>
</html>

		
        <!-- /page -->



<?php
pg_close($db);
?>
