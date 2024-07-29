 <!-- General JS Scripts 
  <script src="assets/modules/jquery.min.js"></script>
  <script src="assets/modules/popper.js"></script>
  <script src="assets/modules/tooltip.js"></script>
  <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="assets/modules/moment.min.js"></script>
  <script src="assets/js/stisla.js"></script>

  
  <!-- JS Libraies  
  <script src="assets/modules/datatables/datatables.min.js"></script>
  <script src="assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script>
  <script src="assets/modules/chart.min.js"></script>
  <script src="assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- Page Specific JS File 
  <script src="assets/js/page/modules-datatables.js"></script>
  <script src="assets/js/page/modules-calendar.js"></script>
  <script src="assets/js/page/modules-chartjs.js"></script>
  
  <!-- Template JS File 
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>

<!--AUTO COMPLETE-->
  <link rel="stylesheet" href="assets/css/jquery-ui.css" />
  <script src="assets/js/jquery-1.12.4.js"></script>
  <script src="assets/js/jquery-ui.js"></script>

<div class="col-sm-2">   

    <input type="text" name="namadokumen" id="namadokumen"  class="form-control" >
    <input type="hidden" name="kodedokumen"  id="kodedokumen"  class="form-control" >
</div>  


<script>
    $(document).ready(function() {
    var ac_config = {
      source: "json/dokumen.php",
      select: function(event, ui) {
        $("#kodedokumen").val(ui.item.id);
        $("#namadokumen").val(ui.item.namadokumen);
      },
      focus: function(event, ui) {
        $("#kodedokumen").val(ui.item.id);
        $("#namadokumen").val(ui.item.namadokumen);
      },
      minLength: 1
    };
    $("#namadokumen").autocomplete(ac_config);
  });
</script>  