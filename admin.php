<?php
// upload google analytics code to header
// GA code upload in UTM analysis tab
function uploadGAcode()
{
	$codewithquote = "'".get_option('pressjuice_cookie_duration')."'"
?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131193435-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config',);
</script>



<?php

	echo esc_attr(get_option('pressjuice_cookie_duration') );
}

add_action('wp_head', 'uploadGAcode');

// start of admin page
	function pressjuice_options_page_html()
{

	if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }
     ?>
     <div id="azarbaijan"></div>
    <div class="wrap">
       <h2>PressJuice Analytics</h2>





<?php
global $wpdb;
// this adds the prefix which is set by the user upon instillation of wordpress
$table_name = $wpdb->prefix . "pressjuice";
// this will get the data from your table
//$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );
//$oneday_data_traffic = $wpdb->get_results( "SELECT HOUR(time) AS x, COUNT(id) AS y FROM `$table_name` GROUP BY HOUR(time)" );
$oneday_data_traffic = $wpdb->get_results("SELECT HOUR(time) AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 1 DAY) AS T GROUP BY HOUR(time) ");
$oneday_trafic_total = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE time >= NOW() - INTERVAL 1 DAY");
//$total_days = $wpdb->get_var("SELECT COUNT(DISTINCT(DATE(time))) FROM `$table_name` ");
$oneweek_data_traffic = $wpdb->get_results("SELECT DATE(time) AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 7 DAY) AS T GROUP BY DATE(time)");
$oneweek_trafic_total = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE time >= NOW() - INTERVAL 7 DAY");
$onemonth_data_traffic = $wpdb->get_results("SELECT DATE(time) AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 30 DAY) AS T GROUP BY DATE(time)");
$onemonth_trafic_total = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE time >= NOW() - INTERVAL 30 DAY");
// jason encoding to display graph 
$data1= json_encode($oneday_data_traffic);
$oneweek_json = json_encode($oneweek_data_traffic);
$onemonth_json = json_encode($onemonth_data_traffic);

$oneday_data_user = $wpdb->get_results("SELECT username AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 1 DAY) AS T GROUP BY username ORDER BY COUNT(id) DESC LIMIT 50");
$oneweek_data_user = $wpdb->get_results("SELECT username AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 7 DAY) AS T GROUP BY username ORDER BY COUNT(id) DESC LIMIT 50");
$onemonth_data_user = $wpdb->get_results("SELECT username AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 30 DAY) AS T GROUP BY username ORDER BY COUNT(id) DESC LIMIT 50");



$oneday_data_pages = $wpdb->get_results("SELECT url AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 1 DAY) AS T GROUP BY url ORDER BY COUNT(id) DESC LIMIT 50");
$oneweek_data_pages = $wpdb->get_results("SELECT url AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 7 DAY) AS T GROUP BY url ORDER BY COUNT(id) DESC LIMIT 50");
$onemonth_data_pages = $wpdb->get_results("SELECT url AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 30 DAY) AS T GROUP BY url ORDER BY COUNT(id) DESC LIMIT 50");
?>
<div>
<style scoped>
		.chart-container{
			width: 700px;
    		height:500px;
		}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
