<?php
session_start();

if(empty($_SESSION['userName'])){
	header("Location: login.php");
}

require_once('./myid.php');
require_once('./siteInfo.php');

$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
try {
		$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
}

$query = "SELECT * FROM Users WHERE ID = :UserID AND Name = :Username";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_STR);
$stmt->bindParam(':Username', $_SESSION['userName'], PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch();

?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>NBoard - Settings</title>
		<link rel="stylesheet" type="text/css" href="css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css?">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<script type="text/javascript" src="js/footerFixed.js"></script>
		<!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
	</head>
	<body>

	<?php require_once('./header.php'); ?>
	<!-- 表示画面 （Google Mapみたいに2画面分割で左にリスト、右にマップ?)-->
	<div class="settingBoard">
		<!-- 設定分類一覧表示 -->
		<div class="collection with-header settingList">
			<div class="collection-header center-align"><a class="waves-effect waves-light btn" href="./dashboard.php">
				<i class="material-icons left">keyboard_arrow_left</i>ホームに戻る</a></div>
			<div class="collection-header"><h5>サービス設定</h5></div>
			<a href="?page=account" class="collection-item blue-grey-text text-darken-4"><i class="material-icons left">account_circle</i>アカウント設定</a>
			<a href="?page=credit" class="collection-item blue-grey-text text-darken-4"><i class="material-icons left">copyright</i>クレジット</a>
		</div>
		<!-- 設定表示 -->
		<div class="settingInfo">
<?php
switch ($_GET['mes']) {
case 1:
	echo 'パスワードが違います。';
	break;

case 2:
	echo '設定を更新しました。';
	break;
}

switch ($_GET['page']) {
default:
	echo '
						<h3>アカウント設定</h3><br />
                				<form action="doSetting.php?Setup=account" method="POST">
                        				NBoard ID (ログインID)<br />
                        	        		<input type="text" name="newUserID" id="newUserID" pattern="^[0-9A-Za-z]+$" value="' . htmlspecialchars($result['UserID'], ENT_QUOTES, 'UTF-8') . '" required>
                                			名前<br />
                                			<input type="text" name="newUsername" id="newUsername"  value="' . htmlspecialchars($result['Name'], ENT_QUOTES, 'UTF-8') . '" required>
                                                        新しいパスワード (変更する場合は入力して下さい)<br />
                                                        <input type="password" name="newPassword" id="newPassword">
                                			<br /><br /><br />
                                                        現在のパスワード (必須)<br />
                                                        <input type="password" name="nowPassword" id="nowPassword" required><br />
                                			<button class="btn waves-effect waves-light" type="submit"><i class="material-icons right">check</i>変更を適用する</button>
						</form>
					';
	break;

case credit:
	require_once ('./credit.php');
	break;
}

?>

		</div>
	</div>

			<script>

				$(document).ready(function(){
					$('.modal').modal();
				});
                        	function deleteUserGroup(userID){
                                	var target = document.getElementById("delUserFG");
                                	target.href = "doSetting.php?Setup=delFG&id=" + userID;
                        	}
                                function changeUserPermission(userID, uType){
                                        var target = document.getElementById("chUserP");
                                        target.href = "doSetting.php?Setup=permission&id=" + userID + "&type=" + uType;
                                }
                                function deleteInvite(userID){
                                        var target = document.getElementById("delInvite");
                                        target.href = "doSetting.php?Setup=delInvite&id=" + userID;
                                }

			</script>
		</footer>
	</body>
</html>

