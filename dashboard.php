<?php
session_start();

if(empty($_SESSION['userName'])){
	header("Location: login.php");
}

require_once('./siteInfo.php');

unset($_SESSION['lat']);
unset($_SESSION['lng']);
unset($_SESSION['getStatus']);
unset($_SESSION['deviceID']);
unset($_SESSION['nickname']);

?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>NBoard - Dashboard</title>
		<!-- <meta name="viewport" content="width=device-with, initial-scale=1"> -->
		<link rel="stylesheet" type="text/css" href="css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css?">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<script type="text/javascript" src="js/footerFixed.js"></script>
		<!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
	</head>

	<body class="grey lighten-5">
		<?php require_once('./header.php'); ?>
		<!-- メニュー -->
		<div class="dashDisplay">
				<div class="container">
					<div class="dashboardTitle">NetBoard&nbsp;へようこそ</div>
					<div class="boardMenu row center-align">
						<!-- マップ表示 -->
						<div class="trashMap col s12 m4 menu-card">
							<a href="addPost.php">
								<div class="menu-content blue-grey lighten-5 hoverable center-align z-depth-1">
									<i class="material-icons center large">create</i>
									<h6>新規書き込み</h6>
								</div>
							</a>
						</div>
						<!-- ゴミ箱管理 -->
						<div class="boxAdmin col s12 m4 menu-card">
							<a href="editPost.php">
								<div class="menu-content blue-grey lighten-5 hoverable center-align z-depth-1">
									<i class="material-icons center large">clear_all</i>
									<h6>編集・削除</h6>
								</div>
							</a>
						</div>
						<!-- 設定 -->
                                                <div class="boxAdmin col s12 m4 menu-card">
                                                        <a href="settings.php">
                                                                <div class="menu-content blue-grey lighten-5 hoverable center-align z-depth-1">
                                                                        <i class="material-icons center large">settings</i>
                                                                        <h6>ユーザ設定</h6>
                                                                </div>
                                                        </a>
                                                </div>
					</div>
				</div>
		</div>
	</body>
</html>

