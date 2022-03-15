<a href="<?php echo site_url(); ?>home"><button>Back To Upload Page</button></a>
<br /><br />
<table class="compact" style="white-space: nowrap;">
    <tr>
        <th>Total OK</th>
        <th style="padding:0 4px">:</th>
        <th><?php echo $ok_count; ?></th>
    </tr>
    <tr>
        <th>Total Error</th>
        <th>:</th>
        <th><?php echo $error_count; ?></th>
    </tr>
    <tr>
        <th>Total Rows</th>
        <th>:</th>
        <th><?php echo ($error_count + $ok_count); ?></th>
    </tr>
</table>
<!-- <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Filter with..." title="Type in a search key...">
<select style="height: 30px">
<?php 
    $columns = array_keys($data[0]);
    for($i=0;$i < count($columns);$i++){
        echo "<option value='". $i. "'>".$columns[$i]."</option>";
    }
?>
</select> -->

<div style="overflow-x: auto;white-space: nowrap; width: 65em;height: 23em">
    <br /><br />

    <table id="bootstrap-data-table" class="table table-striped table-bordered">
    <?php 
        // $columns = array_keys($data[0]);
        $html = "<thead><tr>";
        $html .= "<th>No</th>";
        foreach ($columns as $col) {
            $html .= "<th>". $col ."</th>";
        }
        $html .= "</tr></thead>";

        $count = 1;
        foreach ($data as $row) {
            $html .= "<tr>";
            $html .= "<td>" . $count . "</td>";
            foreach ($columns as $col) {
                // print_r($row[$col]); 
                $html .= "<td>" . $row[$col] . "</td>";
            }
            $html .= "</tr>";
            $count++;
        }
        echo $html;
    ?>
    </table>
</div>

<script src="<?php echo site_url(); ?>assets/js/lib/data-table/datatables.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/dataTables.buttons.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/jszip.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/pdfmake.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/vfs_fonts.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/buttons.html5.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/buttons.print.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/buttons.colVis.min.js"></script>
<script src="<?php echo site_url(); ?>assets/js/lib/data-table/datatables-init.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#bootstrap-data-table-export').DataTable();
});

function myFunction() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    indextd = $('select').val();
    for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[indextd];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
}
}
</script>