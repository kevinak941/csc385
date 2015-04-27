<div class="container">

<div class="bs-callout bs-callout-info">
    <h4>Top Tags</h4>
    <p>Welcome to the world of EPTA tags. Here you can check out some of the fun stats and figures we've collected about various keywords.</p>
</div>


<h3 class="red-bg"><i class="fa fa-tag"></i> Top Average Price Tags</h3>
<?php if(isset($top_avg) && count($top_avg) > 0) { ?>
<table class="table table-striped">
    <thead>
        <tr>
            <td>Name</td>
            <td>Min</td>
            <td>Max</td>
            <td>Avg</td>
            <td># Of Matches</td>
        </tr>
    </thead>
    <tbody>
<?php foreach($top_avg as $tag) { ?>
    <tr>
        <td><?php echo $tag->value; ?></td>
        <td>$<?php echo $tag->min; ?></td>
        <td>$<?php echo $tag->max; ?></td>
        <td>$<?php echo $tag->avg; ?></td>
        <td><?php echo $tag->numItems; ?></td>
    </tr>
<?php } ?>
    </tbody>
</table>
<?php } else { ?>
<p>No tags to report yet</p>
<?php } ?>


<h3 class="blue-bg"><i class="fa fa-tag"></i> Most Occurring Tags</h3>
<?php if(isset($top_num) && count($top_num)) { ?>
<table class="table table-striped">
    <thead>
        <tr>
            <td>Name</td>
            <td>Min</td>
            <td>Max</td>
            <td>Avg</td>
            <td># Of Matches</td>
        </tr>
    </thead>
    <tbody>
<?php foreach($top_num as $tag) { ?>
    <tr>
        <td><?php echo $tag->value; ?></td>
        <td>$<?php echo $tag->min; ?></td>
        <td>$<?php echo $tag->max; ?></td>
        <td>$<?php echo $tag->avg; ?></td>
        <td><?php echo $tag->numItems; ?></td>
    </tr>
<?php } ?>
    </tbody>
</table>
<?php } else { ?>
<p>No tags to report yet</p>
<?php } ?>


<h3 class="yellow-bg"><i class="fa fa-tag"></i> Tags Containing The Highest Max Values</h3>
<?php if(isset($top_max) && count($top_max)) { ?>
<table class="table table-striped">
    <thead>
        <tr>
            <td>Name</td>
            <td>Min</td>
            <td>Max</td>
            <td>Avg</td>
            <td># Of Matches</td>
        </tr>
    </thead>
    <tbody>
<?php foreach($top_max as $tag) { ?>
    <tr>
        <td><?php echo $tag->value; ?></td>
        <td>$<?php echo $tag->min; ?></td>
        <td>$<?php echo $tag->max; ?></td>
        <td>$<?php echo $tag->avg; ?></td>
        <td><?php echo $tag->numItems; ?></td>
    </tr>
<?php } ?>
    </tbody>
</table>
<?php } else { ?>
<p>No tags to report yet</p>
<?php } ?>


<h3 class="green-bg"><i class="fa fa-tag"></i> Tags Containing The Lowest Min Values</h3>
<?php if(isset($top_min) && count($top_min)) { ?>
<table class="table table-striped">
    <thead>
        <tr>
            <td>Name</td>
            <td>Min</td>
            <td>Max</td>
            <td>Avg</td>
            <td># Of Matches</td>
        </tr>
    </thead>
    <tbody>
<?php foreach($top_min as $tag) { ?>
    <tr>
        <td><?php echo $tag->value; ?></td>
        <td>$<?php echo $tag->min; ?></td>
        <td>$<?php echo $tag->max; ?></td>
        <td>$<?php echo $tag->avg; ?></td>
        <td><?php echo $tag->numItems; ?></td>
    </tr>
<?php } ?>
    </tbody>
</table>
<?php } else { ?>
<p>No tags to report yet</p>
<?php } ?>
</div>