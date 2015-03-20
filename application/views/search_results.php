<a href="//<?php echo site_url('search/byKeyword');?>">Back</a>
<h1><i class="fa fa-bars"></i> Search Results</h1>
<div role="tabpanel">
<ul  id="tabs" class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a href="#main" aria-controls="main" role="tab" data-toggle="tab">Main</a>
    </li>
    <li role="presentation">
        <a href="#common" aria-controls="common" role="tab" data-toggle="tab">Common</a>
    </li>
    <li role="presentation">
        <a href="#graphs" aria-controls="graphs" role="tab" data-toggle="tab">Graphs</a>
    </li>
    <li role="presentation">
        <a href="#suggestion" aria-controls="suggestion" role="tab" data-toggle="tab">Suggestion</a>
    </li>
</ul>
<!-- Tabs -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
        <h3>Most Common Name For Your Located Item</h3>
        <p><?php echo $mostCommon['title'];?></p>
        <div class="bs-callout bs-callout-warning">
            <h4>Not your item?</h4>
            <p>If this title does not match what your item is, you may want to try either adding more keywords to the <a href="<?php echo base_url('search/byKeyword');?>">keyword search</a> or refining your <a href="<?php echo base_url('search/advanced');?>">advanced search</a>.</p>
        </div>
        <h3><i class="fa fa-picture-o"></i> Images Matching Your Search</h3>
        <?php if(isset($topImages)&&count($topImages) > 0) { ?>
        <?php foreach($topImages as $src => $image) { ?>
            <img src="<?php echo $src;?>" alt="image">
        <?php } ?>
        <?php } ?>
        <h3><i class="fa fa-bars"></i> Stats About Your Search</h3>
        <table class="table table-striped">
        <tbody>
            <tr>
            <td>Total Items Considered</td><td><?php echo $stats['total']; ?></td>
            </tr>
            <tr>
            <td>Total Remote Items Considered</td><td><?php echo $stats['remote']; ?></td>
            </tr>
            <tr>
            <td>Total Local Items Considered</td><td><?php echo $stats['local']; ?></td>
            </tr>
            <tr>
            <td>Earliest Start Date</td><td><?php echo $stats['firstStartDate'];?></td>
            </tr>
            <tr>
            <td>Lastest Start Date</td><td><?php echo $stats['lastStartDate'];?></td>
            </tr>
        </tbody>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="common">
        <h2>Common Matches</h2>
        <p>Below are various factors that match the item you are searching for. Each list provides the top matches along with the number of items that match. Clicking the view button will display all items in the group.</p>
        <?php if($common) { ?>
            <?php foreach($common as $k => $v) { ?>
                <h3><?php echo ucwords(str_replace('_', ' ', $k)); ?></h3>
                <table class="table table-double-striped">
                <thead>
                    <td>Value</td>
                    <td>Items</td>
                    <td>Max Price</td>
                    <td>Min Price</td>
                    <td>Average Price</td>
                    <td></td>
                </thead>
                <tbody>
                <?php $count = 0; foreach($v as $name => $arr) { ?>
                    <tr class="<?php echo ($count % 2 == 0) ? 'stripe' : '';?>">
                        <td><?php echo $name; ?></td>
                        <td><?php echo $arr['num']; ?></td>
                        <td>$<?php echo number_format($arr['max'],2); ?></td>
                        <td>$<?php echo number_format($arr['min'],2); ?></td>
                        <td>$<?php echo number_format(($arr['total']/$arr['num']),2); ?></td>
                        <td>
                            <input type="hidden" class="itemIds" value="<?php echo implode(', ', $arr['itemIds']);?>">
                            <input type="button" class="lookUpItems btn btn-info" value="View">
                        </td>
                    </tr>
                    <tr style="display:none;" class="displayContainer">
                        <td class="displayItems" colspan="6" style="padding:0;">
                            <div></div>
                        </td>
                    </tr>
                <?php $count++; } ?>
                </tbody>
                </table>
            <?php } ?>
        <?php } else { ?>
            <p>No results found</p>
        <?php } ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="graphs">
        <h2>Graphs</h2>
        <p>Based on the data. NOT COMPLETE</p>
    </div>
    <div role="tabpanel" class="tab-pane" id="suggestion">
        <h2>Suggestion</h2>
        <p>Given the data we obtained, I would recommend doing the following.. NOT COMPLETE</p>
    </div>
</div>
</div>

<script>
    $('.lookUpItems').click(function() {
        var self = $(this);
        var itemIds = $(this).prev('.itemIds').val();
        var parent = self.parent('td').parent('tr').next('tr');
        var container = parent.find('.displayItems>div');
        if(parent.css('display')=='none') {
        $.post('<?php echo base_url('ajax/getItemsById/');?>', {'items':itemIds}, function(data) {
            
            $.each(data, function(i, item) {
                var new_div = $('<div></div>')  .addClass('bs-callout')
                                                .append($('<img/>').css({'max-height':'80px','max-width':'80px'}).attr('src',item.image))
                                                .append($('<a></a>').attr('href', item.site_url).attr('target','_blank').html(item.title+"<br>"+item.currentPrice));
                
                new_div.appendTo(container);
            });
            parent.slideDown();
        }, "JSON");
            self.val('Hide');
        } else {
            
            parent.slideUp();
            container.empty();
            self.val('View');
        }
    });
</script>