<script src="http://momentjs.com/downloads/moment.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div>
	<style scoped>
		body {font-family: Arial;}

		/* Style the tab */
		.tab {
		    overflow: hidden;
		    border: 1px solid #ccc;
		    background-color: #f1f1f1;
		}

		.linkdiv {
		    overflow: hidden;
		    border: 1px solid #ccc;
		    background-color: #f1f1f1;
		}
		/* Style the buttons inside the tab */
		.tab button {
		    background-color: inherit;
		    float: left;
		    border: none;
		    outline: none;
		    cursor: pointer;
		    padding: 14px 16px;
		    transition: 0.3s;
		    font-size: 17px;
		}

		/* Change background color of buttons on hover */
		.tab button:hover {
		    background-color: #ddd;
		}

		/* Create an active/current tablink class */
		.tab button.active {
		    background-color: #ccc;
		}

		/* Style the tab content */
		.tabcontent, .tabcontent1 .tabcontent2{
		    display: none;
		    padding: 6px 6px;
		    border: 1px solid #ccc;
		    border-top: none;
		}

		/* Style the close button */
		.topright {
		    float: right;
		    cursor: pointer;
		    font-size: 28px;
		}

		.topright:hover {color: red;}

		.column {
		    float: left;
		    width: 33%;
		}
		.column2 {
		    float: left;
		    width: 50%;
		}
		.xspan {
			width: 100%;
		}

		/* Clear floats after the columns */
		.row:after {
		    /*content: "";*/
		    display: table;
		    clear: both;
		}

		@media screen and (max-width: 600px) {
		    .column {
		        width: 100%;
		    }
		}

		 tr:hover {background-color: white;
		 			color: #0f1151;
		 								} 

		th {
		    background-color: #406c7a;
		    color: white;
		}
		th, td {
		    padding: 5px;
		    text-align: left;
		}
		table {
			width: 80%;
		    border-collapse: collapse;
		}

		table, th, td {
		    border: 1px solid black;
		}
		.scrolltable
		{
		    max-height: 500px;
		    overflow-y: scroll;
		}


		.tabcontent2{
	    display: none;
	    padding: 6px 6px;
	    border: 1px solid #ccc;
	    border-top: none;
		}
	</style>
	<div class="tab">
		<button class="tablinks1" onclick="openTab1(event, 'traffic')" id="defaultOpen"> Traffic Data</button>
		<button class="tablinks1 target" onclick="openTab1(event, 'user')" >Top User</button>
		<button class="tablinks1" onclick="openTab1(event, 'pages')">Most Visited Pages</button>
		<button class="tablinks1" onclick="openTab1(event, 'behaviour')" id="targetajax">Behaviour Analytics</button>
	</div>

	<div class="tab tabcontent1 row" id="behaviour">
		<div class="xspan"><span onclick="this.parentNode.parentNode.style.display='none'" class="topright">&times</span></div>
		<div class="row" id="Pressjuice_landingpages">
		</div>
	</div>
	<div class="tab tabcontent1 row" id="pages">
		<div class="xspan"><span onclick="this.parentNode.parentNode.style.display='none'" class="topright">&times</span></div>
		<div class="row">
			<div class="column">
				<h3>Last 24 Hrs</h3>
					<div class="scrolltable">
						<table>
							<tr>
								<th>URL</th>
								<th>Number of Hits</th>
							</tr>
							<?php foreach ($oneday_data_pages as $key) { ?>
							<tr>
								<td><button class="td1111"><?php 
									
										echo $key->x;					
									
									 ?></button></td>
									
								<td><?php echo $key->y; ?></td>
							</tr>
							<?php	}
							?>
					</table>
					</div>
			</div>
		  	<div class="column">
		  		<h3>Last 7 Days</h3>
		  		<div class="scrolltable">
			  		<table>
						<tr>
							<th>URL</th>
							<th>Number of Hits</th>
						</tr>
						<?php foreach ($oneweek_data_pages as $key) { ?>
						<tr>
							<td><button class="td1111"><?php 
									echo $key->x;					
								
								 ?></button></td>
									
								<td><?php echo $key->y; ?></td>
						</tr>
								<?php	}
							?>
					</table>
				</div>
		  	</div>
		  	<div class="column">
		  		<h3>Last 30 Days</h3>
		  		<div class="scrolltable">
			  		<table>
						<tr>
							<th>URL</th>
							<th>Number of Hits</th>
						</tr>
						<?php foreach ($onemonth_data_pages as $key) { ?>
							<tr>
							<td><button class="td1111"><?php 
								
									echo $key->x;					
						
								 ?></button></td>
								
							<td><?php echo $key->y; ?></td>
						</tr>
					<?php	}
						?>
					</table>
				</div>
		  	</div>
	  	</div>
  	</div>
  	<div class="tab tabcontent1 row" id="traffic">
			<div class="tab">
			  <button class="tablinks2" onclick="openTab2(event, 'traffichoururl')" id="defaultOpen1">Traffic in last 24 hours</button>
			  <button class="tablinks2" onclick="openTab2(event, 'trafficweekurl')">Traffic in last 7 days</button>
			  <button class="tablinks2" onclick="openTab2(event, 'trafficmonthurl')">Traffic in last 30 days</button>
			</div>

			<div id="traffichoururl" class="tabcontent2">
			  	<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
			  	<h3>Traffic by hour</h3>
			  	<h4>Total Visitors in last 24 hours : <b><?php echo $oneday_trafic_total_url; ?></b></h4>
			    <div class="chart-container"><canvas id="hrchart" width="500" height="300"></canvas></div>

			</div>

			<div id="trafficweekurl" class="tabcontent2">
			  	<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
			  	<h3>Traffic by Days</h3>
			   	<h4>Total Visitors in last 7 days : <b><?php echo $oneweek_trafic_total_url; ?></b></h4>
			 	<div class="chart-container"><canvas id="daychart" width="500" height="300"></canvas></div>

			</div>

			<div id="trafficmonthurl" class="tabcontent2">
				<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
				<h3>Traffic by Days</h3>
				<h4>Total Visitors in last 30 days : <b><?php echo $onemonth_trafic_total_url; ?></b></h4>
				<div class="chart-container"><canvas id="monthchart" width="500" height="300"></canvas></div>
			</div>
			<script type="text/javascript">
				function openTab2(evt, cityName) {
			    var i, tabcontent2, tablinks2;
			    tabcontent2 = document.getElementsByClassName("tabcontent2");
			    for (i = 0; i < tabcontent2.length; i++) {
			        tabcontent2[i].style.display = "none";
			    }
			    tablinks2 = document.getElementsByClassName("tablinks2");
			    for (i = 0; i < tablinks2.length; i++) {
			        tablinks2[i].className = tablinks2[i].className.replace(" active", "");
			    }
			    document.getElementById(cityName).style.display = "block";
			    evt.currentTarget.className += " active";
				}
			</script>
	</div>	
	<div class="tab tabcontent1 row" id="user">
		<div><span onclick="this.parentNode.parentNode.style.display='none'" class="topright">&times</span></div>
		<div class="row">
			<div class="column">
				<h3>Last 24 Hrs</h3>
				<div class="scrolltable">
					<table>
						<tr>
							<th>Username</th>
							<th>Number of Hits</th>
						</tr>
						<?php foreach ($oneday_data_user as $key) { ?>
						<tr>
							<td><?php 
								if ($key->x!='not log in') {
									echo $key->x;					
								}
								else{
									echo 'Users without sign in';
								}
								 ?></td>
								
							<td><?php echo $key->y; ?></td>
						</tr>
						<?php	}
						?>
					</table>
				</div>
			</div>
	  		<div class="column">
			  		<h3>Last 7 Days</h3>
			  		<div class="scrolltable">
			  			<table>
							<tr>
								<th>Username</th>
								<th>Number of Hits</th>
							</tr>
							<?php foreach ($oneweek_data_user as $key) { ?>
								<tr>
								<td><?php 
									if ($key->x!='not log in') {
										echo $key->x;					
									}
									else{
										echo 'Users without sign in';
									}
									 ?></td>
									
								<td><?php echo $key->y; ?></td>
							</tr>
							<?php	}
							?>
						</table>
					</div>
	  		</div>
	  		<div class="column">
		  		<h3>Last 30 Days</h3>
		  		<div class="scrolltable">
			  		<table>
						<tr>
							<th>Username</th>
							<th>Number of Hits</th>
						</tr>
						<?php foreach ($onemonth_data_user as $key) { ?>
						<tr>
							<td><?php 
								if ($key->x!='not log in') {
									echo $key->x;					
								}
								else{
									echo 'Users without sign in';
								}
								 ?></td>
								
							<td><?php echo $key->y; ?></td>
						</tr>
						<?php	}
						?>
					</table>
				</div>
	  		</div>
	  	</div>
	</div>
	<div class="linkdiv" id="linkdiv2">
		<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>

		<div class="linkdiv" id="linkdiv1">
			<div class="tabcontent1kkkk" id="traffic">
				<div class="tab">
				  <button class="tablinks" onclick="openTab(event, 'traffichour')" id="defaultOpen">Traffic in last 24 hours</button>
				  <button class="tablinks" onclick="openTab(event, 'trafficweek')">Traffic in last 7 days</button>
				  <button class="tablinks" onclick="openTab(event, 'trafficmonth')">Traffic in last 30 days</button>
				</div>

				<div id="traffichour" class="tabcontent">
				  	<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
				  	<h3>Traffic by hour</h3>
				  	<h4>Total Visitors in last 24 hours : <b><?php echo $oneday_trafic_total; ?></b></h4>
				    <div class="chart-container"><canvas id="hrcharturl" width="500" height="300"></canvas></div>

				</div>

				<div id="trafficweek" class="tabcontent">
				  	<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
				  	<h3>Traffic by Days</h3>
				   	<h4>Total Visitors in last 7 days : <b><?php echo $oneweek_trafic_total; ?></b></h4>
				 	<div class="chart-container"><canvas id="daycharturl" width="500" height="300"></canvas></div>

				</div>

				<div id="trafficmonth" class="tabcontent">
					<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
					<h3>Traffic by Days</h3>
					<h4>Total Visitors in last 30 days : <b><?php echo $onemonth_trafic_total; ?></b></h4>
					<div class="chart-container"><canvas id="monthcharturl" width="500" height="300"></canvas></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function openTab(evt, cityName) {
	    var i, tabcontent, tablinks;
	    tabcontent = document.getElementsByClassName("tabcontent");
	    for (i = 0; i < tabcontent.length; i++) {
	        tabcontent[i].style.display = "none";
	    }
	    tablinks = document.getElementsByClassName("tablinks");
	    for (i = 0; i < tablinks.length; i++) {
	        tablinks[i].className = tablinks[i].className.replace(" active", "");
	    }
	    document.getElementById(cityName).style.display = "block";
	    evt.currentTarget.className += " active";
	}
	function openTab1(evt, cityName) {
	    var i, tabcontent1, tablinks1;
	    tabcontent1 = document.getElementsByClassName("tabcontent1");
	    for (i = 0; i < tabcontent1.length; i++) {
	        tabcontent1[i].style.display = "none";
	    }
	    tablinks1 = document.getElementsByClassName("tablinks1");
	    for (i = 0; i < tablinks1.length; i++) {
	        tablinks1[i].className = tablinks1[i].className.replace(" active", "");
	    }
	    document.getElementById(cityName).style.display = "block";
	    evt.currentTarget.className += " active";
	}
	function openTab2(evt, cityName) {
	    var i, tabcontent2, tablinks2;
	    tabcontent2 = document.getElementsByClassName("tabcontent2");
	    for (i = 0; i < tabcontent2.length; i++) {
	        tabcontent2[i].style.display = "none";
	    }
	    tablinks2 = document.getElementsByClassName("tablinks2");
	    for (i = 0; i < tablinks2.length; i++) {
	        tablinks2[i].className = tablinks2[i].className.replace(" active", "");
	    }
	    document.getElementById(cityName).style.display = "block";
	    evt.currentTarget.className += " active";
		}
	// Get the element with id="defaultOpen" and click on it
	document.getElementById("defaultOpen").click();

	document.getElementById("linkdiv2").style.display = "none";

	$(".td1111").click(function() {
	//     var page_url =  $(this).text();
	//     //alert(fired_button);
	     document.getElementById("linkdiv2").style.display = "block";
	//     $('#linkdiv1').html('');
	//     //$("#linkdiv1").append(page_url);
	//     var data1111 = {
	// 			'action': 'get_link_data',
	// 			'page_url_1': page_url
	// 		};
	// 		jQuery("#linkdiv1").append(data1111);

	// 		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	// 		jQuery.post(ajaxurl, data1111, function(response) {
	// 				jQuery("#linkdiv1").append(response);
	// 			});

	 });
