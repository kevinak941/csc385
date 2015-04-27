<style>
.graph-wrapper{float:left;width:50%;text-align:center;margin:0 0 20px;}
.graph-bar{height:350px;width:400px;margin:0 auto;}

</style>
<h3>Price Ranges Over Common Values</h3>
<p>The following graphs will show you minimum, maximum, and average values over common data types (Listing Type, Title, Shipping Type, etc.)</p>
<?php if(isset($common['listing_type'])) { ?>
<div class="graph-wrapper">
    <h6>Over Listing Types (<?php echo count($common['listing_type']); ?>)</h6>
    <div id="bar-listing_type-chart" class="graph-bar"></div>
</div>
<?php } ?>
<?php if(isset($common['condition'])) { ?>
<div class="graph-wrapper">
    <h6>Over Item Conditions (<?php echo count($common['condition']); ?>)</h6>
    <div id="bar-condition-chart" class="graph-bar"></div>
</div>
<?php } ?>
<div style="clear:both;"></div>
<?php if(isset($common['selling_state'])) { ?>
<div class="graph-wrapper">
    <h6>Over Final Selling State (<?php echo count($common['selling_state']); ?>)</h6>
    <div id="bar-selling_state-chart" class="graph-bar"></div>
</div>
<?php } ?>
<?php if(isset($common['buy_it_now_price'])) { ?>
<div class="graph-wrapper">
    <h6>Over Buy It Now Price (<?php echo count($common['buy_it_now_price']); ?>)</h6>
    <div id="bar-buy_it_now_price-chart" class="graph-bar"></div>
</div>
<?php } ?>
<div style="clear:both;"></div>
<?php if(isset($common['ship_to_location'])) { ?>
<div class="graph-wrapper">
    <h6>Over Ship To Locations (<?php echo count($common['ship_to_location']); ?>)</h6>
    <div id="bar-ship_to_location-chart" class="graph-bar"></div>
</div>
<?php } ?>
<?php if(isset($common['top_rated_listing'])) { ?>
<div class="graph-wrapper">
    <h6>Top Rated Vs Normal (<?php echo count($common['top_rated_listing']); ?>)</h6>
    <div id="bar-top_rated_listing-chart" class="graph-bar"></div>
</div>
<?php } ?>
<div style="clear:both;"></div>
<?php if(isset($common['shipping_service_cost'])) { ?>
<div class="graph-wrapper">
    <h6>Over Shipping Cost (<?php echo count($common['shipping_service_cost']); ?>)</h6>
    <div id="bar-shipping_service_cost-chart" class="graph-bar"></div>
</div>
<?php } ?>

<script>
    var dataArr;
    <?php 
    function buildBar($ctype, $common){
        echo "var dataArr".$ctype." = [['', 'Min', 'Max', 'Avg'],"; 
        $count = 0; 
        foreach($common[$ctype] as $key=> $type) {
            echo "['".$key."', ".$type['min'].", ".$type['max'].", ".number_format(($type['total']/$type['num']), 2)."]";
            if($count != count($common[$ctype])-1) echo ','; 
            else echo '];';
            $count++;
        } 
        
        echo "google.setOnLoadCallback(function() {drawTitleSubtitle('bar-".$ctype."-chart', dataArr".$ctype.")});";
    }
    ?>
    /* GRAPHS */
    google.load("visualization", "1", {packages:["corechart", "bar"]});
    
    //var dataArr = [['Year', 'New With Tags', 'New'],    ['15', 12.00, 50.00],['15', 15.00,70.00],['15', 12.00],['15', 14.00, 0],['15', 14.00, 0],['15', 9.00, 128.00],['15', 16.00, 0],['15', 18.00, 0]];
    <?php buildBar('listing_type', $common); ?>
    <?php buildBar('condition', $common); ?>
    <?php buildBar('selling_state', $common); ?>
    <?php if(isset($common['buy_it_now_price'])) buildBar('buy_it_now_price', $common); ?>
    <?php if(isset($common['ship_to_location'])) buildBar('ship_to_location', $common); ?>
    <?php if(isset($common['top_rated_listing'])) buildBar('top_rated_listing', $common); ?>
    <?php if(isset($common['shipping_service_cost'])) buildBar('shipping_service_cost', $common); ?>
function drawTitleSubtitle(id, data) {
      var data = google.visualization.arrayToDataTable(data);

      var options = {
        height:300,
        width:400,
        hAxis: {
          title: 'Total Population',
          minValue: 0,
        },
        vAxis: {
          title: 'City'
        },
        bars: 'vertical'
      };
      var material = new google.charts.Bar(document.getElementById(id));
      material.draw(data, options);
    }
</script>