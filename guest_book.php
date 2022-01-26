<!doctype html>

<html lang="en">
	<head>
	 <link rel="stylesheet" type="text/css" href="captcha_css.css">
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<!-- Bootstrap CSS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		<title>Guest book</title>

<script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>



		 <style>
            
         </style>
	</head>
	<body>
		<div class="container">
			
					<h1>Guest book</h1>
					<hr/>
					
					<?php
//connect to MySQL
$linkDb = mysqli_connect("localhost", "root", "", "guest_book", 3306);

if (mysqli_connect_error($linkDb))
{
    die('<div class="alert alert-danger">Connection to DB failed!</div>');
}

if (isset($_POST['submitButton']))
{

    $guest_review = $_POST['guest_review'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $link = $_POST['link'];
    $captcha = $_POST['text'];
	$correctCaptcha = $_POST['check_captcha'];
	$ip_address= $_SERVER['REMOTE_ADDR'];

    if (empty($username))
    {
        echo '<div class="alert alert-warning">Username field cannot be empty</div>';
    }
    elseif (empty($email))
    {
        echo '<div class="alert alert-warning">Email field cannot be empty</div>';
    }

    elseif (empty($guest_review))
    {
        echo '<div class="alert alert-warning">Guest review field cannot be empty</div>';
    }
    elseif (empty($captcha))
    {
        echo '<div class="alert alert-warning">Captcha field cannot be empty</div>';
    }

    elseif ($captcha != $correctCaptcha)
    {
        echo '<div class="alert alert-warning">Captcha incorrect. Please try again</div>';
    }

    else
    {

        $time_now = time();

        $st = "SELECT user_id FROM user WHERE email = '" . $email . "' AND username = '" . $username . "'";
        //add the guest review to the table
        $select = mysqli_query($linkDb, $st) or die(mysqli_error($linkDb));
        $select_link = mysqli_query($linkDb, "SELECT `site_id` FROM `site` WHERE link = '" . $link . "' ") or die(mysqli_error($linkDb));
        $clientIP = false;
        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) $clientIP = $_SERVER['HTTP_CLIENT_IP'];
        /*$ip_address=$_SERVER['HTTP_CLIENT_IP'];*/
        $browser_info = $_SERVER['HTTP_USER_AGENT'];
        if (mysqli_num_rows($select) > 0 && mysqli_num_rows($select_link) > 0)
        {
            $row = mysqli_fetch_array($select);
            $row_link = mysqli_fetch_array($select_link);

            $sql_insert = "INSERT INTO review (text, user_fk, captcha,site_fk, ip_address, browser_info) VALUES('" . $guest_review . "',
									'" . $row[0] . "',
									'1',
									'" . $row_link[0] . "',
									'" . $ip_address . "','" . $browser_info . "' )";
            mysqli_query($linkDb, $sql_insert) or die(mysqli_error($link));

            if (mysqli_affected_rows($linkDb) == 1)
            {
                echo '<div class="alert alert-success">Guest review added :)</div>';
            }
            else
            {
                echo '<div class="alert alert-warning">Something wrong happened :(</div>';
            }

        }
        elseif (mysqli_num_rows($select) > 0 && mysqli_num_rows($select_link) == 0)
        {
            $sql_insert_0 = "INSERT INTO site (link) VALUES('" . $link . "' )";
            mysqli_query($linkDb, $sql_insert_0) or die(mysqli_error($linkDb));
            $select_link = mysqli_query($linkDb, "SELECT `site_id` FROM `site` WHERE link = '" . $link . "' ") or die(mysqli_error($linkDb));
            $row = mysqli_fetch_array($select);
            $row_link = mysqli_fetch_array($select_link);
            $sql_insert_1 = "INSERT INTO review (text, user_fk, captcha,site_fk, ip_address, browser_info) VALUES('" . $guest_review . "',
									'" . $row[0] . "',
									'1',
									'" . $row_link[0] . "',
									'" . $ip_address . "','" . $browser_info . "' )";

            mysqli_query($linkDb, $sql_insert_1) or die(mysqli_error($linkDb));

            if (mysqli_affected_rows($linkDb) == 1)
            {
                echo '<div class="alert alert-success">Guest review added :)</div>';
            }
            else
            {
                echo '<div class="alert alert-warning">Something wrong happened :(</div>';
            }

        }
        elseif (mysqli_num_rows($select) == 0 && mysqli_num_rows($select_link) > 0)
        {
            $sql_insert_0 = "INSERT INTO user (username, email) VALUES('" . $username . "', '" . $email . "' )";
            $st = "SELECT user_id FROM user WHERE email = '" . $email . "' AND username = '" . $username . "'";
            mysqli_query($linkDb, $sql_insert_0) or die(mysqli_error($linkDb));
            $select = mysqli_query($linkDb, $st) or die(mysqli_error($linkDb));
            $row = mysqli_fetch_array($select);
            $row_link = mysqli_fetch_array($select_link);

            $sql_insert_1 = "INSERT INTO review (text, user_fk, captcha,site_fk, ip_address, browser_info) VALUES('" . $guest_review . "',
									'" . $row[0] . "',
									'1',
									'" . $row_link[0] . "',
									'" . $ip_address . "','" . $browser_info . "' )";

            mysqli_query($linkDb, $sql_insert_1) or die(mysqli_error($linkDb));

            if (mysqli_affected_rows($linkDb) == 1)
            {
                echo '<div class="alert alert-success">Guest review added :)</div>';
            }
            else
            {
                echo '<div class="alert alert-warning">Something wrong happened :(</div>';
            }

        }
        elseif (mysqli_num_rows($select) == 0 && mysqli_num_rows($select_link) == 0)
        {

            $sql_insert_0 = "INSERT INTO site (link) VALUES('" . $link . "' )";

            $sql_insert_1 = "INSERT INTO user (username, email) VALUES('" . $username . "', '" . $email . "')";

            mysqli_query($linkDb, $sql_insert_0) or die(mysqli_error($linkDb));
            mysqli_query($linkDb, $sql_insert_1) or die(mysqli_error($linkDb));
            $st = "SELECT user_id FROM user WHERE email = '" . $email . "' AND username = '" . $username . "'";
            $select = mysqli_query($linkDb, $st) or die(mysqli_error($linkDb));
            $select_link = mysqli_query($linkDb, "SELECT `site_id` FROM `site` WHERE link = '" . $link . "' ") or die(mysqli_error($linkDb));
            $row = mysqli_fetch_array($select);
            $row_link = mysqli_fetch_array($select_link);
            $sql_insert_2 = "INSERT INTO review (text, user_fk, captcha,site_fk, ip_address, browser_info) VALUES('" . $guest_review . "',
									'" . $row[0] . "',
									'1',
									'" . $row_link[0] . "',
									'" . $ip_address . "','" . $browser_info . "' )";

            mysqli_query($linkDb, $sql_insert_2) or die(mysqli_error($linkDb));

            if (mysqli_affected_rows($linkDb) == 1)
            {
                echo '<div class="alert alert-success">Guest review added :)</div>';
            }
            else
            {
                echo '<div class="alert alert-warning">Something wrong happened :(</div>';
            }

        }

    }
}
?>
									
					<h2>Add a new Guest review</h2>
					
					<form action="guest_book.php" method="post">
					
					<h6 align="left">Username:</h6>
					<textarea rows="1" cols="10" name="username" placeholder="Please enter your username" class="form-control"></textarea>
					<h6 align="left">Email:</h6>
					<textarea rows="1" cols="50" name="email" placeholder="Please enter your email" class="form-control"></textarea>
					<h6 align="left">Link:</h6>
					<textarea rows="1" cols="50" name="link" placeholder="Please enter link to website" class="form-control"></textarea>
					<h6 align="left">Guest review:</h6>
					<textarea rows="10" cols="50" name="guest_review" placeholder="Please enter your guest review" class="form-control"></textarea>
					<textarea id="check_captcha" name="check_captcha" style="display:none;"></textarea>
					 <div class="left">
					 
					 <h6 align="left">Captcha:</h6>
					 
					 <div id="captchaBackground" height="100px" width="50%">
					
					 <canvas id="captcha">captcha text</canvas>
					  </div>
					  </div>
					<input id="textBox" rows="1" cols="5" name="text" placeholder="Please enter letters/digits which You see in photo" class="form-control"></input>
					
					 
					  
					 <div id="buttons">
					 <input id="submitButton" name="submitButton" type="submit" class="btn btn-success">
					 <button id="refreshButton" type="submit" class="btn btn-success">Refresh Captcha</button>
					 </div>
					 <span id="output"></span>
 <!--<span id="output"></span>-->
					
						<br/>
						
						
					</form>
			
		</div>
		
					<h2>List Guest reviews</h2>
					<?php
