<?php require_once '../db.php'; ?>
<html>
<script>
	function invite(user, level) {
		if (user == "Anonymous") {
			alert("Invalid User ID/Password");
			document.location.href = "../";
		} else {
			document.location.href = "../home";
		}
	}
</script>
<?php
global $conn;

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$user = $_POST["user"];
$pass = $_POST["pass"];

$_SESSION['range_date'] = 0;

$result = $conn->query("select * from teacher where teacher_name='" . $user . "'");
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$_SESSION['user'] = $row['id'];
		$_SESSION['level'] = $row['levels'];
	}
}

$sql = "select * from teacher where teacher_name='" . $user . "';";
$result = $conn->query($sql);

echo '<script>console.log("' . $sql . '");</script>';

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$dbname = $row["teacher_name"];
		$dbpass = $row["teacher_pass"];
		$dblevel = $row["levels"];

		if (strcmp($pass, $dbpass) == 0) {
			echo '<script>invite("' . $user . '", "' . $dblevel . '");</script>';
		} else {
			echo '<script>invite("Anonymous", "none");</script>';
		}
	}
} else {
	echo '<script>invite("Anonymous", "none");</script>';
}
?>

</html>