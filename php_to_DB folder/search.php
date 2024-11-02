<?php
    header('Content-Type: application/json');

    $conn = mysqli_connect(your_host.port, ID, PW, folder);

    // 사용자가 입력한 검색어
    $search_keyword = trim($_GET['keyword']); // 양쪽 공백 제거
    $search_keyword = str_replace(' ', '', $search_keyword); // 검색어에서 공백 제거

    // 검색어가 공백이 아닌 경우에만 쿼리 실행
    if (!empty($search_keyword)) {
        // 쿼리문 실행
        $sql = "select movie_title, movie_num from movie where movie_title like '%$search_keyword%'";
        $qry = mysqli_query($conn, $sql);

        // 결과를 배열로 저장
        $results = array();
        while ($row = mysqli_fetch_assoc($qry)) {
            $results[] = $row;
        }

        // 결과 출력
        echo json_encode($results);
    } else {
        // 검색어가 공백인 경우, 빈 배열 반환
        echo json_encode([]);
    }
?>
