<!-- Responsive Navbar -->
 <nav id="navbar" class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="<?php echo base_url();?>" class="navbar-brand" >
           <img id="navbar-logo" src="<?php echo base_url();?>assets/images/play.png" alt="Logo" height="70" width="70">
      </a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">        
        <ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo base_url();?>player/song_categories"><i class="fa fa-sitemap"></i> Assign categories</a></li>
        <li><a href="<?php echo base_url();?>player/new_category"><i class="fa fa-plus-circle"></i> Create new category</a></li>
    </div>
  </div>
</nav>