<?php
include('checkSession.php');
?>


<?php
include 'dbconnect.php';
$id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
$db = getDB();
$date = '';
$title = '';
$country = '';
$background = '';

$advise = '';

if ($id!=''){
	$queryData = "select * from security_advise where id=".$id;
	$result=pg_query($db, $queryData);
	$row = pg_fetch_array($result);
	$date = $row['date'];
	$title = $row['title'];
	$country = $row['country'];
	$background = $row['background'];
	$advise = $row['advise'];
}

?>

		<div data-role="page" data-add-back-btn="true" id="addalert-page">
			<div data-role="header" id="header">
				<h1>Manage Security Advisory</h1>
			</div><!-- /header -->
			
			
	<div data-role="content">  
		<form class="contact_form" action="saveAdvice.php?id=<?php echo $id?>" method="post" name="contact_form">
			<fieldset data-role="fieldcontain">  
				<label for="date">Date:</label>
				<input name="date" type="date" placeholder="" required <?php if ($date!=''){echo "value='$date'";}?>/>
			</fieldset>
			<?php
		        // include 'dbconnect.php';
		        $query = "select country, country_short from sms_country where status=1";
				$res=pg_query($db, $query);
		    ?>
		    <fieldset data-role="fieldcontain"> 
		    	<label for="country"  class="select">Country:</label>
		        <!-- <input type="text" name= "countries"  list= "countries" placeholder= "Click to select" required /> -->
				<select name="countries" id="countries">
<!-- 				<datalist id= "countries"> -->
						<?php
							while ($row = pg_fetch_array($res)){
								if ($country==$row['country_short']){
									echo '<option selected value= "'.$row['country_short'].'">'.$row['country'].'</option>';
								} else {  
									echo '<option value= "'.$row['country_short'].'">'.$row['country'].'</option>';
								}		
							}
							
						?>
<!-- 				</datalist> -->
				</select>		
		    </fieldset>
		    <fieldset data-role="fieldcontain"> 
				<label for="title">Title:</label>
				<textarea name="title" cols="40" rows="6"  required ><?php if ($title!=''){echo "$title";}?></textarea>
			</fieldset>
		    <fieldset data-role="fieldcontain"> 
				<label for="background">Background:</label>
				<textarea name="background" cols="40" rows="6"  required ><?php if ($background!=''){echo "$background";}?></textarea>
			</fieldset>
			<fieldset data-role="fieldcontain"> 
				<label for="advice">advice:</label>
				<textarea name="advice" cols="40" rows="6"  required ><?php if ($advise!=''){echo "$advise";}?></textarea>
			</fieldset>
		    <button class="submit" type="submit">Submit Form</button>
		</form>
	</div>
			

	      <div data-role="footer" data-theme="a">
            <div class="ui-bar">
                        
           	 <a href="saveAdvice.php?state=del&id=<?php echo $id;?>" data-role="button" data-icon="arrow-u" data-theme="a" style="float:right;">Delete</a> 
           
           </div>
          </div>
          <!-- /Footer --> 
          
		</div><!-- /page -->


<?php
pg_close($db);
?>
