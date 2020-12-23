<?php  
require_once 'databaseConnPDO.php';
if (isset($_GET['delete_id']))
{
	$stmt_select=$DB_con->prepare('SELECT pimage FROM tbl_users WHERE id=:uid');
	$stmt_select->execute(array(':uid'=>$_GET['delete_id']));
	$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
	unlink("user_images/".$imgRow['pimage']);
	$stmt_delete=$DB_con->prepare('DELETE FROM tbl_users WHERE id=:uid');
	$stmt_delete->bindParam(':uid', $_GET['delete_id']);
	$stmt_delete->execute();
	header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Insert With Image</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<h1 class="bg-danger text-white text-center p-2">All Member</h1>
				<h1 class="bg-success text-white text-center p-2 " ><a href="addnew.php"><i class="fas fa-list text-white"></i></a></h1>
				<!-- <div class="row">
					<div class="col-12 bg-success">
						<div class="col-3">
							<a class="text-white " href="addnew.php"><i class="fas fa-list fa-3x"></i></a>
						</div>
					</div>

				</div> -->
			</div>
		</div>
		<?php
		$stmt= $DB_con->prepare('SELECT id, pname, pmodel, price, pimage FROM tbl_users ORDER BY id DESC');
		$stmt->execute();
		if ($stmt->rowCount() > 0) 
		{
			while ($row=$stmt->fetch(PDO::FETCH_ASSOC) )
			{
				extract($row);

				?>

				<div class="col-3 mb-3 float-left border border-primary">
					<p class="bg-info p-2 m-2 text-center text-white"><?php echo "Product Name: ".$pname; ?></p>
					<img style="display: block; margin-left: auto; margin-right: auto; width: 200px; height: 214px;" class="img-thumbnail" src="user_images/<?php echo $row['pimage']; ?>"/>
					<p class="text-center m-2 p-2"><?php echo "Model: ".$pmodel; ?></p>
					<p class="bg-primary text-center"><?php echo "Price($): ".$price ; ?></p>
					<div class="text-center">
						<a class="btn btn-info m-2" href="editform.php?edit_id=<?php echo $row['id']; ?>" title="Click for edit" onclick="return confirm('Sure to edit?')">Edit</a>
						<a class="btn btn-danger m-2" href="?delete_id=<?php echo $row['id']; ?>" title="click for delete" onclick="return confirm('sure to delete ?')">Delete</a><br>
						<a class="btn btn-warning mb-3" href="#">Oder Now</a>
					</div>
				</div>
				
				<?php
			}
		}
		else
		{
			?>
			<div>
				<p class="p-3">No data found</p>
			</div>
			<?php
		}
		?>
	</div>
</body>
</html>