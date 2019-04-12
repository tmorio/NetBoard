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

$query = "SELECT * FROM Posts WHERE ID = :devid AND UserID = :usernum";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':devid', $_GET['id'], PDO::PARAM_STR);
$stmt->bindParam(':usernum', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch();

?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>NBoard - New</title>
		<link rel="stylesheet" type="text/css" href="css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css?Ver=2">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<script type="text/javascript" src="js/footerFixed.js"></script>
		<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
		<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
	</head>
	<body>
	<?php require_once('./header.php'); ?>
	<div class="deviceListBoard">
		<a class="waves-effect waves-light btn red" href="./dashboard.php"><i class="material-icons left">keyboard_arrow_left</i>作成取り消し</a>
		<span class="listTitle">新規書き込み</span>
	<div class="graphArea">
		<form action="add.php" method="POST">
			<h5>タイトル (最大30文字)</h5>
				<div class="input-field col s12">
                                	<input type="text" id="titleIn" name="titleIn" data-length="30" maxlength="30" placeholder="タイトル" class="validate" required>
				</div>
			<h5>連絡種別</h5>
				<div class="input-field col s12">
					<select id="infoType" name="infoType">
						<option value="1" selected>打ち合わせ</option>
						<option value="2">出席関係</option>
						<option value="3">勉強会</option>
						<option value="4">その他</option>
					</select>
				</div>
			<h5>表示期間</h5>
				<div style="display:inline-flex">
					<input type="text" id="exDate" name="exDate" class="datepicker" placeholder="月/日" required>
					&nbsp;
					<input type="text" id="exTime" name="exTime" class="timepicker" placeholder="時間" required>
				</div>&nbsp;まで<br>
			<h5>メッセージ (最大140文字)</h5>
				<div class="input-field col s12">
					<textarea id="comment" name="comment" class="materialize-textarea" placeholder="内容 (コメント)" data-length="140" required></textarea>
				</div>
                                <button class="btn waves-effect waves-light btn blue" type="submit"><i class="material-icons right">check</i>書き込む</button>
                </form>

	</div>
	</div>
		<script>
			$('.dropdown-trigger').dropdown();
			$(document).ready(function() {
				$('input#titleIn, textarea#comment').characterCounter();
			});
			$(document).ready(function(){
				$('select').formSelect();
			});
			$(document).ready(function(){
				$('.datepicker').datepicker({format: 'yyyy-mm-dd'});
			});
			$(document).ready(function(){
				$('.timepicker').timepicker({twelveHour: false, format: 'HH:ii:SS', vibrate: true});
				$('.timepicker').on('change', function() {
					let receivedVal = $(this).val();
					$(this).val(receivedVal + ":00");
				});
			});
		</script>
	</body>
</html>

