<?php
session_start();
include_once "conf.php";
include_once "sqlLib.php";
include "function/function.php";
include "function/ceisa/get_login.php";
include "function/ceisa/get_token.php";
include "function/jsonPage.php";
$sqlLib = new sqlLib();
if (!isset($_SESSION["userid"])) {
  if ($_COOKIE["userid"] != "") {

    $_SESSION["userid"]   = $_COOKIE["userid"];
    $_SESSION["admin"]     = $_COOKIE["admin"];
    $_SESSION["nama"] = $_COOKIE["nama"];
  } else header("Location:signin.php");
}
?>
<!DOCTYPE html>
<html lang="en">
	<meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>IT INVENTORY</title>
	<head>
		<script src="assets/js/jquery-2.1.3.js"></script>
		<script src="assets/js/tableHeadFixer.js"></script>
		<!-- Favicon -->
    	<link rel="icon" type="image/png" href="images/favicon.png"> 

		<!-- General CSS Files -->
		  <!-- <link rel="stylesheet" href="assets/css/bootstrap.css"> -->
		  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
		  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

		  <link rel="stylesheet" href="assets/css/stackpath.bootstrapcdn.com_bootstrap_4.3.1_css_bootstrap.min.css" 
		      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		  
		  <link rel="stylesheet" href="assets/css/use_fontawesome_com_releases_v5_7_2_css_all.css" 
		  integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous"> 

		  <!-- CSS Libraries -->
		  <!-- datatabel -->
		  <link rel="stylesheet" href="assets/modules/datatables/datatables.min.css">
		  <link rel="stylesheet" href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
		  <link rel="stylesheet" href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">

		  <!-- datepicker -->
		  <link rel="stylesheet" href="assets/modules/bootstrap-daterangepicker/daterangepicker.css">

		  <!-- Template CSS -->
		  <link rel="stylesheet" href="assets/css/style.css">
		  <link rel="stylesheet" href="assets/css/components.css">  

		  
	
  

		<style>
			#parent {
				height: 300px;
			}
		</style>

		<script>
			$(document).ready(function() {
				$("#fixTable").tableHeadFixer(); 
			});
		</script>

		<style>
		    /* Center the loader */
		    #loader {
		      position: absolute;
		      left: 50%;
		      top: 50%;
		      z-index: 1;
		      width: 120px;
		      height: 120px;
		      margin: -76px 0 0 -76px;
		      border: 16px solid #f3f3f3;
		      border-radius: 50%;
		      border-top: 16px solid #3498db;
		      -webkit-animation: spin 2s linear infinite;
		      animation: spin 2s linear infinite;
		    }

		    @-webkit-keyframes spin {
		      0% {
		        -webkit-transform: rotate(0deg);
		      }

		      100% {
		        -webkit-transform: rotate(360deg);
		      }
		    }

		    @keyframes spin {
		      0% {
		        transform: rotate(0deg);
		      }

		      100% {
		        transform: rotate(360deg);
		      }
		    }

		    /* Add animation to "page content" */
		    .animate-bottom {
		      position: relative;
		      -webkit-animation-name: animatebottom;
		      -webkit-animation-duration: 1s;
		      animation-name: animatebottom;
		      animation-duration: 1s
		    }

		    @-webkit-keyframes animatebottom {
		      from {
		        bottom: -100px;
		        opacity: 0
		      }

		      to {
		        bottom: 0px;
		        opacity: 1
		      }
		    }

		    @keyframes animatebottom {
		      from {
		        bottom: -100px;
		        opacity: 0
		      }

		      to {
		        bottom: 0;
		        opacity: 1
		      }
		    }

		    #myDiv {
		      display: none;
		      /*text-align: center;*/
		    }
		  </style>
	</head>

	<body onload="myFunction()" class="sidebar-mini">
	  <div id="app">
	    <div class="main-wrapper">
	    <div id="loader"></div>
	      <div class="navbar-bg"></div>
	      <nav class="navbar navbar-expand-lg main-navbar">
	        <form class="form-inline mr-auto">
	          <ul class="navbar-nav mr-3">
	            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
	            <!-- <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li> -->
	          </ul>

	        </form>
	        <ul class="navbar-nav navbar-right">

	          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
	              <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
	              <div class="d-sm-none d-lg-inline-block">Hi,  <?php echo  ucwords($_SESSION["userid"]);  ?></div>
	            </a>
	            <div class="dropdown-menu dropdown-menu-right">
	              <div class="dropdown-title"><?php echo  ucwords($_SESSION["userid"]);  ?></div>
	              <a href="index.php?m=<?php echo acakacak("encode", "cp") ?>&p=<?php echo acakacak("encode", "Change Password") ?>" class="dropdown-item has-icon">
		            <i class="fas fa-lock"></i> Change Password
		          </a>
	              <!-- <div class="dropdown-divider"></div> -->

	              <a href="index.php?m=<?php echo acakacak("encode", "manualbook") ?>&p=<?php echo acakacak("encode", "Manual Book") ?>" class="dropdown-item has-icon">
	                <i class="fas fa-book"></i> Manual Book
	              </a>
	              <a href="signout.php" class="dropdown-item has-icon text-danger">
	                <i class="fas fa-sign-out-alt"></i> Logout
	              </a>
	            </div>
	          </li>
	        </ul>
	      </nav>
	      <div class="main-sidebar">
	        <aside id="sidebar-wrapper">
	          <div class="sidebar-brand">
	            <a href="index.php">IT INVENTORY</a>
	          </div>
	          <div class="sidebar-brand sidebar-brand-sm">
	            <a href="index.php">INV</a>
	          </div>
	           <?php include "menu.php" ?>

	        </aside>
	      </div>

	      <!-- Main Content -->
	      <div class="main-content">
	      <div style="display:none;" id="myDiv">
	        <section class="section">
	        	<?php
		          if ($_GET["m"] == "")
		            include "master/dashboard/index.php";
		          else if ($_GET["m"] != "" and $_GET["sm"] != "")
		            include "master/" . acakacak("decode", $_GET["m"]) . "/" . acakacak("decode", $_GET["sm"]) . ".php";
		          else if ($_GET["m"] != "")
		            include "master/" . acakacak("decode", $_GET["m"]) . "/index.php";
		          else include "master/dashboard/index.php";
		          ?>
		        		
			</section>
      	</div>      
        </div>	
    </div>
  </div>    
	</body>