//We will use PHP to connect to the guest reviews table and get all guest reviews..


//connection is fine so we get the list of guest reviews from the DB
$sql = "SELECT * FROM review";
$users = "SELECT * FROM user";

//send query to mysql
$results = mysqli_query($linkDb, $sql);

//check if there are any rows (guest reviews)
if (mysqli_num_rows($results) > 0)
{
    //list all guest reviews
    echo '<div class="alert alert-success">' . mysqli_num_rows($results) . ' guest review(s) found</div>';

    while ($row = mysqli_fetch_array($results))
    {

        print "<div class=table-responsive>";
        print "<table id=myTable class=table>";

        print "<thead class=thead-dark>";
        print " <tr>";

        print " <td>Created on</td>";
        print " <td>Review</td>";

        print "<td>Username</td>";

        print " <td>Email</td>";

        print " <td>Site</td>";

        print "</thead>";
        // end foreach
        print " </tr>";

        //second query gets the data
        foreach ($results as $row)
        {
            $findUserId = "SELECT * FROM user WHERE user_id='" . $row['user_fk'] . "'";
            $userFind = mysqli_query($linkDb, $findUserId);
            $userRow = mysqli_fetch_array($userFind);
            $findSiteId = "SELECT * FROM site WHERE site_id='" . $row['site_fk'] . "'";
            $siteFind = mysqli_query($linkDb, $findSiteId);
            $siteRow = mysqli_fetch_array($siteFind);
            print " <tr>";
            print " <th>'" . $row['date'] . "'</th>";
            print " <th>'" . $row['text'] . "'</th>";

            print " <th>'" . $userRow['username'] . "'</th>";
            print " <th>'" . $userRow['email'] . "'</th>";

            print " <th>'" . $siteRow['link'] . "'</th>";

            print " </tr>";
        } //end record loop
        print "</table>";
        print " </div>";
?>
							
<?php
    }

}
else
{
    //no guest reviews, show message
    echo '<div class="alert alert-warning">There are no guest reviews yet, please add one.</div>';
}

