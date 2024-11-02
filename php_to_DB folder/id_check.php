<?php
	$userid = $_POST['userid'];

	$conn = mysqli_connect(your_host.port, ID, PW, folder);
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
