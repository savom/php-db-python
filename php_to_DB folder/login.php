<!DOCTYPE html>
<html>
<head>
    <meta charset = 'utf-8'>
    <title>마이페이지</title>
    <link rel='stylesheet' type='text/css' href='./css/login.css'>
</head>

<body>
    <section>
        <div id="login_box">
            <div id="login_form">
                <div id="login_top">
                    <div class="prev" onclick="history.back()"></div>
                    <h3>

                        <a class="front" href="index.php">C
                            <a class="back" href="index.php">INEMA</a>
                        </a>
                    </h3>
                    <form class="login_form" name="login" method="post" action="login_chk.php">
                        <ul class="input_wrap">
                            <li class="input">
                                <input type="text" name="id" placeholder="아이디" style="cursor: pointer; font-size: 16px; padding-left: 20px;">
                            </li>
                            <li class="input">
                                <input type="password" id="pass" name="pass" placeholder="비밀번호" style="cursor: pointer; font-size: 16px;padding-left: 20px;">
                            </li>
                        </ul>
                        <td>
                            <div id="check_login">
                                <input style="zoom: 0.4;" type="checkbox" value="yes" name="check">
                                <label for="label" style="font-size: 14px;">로그인 유지
                                </label>
                            </div>
                            <div id="login_btn" style="cursor: pointer;" onclick="check_input()">
                                <a>로그인</a>
                            </div>
                        </td>
                        <div class="line">또는
                        </div>
                        <ul id="login_menu_sign">
                            <li>
                                <a href="signup.php">회원가입</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="./js/login.js"></script>
</body>
</html>