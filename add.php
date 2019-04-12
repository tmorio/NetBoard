<?php
session_start();

if(empty($_SESSION['userName'])){
	header("Location: login.php");
}

if(empty($_POST['titleIn'])){
        header("Location: addPost.php");
}

require_once('./myid.php');
require_once('./siteInfo.php');

$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
try {
		$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
}

$dateTime = $_POST['exDate'] . " " . $_POST['exTime'];
$nowTime = date('Y-m-d H:i:s');
$infType = $_POST['infoType'];
$postTitle = $_POST['titleIn'];
$postContent = $_POST['comment'];

if(!empty($postTitle)){

	$query = "INSERT INTO Posts ( UserID, created, destory, infoType, title, content) VALUES (:userID, :makeTime, :delTime, :infType, :postTitle, :postContent)";

	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':userID', $_SESSION['userNo'], PDO::PARAM_INT);
	$stmt->bindParam(':makeTime', $nowTime, PDO::PARAM_STR);
	$stmt->bindParam(':delTime', $dateTime, PDO::PARAM_STR);
	$stmt->bindParam(':infType', $infType, PDO::PARAM_INT);
	$stmt->bindParam(':postTitle', $postTitle, PDO::PARAM_STR);
	$stmt->bindParam(':postContent', $postContent, PDO::PARAM_STR);
	$stmt->execute();
}
?>

<!doctype html>
<html>
        <head>
                <meta charset="UTF-8">
                <title>NBoard - Success</title>
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

		<div class="listTitle">
                	<h3>Post Success</h3>
                        書き込み成功しました。
                        <br>3秒後にダッシュボードに戻ります。<META http-equiv="Refresh" content="3;URL=dashboard.php">
                </div>
	</div>
	</body>
</html>
