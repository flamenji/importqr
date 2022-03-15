<a href="<?php echo $link_template ?>">
    <div>
        <img src="<?php echo site_url(); ?>images/excel.png" style="width: 30px"/>
        <?php echo $text_template; ?> 
    </div>
</a>
<br />
<form action="<?php echo $link; ?>" method="post" enctype="multipart/form-data">
    <input type="file" id="excelfile" name="fileupload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />  
    <input type="button" id="viewfile" value="Preview" onclick="ExportToTable()" />  
    <input type="submit" id="submitfile" value="Upload" />  
    <input type="hidden" name="extension" id="extension" />
    <div id="csvmenu" style="display: none;margin-top: 10px;">
        <label>Delimiter(CSV Only)</label>
        <select id="delimiter_par" name="delimiter_par" disabled>
            <option value=",">,</option>
            <option value=";">;</option>
            <option value="\t">tabs</option>
        </select>
    </div>
</form>
<br />  
<br />  

<style>
    table.font_table tr td {font-size : 12px;padding : 0px 4px 0px 4px;}
    table.font_table tr th {font-size : 13px;padding : 0px 4px 0px 4px;text-align: center;}
    #delimiter_par:not(:disabled):hover{
    border:1px solid #bfc4c4;
    background-color: #d9dddd; background-image: linear-gradient(to bottom, #d9dddd, #c6c3c3);
    }
    /* this is added */
    #delimiter_par:disabled {
       color:grey;
       background-color:gray;
    }
    /* style for graying out the img */
    #delimiter_par:disabled > img {    
       filter:gray;
       -webkit-filter:grayscale(100%);
       -moz-filter:grayscale(100%);    
       filter:grayscale(100%);
</style>

<div class="card">
<div class="card-header">
    <h3>Preview</h3>
</div>
<div class="card-body" id="dvCSV" style="overflow: auto;width: 100%;">
    <table class="font_table table" border="1" id="exceltable">  
    </table> 
</div>
</div>


<!-- <script src="jquery-1.10.2.min.js" type="text/javascript"></script>   -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>  

