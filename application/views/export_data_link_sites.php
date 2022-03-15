<script lang="javascript" src="<?php echo site_url(); ?>assets/js/xlsx.full.min.js"></script>
<script lang="javascript" src="<?php echo site_url(); ?>assets/js/FileSaver.js"></script>

</head>
<body>
<div id="navbar"><span>Export To Excel (.xlxs) </span></div>
<div id="wrapper">
    
<button id="button-a">Download Excel</button>
</div>
<script>
        var wb = XLSX.utils.book_new();
        wb.Props = {
                Title: "Magenta MSO",
                Subject: "ODP",
                Author: "MSO Regional 5",
                CreatedDate: new Date(2017,12,19)
        };
        
        wb.SheetNames.push("MASTER");
        // var ws_data = [['hello' , 'world']];
        var ws_data = <?php echo json_encode($rows); ?>;
        var ws = XLSX.utils.json_to_sheet(ws_data);

        // var ws = XLSX.utils.aoa_to_sheet(ws_data);
        wb.Sheets["MASTER"] = ws;
        var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});
        function s2ab(s) {
  
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
                
        }
        $("#button-a").click(function(){
                saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), 'Data Link Sites.xlsx');
        });
  
</script>