</script>


	</div>
	<script type="text/javascript">
		var ctx1 = document.getElementById("hrchart");
		var ctx2 = document.getElementById("daychart");
		var ctx3 = document.getElementById("monthchart");
		var data2 = <?php echo $data1 ?>;
		var data3 = [];
		var data4 = [];
		var data5 = [];
		var j=0;
		for (var i =0; i <= 23; i++) {
			data3[i]= { x: "6", y: "3" };
			var time = moment().toDate();
			time.setHours(i);
			time.setMinutes(0);
			time.setSeconds(0);
			time.setMilliseconds(0);
			data3[i]["x"]=time;
			if(data2[j]["x"]==i){

				data3[i]["y"]=data2[j]["y"];
			if(j<data2.length-1){
				j++;
			}
			
			}else{
			data3[i]["y"]=0;
			}
				}
		var myLineChart = new Chart(ctx1, {
		    type: 'line',
		    data: {
		        datasets: [{
		            label: 'Trafic',
		            data: data3,
		                    lineTension: 0,
		                    backgroundColor: [
		                '#6db7ce']


		        }]
		    },
		    options: {
		        scales: {
		            xAxes: [{
		                type: 'time',
		                time: {
		               
		                	displayFormats: {
		                        hour : 'hA'
		                    }

		                }
		          
		            }],
		             yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        }
		    }
		});
