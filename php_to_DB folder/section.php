<div class ="tab_wrap">
    <ul class="tab_title">
        <li>
            <a style="cursor: default; user-select: none;">박스오피스 순위</a>
        </li>
    </ul>
    <div class="tab_list">
        <div class="slider">
            <div class="inner">
                <ul class="swiper-wrapper slide_list">
                    <?php
                    $conn = mysqli_connect("codingmaker.net:33060", "khs0624", "0624", "khs0624");
                    $sql = "select movie_num, movie_title, movie_poster, ranking from movie where ranking ORDER BY ranking asc";
                    $qry = mysqli_query($conn, $sql);

                    $start = 0;
                    $end = 10;

                    for ($i = $start; $i < $end; $i++)
                    {
                        $row = mysqli_fetch_array($qry);
                        $rank = $row["ranking"];
                        $num = $row["movie_num"];
                        $title = $row["movie_title"];
                        $poster = $row["movie_poster"];
                        $rank = $row["ranking"];

                        $sql2 = "select AVG(movie_score) AS avg_score from movie_review_test where movie_num=$num";
                        $qry2 = mysqli_query($conn, $sql2);
                        $row2 = mysqli_fetch_array($qry2);
                        $score = $row2["avg_score"];
                        $score = number_format($score, 1);

                        if ($poster == NULL) {
                    ?>
                    <li class="swiper-slide">
                        <div class="movie-poster">
                            <div class="rank"><?=$rank?></div>
                            <a href="contents.php?num=<?=$num?>"><img class="movie-poster"  src="/movie/img/thumb_ing.gif" style="filter: brightness(0.5) invert(1);">
                            </a>
                        </div>
                        <?php } 
                        else {
                        ?>
                        <li class="swiper-slide">
                            <div class="movie-poster">
                                <div class="rank"><?=$rank?></div>
                                <a href="contents.php?num=<?=$num?>">
                                    <?php echo '<img class="movie-poster" src="data:image;base64,'.base64_encode($row['movie_poster']).'">'
                                    ?>
                                </a>
                            </div>

                        <?php } ?>
                        <div class="title_wrap">
                            <div class="movie-title"><?=$title?></div>
                            <?php if ($score != 0) { ?>
                                <div class="score"><?=$score?>점</div>
                            <?php } else { ?>
                                <div class="score">평가없음</div>
                            <?php } ?>
                        </div>
                    </li>
                    <?php } 
                        mysqli_close($conn);
                    ?>

                </ul>
                <span class="btn btn_next">다음</span>
                <span class="btn btn_prev">이전</span>
            </div> 
        </div>
    </div>
</div>

<?php 
    $conn = mysqli_connect("codingmaker.net:33060", "khs0624", "0624", "khs0624");
    $sql = "select f.liked_movie, m.movie_poster, f.movie_num, u.user_id
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

    if ($userid == $user_id) { ?>
        <div class ="tab_wrap">
            <ul class="tab_title">
                <li>
                    <a style="cursor: default; user-select: none;">좋아요 한 영화</a>
                </li>
            </ul>
            <div class="tab_list">
                <div class="slider">
                    <div class="inner">
                        <ul class="swiper-wrapper slide_list">

                            <?php 
                                $count = mysqli_num_rows($qry);
                                $variable_end = min(10, $count);
                                mysqli_data_seek($qry, $start);

                                for ($i = $start; $i < $variable_end; $i++) {
                                    $row = mysqli_fetch_array($qry);
                                    $num = $row["movie_num"];
                                    $title = $row["liked_movie"];
                                    $poster = $row["movie_poster"];
                             ?>
                                <li class="swiper-slide">
                                    <?php if ($poster != NULL) { ?>
                                        <div class="movie-poster">
                                            <a href="contents.php?num=<?=$num?>">
                                                <?php echo '<img class="movie-poster" src="data:image;base64,'.base64_encode($row['movie_poster']).'">'
                                                ?>
                                            </a>
                                        </div>
                                    <?php } else { ?>
                                        <div class="movie-poster">
                                            <a href="contents.php?num=<?=$num?>"><img class="movie-poster"  src="/movie/img/thumb_ing.gif" style="filter: brightness(0.5) invert(1);">
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <div class="title_wrap">
                                        <div class="movie-title"><?=$title?></div>
                                    </div>
                                </li>
                            <?php } ?>

                        </ul>
                        <?php if ($count > 5) { ?>
                            <span class="btn btn_next">다음</span>
                            <span class="btn btn_prev">이전</span>
                        <?php } else { ?>
                            
                        <?php } ?>
                    </div> 
                </div>
            </div>
        </div>
<?php } ?>