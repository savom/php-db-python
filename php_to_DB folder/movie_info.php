<?php
    session_start();
    if (isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
    else $userid = "";

    if (isset($_GET['num'])) $num = $_GET['num'];

    $conn = mysqli_connect(your_host.port, ID, PW, folder);
    $sql = "select movie_title, movie_character, movie_genre, movie_Date, movie_running, movie_age from movie where movie_num=$num";
    $qry = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($qry);
    $title = $row["movie_title"];
    $actor = $row["movie_character"];
    $genre = $row["movie_genre"];
    $date = $row["movie_Date"];
    $running = $row["movie_running"];
    $age = $row["movie_age"];

    $parsing = explode(',', $actor);
?>

<section class="contents_info">
    <div class="synopsis">
        <div class="synopsis_wrap">
            <div class="synopsis_text">
                <span class="font"></span>
            </div>
        </div>
    </div>
    <ul class="metadata">
        <li class="items">
            <span class="item_title">장르</span>
            <span class="item_body"><?= $genre ?></span>
        </li>
        <li class="items">
            <span class="item_title">방영일</span>
            <span class="item_body"><?= $date ?></span>
        </li>
        <li class="items">
            <span class="item_title">연령등급</span>
            <span class="item_body"><?= $age ?></span>
        </li>
        <li class="items">
            <span class="item_title">러닝타임</span>
            <span class="item_body"><?= $running ?>분</span>
        </li>
    </ul>
</section>
<section class="contents_info">
    <div class="person">
        <div class="person_title">
            <a><h2>출연진</h2></a>
        </div>
        <div class="person_actor">
            <?php for ($i = 1; $i < count($parsing); $i++) { ?>
            <div class="actor_wrap">
                <div class="actor_list">
                    <div class="photo">
                        <div class="profile">
                            
                        </div>
                    </div>
                    <div class="actor_name">
                        <div class="name"><?= $parsing[$i] ?></div>
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
                        <h1 class="staff_name"><?= $parsing[0] ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