</html>

<script>
    var myVar;

    function myFunction() {
      myVar = setTimeout(showPage, 300);
    }

    function showPage() {
      document.getElementById("loader").style.display = "none";
      document.getElementById("myDiv").style.display = "block";
    }
  </script>

<!-- General JS Scripts
  <script src="assets/modules/jquery.min.js"></script>-->
  <script src="assets/modules/popper.js"></script>
  <script src="assets/modules/tooltip.js"></script>
  <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script> 
  <script src="assets/modules/moment.min.js"></script>  
  <script src="assets/js/stisla.js"></script>

  <!-- JS Libraies -->  
  <script src="assets/modules/datatables/datatables.min.js"></script>
  <script src="assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
  <script src="assets/modules/chart.min.js"></script>
  <script src="assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- JS Libraies -->
  <script src="assets/modules/jquery-ui/jquery-ui.min.js"></script>
  <script src="assets/modules/prism/prism.js"></script>

  <!-- Page Specific JS File -->
  <script src="assets/js/page/modules-datatables.js"></script>
  <script src="assets/js/page/modules-calendar.js"></script>
  <script src="assets/js/page/modules-chartjs.js"></script>

  <!-- Page Specific JS File -->
  <script src="assets/js/page/index-0.js"></script>
  
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>


<script type="text/javascript">
	function popup(title, url, width, height) {
		var leftPosition, topPosition;
		leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
		topPosition = (window.screen.height / 2) - ((height / 2) + 50);
		newwindow = window.open(url, title, "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,height=" + height + ",width=" + width + ",resizable=yes,left=" +
			leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" +
			topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
		newwindow.focus();
	}
</script>		
