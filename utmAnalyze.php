<?php
  if (isset($_POST['pressjuice_cookie_duration'])) {
        update_option('pressjuice_cookie_duration', $_POST['pressjuice_cookie_duration']);
     }

?><h1>UTM Analysis</h1>
<p>UTM urls are a great tool to track your marketing and PR campaigns and see where your users come from.</p>
<p>Create UTM urls <a href="https://ga-dev-tools.appspot.com/campaign-url-builder/" target="_blank"> here.</a></p>
<div>
	<style scoped>
body {font-family: Arial;}

/* Style the tab */
.tab {
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
.tabcontent, .tabcontent1 {
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
</style>
<?php
global $wpdb; // this is how you get access to the database
	$table_name_utm = $wpdb->prefix . 'pressjuice_utm';
	$days[0] = 1;
	$days[1] = 7;
	$days[2] = 30;
?>
<div class="tab">
	<button class="tablinks" onclick="openTab(event, 'source')" id="defaultOpen"> UTM Source</button>
	<button class="tablinks target" onclick="openTab(event, 'medium')" > UTM Medium</button>
	<button class="tablinks" onclick="openTab(event, 'campaign')"> UTM Campaign </button>
	<button class="tablinks" onclick="openTab(event, 'term')" id="targetajax">UTM Term</button>
	<button class="tablinks" onclick="openTab(event, 'content')" id="targetajax">UTM Content</button>
	<button class="tablinks" onclick="openTab(event, 'googleAnalytics')" id="targetajax">Google Analutics</button>
</div>
<div id="source" class="tabcontent tab">

	<div class="row"> <?php

		foreach ($days as $key) {
		$utm_source = $wpdb->get_results("SELECT utm_source AS x, COUNT(utm_source) AS y FROM $table_name_utm WHERE utm_source IS NOT NULL AND time >= NOW() - INTERVAL $key DAY GROUP BY utm_source ORDER BY COUNT(utm_source) DESC LIMIT 15");
		?>
		<div class="column">
			<h3>Top UTM Source in <?php echo $key; ?> days.</h3>
			<div class="scrolltable">
				<?php if(!empty($utm_source)) {?>
				<table>
						<tr>
							<th>Source</th>
							<th>Number of Hits</th>
						</tr>
						<?php 
				 foreach ($utm_source as $key1) { ?>
							<tr>
							<td><?php echo $key1->x; ?></td>
								
							<td><?php echo $key1->y; ?></td>
						</tr>
					<?php	}?>

					</table> 
				<?php  }
				else{
					?>
					<h3> Not enough UTM Source data.</h3>
					<?php
				} ?> 
			</div>
			</div><?php	}
				
				?>
	</div>
</div>	
<div id="medium" class="tabcontent tab">

	<div class="row"> <?php

	foreach ($days as $key) {
	$utm_medium = $wpdb->get_results("SELECT utm_medium AS x, COUNT(utm_medium) AS y FROM $table_name_utm WHERE utm_medium IS NOT NULL AND time >= NOW() - INTERVAL $key DAY GROUP BY utm_medium ORDER BY COUNT(utm_medium) DESC LIMIT 15");
	?>
	<div class="column">
			<h3>Top UTM Medium in <?php echo $key; ?> days.</h3>
			<div class="scrolltable">
			<?php if(!empty($utm_medium)) {?>

		<table>
				<tr>
					<th>Medium</th>
					<th>Number of Hits</th>
				</tr>
				<?php 
		 foreach ($utm_medium as $key1) { ?>
					<tr>
					<td><?php echo $key1->x; ?></td>
						
					<td><?php echo $key1->y; ?></td>
				</tr>
			<?php	}?>

			</table> 
			<?php  }
		else{
			?>
			<h3> Not enough UTM Medium data.</h3>
			<?php
		} ?> 
			</div>
			</div><?php	}
			
			?></div>
</div>
<div id="campaign" class="tabcontent tab">

	<div class="row"> <?php

	foreach ($days as $key) {
	$utm_campaign = $wpdb->get_results("SELECT utm_campaign AS x, COUNT(utm_campaign) AS y FROM $table_name_utm WHERE utm_campaign IS NOT NULL AND time >= NOW() - INTERVAL $key DAY GROUP BY utm_campaign ORDER BY COUNT(utm_campaign) DESC LIMIT 15");
	?>
	<div class="column">
			<h3>Top UTM Campaign in <?php echo $key; ?> days.</h3>
			<div class="scrolltable">
		<?php if(!empty($utm_campaign)) {?>

		<table>
				<tr>
					<th>Campaign</th>
					<th>Number of Hits</th>
				</tr>
				<?php 
		 foreach ($utm_campaign as $key1) { ?>
					<tr>
					<td><?php echo $key1->x; ?></td>
						
					<td><?php echo $key1->y; ?></td>
				</tr>
			<?php	}?>

			</table> 
			<?php  }
		else{
			?>
			<h3> Not enough UTM Campaign data.</h3>
			<?php
		} ?> 
			</div>
			</div><?php	}
			
			?></div>
</div>
<div id="term" class="tabcontent tab">

	<div class="row"> <?php

	foreach ($days as $key) {
	$utm_term = $wpdb->get_results("SELECT utm_term AS x, COUNT(utm_term) AS y FROM $table_name_utm WHERE utm_term IS NOT NULL AND time >= NOW() - INTERVAL $key DAY GROUP BY utm_term ORDER BY COUNT(utm_term) DESC LIMIT 15");
	?>
	<div class="column">
			<h3>Top UTM Term in <?php echo $key; ?> days.</h3>
			<div class="scrolltable">
			<?php if(!empty($utm_term)) {?>

		<table>
				<tr>
					<th>Term</th>
					<th>Number of Hits</th>
				</tr>
				<?php 
		 foreach ($utm_term as $key1) { ?>
					<tr>
					<td><?php echo $key1->x; ?></td>
						
					<td><?php echo $key1->y; ?></td>
				</tr>
			<?php	}?>

			</table> 
			<?php  }
		else{
			?>
			<h3> Not enough UTM term data.</h3>
			<?php
		} ?> 
			</div>
			</div><?php	}
			
			?></div>
</div>
<div id="content" class="tabcontent tab">

	<div class="row"> <?php

	foreach ($days as $key) {
	$utm_content = $wpdb->get_results("SELECT utm_content AS x, COUNT(utm_content) AS y FROM $table_name_utm WHERE utm_content IS NOT NULL AND time >= NOW() - INTERVAL $key DAY GROUP BY utm_content ORDER BY COUNT(utm_content) DESC LIMIT 15");
	?>
	<div class="column">
			<h3>Top UTM Content in <?php echo $key; ?> days.</h3>
			<div class="scrolltable">
			<?php if(!empty($utm_content)) {?>

		<table>
				<tr>
					<th>content</th>
					<th>Number of Hits</th>
				</tr>
				<?php 
		 foreach ($utm_content as $key1) { ?>
					<tr>
					<td><?php echo $key1->x; ?></td>
						
					<td><?php echo $key1->y; ?></td>
				</tr>
			<?php	}?>

			</table> 
			<?php  }
		else{
			?>
			<h3> Not enough UTM Content data.</h3>
			<?php
		} ?> 
			</div>
			</div><?php	}
			
			?></div>
</div>
<div id="googleAnalytics" class="tabcontent tab">
	<form method="POST">
	<textarea name="pressjuice_cookie_duration" rows="10" cols="150" ><?php echo esc_attr(get_option('pressjuice_cookie_duration') ); ?></textarea>
	<input type="submit" value="Upload code" class="button">
	</form>
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
// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

<?php
?>