<?php
// ajax functions for the admin page showing top landig and leaving page.


add_action( 'admin_footer', 'adminajaxscript' ); // Write our JS below here

function adminajaxscript() { ?>


	<script type="text/javascript" >
		jQuery(document).ready(function($) {
     var txt1 = "<b>I </b>";   
      
                 // Create element with HTML  
	
		var data = {
			'action': 'get_landing_leaving',
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
				jQuery("#Pressjuice_landingpages").append(response);		});
	});
	</script> <?php
}



add_action( 'wp_ajax_get_link_data', 'get_link_data' );

function get_link_data() {

	global $wpdb;
// this adds the prefix which is set by the user upon instillation of wordpress
$table_name = $wpdb->prefix . "pressjuice";

	$page_url = $_POST['page_url_1'];

	$oneday_data_traffic_url = $wpdb->get_results("SELECT HOUR(time) AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 1 DAY AND url = '$page_url') AS T GROUP BY HOUR(time) ");
$oneday_trafic_total_url = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE time >= NOW() - INTERVAL 1 DAY AND url = '$page_url'");
//$total_days = $wpdb->get_var("SELECT COUNT(DISTINCT(DATE(time))) FROM `$table_name` ");
$oneweek_data_traffic_url = $wpdb->get_results("SELECT DATE(time) AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 7 DAY AND url = '$page_url') AS T GROUP BY DATE(time)");
$oneweek_trafic_total_url = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE time >= NOW() - INTERVAL 7 DAY AND url = '$page_url'");
$onemonth_data_traffic_url = $wpdb->get_results("SELECT DATE(time) AS x, COUNT(id) AS y FROM (SELECT * FROM $table_name WHERE time >= NOW() - INTERVAL 30 DAY AND url = '$page_url') AS T GROUP BY DATE(time)");
$onemonth_trafic_total_url = $wpdb->get_var("SELECT COUNT(id) FROM $table_name WHERE time >= NOW() - INTERVAL 30 DAY AND url = '$page_url'");
$oneday_json_url= json_encode($oneday_data_traffic_url);
$oneweek_json_url = json_encode($oneweek_data_traffic_url);
$onemonth_json_url = json_encode($onemonth_data_traffic_url);
function htmldump(){
?>
	<style scoped>
	.tabcontent2{
    display: none;
    padding: 6px 6px;
    border: 1px solid #ccc;
    border-top: none;
}

</style>

<div class="tab">
  <button class="tablinks2" onclick="openTab2(event, 'traffichoururl')" id="defaultOpen1">Traffic in last 24 hours</button>
  <button class="tablinks2" onclick="openTab2(event, 'trafficweekurl')">Traffic in last 7 days</button>
  <button class="tablinks2" onclick="openTab2(event, 'trafficmonthurl')">Traffic in last 30 days</button>
</div>

<div id="traffichoururl" class="tabcontent2">
  	<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
  	<h3>Traffic by hour</h3>
  	<h4>Total Visitors in last 24 hours : <b><?php echo $oneday_trafic_total_url; ?></b></h4>
    <div class="chart-container"><canvas id="hrcharturl" width="500" height="300"></canvas></div>

</div>

<div id="trafficweekurl" class="tabcontent2">
  	<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
  	<h3>Traffic by Days</h3>
   	<h4>Total Visitors in last 7 days : <b><?php echo $oneweek_trafic_total_url; ?></b></h4>
 	<div class="chart-container"><canvas id="daycharturl" width="500" height="300"></canvas></div>

</div>

<div id="trafficmonthurl" class="tabcontent2">
	<span onclick="this.parentElement.style.display='none'" class="topright">&times</span>
	<h3>Traffic by Days</h3>
	<h4>Total Visitors in last 30 days : <b><?php echo $onemonth_trafic_total_url; ?></b></h4>
	<div class="chart-container"><canvas id="monthcharturl" width="500" height="300"></canvas></div>
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
	//document.getElementById("defaultOpen1").click();


		var ctx1url = document.getElementById("hrcharturl");
		var ctx2url = document.getElementById("daycharturl");
		var ctx3url = document.getElementById("monthcharturl");
		var jasononedayurl = <?php echo $oneday_data_traffic_url ?>;
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
			if(jasononedayurl[j]["x"]==i){

				data3[i]["y"]=jasononedayurl[j]["y"];
			if(j<jasononedayurl.length-1){
				j++;
			}
			
			}else{
			data3[i]["y"]=0;
			}
				}
	var myLineChart = new Chart(ctx1url, {
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
		var jasononeweekurl = <?php echo $oneweek_json_url ?>;
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
			jasononeweekurl[j]["x"]=moment(jasononeweekurl[j]["x"]).toDate();
			if(jasononeweekurl[j]["x"].getDate()==data4[i]["x"].getDate()){
				k=2;
				data4[i]["y"]=jasononeweekurl[j]["y"];
			if(j<jasononeweekurl.length-1){
				j++;
			}
			
			}else{
			data4[i]["y"]=0;
			}
		}
	var myLineChart2 = new Chart(ctx2url, {
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
var jasononemonthurl = <?php echo $onemonth_json_url ?>;
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
			jasononemonthurl[j]["x"]=moment(jasononemonthurl[j]["x"]).toDate();
			if(jasononemonthurl[j]["x"].getDate()==data5[i]["x"].getDate()){
				data5[i]["y"]=jasononemonthurl[j]["y"];
			if(j<jasononemonthurl.length-1){
				j++;
			}
			
			}else{
			data5[i]["y"]=0;
			}
		}
	var myLineChart3 = new Chart(ctx3url, {
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
<?php
}
	echo htmldump();
	wp_die(); // this is required to terminate immediately and return a proper response
}



add_action( 'wp_ajax_get_landing_leaving', 'get_landing_leaving' );

function get_landing_leaving() {
	
	function htmldump(){

		global $wpdb; // this is how you get access to the database
	$table_name = $wpdb->prefix . 'pressjuice_cookie';
	$table_name_press = $wpdb->prefix . 'pressjuice';
	$landingpage=$wpdb->get_results("SELECT url AS x, COUNT(sc) AS y FROM (SELECT url, $table_name_press.sessioncookie AS sc FROM $table_name_press INNER JOIN( SELECT sessioncookie, MIN(time) AS mintime FROM `wp2_pressjuice` GROUP BY sessioncookie) T ON T.sessioncookie = wp2_pressjuice.sessioncookie AND T.mintime = $table_name_press.time)AS Y GROUP BY url ORDER BY COUNT(sc) DESC LIMIT 15");

	$leavingpage=$wpdb->get_results("SELECT url AS x, COUNT(sc) AS y FROM (SELECT url, $table_name_press.sessioncookie AS sc FROM $table_name_press INNER JOIN( SELECT sessioncookie, MAX(time) AS mintime FROM `wp2_pressjuice` GROUP BY sessioncookie) T ON T.sessioncookie = wp2_pressjuice.sessioncookie AND T.mintime = $table_name_press.time)AS Y GROUP BY url ORDER BY COUNT(sc) DESC LIMIT 15");
	?>
	<div class="column2">
		<h3>Top Landing Pages</h3>
		<div class="scrolltable">
	<table>
			<tr>
				<th>URL</th>
				<th>Number of Hits</th>
			</tr>
			<?php 
	 foreach ($landingpage as $key1) { ?>
				<tr>
				<td><?php echo $key1->x; ?></td>
					
				<td><?php echo $key1->y; ?></td>
			</tr>
		<?php	}?>
		</table>
		</div>
	</div>
  	<div class="column2">
  		<h3>Most frequent leaving page</h3>
  		<div class="scrolltable">
  		<table>
			<tr>
				<th>URL</th>
				<th>Number of Hits</th>

			</tr>
			<?php 
			foreach ($leavingpage as $key1) { ?>
				<tr>
				<td><?php echo $key1->x; ?></td>
					
				<td><?php echo $key1->y; ?></td>
			</tr>
		<?php	}
			?>
		</table>
	</div>
  	</div>
<?php
    }
    echo htmldump();
	wp_die(); // this is required to terminate immediately and return a proper response
}

?>