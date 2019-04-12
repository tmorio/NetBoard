<?php
require_once('./myid.php');

$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
try {
		$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
}

$query = "SELECT * FROM Posts";

$stmt = $dbh->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll();

foreach($result as $data){

	$query = "SELECT * FROM Users WHERE ID = :userNum";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':userNum', $data['UserID'], PDO::PARAM_INT);
	$stmt->execute();
	$userInfo = $stmt->fetch();

	echo '<div class="itemBox">';
	if(empty($userInfo['PhotoID'])){
		echo '<img class="boxIcon circle" src="img/default.jpg">';
	}else{
		echo '<img class="boxIcon circle" src="img/users/' . $userInfo['PhotoID'] . '.jpg">';
	}

	echo '<div class="userInfo">';
	echo '<span class="userName">' . $userInfo['Name'] . '</span><br>';
	echo '<span class="postInfo">';

	switch($data['infoType']){
		case 1:
			echo '打ち合わせ';
			break;
		case 2:
			echo '出席関係';
			break;
		case 3:
			echo '勉強会';
			break;
		case 4:
			echo 'その他';
			break;
	}

	//echo '&nbsp;-&nbsp;' . $data['created'] . '</span>';
	echo '</div><div class="subTitle">' . $data['title']  . '</div><div class="postContent">';
	echo nl2br($data['content']);
	echo '</div></div>';
}
?>
