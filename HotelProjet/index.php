<?php 
	
	session_start();
	require "admin/includes/functions.php";
	require "admin/includes/db.php";
	
	$get_recent = $db->query("SELECT * FROM food LIMIT 9");
	
	$result = "";
	
	if($get_recent->num_rows) {
		
		while($row = $get_recent->fetch_assoc()) {
			
			$result .= "<div class='centrr'><div class='parallax_item'>
				
							<a href='detail.php?fid=".$row['id']."'><img src='image/FoodPics/".$row['id'].".jpg' width='320px' height='320px'  style='border-top-left-radius: 16px;border-top-right-radius: 16px;'/> 
							<div class='detail'>
								
								<h4 style='color:#0A1B86'>".$row['food_name']."</h4>
								<p class='desc'>".substr($row['food_description'], 0, 33)."...</p>
								<p class='price'>#".$row['food_price']."</p>
								
							</div>
							<p class='clear'></p>
							
							
						</div>
						</div>";
			
		}
		
	}else{
		
		
		
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="js/jquery.min.js" ></script>

<script src="js/myscript.js"></script>
	
</head>

<body>
	
<?php require "includes/header.php"; ?>

<div class="parallax" onclick="remove_class()">
	
	<div class="parallax_head">
		
		<h2>Welcome to</h2>
		<h3 id="title">LaMirage Hotel</h3>
		
	</div>
	
</div>

<div class="content" onclick="remove_class()">
<p id="pIndex">Plan Your Stay </p>
	
	<a href="Room.php" class="submit">BOOK A ROOM</a>

<p id="pIndex">	Or <br>
Prebook Activities </p>
		
</div>

<div class="content remove_pad" onclick="remove_class()">
	
	<div class="inner_content on_parallax">
		
		
		<div class="parallax_content">
			
			<?php echo $result; ?>
			
			<p class="clear"></p>
			
		</div>
		
	</div>
	
</div>


		
	
<?php require "includes/footerr.php"; ?>

	
</body>

</html>