<div class="container">

<div class="bs-callout bs-callout-info">
    <h4>Top Tags</h4>
    <p>Welcome to the world of EPTA tags. Here you can check out some of the fun stats and figures we've collected about various keywords.</p>
</div>

<?php if(isset($top_avg)) { ?>
<h3>Top Average Price Tags</h3>
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
<?php } ?>

<?php if(isset($top_num)) { ?>
<h3>Most Occurring Tags</h3>
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
<?php } ?>

<?php if(isset($top_max)) { ?>
<h3>Tags Containing The Highest Max Values</h3>
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
<?php } ?>

<?php if(isset($top_min)) { ?>
<h3>Tags Containing The Lowest Min Values</h3>
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
<?php } ?>
</div>