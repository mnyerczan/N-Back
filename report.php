<div id="report_container">
<style>
	.report-h1:{
		text-align: center;
		font-size: 30px
	}
</style>
<?php
if(isset($_GET['rep_common']) && $_GET['rep_common'] != null){

	$case = $_GET['rep_common'];

	switch($case){

		case '1':{
			if(!isset($_SESSION['user_datas']['id'])){
				echo '<div id="wellcome_img_container"><img src="img/welcome.png" ></div>';
				header('refresh:2;url=index.php?index=1');
			}
			else echo '<h1 class="report-h1" id="n_back_modify_level"><span>CREATE USER SUCCESSFUL</span></h1>';
				header('refresh:2;url=index.php');
		} break;
		case '2':{

			if(!isset($_SESSION['user_datas']['id'])){

				echo '<div id="wellcome_img_container"><img src="img/goodbye.png" ></div>';
				header('refresh:2;url=index.php');
			}
			else
				echo '<h1 class="report-h1" id="n_back_modify_level"><span>DELETE SUCCESSFUL</span></h1>';
				header('refresh:2;url=index.php');

		}break;
		case '3':{

				echo '<h1 class="report-h1">N-Back Decrease</h1><div id="n_back_modify_level"><span color="#f44">Level -1</span></div>';
				header('refresh: 2; url=index.php');

		}break;
		case '4':{
				echo '<h1 class="report-h1">N-Back Increase</h1><div id="n_back_modify_level"><span color="#0a0">Level +1</span></div>';
				header('refresh: 2; url=index.php');

		}break;
	}
}
?>

</div>
