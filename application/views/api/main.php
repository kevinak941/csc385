<h1>EPTA API Reference v0.5</h1>

<div style="position:fixed;left:0;top:0;bottom:0;margin:110px 20px 10px;width:100px;">
    <div>
        <a href="#api_calls">API Calls</a>
    </div>
    <div>
        <a href="#data_formats">Data Formats</a>
    </div>
    <div>
        <a href="#get_data">Get Data</a>
        <ul>
            <li><a href="#getTags">Tags</a></li>
            <li><a href="#getItems">Items</a></li>
            <li><a href="#getCategories">Ctegories</a></li>
        </ul>
    </div>
</div>
<div style="margin-left:100px;">
<div class="container">
    <h2 id="api_calls">API Calls</h2>
    <p>The EPTA API using a RESTful API design. Thus, you may use standard HTTP methods to retrieve and modify resources. An example would be the following:</p>
    <pre>GET <?php echo base_url('api/method/parameters');?></pre>

    <h2 id="data_formats">Data Formats</h2>
    <p>Resources in EPTA API are returned using JSON data formats. An example follows:</p>
    <pre>
        {
            "id": "345",
            "value": "Test",
            "numItems": "123"
        }
    </pre>


    <h2 id="get_data">Get Data</h2>

    <p>The following are options for selecting EPTA Database Data.</p>

    <h3 id="getTags">getTags</h3>
    <p>Used to retrieve all tags collected by EPTA.</p>
    <pre>GET <?php echo base_url('api/getTags/');?></pre>
    <p>This call returns an array of JSON objects. Each object being a tag in our system.</p>
    <pre>
        [
            {
                "id":"1",
                "value": "LEGO",
                "numItems": "10"
            }
        ]
    </pre>
    <div class="bs-callout">
    <table class="table table-striped">
        <h4>Properties</h4>
        <thead>
            <tr><th>Name</th><th>Value</th></tr>
        </thead>
        <tbody>
            <tr><td>id</td><td>Internal ID assigned to tag</td></tr>
            <tr><td>value</td><td>Keyword word of the tags</td></tr>
            <tr><td>numItems</td><td>Number of items matching this keyword</td></tr>
        </tbody>
    </table>
    </div>
    
    <div class="bs-callout bs-callout-info">
        <h4>getTags - Optional Parameter</h4>
        <p>Additionally, you may provide a keyword as part of the getTags call to retrieve only tags similar to the keyword.
        <pre>GET <?php echo base_url('api/getTags/keyword');?></pre>
        </p>
    </div>
    
    <h3>getTagsByItemId</h3>
    <p>This call returns all tags linked to a specific internally used item id</p>
    <pre>GET <?php echo base_url('getTagsByItemId/&lt;item_id&gt;');?></pre>
    
    <h3>getTagsByEbayId</h3>
    <p>This call returns all tags linked to a specific ebay item id</p>
    <pre>GET <?php echo base_url('api/getTagsByEbayId/&lt;ebay_item_id&gt;');?></pre>
    
    <h3 id="getItems">getItems</h3>
    <p>Used to retrieve all items collected by EPTA. As of this version only Ebay Items are supported.</p>
    <pre>GET <?php echo base_url('api/getItems');?></pre>
    
    <h3 id="getCategories">getCategories</h3>
    <p>Used to retrieve all categories collected by EPTA. As of this version only Ebay Categories are supported.</p>
    <pre>GET <?php echo base_url('api/getCategories');?></pre>
</div>
</div>