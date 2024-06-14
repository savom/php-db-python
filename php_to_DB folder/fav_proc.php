<?php
	session_start();
    if (isset($_SESSION["userid"])) {
        $userid = $_SESSION["userid"];
    } else {
        $userid = "";
    }

    $movie_num = $_POST['movieNum'];
    $movie_title = $_POST['movieTitle'];

    $conn = mysqli_connect("codingmaker.net:33060", "khs0624", "0624", "khs0624");

    $u_num_sql = "select user_num from movie_user where user_id='$userid'";
    $u_num_qry = mysqli_query($conn, $u_num_sql);
    $u_num_row = mysqli_fetch_array($u_num_qry);
    $u_num = $u_num_row["user_num"];

    $sql = "select * from movie_favorite_test where movie_num='$movie_num' and user_num='$u_num'";
    $qry = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($qry);
    $chk = $row["chk"];

    if ($chk == 1) {
    	$del_sql = "delete from movie_favorite_test where movie_num='$movie_num' and user_num='$u_num'";
    	mysqli_query($conn, $del_sql);
    } else {
    	$ins_sql = "insert into movie_favorite_test (favorite_actor, liked_movie, chk, movie_num, user_num) values('', '$movie_title', 1, '$movie_num', '$u_num')";
    	mysqli_query($conn, $ins_sql);
    }

    mysqli_close($conn);
?>