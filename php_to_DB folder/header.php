<?php
    session_start();
    if (isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
    else $userid = "";
?>

<div class="top">
	<ul class="top_main">
		<li class="top_logo">
			<a class="front" href="index.php">C
				<a class="back" href="index.php">INEMA</a>
			</a>
		</li>
		<li class="top_movie">
			<a href="moviePage.php">영화</a>
		</li>

		<li class="top_search">
			<div class="search_container">
				<div class="search_wrap">
					<div class="search">
						<input class="search_text" id="search_input" placeholder="검색 키워드를 적어주세요." type="text">
						<span id="clear" class="clear_text">&times;</span>
					</div>
				</div>
				<div id="search_res" class="search_result"></div>
			</div>
		</li>
		<?php if (!$userid) { ?>
			<li class="top_login">
				<a href="login.php">마이페이지</a>
			</li>
		<?php } else { ?>
			<li class="top_login">
	        	<a class="my_page" href="myPage.php">마이페이지</a>
	        	<a href="logout.php">로그아웃</a>
	        </li>
	    <?php } ?>
	</ul>
</div>

<script type="text/javascript" src="./js/jquery-3.7.1.js"></script>
<script type="text/javascript" src="./js/search.js"></script>