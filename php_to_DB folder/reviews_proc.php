<?php
    session_start();
    if (isset($_SESSION["userid"])) {
        $userid = $_SESSION["userid"];
    } else {
        $userid = "";
    }

    $movie_num = $_GET['num'];
    $comment = $_POST['review_comment'];
    $score = $_POST['movie_score'];
    if (isset($_POST["update_review_num"])) { $review_num = $_POST['update_review_num'];
    } else {
        $review_num = "";
    }
    if (isset($_POST["update_submit"])) { $submit = $_POST['update_submit'];
    } else {
        $submit = "";
    }
    if (isset($_POST["update_review_comment"])) { $update_comment = $_POST['update_review_comment'];
    } else {
        $update_comment = "";
    }

    if (isset($_POST["delete_review_num"])) { $del_review_num = $_POST['delete_review_num'];
    } else {
        $del_review_num = "";
    }

    if (isset($_POST["delete_submit"])) { $del_submit = $_POST['delete_submit'];
    } else {
        $del_submit = "";
    }

    $conn = mysqli_connect("codingmaker.net:33060", "khs0624", "0624", "khs0624");

    $u_num_sql = "select user_num from movie_user where user_id='$userid'";
    $u_num_qry = mysqli_query($conn, $u_num_sql);
    $u_num_row = mysqli_fetch_array($u_num_qry);
    $u_num = $u_num_row["user_num"];


    if ($del_submit == "true") {
        $review = "delete from movie_review_test where num=$del_review_num";
        if (mysqli_query($conn, $review)) {
            echo json_encode(["status" => "success", "message" => "review delete success."]);
        } else {
            echo json_encode(["status" => "error", "message" => "review delete failed."]);
        }
        exit();
    }

    if ($submit == "true" && $update_comment != "") {
        if ($score != "") {
            $review = "update movie_review_test set comments='$update_comment', movie_score='$score' where user_num=$u_num and num=$review_num";
        } else {
            $review = "update movie_review_test set comments='$update_comment', movie_score=NULL where user_num=$u_num and num=$review_num";
        }

        mysqli_query($conn, $review);
            
    } else if ($submit != "true") {
        if ($score != "") {
            $review = "insert into movie_review_test (comments, movie_score, movie_num, user_num) values ('$comment', '$score', '$movie_num', '$u_num')";
        } else {
            $review = "insert into movie_review_test (comments, movie_score, movie_num, user_num) values ('$comment', null, '$movie_num', '$u_num')";
        }

        mysqli_query($conn, $review);
        echo "<script>
                alert('리뷰를 작성하였습니다.');
                history.back();
                </script>";

    } else {
        echo "<script>
                alert('데이터베이스 처리 오류가 발생하였습니다.');
                history.back();
                </script>";
    }

    

    mysqli_close($conn);
?>