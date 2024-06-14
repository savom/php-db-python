<!DOCTYPE html>
<html>
<head>
  <meta charset = 'utf-8'>
  <title>회원가입</title>
  <link rel='stylesheet' type='text/css' href='./css/login.css'>
</head>

<body>
	<div id ="member_top">
		<h3>
				<a class="front" href="index.php">C
        <a class="back" href="index.php">INEMA</a>
      </a>
		</h3>
	<section>
		<div id="join_box">
    	<form name="signup" method="post" action="user_insert.php">
	    	<div class="join_input">
	    		<input type="text" name="id" id="id" placeholder="아이디">
	    		<div class="dup_chk_wrap">
	    			<a class="id_dup_chk" id="idck">아이디 중복확인</a>
	    		</div>
	    			<a id="duplicate-info"></a>
	    	</div>
	    	<div class="join_input">
	    		<input type="text" name="pass" placeholder="비밀번호">
	    	</div>
       	<div class="bottom_line"></div>
       	<div class="buttons">
      		<a class="cancel_button" style="cursor:pointer"
      			onclick="history.back(); reset_form()">취소</a>
      		<a class="confirm_button" id="f" style="cursor:pointer" onclick="check_input()">완료</a>
       	</div>
     	</form>
  	</div>
	</section> 
	<script type="text/javascript" src="./js/jquery-3.7.1.js"></script>
	<script type="text/javascript" src="./js/signup.js"></script>
</body>
</html>