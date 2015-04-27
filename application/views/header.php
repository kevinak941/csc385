<!DOCTYPE html>
<html>
    <head>
        <title>Ebay</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url('htdocs/css/main.css');?>">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    </head>
    <body>
        <div id="wrapper" class="container-fluid">
            <header>
                <nav class="navbar navbar-default navbar-fixed-top">
                    <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo base_url();?>">
                            <img src="<?php echo base_url('htdocs/images/epta_logo.png');?>" style="max-width: 90px;">
                        </a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="navbar-collapse collapse" id="main-nav">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="<?php echo base_url();?>">Home <span class="sr-only">(current)</span></a></li>
                            <li><a href="<?php echo base_url('search/byKeyword/');?>">Quick Search</a></li>
                            <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Search <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo base_url('search/byKeyword/');?>">By Keyword</a></li>
                            <li><a href="<?php echo base_url('search/advanced/');?>">Advanced</a></li>
                            </ul>
                            </li>
                            <li><a href="<?php echo base_url('tags');?>">Tags</a></li>
                            <li><a href="<?php echo base_url('api');?>">API</a></li>
                        </ul>
                        <form class="navbar-form navbar-right" role="search" action="<?php echo base_url('search/byKeyword_results/');?>" method="POST">
                            <div class="form-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Keywords">
                            </div>
                            <button type="submit" class="btn btn-default">Search</button>
                        </form>
                    </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                    </nav>
            </header>