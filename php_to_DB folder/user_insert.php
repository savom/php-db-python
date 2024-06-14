<?php
    $id   = $_POST["id"];
    $pass = $_POST["pass"];
   
    $conn = mysqli_connect("codingmaker.net:33060", "khs0624", "0624", "khs0624");

	$sql = "insert into movie_user(user_id, user_pw)";
	$sql .= "values('$id', '$pass')";

	mysqli_query($conn, $sql);  // $sql 에 저장된 명령 실행
    mysqli_close($conn);     

    echo "
	      <script>
	          location.href = 'index.php';
	      </script>
	  ";
?>

   