// doing the whole thing again for week table
		var jasononeweek = <?php echo $oneweek_json ?>;
		var j=0;
		var k=0;
		var todaydate = moment().toDate().getDate();
		for (var i =0; i <= 6; i++) {
			data4[i]= { x: "6", y: "3" };
			var dateis = todaydate-7+i;
			var time = moment().toDate();
			time.setDate(dateis);
			time.setHours(0);
			time.setMinutes(0);
			time.setSeconds(0);
			time.setMilliseconds(0);
			data4[i]["x"]=time;
			jasononeweek[j]["x"]=moment(jasononeweek[j]["x"]).toDate();
			if(jasononeweek[j]["x"].getDate()==data4[i]["x"].getDate()){
				k=2;
				data4[i]["y"]=jasononeweek[j]["y"];
			if(j<jasononeweek.length-1){
				j++;
			}
			
			}else{
			data4[i]["y"]=0;
			}
		}
	var myLineChart2 = new Chart(ctx2, {
	    type: 'line',
	    data: {
	        datasets: [{
	            label: 'Trafic',
	            data: data4,
	                    lineTension: 0,
	                    backgroundColor: [
	                '#6db7ce']


	        }]
	    },
	    options: {
	        scales: {
	            xAxes: [{
	                type: 'time',
	                time: {
	               		unit: 'day'
	                	

	                }
	          
	            }],
	             yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
	});
	// Doing the Same for 30 days.
	var jasononemonth = <?php echo $onemonth_json ?>;
			var j=0;
			var todaydate = moment().toDate().getDate();
			for (var i =0; i <= 29; i++) {
				data5[i]= { x: "6", y: "3" };
				var dateis = todaydate-30+i;
				var time = moment().toDate();
				time.setDate(dateis);
				time.setHours(0);
				time.setMinutes(0);
				time.setSeconds(0);
				time.setMilliseconds(0);
				data5[i]["x"]=time;
				jasononemonth[j]["x"]=moment(jasononemonth[j]["x"]).toDate();
				if(jasononemonth[j]["x"].getDate()==data5[i]["x"].getDate()){
					data5[i]["y"]=jasononemonth[j]["y"];
				if(j<jasononemonth.length-1){
					j++;
				}
				
				}else{
				data5[i]["y"]=0;
				}
			}
	var myLineChart3 = new Chart(ctx3, {
	    type: 'line',
	    data: {
	        datasets: [{
	            label: 'Trafic',
	            data: data5,
	                    lineTension: 0,
	                    backgroundColor: [
	                '#6db7ce']


	        }]
	    },
	    options: {
	        scales: {
	            xAxes: [{
	                type: 'time',
	                time: {
	               		unit: 'day'
	                	

	                }
	          
	            }],
	             yAxes: [{
	                ticks: {
	                    beginAtZero:true
	                }
	            }]
	        }
	    }
	});
	</script>
</div>


    <?php
// closing the main php function
}
?>