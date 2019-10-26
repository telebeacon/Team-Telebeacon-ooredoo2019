<?php
/*
 * MQTT Bridge Rule Manager
 * https://github.com/eercanayar/mqtt-bridge-rule-manager
 * for IoNeeds Open IoT Project
 * http://ioneeds.com
 * == Author ==
 * Emir Ercan Ayar
 * emir@eercan.com
 * http://eercan.com
 * https://github.com/eercanayar/
*/


include("../mysql.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MQTT Bridge Rule Manager</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>
    <div class="container">
	<p>&nbsp;</p>
<?php

if($_GET['do']=="delete") {
	if(mysql_query("DELETE FROM rules WHERE rule_id='".$_GET['rule_id']."'")) {
		echo '<div class="alert alert-success" role="alert">Rule deleted.</div>';
	} else {
		echo '<div class="alert alert-danger" role="alert">Rule can\'t deleted.</div>';
	}
}

if($_GET['do']=="new") {
	
	if($_POST['do']=="add_rule") {
		if(mysql_query("INSERT into rules SET device_id='".$_POST['device_id']."', data_key='".$_POST['data_key']."', comparison='".$_POST['comparison']."', value='".$_POST['value']."', action='".$_POST['action']."', target='".$_POST['target']."', data='".$_POST['data']."'")) {
			echo '<div class="alert alert-success" role="alert">Rule added.</div>';
		} else {
			echo '<div class="alert alert-danger" role="alert">Rule can\'t added.</div>';
		}
	}
			
	echo ' <div class="panel panel-default">
		<div class="panel-heading">
		  <h3 class="panel-title"><span style=\'float:right\'><a href="?" class="btn btn-primary btn">&raquo; Return to Home</a></span><strong>MQTT Bridge Rule Manager</strong><br />Add new rule</h3>
		</div>
		<div class="panel-body">';
		
		echo '
<form method="POST">
<input type="hidden" name="do" value="add_rule">
<div class="form-group">
    <label for="device_id">Device ID</label>
    <input type="text" class="form-control" id="device_id" name="device_id" placeholder="Enter device ID">
  </div>
<div class="form-group">
    <label for="data_key">Key</label>
    <input type="text" class="form-control" id="data_key" name="data_key" placeholder="Enter data key">
  </div>
  
<div class="form-group">
    <label for="comparison">Comparison</label>
    <select class="form-control" id="comparison" name="comparison">
	  <option value="greaterthan">&gt;</option>
	  <option value="equals">=</option>
	  <option value="lessthan">&lt;</option>
	</select>
</div>
  
<div class="form-group">
    <label for="value">Value</label>
    <input type="text" class="form-control" id="value" name="value" placeholder="Enter value">
  </div>

<div class="form-group">
    <label for="action">Action</label>
    <select class="form-control" id="action" name="action">
	  <option value="email">E-Mail</option>
  	  <option value="publish">Publish</option>
	  <option value="url_trigger">URL Trigger</option>
	</select>
</div>

<div class="form-group">
    <label for="target">Target</label>
    <input type="text" class="form-control" id="target" name="target" placeholder="Enter data to action target">
  </div>

<div class="form-group">
    <label for="data">Data</label>
    <input type="text" class="form-control" id="data" name="data" placeholder="Enter data to trigger">
  </div>
<button type="submit" class="btn btn-default">Submit</button>
</form>
  ';
  
		echo '</div></div>';
} else if($_GET['do']=="edit") {
	
	if($_POST['do']=="edit_rule") {
		if(mysql_query("UPDATE rules SET device_id='".$_POST['device_id']."', data_key='".$_POST['data_key']."', comparison='".$_POST['comparison']."', value='".$_POST['value']."', action='".$_POST['action']."', target='".$_POST['target']."', data='".$_POST['data']."' WHERE rule_id='".$_GET['rule_id']."'")) {
			echo '<div class="alert alert-success" role="alert">Rule edited.</div>';
		} else {
			echo '<div class="alert alert-danger" role="alert">Rule can\'t edited.</div>';
		}
	}
			
	echo ' <div class="panel panel-default">
		<div class="panel-heading">
		  <h3 class="panel-title"><span style=\'float:right\'><a href="?" class="btn btn-primary btn">&raquo; Return to Home</a></span><strong>MQTT Bridge Rule Manager</strong><br />Editing: #'.$_GET['rule_id'].'</h3>
		</div>
		<div class="panel-body">';

	$filtresql2 = mysql_query("SELECT * FROM rules WHERE rule_id='".$_GET['rule_id']."'");
	$row2 =  mysql_fetch_assoc($filtresql2);

		echo '
<form method="POST">
<input type="hidden" name="do" value="edit_rule">
<div class="form-group">
    <label for="device_id">Device ID</label>
    <input type="text" class="form-control" value="'.$row2['device_id'].'" id="device_id" name="device_id" placeholder="Enter device ID">
  </div>
<div class="form-group">
    <label for="data_key">Key</label>
    <input type="text" class="form-control" value="'.$row2['data_key'].'" id="data_key" name="data_key" placeholder="Enter data key">
  </div>
  
<div class="form-group">
    <label for="comparison">Comparison</label>
    <select class="form-control" id="comparison" name="comparison">
	  <option value="greaterthan"'; if($row2['comparison']=="greaterthan") { echo ' selected'; } echo '>&gt;</option>
	  <option value="equals"'; if($row2['comparison']=="equals") { echo ' selected'; } echo '>=</option>
	  <option value="lessthan"'; if($row2['comparison']=="lessthan") { echo ' selected'; } echo '>&lt;</option>
	</select>
</div>
  
<div class="form-group">
    <label for="value">Value</label>
    <input type="text" class="form-control" value="'.$row2['value'].'" id="value" name="value" placeholder="Enter value">
  </div>

<div class="form-group">
    <label for="action">Action</label>
    <select class="form-control" id="action" name="action">
	  <option value="email"'; if($row2['action']=="email") { echo ' selected'; } echo '>E-Mail</option>
	  <option value="publish"'; if($row2['action']=="publish") { echo ' selected'; } echo '>Publish</option>
	  <option value="url_trigger"'; if($row2['action']=="url_trigger") { echo ' selected'; } echo '>URL Trigger</option>
	</select>
</div>

<div class="form-group">
    <label for="target">Target</label>
    <input type="text" class="form-control" value="'.$row2['target'].'" id="target" name="target" placeholder="Enter data to action target">
  </div>


<div class="form-group">
    <label for="data">Data</label>
    <input type="text" class="form-control" value="'.$row2['data'].'" id="data" name="data" placeholder="Enter data to trigger">
  </div>
<button type="submit" class="btn btn-default">Submit Changes</button>
</form>
  ';
  
		echo '</div></div>';
} else {

	echo ' <div class="panel panel-default">
            <div class="panel-heading">
				<h3 class="panel-title"><span style=\'float:right\'><a href="?do=new" class="btn btn-primary">+ Add New Rule</a></span><strong>MQTT Bridge Rule Manager</strong><br />Homepage</h3>
            </div>
            <div class="panel-body">';

	$filtresql2 = mysql_query("SELECT * FROM rules order by rule_id");
echo '          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Device ID</th>
                <th>Rule</th>
                <th>Action</th>
                <th>Target</th>
		<th>Data</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              
            ';
	while($row2 =  mysql_fetch_assoc($filtresql2)) {
		
		if($row2['comparison']=="greaterthan") { $print_comparison = ">"; }
		if($row2['comparison']=="equals") { $print_comparison = "="; }
		if($row2['comparison']=="lessthan") { $print_comparison = "<"; }
		
		if (strlen($row2['target']) > 50) { $row2['target'] = substr($row2['target'], 0, 50)."..."; }

		echo '<tr>
                <td>#'.$row2['rule_id'].'</td>
                <td>'.$row2['device_id'].'</td>
                <td>'.$row2['data_key'].' '.$print_comparison.' '.$row2['value'].'</td>
                <td>'.$row2['action'].'</td>
                <td>'.$row2['target'].'</td>
		<td>'.$row2['data'].'</td>
				<td><a href="?do=edit&rule_id='.$row2['rule_id'].'" class="btn btn-sm btn-warning">Edit</a>
        <a href="?do=delete&rule_id='.$row2['rule_id'].'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a></td>
			  </tr>';
	}
	
	echo '</tbody>
          </table>
          </div></div>
';
}
?>
    </div><!-- /.container -->
  </body>
</html>
