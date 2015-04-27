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
    <?php if($removedItems) { ?>
    <li role="presentation">
        <a href="#removed" aria-controls="removed" role="tab" data-toggle="tab">Removed Items (<?php echo count($removedItems); ?>)</a>
    </li>
    <?php } ?>
    <?php if($allItems) { ?>
    <li role="presentation">
        <a href="#all" aria-controls="all" role="tab" data-toggle="tab">All Items (<?php echo count($allItems); ?>)</a>
    </li>
    <?php } ?>
    <li role="presentation">
        <a href="#refine" aria-controls="refine" role="tab" data-toggle="tab">Refine</a>
    </li>
</ul>
<!-- Tabs -->
<div class="tab-content container">
    <div role="tabpanel" class="tab-pane active" id="main">
        <?php if(isset($mostCommon['title'])) { ?>
            <h3>Most Common Name For Your Located Item</h3>
            <p><?php echo $mostCommon['title'];?></p>
        <?php } ?>
        
        <div class="bs-callout bs-callout-warning">
            <h4>Not your item?</h4>
            <p>If this title does not match what your item is, you may want to try either adding more keywords to the <a href="<?php echo base_url('search/byKeyword');?>">keyword search</a> or refining your <a href="<?php echo base_url('search/advanced');?>">advanced search</a>.</p>
        </div>
        
        <?php if(isset($topImages)&&$topImages!=false) { ?>
        <h3><i class="fa fa-picture-o"></i> Images Matching Your Search</h3>
        <?php foreach($topImages as $src => $image) { ?>
            <img src="<?php echo $src;?>" alt="image">
        <?php } ?>
        <?php } ?>
        
        <h3><i class="fa fa-bars"></i> Stats About Your Search</h3>
        <table class="table table-striped">
        <tbody>
            <tr>
            <td>Search Time Elapsed</td><td><?php echo $time;?></td>
            </tr>
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
            <td>Total Items Removed By Title Matching</td><td><?php echo $stats['removed']; ?></td>
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
        <div class="bs-callout bs-callout-info">
            <p>Graphical output of various data components</p>
        </div>

        <?php echo $this->load->view('sections/graphOutput', array('common'=>$common)); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="suggestion">
        <h2>Suggestion</h2>
        <div class="bs-callout bs-callout-warning">
            <p>Given the data we obtained, I would recommend doing the following.. </p>
            <p>This is where advanced logical output will be placed to determine optimal pricing</p>
        </div>
    </div>
    
    <?php if($removedItems) { ?>
    <div role="tabpanel" class="tab-pane" id="removed">
        <h2>Removed Items (<?php echo count($removedItems); ?>)</h2>
        <div class="bs-callout bs-callout-danger">
            <p>Below are all of the items that were removed from your search.</p>
            <p>None of the following items were used in the price calculations</p>
        </div>
        <?php if(isset($removedItems)) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>eBay Item Id</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
            <?php foreach($removedItems as $key => $item) { ?>
                <tr>
                    <td><img src="<?php echo $item->galleryURL; ?>"></td>
                    <td><?php echo $item->itemId; ?></td>
                    <td><a href="<?php echo $item->viewItemURL; ?>"><?php echo $item->title; ?></a></td>
                </tr>
            <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <?php } ?>
    
    <?php if($allItems) { ?>
    <div role="tabpanel" class="tab-pane" id="all">
        <h2>All Items Found With Your Search (<?php echo count($allItems); ?>)</h2>
        <?php if(isset($allItems)) { ?>
            <table id="all_items_table" class="table table-double-striped">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Condition</th>
                        <th>Current Price</th>
                        <th>Sold</th>
                        <th>Duration</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
            <?php $count = 0; foreach($allItems as $key => $item) { ?>
                <tr class="<?php echo ($count % 2 == 0) ? 'stripe' : '';?>">
                    <td><img src="<?php echo $item->galleryURL; ?>"></td>
                    <td><a href="<?php echo $item->viewItemURL; ?>"><?php echo $item->title; ?></a></td>
                    <td><?php echo $item->listingInfo->listingType;?></td>
                    <td><?php echo $item->condition->conditionDisplayName; ?></td>
                    <td>$<?php echo number_format((double)$item->sellingStatus->currentPrice,2);?></td>
                    <td><?php echo $item->sellingStatus->sellingState;?></td>
                    <td><?php echo date('H:i:s', (strtotime($item->listingInfo->endTime) - strtotime($item->listingInfo->startTime))); ?></td>
                    <td><a class="show-more-details btn btn-primary">More</a></td>
                </tr>
                <tr style="display:none;">
                    <td>Category: <?php echo $item->primaryCategory->categoryName; ?></td>
                    <td>Item Id: <?php echo $item->itemId; ?></td>
                    <td>Returns Accepted: <?php echo $item->returnsAccepted; ?></td>
                    <td>Best Offer Enabled: <?php echo $item->listingInfo->bestOfferEnabled; ?> </td>
                    <td>Buy It Now Available: <?php echo $item->listingInfo->buyItNowAvailable; ?></td>
                    <td>Is Gift: <?php echo $item->listingInfo->gift; ?></td>
                    <td>
                    <td></td>
                </tr>
            <?php $count++; } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <?php } ?>
    
    <div role="tabpanel" class="tab-pane" id="refine">
        <h2>Refine Your Search</h2>
        <div class="bs-callout bs-callout-green">
            <p>Check all of the tags that accurately describe your item. We'll try to more accurately predict your item.</p>
        </div>
        <?php if(isset($topTags)) { ?>
        <form class="form-inline text-center" action="<?php echo base_url('search/byKeyword_results/');?>" method="POST">
        <div style="max-height:300px;overflow:auto;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Is Outlier</th>
                    </tr>
                </thead>
                <tbody>
            <?php foreach($topTags as $key => $tag) { ?>
                <tr>
                    <td><input type="checkbox" <?php echo ($key != "" && strpos(strtolower($keyword), $key) > -1) ? 'checked="checked"' : ''; ?>></td>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $tag; ?></td>
                    <td><?php echo ($tag > 1) ? "False" : "True"; ?></td>
                </tr>
            <?php } ?>
                </tbody>
            </table>
        </div>
        <input type="submit" class="btn btn-primary" value="Refine Search">
        </form>
        <?php } ?>
    </div>
</div>
</div>

<script>
    $('.show-more-details').click(function(e) {
        e.preventDefault();
        var $info = $(this).parent('td').parent('tr').next('tr');
        $info.toggle();
    });
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