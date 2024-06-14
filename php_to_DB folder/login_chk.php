<?php
    $id   = $_POST["id"];
    $pass = $_POST["pass"];

    $conn = mysqli_connect("codingmaker.net:33060", "khs0624", "0624", "khs0624");
    $sql = "select user_id, user_pw from movie_user where binary user_id='$id'";
    $qry = mysqli_query($conn, $sql);

    $num_match = mysqli_num_rows($qry);

    if(!$num_match) 
    {
     echo("
           <script>
             window.alert('아이디 또는 비밀번호가 틀립니다!')
             history.go(-1)
           </script>
         ");
    }
    else
    {
        $row = mysqli_fetch_array($qry);
        $db_pass = $row["user_pw"];

        mysqli_close($conn);

        if($pass != $db_pass)
        {

           echo("
              <script>
                window.alert('아이디 또는 비밀번호가 틀립니다!')
                history.go(-1)
              </script>
           ");
           exit;
        }
        else
        {
            session_start();
            $_SESSION["userid"] = $row["user_id"];

            echo("
              <script>
                window.alert('$id 님 어서오세요!')
                history.go(-2)
              </script>
            ");
        }
     }     
?>
