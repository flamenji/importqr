<!DOCTYPE HTML>
<!--
	Phantom by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Sekar Telkom Witel Sidoarjo</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="<?php echo site_url(); ?>assets/js/ie/html5shiv.js"></script><![endif]-->

		<link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/button.css" />
		<link rel="stylesheet" href="<?php echo site_url(); ?>assets/js/button.js" />
		<link rel="stylesheet" href="<?php echo site_url(); ?>assets/Chart.js-master/src/chart.js" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="/resources/demos/style.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<!-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css"> -->


		<link rel="stylesheet"
		  href="https://cdn.jsdelivr.net/npm/animate.css@3.5.2/animate.min.css">
		  <!-- or -->
		  <link rel="stylesheet"
		  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

		<link rel="icon" 
		      type="image/png" 
		      href="<?php echo site_url(); ?>images/favicon.ico" />
		<!--[if lte IE 9]><link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="<?php echo site_url(); ?>assets/css/ie8.css" /><![endif]-->
		<style>
			.simple_table {
			    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			    border-collapse: collapse;
			    width: 50%;
			}

			.simple_table td, .simple_table th {
			    border: 1px solid #ddd;
			    padding: 1 px;

			}

			.simple_table tr:nth-child(even){background-color: #f2f2f2;}

			.simple_table tr:hover {background-color: #ddd;}

			.simple_table th {
			    padding-top: 12px;
			    padding-bottom: 12px;
			    text-align: center;
			    background-color: #4CAF50;
			    color: white;
			}
</style>
</head>
<body>
<h1>ADMIN PAGE</h1>
<a href="./main/logout">
	<button style="margin-bottom: 40px;">LOGOUT</button>
</a>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Grafik Hasil Vote</a></li>
    <li><a href="#tabs-2">Status Karyawan</a></li>
  </ul>
  <div id="tabs-1">
    <center>
    	<h3>Vote Officer</h3>
    		<table class="simple_table">
    			<tr>
					<th>SUDAH VOTE</th>
					<th>BELUM VOTE</th>
					<th>TOTAL</th>
				</tr>
				<tr>
					<?php
						echo "<td>".count($vote_officer)."</td>";
						$belum = 133 - count($vote_officer);
						echo "<td>".$belum."</td>";
					?>
					<td>133</td>
				</tr>
    		</table>

		<h3>Vote Officer</h3>
			<table class="simple_table">
				<tr>
					<th>NIK</th>
					<th>Nama</th>
					<th>Total Vote</th>
				</tr>
				<?php
					foreach($vote_officer as $i);
					echo "<tr>";
					echo "<td>". $i['vote_officer'] ."</td>";
					echo "<td>". $i['nama'] ."</td>";
					echo "<td>". $i['total_vote'] ."</td>";
					echo "</tr>";
				?>
			</table>
		<h3>Vote Manager</h3>
		<table class="simple_table">
				<tr>
					<th>NIK</th>
					<th>Nama</th>
					<th>Total Vote</th>
				</tr>
				<?php
					foreach($vote_manager as $i);
					echo "<tr>";
					echo "<td>". $i['vote_manager'] ."</td>";
					echo "<td>". $i['nama'] ."</td>";
					echo "<td>". $i['total_vote'] ."</td>";
					echo "</tr>";
				?>
			</table>
	</center>
  </div>
  <div id="tabs-2">
  <center>
  <input style="margin-bottom: 30px;width:500px;" type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names/vote.." title="Type in a name/vote">
  <select id="kategori">
  	<option value="nik">NIK</option>
  	<option value="vote">Vote Officer</option>
  	<option value="vote">Vote Manager</option>
  	<option value="last_login">Last Login</option>
  	<option value="date_vote">Date Vote</option>
  	<option value="ip_last_access">IP Last Access</option>
  </select>
  <script>
	function myFunction() {
	  var input, filter, table, tr, td, i;
	  input = document.getElementById("myInput");
	  filter = input.value.toUpperCase();
	  table = document.getElementById("list_vote");
	  tr = table.getElementsByTagName("tr");
	  var cat = $('#kategori').val();
	  var a = 0;
	  if(cat == 'nik'){
	  	a = 0;
	  }
	  else if(cat == 'vote'){
	  	a = 1;
	  }
	  else if(cat == 'last_login'){
	  	a = 2;
	  }
	  else if(cat == 'date_vote'){
	  	a = 3;
	  }
	  else if(cat == 'ip_last_access'){
	  	a = 4;
	  }

	  for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[a];
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

  	<?php 
		//print_r($data); 
		echo "<table id=\"list_vote\" class=\"simple_table\" style=\"width:70%;\">";
		echo "<tr align=\"right\"><th>NIK</th><th>Vote Officer</th><th>Vote Manager</th><th>Last Login</th><th>Date Vote</th><th>IP Last Access</th>";
		for($i=0;$i<count($data);$i++){
			echo "<tr>";
			echo "<td>" . $data[$i]['nik'] . "</td>";
			echo "<td>" . $data[$i]['vote_officer'] . "</td>";
			echo "<td>" . $data[$i]['vote_manager'] . "</td>";
			echo "<td>" . $data[$i]['last_login'] . "</td>";
			echo "<td>" . $data[$i]['date_vote'] . "</td>";
			echo "<td>" . $data[$i]['last_ip'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	?>
	</center>
  </div>
</div>


<script src="http://www.chartjs.org/dist/2.7.1/Chart.bundle.js"></script>
<script src="http://www.chartjs.org/samples/latest/utils.js"></script>
  <script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>


</body>

</html>