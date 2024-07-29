<?php
session_start();
include_once "../../../sqlLib.php"; $sqlLib = new sqlLib();
if(!isset($_SESSION["userid"])) 
{
	if($_COOKIE["userid"]!="")
	{
		$_SESSION["userid"] = $_COOKIE["userid"];
	}
	else header("Location:../../signin.php");
}

//$pakai = array("mi", "assembly", "finishing", "finishing 2", "covering");
?>
<option value="">-Select Module-</option>
<?php
	$sql_modul = "SELECT DISTINCT Nav1 FROM ms_nav";
    $data_modul= $sqlLib->select($sql_modul); 
    foreach ($data_modul as $nav)
    {
    	?>
        	<option value="<?php echo $nav['Nav1'] ?>" 
        		<?php if ($_POST['nav'] == $nav['Nav1']) { echo "selected";  } ?>><?php echo $nav['Nav1'] ?>
        	</option>
        <?php
    }   
?>  

  <!--                           
<select class="form-control" id="proses" name="proses" onchange="isinopalet()"  required="required"  onclick="selected();">
	<option value=""></option>
	<?php
	$sql = "SELECT ProsesID, Proses 
			FROM PRO_PROSES
			WHERE ProdukID = '".$_POST["produkid"]."' ORDER BY Proses ASC";
	$data = $sqlLib->select($sql);
	foreach($data as $row)
	{
		if(!in_array(strtolower($row["Proses"]),$pakai)) continue;
		?>
        <option value="<?php echo $row["ProsesID"]?>" 
        <?php if($_POST["proses"]==$row["ProsesID"]){ echo "selected";}?>><?php echo $row["Proses"]?></option>
	<?php }?>
</select> -->