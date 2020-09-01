<?php 
	
	session_start();
	require "admin/includes/functions.php";
	require "admin/includes/db.php";
	
	$msg = "";
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		if(isset($_POST['submit'])) {
			
			$guest = preg_replace("#[^0-9]#", "", $_POST['guest']);
			$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
			$phone = preg_replace("#[^0-9]#", "", $_POST['phone']);
			$date_res = htmlentities($_POST['date_res'], ENT_QUOTES, 'UTF-8');
			$time = htmlentities($_POST['time'], ENT_QUOTES, 'UTF-8');
			$fullname = htmlentities($_POST['fullname'], ENT_QUOTES, 'UTF-8');
			$room = htmlentities($_POST['room'], ENT_QUOTES, 'UTF-8');
			
			if($guest != "" && $email && $phone != "" && $date_res != "" && $time != "" && $fullname != "" && $room != "") {
				
				//Check for remaining table space;
				$table_array = array();
				$mtime = strftime("%H", time());
				$mdate = strftime("%Y-%m-%d", time());
				$get_table_count = $db->query("SELECT global_amt FROM Globals");
				$row_count = $get_table_count->fetch_assoc();
				$table_count = $row_count['global_amt'];
				$fetch = $db->query("SELECT * FROM reservation");
				if($fetch->num_rows) {
					while($row_fetch = $fetch->fetch_assoc()) {
						if(($row_fetch['date_res'] >= $mdate) && ($row_fetch['time'] >= $mtime)) {
							$table_array[] = $row_fetch['reserve_id'];
						}
					}
				}
				echo(count($table_array));
				if(count($table_array) < $table_count) {
					$check = $db->query("SELECT * FROM reservation WHERE no_of_guest='".$guest."' AND email='".$email."' AND phone='".$phone."' AND date_res='".$date_res."' AND room='".$room."' AND time='".$time."' LIMIT 1");
					
					if($check->num_rows) {
						
						$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>You have already placed a reservation with the same information</p>";
						
					}else{
						
						$insert = $db->query("INSERT INTO reservation(no_of_guest, email, phone, date_res, room, time, fullname) VALUES('".$guest."', '".$email."', '".$phone."', '".$date_res."', '".$room."', '".$time."', '".$fullname."')");
						
						if($insert) {
							
							$ins_id = $db->insert_id;
							
							$reserve_code = "UNIQUE_$ins_id".substr($phone, 3, 8);
							
							$msg = "<p style='padding: 15px; color: green; background: #eeffee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Reservation placed successfully. Your reservation code is $reserve_code. Please Note thatyour reservation expires after One hour</p>";
							
						}else{
							
							$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Could not place reservation. Please try again</p>";
							
						}
						
					}
				}else{
					
					$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>No table space available.Please check back after one hour</p>";
					
				}
				
			}else{
				
				$msg = "<p style='padding: 15px; color: red; background: #ffeeee; font-weight: bold; font-size: 13px; border-radius: 4px; text-align: center;'>Incomplete form data or Invalid data type</p>";
				
				//print_r($_POST);
				
			}
			
		}
		
	}
	
?>

<!Doctype html>

<html lang="en">

<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<meta name="description" content="" />

<meta name="keywords" content="" />

<head>
	
<title>LaMirage</title>

<link rel="stylesheet" href="css/main.css" />

<script src="js/jquery.min.js" ></script>

<script src="js/myscript.js"></script>
	
</head>

<body>
	
<?php require "includes/header.php"; ?>

<div class="parallax" onclick="remove_class()">
	
	<div class="parallax_head">
		
		<h2>  Booking
</h2>
		
		
	</div>
	
</div>

<div class="content" onclick="remove_class()">
	
	<div class="inner_content">
		
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="hr_book_form">
			
			<p id="pIndex">Book Your room</p>
			<p class="form_slg" style="color:black">We offer you the best reservation services</p>
			
			<?php echo "<br/>".$msg; ?>
			
			<div class="left">
				
				<div class="form_group">
					 
					 <label>Full Name</label>
					<input type="text" name="fullname" placeholder="Your full Name"  required>
					
				</div>
				
				
				<div class="form_group">
					
					<label>Email</label>
					<input type="email" name="email" placeholder="Enter your email" required>
					
				</div>
				
				<div class="form_group">
					
					<label>Date</label>
					<input type="date" name="date_res" placeholder="Select date for booking" required>
					
				</div>



				<div class="form_group">
					<label>Select your room</label>
					<select name="room" id="room">
  							<option value="Standard Double Room">Standard Double Room</option>
  							<option value="Superior Double Room">Superior Double Room</option>
  							<option value="Royal Suite">Royal Suite</option>
					</select>
                </div>
				
				
			</div>
			
			<div class="left">
				
			<div class="form_group">
					 
					 <label>No of Guest</label>
					<input type="number" placeholder="How many guests" min="1" name="guest" id="guest" required>
					
				</div>


				<div class="form_group">
					
					<label>Phone Number</label>
					<input type="text" name="phone" placeholder="Enter your phone number" required>
					
				</div>
				
				
				
				<div class="form_group">
					
					<label>Time</label>
					<input type="time" name="time" placeholder="Select time for booking" required>
					
				</div>

				
				<div class="form_group">
					
					<input type="submit" class="submit" name="submit" value="MAKE YOUR BOOKING"  style="background-color:#0A1B86" />
					
				</div>
				
			</div>
			
			<p class="clear"></p>
			
		</form>
		
	</div>
	
</div>

<!--
   start footer
  -->

   
  <div class="footer">
     <div class="infooter">
       <div class="Lo">
         <img class="logo" src="img/logo.png">
         <br>
         <br>
         <img class="app" src="img/app.png">
         <img class="app" src="img/play.png">
         <p>&copy; Copyright 2020 <span>LaMirage</span>,  All Right Reserved</p>


       </div>
       <div class="QV">
         <h1>Quick View</h1>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Reservation</a></li>
           
          </ul>
        </div>
       
       <div class="Contact">
            <h1>Contact Us</h1>
            <ul>
              <li>Phone: +1 (800) 654-9647</li>
              <li>Email: info@LaMirage.com</li>
              <li>Fax: +1 (800) 654-9646</li>
            </ul>
            <ul class="social-media">
              <li><a href="#" class="fa fa-facebook"></a></li>
              <li><a href="#" class="fa fa-twitter"></a></li>
              <li><a href="#" class="fa fa-instagram"></a></li>
              <li><a href="#" class="fa fa-linkedin"></a></li>
              <li><a href="#" class="fa fa-youtube"></a></li>
            </ul>
          </div>
      </div>
   <!--
   end footer
  -->
</body>

</html>