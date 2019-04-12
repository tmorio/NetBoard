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

$query = "SELECT * FROM Posts WHERE UserID = :UserID AND ID = :Postid";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->bindParam(':Postid', $_GET['id'], PDO::PARAM_STR);
$stmt->execute();
$data = $stmt->fetch();
if(empty($data['title'])){
	echo '削除権限がありません。';
        exit(0);
}

$query = "DELETE FROM Posts WHERE UserID = :UserID AND ID = :Postid";

$stmt = $dbh->prepare($query);
$stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->bindParam(':Postid', $_GET['id'], PDO::PARAM_STR);
$stmt->execute();

header("Location: editPost.php");

?>