<script type="text/javascript">
    $( document ).ready(function() {
        var file = $("#excelfile").val().toLowerCase();
        var extension = file.substr((file.lastIndexOf('.') +1));
        $('#extension').val(extension);
        console.log(extension);
        if(extension == "csv" || extension == "txt" ){
            $('#delimiter_par').prop('disabled', false);
            $('#csvmenu').css("display", "block");
        }
        else{
            $('#delimiter_par').prop('disabled', true);
            $('#csvmenu').css("display", "none");
        }
    });

    $(function() {
        $("input:file").change(function (){
            // if(this.files[0].size > 10485760){
            if(this.files[0].size > 10485760){
               alert("File is too big!\nMaximum is 10MB");
               this.value = "";
            };
            // console.log("DOR GANTI");
            var file = $("#excelfile").val().toLowerCase();
            var extension = file.substr((file.lastIndexOf('.') +1));
            $('#extension').val(extension);
            console.log(extension);
            if(extension == "csv" || extension == "txt" ){
                $('#delimiter_par').prop('disabled', false);
                $('#csvmenu').css("display", "block");
            }
            else{
                $('#delimiter_par').prop('disabled', true);
                $('#csvmenu').css("display", "none");
            }
        });
    });

    function getFileExtension(filename) {
        return (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename)[0] : undefined;
    }

    function ExportToTable() {
        var filename = '';
        $("#exceltable").html('');
        var file = $("#excelfile").val().toLowerCase();
        console.log(file);
        // var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls|.csv|.txt)$/;
        var regex = /^(.*)+(.xlsx|.xls|.csv|.txt)$/;
        
        // console.log(regex.test($("#excelfile").val()));
        /*Checks whether the file is a valid excel file*/  
        if (regex.test(file)) {
            filename = getFileExtension(file);
            // filename = file.split('.').pop();
            // var filename = file.substr( (file.lastIndexOf('.') +1) );
            // $("#exceltable").html('');
        }
        else {
            console.log('regex ext gagal');
        }
        console.log('filename : ' + filename);
        if(filename == 'xlsx' || filename == 'xls'){
            console.log('xlsx dan xls');
            var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/  
            if ($("#excelfile").val().toLowerCase().indexOf(".xlsx") > 0) {  
                xlsxflag = true;  
            }  
            /*Checks whether the browser supports HTML5*/  
            if (typeof (FileReader) != "undefined") {  
                var reader = new FileReader();  
                reader.onload = function (e) {  
                    $("#exceltable").html('');
                    var data = e.target.result; 
                    // console.log(data); 
                    /*Converts the excel data in to object*/  
                    if (xlsxflag) {  
                        var workbook = XLSX.read(data, { type: 'binary' });  
                    }  
                    else {  
                        var workbook = XLS.read(data, { type: 'binary' });  
                    }  
                    // console.log(workbook);
                    /*Gets all the sheetnames of excel in to a variable*/  
                    var sheet_name_list = workbook.SheetNames;  

                    var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/  
                    sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/  
                        /*Convert the cell value to Json*/  
                        if (xlsxflag) {  
                            var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);  
                        }  
                        else {  
                            var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);  
                        }  
                        if (exceljson.length > 0 && cnt == 0) {  
                            BindTable(exceljson, '#exceltable', 5);  
                            cnt++;  
                        }  
                    });

                    $('#exceltable').show();
                    $('.menu-item-has-children').css('width', '230px');
                }  
                if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/  
                    reader.readAsArrayBuffer($("#excelfile")[0].files[0]);  
                }  
                else {  
                    reader.readAsBinaryString($("#excelfile")[0].files[0]);  
                }  
            }

            else {  
                alert("Sorry! Your browser does not support HTML5!");  
            }  
        }
        else if(filename == 'csv' || filename == 'txt'){
            // console.log('CSV!!');
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
                // console.log("inside 1");
                // console.log(reader);
                reader.onload = function (e) {
                    // console.log("inside 2");
                    var table = '';
                    // var table = "<table border=\"1\" class=\"table\">";
                    var rows = e.target.result.split("\n");
                    // console.log("delimiter : " + $('#delimiter_par').val());

                    // if($('.header').is(':checked') == true){
                        var delimiter = $('#delimiter_par').val();
                        // delimiter = '\t';
                        var cells = rows[0].split(delimiter);
                        // var cells = rows[0].split($('#delimiter_par').val());
                        var row = "<tr class=\"thead-dark\">";
                        for (var j = 0; j < cells.length; j++) {
                            var cell = "<th>";
                            cell += cells[j];
                            cell += "</th>";
                            row += cell;
                        }
                        row += "</tr>";
                        table += row;
                        var i = 1;
                    // }
                    // else {
                        // var i = 0;
                    // }
                    // console.log(i);

                    for (i; i < 5; i++) {
                        var row = "<tr role=\"row\">";
                        var cells = rows[i].split($('#delimiter_par').val());
                        console.log(cells);
                        
                        for (var j = 0; j < cells.length; j++) {
                            var cell = "<td>";
                            cell += cells[j];
                            cell += "</td>";
                            row += cell;
                        }
                        row += "</tr>";
                        table += row;
                    }
                    //table += row;
                    //table(row);
                    // table += "</table>";
                    console.log(table);
                    $("#exceltable").html('');
                    $("#exceltable").append(table);
            }
        }
        else {
            alert("This browser does not support HTML5.");
        }
        reader.readAsText($("#excelfile")[0].files[0]);
    }      
    else {  
        alert("Please upload a valid Excel file!");  
    }
}

 function BindTable(jsondata, tableid, numRow) {/*Function used to convert the JSON array to Html Table*/  
     var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/  
     for (var i = 0; i < numRow; i++) {  
         var row$ = $('<tr/>');  
         for (var colIndex = 0; colIndex < columns.length; colIndex++) {  
             var cellValue = jsondata[i][columns[colIndex]];  
             if (cellValue == null)  
                 cellValue = "";  
             row$.append($('<td/>').html(cellValue));  
         }  
         $(tableid).append(row$);  
     }  
 }  
 function BindTableHeader(jsondata, tableid) {/*Function used to get all column names from JSON and bind the html table header*/
     var columnSet = [];  
     var headerTr$ = $('<tr class=\'thead-dark\'/>');  
     for (var i = 0; i < jsondata.length; i++) {  
         var rowHash = jsondata[i];  
         for (var key in rowHash) {  
             if (rowHash.hasOwnProperty(key)) {  
                 if ($.inArray(key, columnSet) == -1) {/*Adding each unique column names to a variable array*/  
                     columnSet.push(key);  
                     headerTr$.append($('<th/>').html(key));  
                 }  
             }  
         }  
     }  
     $(tableid).append(headerTr$);  
     return columnSet;  
 }  
</script>