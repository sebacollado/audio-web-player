<audio autoplay controls id="audio" hidden>
    <source src="" type="audio/mpeg">
Your browser does not support the audio element.
</audio>

<div class="container" id="loading" hidden>
    <div class="row vertical-center">
        <div class="col-xs-12 text-center">
            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
            <span class="sr-only">Updating songs database</span>
            <h1>Updating songs database</h1>
        </div>
    </div>
</div>

<div class="container" id="index" hidden>
    <div class="row vertical-center">
        <div class="col-xs-12 text-center">
            <h1>Multimedia Web Project</h1>
        </div>
        <div class="col-xs-12 text-center">
            <h3>Audio player based in web technologies</h3>
            <h3>Single Page Application (SPA)</h3>
            <h3>Based on jQuery and PHP with MVC pattern</h3>
            <br>
            <button id="categories-btn" class="btn btn-primary btn-outline btn-lg">Go to categories</button>
        </div>
    </div>
</div>

<div class="container" id="categories" hidden>
    <div class="row">
        <h1>Categories <button id="index-btn" class="btn btn-primary btn-outline">Go to index page</button></h1>
        <hr>
    </div>
    <div class="row">
        <?php
        //Show all the categories
        foreach($categoriesList->result() as $cat){
            $cat = $cat->cat_name;
        ?>
        <a class="category" id="cat-<?php echo $cat;?>">
            <div class="col-xs-6 col-sm-3 col-md-2">
                <div class="card">
                    <div class="card-header"><img src="<?php echo base_url();?>assets/images/categories/icons/<?php echo $cat;?>.png"></div>
                    <div class="card-main"><?php echo ucfirst($cat);?></div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </a>
        <?php
        }
        ?>
    </div>
</div>

<div class="container" id="songs" hidden>
    
</div>