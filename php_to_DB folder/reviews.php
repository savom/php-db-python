<?php
	session_start();
    if (isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
    else $userid = "";

    if (isset($_GET['num'])) $num = $_GET['num'];

    $conn = mysqli_connect(your_host.port, ID, PW, folder);
    $sql = "select U.user_id, R.comments, R.movie_score, M.movie_num, R.num
    	from movie_review_test R
    	inner join movie M on R.movie_num = M.movie_num
    	inner join movie_user U on R.user_num = U.user_num
    	where M.movie_num=$num order by R.num";

    $qry = mysqli_query($conn, $sql);
?>

<section class="contents_info">
	<?php if ($userid) { ?>
	<form action="reviews_proc.php?num=<?=$num?>" method="post" name="proc">
	    <div class="reviews">
	        <div class="person_title">
	            <h2>리뷰 작성하기</h2>
	        </div>
	        <div class="write_area">
				<div class="write_review">
					<textarea class="edit" name="review_comment" placeholder="작품이 마음에 드셨으면 리뷰를 작성해주세요!"></textarea>
				</div>
			</div>
			<div class="rating_wrap">
				<div class="person_title">
					<h2>이 영화의 점수는 몇 점인가요?</h2>
				</div>
				
				<div class="rating_btn_wrap">
					<?php for ($i = 1; $i < 6; $i++) { ?>
					<div class="rating_btn">
						<button class="rating" data-rating="<?=$i?>"><?=$i?></button>
					</div>
					<?php } ?>
				</div>
			<div class="reg_wrap">
				<a onclick="review_chk()" class="reg">등록</a>
			</div>
	    </div>
	    <input type="hidden" name="movie_score" value="">
	</form>
	<?php } else { ?>
		<div class="reviews">
	        <div class="person_title">
	            <h2>리뷰 작성하기</h2>
	        </div>
	        <div class="write_area">
				<div class="write_review">
					<textarea class="edit" name="review_comment" placeholder="로그인 후 리뷰를 작성해보세요!"></textarea>
				</div>
			</div>
	<?php } ?>
	<div class="written_reviews">
		<div class="person_title">
			<h2>작성된 리뷰</h2>
		</div>
		<div class="written_review_wrap">
			<?php if (mysqli_num_rows($qry) != 0) {
			    while ($row = mysqli_fetch_array($qry)) { 
			    	$review_num = $row['num'];
				    $comment = $row['comments'];
				    $id = $row['user_id'];
				    $rate = $row['movie_score'];
			?>
			<div class="comment_box">
				<div class="comment_wrap">
					<div class="user">
						<a><?=$id?></a>
					</div>
					<?php if ($rate != NULL) { ?>
					<div class="movie_score_wrap">
						<div class="movie_score">
							<a><?=$rate?>점</a>
						</div>
					</div>
					<?php } else { ?>
						<div class="movie_score_wrap">
							<div class="movie_score">
								<a>점수없음</a>
							</div>
						</div>
					<?php } ?>
					<div class="user_comment">
						<a><?=$comment?></a>
					</div>
					<?php if ($userid == $id) { ?>
						<div class="update_btn">
							<div class="delete_review">
								<button>삭제
									<input type="hidden" name="delete_review_num" value="<?=$review_num?>">
									<input type="hidden" name="delete_submit" value="">
								</button>
							</div>
							<div class="update_review">
    							<button class="review_edit">수정
    								<input type="hidden" name="update_num" value="<?=$review_num?>">
    							</button>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			<?php } else { ?>
				<div class="none">
		            <div class="comment_wrap">
		                <div class="none_comment">
		                    <a>작성된 리뷰가 없어요</a>
		                </div>
		            </div>
		        </div>
			<?php } mysqli_close($conn); ?>
		</div>
	</div>
</section>
<div class="modal">
	<div class="modal_popup">
		<h2>리뷰 수정</h2>
		<span class="close">&times;</span>
	    <div class="edit_content">
	        <textarea class="update_edit" name="update_review_comment"></textarea>
	    </div>
	    <div class="person_title">
			<h3>영화 점수의 수정을 원하시면 다시 선택해주세요.</h3>
		</div>
	    <div class="rating_btn_wrap">
			<?php for ($i = 1; $i < 6; $i++) { ?>
			<div class="rating_btn">
				<button class="rating" data-rating="<?=$i?>"><?=$i?></button>
			</div>
			<?php } ?>
		</div>
		<button style="cursor: pointer;" class="submit">완료
			<input type="hidden" name="update_submit" value="">
			<input type="hidden" name="update_review_num" value="">
		</button>
	</div>
</div>

<script type="text/javascript" src="./js/reviews.js?after"></script>
