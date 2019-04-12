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

if(($_GET['do'] == 1) && !empty($result)){
	$dateTime = $_POST['exDate'] . " " . $_POST['exTime'];
	$infType = $_POST['infoType'];
	$postTitle = $_POST['titleIn'];
	$postContent = $_POST['comment'];

	$query = "UPDATE Posts SET destory = :exTime, infoType = :infType, title = :title, content = :content WHERE ID = :postID";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':exTime', $dateTime, PDO::PARAM_STR);
	$stmt->bindParam(':infType', $infType, PDO::PARAM_STR);
	$stmt->bindParam(':title', $postTitle, PDO::PARAM_STR);
	$stmt->bindParam(':content', $postContent, PDO::PARAM_INT);
	$stmt->bindParam(':postID', $_GET['id'], PDO::PARAM_INT);
	$stmt->execute();
	header("Location: editPost.php");
	exit(0);
}

?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>NBoard - Edit</title>
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
	<?php

		if(!empty($result)){
			echo '<a class="waves-effect waves-light btn red" href="./editPost.php"><i class="material-icons left">keyboard_arrow_left</i>編集取り消し</a>';
			echo '<span class="listTitle">編集&nbsp;:&nbsp;' . htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8') . '</span>';
		}else{
			echo '<div class="listTitle">';
			echo '<h3>E002: Permission Error</h3>';
                        echo 'この項目を編集する権限がありません。';
                        echo '<br>5秒後にダッシュボードに戻ります。<META http-equiv="Refresh" content="5;URL=dashboard.php">';
			echo '</div></div>';
			echo '<footer id="footer" class="footer center">';
       			echo FOOTER_INFO;
                	echo '</footer>';
			exit(0);
		}

	list($exDate, $exTime) = preg_split('/[ ]/', $result['destory']);
	?>
	<div class="graphArea">
		<form action="edit.php?id=<?php echo $_GET['id']; ?>&do=1" method="POST">
			<h5>タイトル (最大30文字)</h5>
				<div class="input-field col s12">
                                	<input type="text" id="titleIn" name="titleIn" data-length="30" maxlength="30" class="validate" value="<?php echo htmlspecialchars($result['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
				</div>
			<h5>連絡種別</h5>
				<div class="input-field col s12">
					<select id="indoType" name="infoType">
						<option value="1" <?php if($result['infoType'] == 1){ echo "selected";} ?>>打ち合わせ</option>
						<option value="2" <?php if($result['infoType'] == 2){ echo "selected";} ?>>出席関係</option>
						<option value="3" <?php if($result['infoType'] == 3){ echo "selected";} ?>>勉強会</option>
						<option value="4" <?php if($result['infoType'] == 4){ echo "selected";} ?>>その他</option>
					</select>
				</div>
			<h5>表示期間</h5>
				<div style="display:inline-flex">
					<input type="text" id="exDate" name="exDate" class="datepicker" placeholder="月/日" value="<?php echo $exDate; ?>">
					&nbsp;
					<input type="text" id="exTime" name="exTime" class="timepicker" placeholder="時間" value="<?php echo $exTime; ?>">
				</div>&nbsp;まで<br>
			<h5>メッセージ (最大140文字)</h5>
				<div class="input-field col s12">
					<textarea id="comment" name="comment" class="materialize-textarea" data-length="140"><?php echo htmlspecialchars($result['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
				</div>
                                <button class="btn waves-effect waves-light btn blue" type="submit"><i class="material-icons right">check</i>保存して適用する</button>
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

