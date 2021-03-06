<?php
require_once '../db.php';
require_once '../checksession.php';

$times = array("09:35 - 10:55", "11:10 - 12:30", "01:30 - 02:50", "03:05 - 04:25");
$tbookedby = array("-", "-", "-", "-");
$cbookedby = array("-", "-", "-", "-");
$date = $_GET['date'];

$sql = "select date_format(booked_date, '%Y %m %d') as booked_date, booked_time, teacher_id, class_id from lab_booking where lab='" . $_SESSION['lab'] . "' and date_format(booked_date, '%Y %m %d')='" . $date . "';";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (strcmp($row["booked_date"], $date) == 0) {
            $dbtime = $row["booked_time"];
            $dbteacher = $row["teacher_id"];
            $dbclass = $row["class_id"];

            $var = 0;
            while ($var < 4) {
                if (strcmp($times[$var], $dbtime) == 0) {
                    $sql = "select * from teacher where id=" . $dbteacher . ";";
                    $res = $conn->query($sql);
                    while ($r = $res->fetch_assoc()) {
                        $tbookedby[$var] = $r['teacher_name'];
                    }

                    $sql = "select * from class where id=" . $dbclass . ";";
                    $res = $conn->query($sql);
                    while ($r = $res->fetch_assoc()) {
                        $cbookedby[$var] = $r['class_name'];
                    }
                }
                $var += 1;
            }
        }
    }
}

$var = 0;
while ($var < 4) {
    echo '<tr>
        <td>' . $times[$var] . '
        <td>' . $tbookedby[$var] . '
        <td>' . $cbookedby[$var] . '
        <td>';

    $sql = "select id from teacher where teacher_name='" . $tbookedby[$var] . "';";
    $id = $conn->query($sql)->fetch_assoc()['id'];

    if ($tbookedby[$var] != "-" && $id == $_SESSION['user']) {
        echo '<button class="btn btn-sm btn-success" onclick="bookitem(' . $var . ')">Book Items</button>';
    } else {
        echo "-";
    }
    $var += 1;
}
