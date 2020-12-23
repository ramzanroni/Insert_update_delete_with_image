<?php
	error_reporting(~E_NOTICE);
	require_once 'databaseConnPDO.php';
	if (isset($_POST['btnsave']))
	{

		$pname= $_POST['pname'];
		$pmodel=$_POST['pmodel'];
		$price=$_POST['price'];



		$imgFile = $_FILES['pimage']['name'];
		$tmp_dir = $_FILES['pimage']['tmp_name'];
		$imgSize = $_FILES['pimage']['size'];

		if (empty($pname)) {
			$errMSG="Please Enter Product Name";
		}
		else if (empty($pmodel)) {
			$errMSG="Please Enter Product Model";
		}
		else if (empty($price)) {
		 $errMSG="Please Enter Product Price";
		}
		else if(empty($imgFile))
		{
			$errMSG="Please Insert an image";
		}
		else
		{
			
			$upload_dir = 'user_images/';
	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); 
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
			$userpic = rand(1000,1000000).".".$imgExt;
				
			
			if(in_array($imgExt, $valid_extensions))
			{			
	
				if($imgSize < 5000000)				
				{
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
				}
				else
				{
					$errMSG = "Sorry, your file is too large.";
				}
			}
			else
			{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}
		}
		if (!isset($errMSG)) {
			$stmt=$DB_con->prepare('INSERT INTO tbl_users(pname, pmodel, price, pimage) VALUES (:pname, :pmodel, :price, :pimage)');
			$stmt->bindParam(':pname', $pname);
			$stmt->bindParam(':pmodel', $pmodel);
			$stmt->bindParam(':price', $price);
			$stmt->bindParam(':pimage', $userpic);
			if ($stmt->execute()) {
				
				// <script>
				// alert('Successfully Updated ...');
				// //window.location.href='index.php';
				// </script>
				$successMSG= "New record successfully inserted...";
				
				header("refresh:5; index.php");
			}
			else
			{
				$errMSG="error while inserting";
			}
		}

	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Add member</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>
<body>
	<div class="container">
    	<h1 class="bg-success text-center text-white">Add new user. </h1>
    	<h1 class="bg-info  text-center"><a class="text-decoration-none text-white" href="index.php"><i class="fas fa-eye"></i>View All Producr<i class="fas fa-eye"></i></a></h1>
    </div>
    <?php
    	error_reporting(~E_NOTICE);
    	if (isset($errMSG)) 
    	{
    		?>
    		<div>
    			<span><strong><?php echo $errMSG; ?></strong></span>
    		</div>
    <?php
    	}
    	else if (isset($successMSG)) 
    	{
    		?>
    		<div>
    			<!-- <strong><span><?php echo $successMSG; ?></span></strong> -->
				<script>
				alert('Successfully Updated ...');
				window.location.href='index.php';
				 </script>
    		</div>	
    		<?php
    	}
    ?>
    <div class="container">
    	<form method="post" enctype="multipart/form-data">
    		<div class="form-group">
    			<input type="text" name="pname" class="form-control" placeholder="Enter Product Name" value="<?php echo $pname; ?>">
    		</div>
    		<div class="form-group">
    			<input type="text" name="pmodel" class="form-control" placeholder="Enter Product Model" value="<?php echo $pmodel; ?>">
    		</div>
    		<div class="form-group">
    			<input type="text" name="price" class="form-control" placeholder="Enter Your Product Price" value="<?php echo $price; ?>">
    		</div>
    		<div class="form-group">
    			<label>Enter Your Product Image</label><br>
    			<input type="file" name="pimage" accept="image/*" />
    		</div>
    		<div>
    			<input type="submit" name="btnsave" value="Save" class="form-control btn btn-info">
    		</div>
    	</form>
    </div>
</body>
</html>