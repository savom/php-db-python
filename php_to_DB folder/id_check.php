<?php
	$userid = $_POST['userid'];

	$conn = mysqli_connect("codingmaker.net:33060", "khs0624", "0624", "khs0624");
    $sql = "select user_id from movie_user where user_id='$userid'";
    $qry = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($qry);

    if ($row) {
    	$data['exists'] = true;
    } else {
    	$data['exists'] = false;
    }

    echo json_encode($data);
?>