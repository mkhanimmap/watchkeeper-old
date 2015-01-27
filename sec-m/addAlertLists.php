<?php
$limit = $_REQUEST['limit'];
$offset= $_REQUEST['offset'];

$arr = array();
include 'dbconnect.php';
$query = "select i.id as id, i.date as tgl, i.time as time, i.country, c.country, i.location, i.desc from \"incidentEvents\" i
		inner join sms_country c on i.country=c.country_short
		order by i.date DESC, i.time DESC limit $limit offset $offset";
$db = getDB();
$res=pg_query($db, $query);
while ($row = pg_fetch_object($res)){
	$arr[] = $row;
}
pg_close($db);
echo json_encode($arr);

?>
