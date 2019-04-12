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

if(!empty($_POST['searchKey'])){
	$query = "SELECT * FROM Posts WHERE UserID = :UserID AND (title LIKE :searchWordB)";
}else{
        $query = "SELECT * FROM Posts WHERE UserID = :UserID";
}

$stmt = $dbh->prepare($query);
$stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_INT);
if(!empty($_SESSION['userGroup'])){
        $stmt->bindParam(':usergroup', $_SESSION['userGroup'], PDO::PARAM_INT);
}

if(!empty($_POST['searchKey'])){
	$SearchWord = "%" . $_POST['searchKey'] . "%";
        $stmt->bindParam(':searchWordA', $SearchWord, PDO::PARAM_STR);
        $stmt->bindParam(':searchWordB', $SearchWord, PDO::PARAM_STR);

}

$stmt->execute();

?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>NBoard - MyPost</title>
		<link rel="stylesheet" type="text/css" href="css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css?Ver=2">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<script type="text/javascript" src="js/footerFixed.js"></script>
		<!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
	</head>
	<body>
	<?php require_once('./header.php'); ?>
	<script>
        	function deletePost(DevID){
                	var target = document.getElementById("delControl");
                        target.href = "delete.php?id=" + DevID;
                }
	</script>

	<div class="deviceListBoard">
        	<a class="waves-effect waves-light btn" href="./dashboard.php">
        		<i class="material-icons left">keyboard_arrow_left</i>ホームに戻る
        	</a>
                <?php
                        echo '<span class="listTitle">';
                        if(!empty($_POST['searchKey'])){
                                echo "検索結果&nbsp;:&nbsp;" . htmlspecialchars($_POST['searchKey'], ENT_QUOTES, 'UTF-8');
                        }else{
                                echo "書き込み済み一覧";
                        }
                        echo '</span>';

                ?>
                <a class="waves-effect waves-light btn" href="./addPost.php">
                        <i class="material-icons left">add</i>新規書き込み
                </a>
                <a class="waves-effect waves-light btn modal-trigger" href="#modal1">
                        <i class="material-icons left">search</i>検索
                </a>
                <?php
                        if(!empty($_POST['searchKey'])){
                                echo '<a class="waves-effect waves-light btn modal-trigger red" href="editPost.php"><i class="material-icons left">clear_all</i>検索をクリア</a>';
                        }
                ?>

		<div class="listOutput">
		<?php
			$counter = 0;
			foreach($stmt as $data){
				if(empty($data['title'])){
					break;
				}
				if($counter == 0){
					echo '<ul class="collapsible">';
				}
				echo '<li><div class="collapsible-header">';
				echo htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8');
				echo "<br>";
				echo "作成日時: " . $data['created'];

                                echo '<div class="listButton">';
                                echo '<a class="waves-effect waves-light btn" href="edit.php?id=' . $data['ID'] . '"><i class="material-icons left">edit</i>編集</a>';
				echo '&nbsp;<a class="waves-effect waves-light btn red modal-trigger" href="#modal2" onclick="deletePost(' . $data['ID'] . ');"><i class="material-icons left">delete</i>削除</a>';
				echo '</div>';
				echo '</div></li>';
				$counter++;
			}
			if($counter != 0){
				echo '</ul>';
			}else{
				echo '<br><span class="listTitle">利用中のアカウントでの書き込みがありません。</span>';
			}
		?>
		</ul>
		</div>
        <div id="modal1" class="modal">
                <form action="editPost.php" method="POST">
                <div class="modal-content">
                        <h4>デバイス検索</h4>
                        <p>検索したいキーワードを入力して下さい。</p>
                        <div class="row">
                                <div class="input-field col s12">
                                        <input id="searchKey" name="searchKey" type="text" class="validate" required>
                                        <label for="searchKey">検索キーワード</label>
                                </div>
                        </div>
                </div>
                <div class="modal-footer">
                        <a class="waves-effect waves-light modal-close btn red"><i class="material-icons left">close</i>キャンセル</a>
                        <button type="submit" class="waves-effect waves-light btn" href=""><i class="material-icons left">search</i>検索</button>
                </div>
                </form>
        </div>

        <div id="modal2" class="modal">
                <div class="modal-content">
                        <h4>確認</h4>
                        <p>本当に削除して宜しいですか?</p>
                </div>
                <div class="modal-footer">
                        <a class="waves-effect waves-light modal-close btn"><i class="material-icons left">close</i>キャンセル</a>
                        <a id="delControl" class="waves-effect waves-light btn red" href=""><i class="material-icons left">delete</i>削除する</a>
                </div>
        </div>

	</div>
                        <script>
                                $(document).ready(function(){
                                        $('.modal').modal();
                                });

                        </script>
	</body>
</html>

