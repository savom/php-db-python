<!DOCTYPE html>
<html>
<head>
	<meta charset = 'utf-8'>
	<link rel='stylesheet' type='text/css' href='./css/contents.css'>
	<title>영화정보</title>

	<?php
	    session_start();
	    if (isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
	    else $userid = "";

	    if (isset($_GET['num'])) $num = $_GET['num'];

	    $conn = mysqli_connect("codingmaker.net:33060", "khs0624", "0624", "khs0624");
        $sql = "select * from movie where movie_num=$num";
        $qry = mysqli_query($conn, $sql);

        $row = mysqli_fetch_array($qry);
        $title = $row["movie_title"];
        $actor = $row["movie_character"];
        $genre = $row["movie_genre"];
        $date = $row["movie_Date"];
        $running = $row["movie_running"];
        $age = $row["movie_age"];
        $poster = $row["movie_poster"];

        // $sql2 = "select chk from movie_favorite_test where movie_num=$num";

        $sql2 = "select f.movie_num, u.user_id, f.chk
	        from movie_favorite_test f
	        inner join movie_user u on u.user_num = f.user_num
	        where movie_num=$num and u.user_id='$userid'";

        $qry2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_array($qry2);
        // $chk = $row2["chk"];

        if (isset($row2["chk"])) {
        $chk = $row2["chk"];
	    } else {
	        $chk = "";
	    }

	    if (isset($row2["user_id"])) {
        $user_id = $row2["user_id"];
	    } else {
	        $user_id = "";
	    }

        $parsing = explode(',', $actor);
	?>

</head>

<body>
	<div class="layout">
		<div class="main_layout">
			<div class="header">
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
			</div>
			<div class="info_container">
				<div class="movie_img_area">
					<div class="backdrop">
						<?php echo '<img class="img" src="data:image;base64,'.base64_encode($row['movie_poster']).'">' ?>
					</div>
					<div class="gradient"></div>
				</div>
				<div class="contents">
					<div class="contents_header">
						<?php if ($poster != NULL) { ?>
							<div class="movie_poster">
								<?php echo '<img class="poster" src="data:image;base64,'.base64_encode($row['movie_poster']).'">' ?>
							</div>
						<?php } else { ?>
							<div class="movie_poster">
								<img class="movie_poster" src="/movie/img/thumb_ing.gif" style="filter: brightness(0.5) invert(1);">
							</div>
						<?php } ?>
						<div class="movie_title">
							<h2 class="title_kor"><?=$title?></h2>
						</div>
					</div>

					<?php
					if (!$userid) { ?>
						<a href="javascript:alert('로그인 후 이용하실 수 있습니다!')">
							<div class="my_rating">
								<div class="like_mark"><img src="/movie/img/heart-regular.svg"></div>
								<div class="like_wrap">
									<button class="like_non">좋아요</button>
								</div>
							</div>
						</a>
					<?php } else { ?>
					<?php if ($chk != 1) { ?>
						<div class="my_rating">
								<div class="like_wrap">
									<div class="like_mark"><img src="/movie/img/heart-regular.svg"></div>
									<button class="like">좋아요
										<input type="hidden" name="movieNum" value="<?=$num?>">
										<input type="hidden" name="movieTitle" value="<?=$title?>">
									</button>
								</div>
							</div>
					<?php } else { ?>
						<div class="my_rating">
								<div class="like_wrap">
									<div class="like_mark_chk"><img src="/movie/img/heart-solid.svg"></div>
									<button class="like">좋아요
										<input type="hidden" name="movieNum" value="<?=$num?>">
										<input type="hidden" name="movieTitle" value="<?=$title?>">
									</button>
								</div>
							</div>
					<?php } ?>
					<?php } ?>
				</div>
				<section>
					<div class="tab_area">
						<div class="tab_area_btn">
							<button class="tab_btn">
								<span class="btn_title">작품정보</span>
							</button>
							<button class="tab_btn">
								<span class="btn_title">리뷰</span>
							</button>
						</div>
					</div>
					<div class="btn_title_section">
						<section class="contents_info">
							<div class="synopsis">
								<div class="synopsis_wrap">
									<div class="synopsis_text">
										<span class="font">
										</span>
									</div>
								</div>
							</div>
							<ul class="metadata">
								<li class="items">
									<span class="item_title">장르</span>
									<span class="item_body"><?=$genre?></span>
								</li>
								<li class="items">
									<span class="item_title">방영일</span>
									<span class="item_body"><?=$date?></span>
								</li>
								<li class="items">
									<span class="item_title">연령등급</span>
									<span class="item_body"><?=$age?></span>
								</li>
								<li class="items">
									<span class="item_title">러닝타임</span>
									<span class="item_body"><?=$running?>분</span>
								</li>
							</ul>
						</section>
						<section class="contents_info">
							<div class="person">
								<div class="person_title">
									<a>
										<h2>출연진</h2>
									</a>
								</div>
								<div class="person_actor">
									<?php for ($i = 1; $i < count($parsing); $i++) { ?>
									<div class="actor_wrap">
											<div class="actor_list">
												<div class="photo">
													<div class="profile"></div>
												</div>
												<div class="actor_name">
													<div class="name"><?=$parsing[$i]?></div>
												</div>
											</div>
										</div>
									<?php } mysqli_close($conn); ?>
								</div>
								<div class="solid_line"></div>
								<div class="staff_wrap">
									<div class="staff">
										<span class="staff_title">감독</span>
										<div class="staff_body">
											<div class="staff_body_wrap">
												<h1 class="staff_name"><?=$parsing[0]?></h1>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>
				</section>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="./js/jquery-3.7.1.js"></script>
	<script type="text/javascript" src="./js/header.js"></script>
	<script type="text/javascript"> var num = <?= json_encode($num) ?>; </script>
	<script type="text/javascript" src="./js/contents.js"></script>
	<script type="text/javascript" src="./js/search.js"></script>
</body>
</html>