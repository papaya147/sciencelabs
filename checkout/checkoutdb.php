<?php require_once '../db.php'; ?>
<?php
global $conn;

$sql = 'select * from item where id=' . $_POST['itemid'];
$uniqueitem = $conn->query($sql)->num_rows;
if ($uniqueitem != 1) {
	echo '<script>alert("Select one single item.<' . $itemid . '>");document.location.href="index.php";</script>';
}
$sql = 'select * from student where id=' . $_POST['rollno'];
$uniqueitem = $conn->query($sql)->num_rows;
if ($uniqueitem != 1) {
	echo '<script>alert("Select one single person.");document.location.href="index.php";</script>';
}

$sql = 'select * from item where id=' . $_POST['itemid'];
$uniqueitem = $conn->query($sql)->fetch_assoc()['quantity'];
if ($uniqueitem < $_POST['quantity']) {
	echo '<script>alert("Not enough stock.");document.location.href="index.php";</script>';
}

$sql = 'insert into student_checkout (student_id, item_id, quantity, returned) values (' .
	$_POST['rollno'] . ', ' .
	$_POST['itemid'] . ', ' .
	$_POST['quantity'] . ', ' .
	'0);';

$conn->query($sql);

$sql = 'select * from student where id=' . $_POST['rollno'];
$student = $conn->query($sql)->fetch_assoc()['student_name'];
$item = $_POST['itemname'];
echo '<script>
		alert("Recorded '. $_POST['quantity'] .' '. $item .' checked out by '. $student .'");
		document.location.href = "index.php";
	</script>';
?>