?>
					<hr/>
					
					
	

<script>
 
$(document).ready(function(){
    $('#myTable').dataTable();
});

$(document).ready(function(){
    $('#myTable').dataTable();
});

	
	function sortTableDate() {
	var myElement = document.getElementById("myTable");
	var myElement2 = document.getElementById("myTable2");
	if(myElement)
	{
		 var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("myTable");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TH")[0];
	  y = rows[i + 1].getElementsByTagName("TH")[0];
	  //check if the two rows should switch place:
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
		document.getElementById("myTable").id = "myTable2";
	}
 
 else {
	 	 var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("myTable2");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TH")[0];
      y = rows[i + 1].getElementsByTagName("TH")[0];
      //check if the two rows should switch place:
      if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
		document.getElementById("myTable2").id = "myTable";
 }
}
function sortTable() {
	var myElement = document.getElementById("myTable");
	var myElement2 = document.getElementById("myTable2");
	if(myElement)
	{
		 var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("myTable");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TH")[0];
      y = rows[i + 1].getElementsByTagName("TH")[0];
      //check if the two rows should switch place:
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
		document.getElementById("myTable").id = "myTable2";
	}
 
 else {
	 	 var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("myTable2");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TH")[2];
      y = rows[i + 1].getElementsByTagName("TH")[2];
      //check if the two rows should switch place:
      if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
		document.getElementById("myTable2").id = "myTable";
 }
}
</script>
<script type="text/javascript" src="captcha.js"></script>
	</body>
</html>
