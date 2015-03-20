
<div class="container text-left">
<div class="bs-callout bs-callout-info">
    <h4>Search By Keyword</h4>
    <p>This is one of the fastest ways to search using the EPTA. However, the more specific you are with your keywords, the more likely you are to find the items you want.</p>
    <form class="form-inline text-center" action="<?php echo base_url('search/byKeyword_results/'.$isLocal);?>" method="POST">
        <div class="form-group">
            <label for="keyword">Keyword</label>
            <input class="form-control" type="text" value="" name="keyword" id="keyword">
            <input class="btn btn-primary" type="submit" value="Search">
        </div>
    </form>
    
</div>
<div class="bs-callout bs-callout-warning">
<div class="row">
        <div class="col-md-6">
            <h4>Poor Examples</h4>
            <table class="table table-striped">
                <tbody>
                    <tr>
                    <td>Harry Potter</td>
                    <td>Will return hundred of items pertaining to Harry Potter. What is it? A dvd, magizine, toy, game, broomstick or even a magical edible beans. I have no idea what you are looking for!</td>
                    </tr>
                    <tr>
                    <td>Doctor Who Dvd</td>
                    <td>Well, which DVD do you mean? Season 1, 2, 3, 4, 5, 6, 7, 8 .. 26? Or did you mean the modern doctor starting after 2005. Maybe you want the Christmas special on DVD? If you can think of three different items that match your keyword, then I can find 50. Please provide some indication of which item it would be.</td>
                    </tr>
                </tbody>

            </table>
        </div>
        <div class="col-md-6">
            <h4>Good Examples</h4>
            <table class="table table-striped">
                <tbody>
                    <tr>
                    <td>harry potter POWERCaster SpellCasting Playset</td>
                    <td>Great, you described the type and now I know exactly what you mean. Here I thought you were looking for a broom before.</td>
                    </tr>
                    <tr>
                    <td>doctor who Christmas special dvd 2010</td>
                    <td>That was a good! I know exactly what you are talking about now. Providing the title of the individual dvd or even a year helps me target down your item.</td>
                    </tr>
                </tbody>
            </table>    
        </div>
    </div>
</div>
<?php if($pastSearches) { ?>
<div class="bs-callout bs-callout-green">
    <h4>Most Recent Searches</h4>
    <table class="table table-striped">
        <tbody>
        <?php foreach($pastSearches as $search) { ?>
            <tr>
            <td><?php echo $search['value'];?></td>
            <td class="text-right"><?php echo date_format(date_create($search['dbCreatedOn']), 'm/d/y h:i');?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>
</div>