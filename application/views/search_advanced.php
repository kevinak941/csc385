
<div class="container">
<div class="bs-callout bs-callout-danger">
<h4>Advanced Search</h4>
<form class="text-center" action="<?php echo base_url('search/byKeyword_results/');?>" method="POST">
    <div class="form-inline">
        <label for="keyword">Keyword</label>
        <input class="form-control" type="text" value="" name="keyword" id="keyword">
        <input class="btn btn-primary" type="submit" value="Search">
    </div>
    <div class="form-group">
        <p>Condition Of Item</p>
        <label>
        <input type="checkbox" name="Condition[]" value="New">
        New
        </label>
        <label>
        <input type="checkbox" name="Condition[]" value="Used">
        Used
        </label>
        <label>
        <input type="checkbox" name="Condition[]" value="Unspecified">
        Unspecified
        </label>
    </div>
    <div class="form-group">
        <label>Specific Sellers (Type usernames separated by commas)</label>
        <input type="text" name="Seller" data-placement="bottom" data-toggle="tooltip" title="Use this field to narrow search results down to only items sold by a specific seller(s)">
    </div>
    <div class="form-group">
        <label>Exclude Sellers (Type usernames separated by commas)</label>
        <input type="text" name="ExcludeSeller">
    </div>
    <div class="form-group">
    <p>Free Shipping</p>
        <label>
        <input type="radio" name="FreeShippingOnly[]" value="false" checked="checked">
        False (Default)
        </label>
        <label>
        <input type="radio" name="FreeShippingOnly[]" value="true">
        True
        </label>
    </div>
    
    <div class="form-group">
        <p>Sold Items Only</p>
        <label>
        <input type="radio" name="SoldItemsOnly[]" value="false" checked="checked">
        False (Default)
        </label>
        <label>
        <input type="radio" name="SoldItemsOnly[]" value="true">
        True
        </label>
    </div>
    
    <div class="form-group">
        <p>Authorized Seller Only</p>
        <label>
        <input type="radio" name="AuthorizedSellerOnly[]" value="false" checked="checked">
        False (Default)
        </label>
        <label>
        <input type="radio" name="AuthorizedSellerOnly[]" value="true">
        True
        </label>
    </div>
    
    <div class="form-group">
        <label>
        <input type="checkbox" name="ListingType[]" value="Auction">
        Auction
        </label>
        <label>
        <input type="checkbox" name="ListingType[]" value="AuctionWithBIN">
        Auction With Buy It Now
        </label>
        <label>
        <input type="checkbox" name="ListingType[]" value="Classified">
        Classified
        </label>
        <label>
        <input type="checkbox" name="ListingType[]" value="FixedPrice">
        Fixed Price
        </label>
    </div>
    
    <div class="form-group form-inline">
        <label>Max Value</label>
        <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
        </div>
        <label>Min Value</label>
        <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
        </div>
    </div>
</form>
</div>
</div>


<script>
    $('[data-toggle="tooltip"]').tooltip();
</script>