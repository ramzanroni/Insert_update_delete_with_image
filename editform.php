<?php
error_reporting(~E_NOTICE);
require_once 'databaseConnPDO.php';
if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) 
{
	$id=$_GET['edit_id'];
	$stmt_edit=$DB_con->prepare('SELECT pname, pmodel, price, pimage FROM tbl_users WHERE id=id');
	$stmt_edit->execute(array(':id'=>$id));
	$edit_row= $stmt_edit->fetch(PDO::FETCH_ASSOC);
	extract($edit_row);	
}
else
{
	header("Location: index.php");
}


if (isset($_POST['btn_save_updates'])) 
{
	$pname=$_POST['pname'];
	$pmodel=$_POST['pmodel'];
	$price=$_POST['price'];

	$imgFile=$_FILES['uimage']['name'];
	$tmp_dir=$_FILES['uimage']['tmp_name'];
	$imgSize=$_FILES['uimage']['size'];

	if ($imgFile) 
	{
		$upload_dir='user_images/';
		$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));
		$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
		$userpic = rand(1000,1000000).".".$imgExt;
		if(in_array($imgExt, $valid_extensions))
		{			
			if($imgSize < 5000000)
			{
				unlink($upload_dir.$edit_row['pimage']);
				move_uploaded_file($tmp_dir,$upload_dir.$userpic);
			}
			else
			{
				$errMSG = "Sorry, your file is too large it should be less then 5MB";
			}
		}
		else
		{
			$errMSG="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		}
	}
	else
	{
			// if no image selected the old image remain as it is.
		$userpic = $edit_row['pimage']; // old image from database
	}
		if (!isset($errMSG)) 
		{
			$stmt=$DB_con->prepare('UPDATE   tbl_users SET pname=:pname, pmodel=:pmodel, price=:price, pimage=:pimage WHERE id=:id');
			$stmt->bindParam(':pname', $pname);
			$stmt->bindParam(':pmodel', $pmodel);
			$stmt->bindParam(':price', $price);
			$stmt->bindParam(':pimage', $userpic);
			$stmt->bindParam('id', $id);
			if ($stmt->execute())
			{
				?>
				<script>
					alert('Successfully Updated ...');
					window.location.href='index.php';
				</script>
				<?php
			}
			else
			{
				$errMSG="Sorry Data could not Update.!";
			}	
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Data</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>
<body>
	<div class="col-12">
		<h1>Update Product</h1>
		<a href="index.php">All product</a>
	</div>
	<div class="col-3"></div>
	<div class="col-6">
		<form method="post" enctype="multipart/form-data">
			<?php
			if(isset($errMSG))
			{
				?>
				<div>
					<span></span> &nbsp; <?php echo $errMSG; ?>
				</div>
				<?php
			}
			?>
			<div class="form-group">
				<input type="text" name="pname" class="form-control" value="<?php echo $edit_row['pname']; ?>" required>
			</div>
			<div class="form-group">
				<input type="text" name="pmodel" class="form-control" value="<?php echo $edit_row['pmodel']; ?>" required>
			</div>
			<div class="form-group">
				<input type="text" name="price" class="form-control" value="<?php echo $edit_row['price']; ?>" required>
			</div>
			<div class="form-group">
				<p><img src="user_images/<?php echo $edit_row['pimage']; ?>" height="150" width="150"></p>
				<input type="file" name="uimage" accept="image/*">
			</div>
			<div class="form-group">
				<input type="submit" name="btn_save_updates" value="Update" class="form-control btn btn-success">
			</div>
		</form>
	</div>
	<div class="col-3"></div>
</body>
</html>