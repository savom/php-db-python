<!DOCTYPE html>
<html>
<head>
	<meta charset = 'utf-8'>
	<title>검색결과</title>
	<link rel='stylesheet' type='text/css' href='./css/search_result.css'>
	<?php
	    session_start();
	    if (isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
	    else $userid = "";

	    $search_movie_num = $_GET['movie_num'];
	?>
</head>

<body>
	<header>
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
	</header>
	<?php
		$conn = mysqli_connect(your_host.port, ID, PW, folder);
        $sql = "select movie_num, movie_title, movie_poster from movie where movie_num=$search_movie_num";
        $qry = mysqli_query($conn, $sql);

        $row = mysqli_fetch_array($qry);
        $num = $row["movie_num"];
        $title = $row["movie_title"];
        $poster = $row["movie_poster"];

        $sql2 = "select AVG(movie_score) AS avg_score from movie_review_test where movie_num=$num";
        $qry2 = mysqli_query($conn, $sql2);

        $row2 = mysqli_fetch_array($qry2);
        $score = $row2["avg_score"];
        $score = number_format($score, 1);
	?>
	<section>
		<div class="section_wrap">
			<div class="title">
				<a>검색 결과</a>
			</div>
			<div class="movie_grid">
				<div class="movie_wrap">
					<?php if ($poster != NULL) { ?>
						<div class="movie_poster">
							<a href="contents.php?num=<?=$num?>">
								<?php echo '<img class="movie_poster" src="data:image;base64,'.base64_encode($row['movie_poster']).'">' ?>
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
                        <?php if ($score == 0) { ?>
	                        <div class="score">평가없음</div>
	                    <?php } else { ?>
	                    	<div class="score"><?=$score?>점</div>
	                    <?php } ?>
                    </div>
				</div>
			</div>
		</div>
	</section>
	<footer>
		<?php include "footer.php";?>
	</footer>
	<script type="text/javascript" src="./js/jquery-3.7.1.js"></script>
	<script type="text/javascript" src="./js/search.js"></script>
</body>
</html>
