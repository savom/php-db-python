<?php
  session_start();
  unset($_SESSION["userid"]);
  
  echo("
        <script>
            window.alert('로그아웃 되었습니다.')
            location.href = 'index.php';
        </script>
        ");
?>
