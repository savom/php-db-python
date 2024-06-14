<!DOCTYPE html>
<html>
<head>
	<meta charset = 'utf-8'>
	<link rel='stylesheet' type='text/css' href='./css/myPage.css'>
	<title>마이페이지</title>
	<?php
	    session_start();
	    if (isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
	    else $userid = "";
	?>
</head>

<body>
	<div class="top">
		<ul class="top_main">
			<div class="prev" onclick="history.back()"></div>
			<?php if ($userid) { ?>
				<li class="top_login">
					<a href="logout.php">로그아웃</a>
				</li>
			<?php } ?>
		</ul>
	</div>
	<?php
		$conn = mysqli_connect("codingmaker.net:33060", "khs0624", "0624", "khs0624");
	    $sql = "select f.liked_movie, m.movie_poster, f.movie_num, u.user_id, f.chk
	        from movie_favorite_test f
	        inner join movie_user u on u.user_num = f.user_num
	        inner join movie m on f.movie_num = m.movie_num
	        where f.chk = 1 and u.user_id='$userid' order by favorite_num desc";
	    $qry = mysqli_query($conn, $sql);

	    $user_row = mysqli_fetch_array($qry);
	    if (isset($user_row["user_id"])) {
	        $user_id = $user_row["user_id"];
	    } else {
	        $user_id = "NULL";
	    }
	    if (isset($user_row["chk"])) {
	        $chk = $user_row["chk"];
	    } else {
	        $chk = 0;
	    }
	    
	    $sql2 = "select user_id, user_pw from movie_user where user_id='$userid'";
	    $qry2 = mysqli_query($conn, $sql2);
	    $my_info_row = mysqli_fetch_array($qry2);
	    $id = $my_info_row['user_id'];
	    $pw = $my_info_row['user_pw'];
	?>
	<section>
		<div class="section_wrap">
			<div class="title">
				<a>좋아요 한 영화</a>
			</div>
			<?php if ($chk != 0) { ?>
				<div class="movie_grid">
					<?php 
	                    $count = mysqli_num_rows($qry);
	                    $start = 0;
	                    mysqli_data_seek($qry, $start);

	                    for ($i = $start; $i < $count; $i++) {
	                        $row = mysqli_fetch_array($qry);
	                        $num = $row["movie_num"];
	                        $title = $row["liked_movie"];
	                        $poster = $row["movie_poster"];
	                 ?>
					<div class="movie_wrap">
						<?php if ($poster != NULL) { ?>
							<div class="movie_poster">
								<a href="contents.php?num=<?=$num?>">
									<?php echo '<img class="movie_poster" src="data:image;base64,'.base64_encode($row['movie_poster']).'">'
		                            ?>
	                        	</a>
							</div>
						<?php } else { ?>
							<div class="movie_poster">
								<a href="contents.php?num=<?=$num?>"><img class="movie_poster"  src="/movie/img/thumb_ing.gif" style="filter: brightness(0.5) invert(1);">
	                            </a>
	                        </div>
						<?php } ?>
						<div class="title_wrap">
	                        <div class="movie_title"><?=$title?></div>
	                    </div>
					</div>
					<?php } ?>
				</div>
			<?php } else { ?>
				<h3>좋아요한 영화가 없습니다.</h3>
			<?php } ?>
		</div>
	</section>
	<section>
		<div class="section_wrap">
			<div class="title">
				<a>내정보</a>
			</div>
			<h2>아이디 : <?=$id?></h2>
			<h2>비밀번호 : <?=$pw?></h2>
		</div>
	</section>
</body>
</html>

