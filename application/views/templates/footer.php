    <footer class="footer">
        <div class="container">
            <div class="row" id="player-controls">
                <div class="col-xs-5">
                    <div class="song-playing">
                        <div id="current-song"></div>
                        <div id="current-artist"></div>
                    </div>
                </div>
                <div class="col-xs-7">
                    <div class="pull-right" id="player-buttons" hidden>
                        <div id="prev" class="controls"><i class="fa fa-step-backward  fa-3x"></i></div>
                        <div id="play" class="controls"><i class="fa fa-play-circle  fa-3x"></i></div>
                        <div id="next" class="controls"><i class="fa fa-step-forward  fa-3x"></i></div>
                        <div id="rand" class="controls"><i class="fa fa-random fa-2x"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- =================================================
                        JavaScript
    ================================================== -->
    
    <!-- jQuery 2.1.4 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    
    <!-- Audio Web Player (Collado Team) v0.0.1 -->
    
    <script>
    $(document).ready(function(){
        
        /********************************************
        * *******************************************
        *         SINGLE PAGE APPLICATION           *
        * *******************************************
        ********************************************/
        
        //Load functionality only on index
        var pathname = window.location.pathname;
        if(pathname == '/mwp/' || pathname == '/' || '/audio-web-player-multimedia/'){
            
            //This is required to use live event (for ajax response interaction)
            jQuery.fn.extend({
                live: function (event, callback) {
                   if (this.selector) {
                        jQuery(document).on(event, this.selector, callback);
                    }
                }
            });
            
            /*
             * INDEX PAGE ON LOAD
             */
            $('body').addClass('bgimg');
            $('#loading').show();
            $('#categories-btn').hide();
            $('#player-controls').hide();
            
            /*
             * 
             */
            $('#navbar').on('click', function() {
                return confirm('This action will stop music!');
            });

            /*
            * SONGS DATABASE UPDATE ON LOAD
            */
            $.ajax({
                //petition url
                url : 'player/song_update',
                //type: get/post
                type : 'POST',
                // code to execute if sucess
                success : function(result) {
                    $('#loading').hide();
                    $('#index').show();
                    $('#categories').hide();
                    $('#songs').hide();
                    $('#categories-btn').show();
                    $('#player-controls').show();
                    if(result != '') toastr.success(result,'Success');
                },
                // code to execute if error
                // xhr: petition object
                // status: error code status
                error : function(xhr, status) {
                },
                //code to execute always
                complete : function(xhr, status) {
                }
            });

            /*
             * BUTTONS SPA PLAYER
             */
            $('#index-btn').on('click', function() {
                $('body').addClass('bgimg');
                $('#index').show();
                $('#categories').hide();
                $('#songs').hide();
            });
            $('#categories-btn').live('click', function() {
                $('body').removeClass('bgimg');
                $('#index').hide();
                $('#categories').show();
                $('#songs').hide();

                var width = $(".card").css("width");
                $(".card").css("height", width);
            });
            $('.category').on('click', function() {
                songsUpdate(this.id);
                $('body').removeClass('bgimg');
                $('#index').hide();
                $('#categories').hide();
                $('#songs').show();
            });

            //Card class always proportional
            $(window).resize(function() {
                var width = $(".card").css("width");
                $(".card").css("height", width);
            });


            /********************************************
            * *******************************************
            *              PLAYER ACTIONS               *
            * *******************************************
            ********************************************/

            /*
             * PLAY SONG ON CLICK
             */
            $('#musiclist li').live('click', function() {
                //Remove selected class
                $('#player-buttons').show();
                removeSelectedClass();

                //Delete first character (BLANK SPACE)
                var song = $(this).text().substr(1);
                $("#audio").attr('src', '<?php echo base_url();?>music/'+song);

                //Upload current song
                updateArtistTitle(song);

                //RELOAD AUDIO
                $('#audio').load();
                $('#play i').removeClass('fa-play-circle');
                $('#play i').addClass('fa-pause');

                //Add selected class
                addSelectedClass();
            });

            /*
             * PLAY/PAUSE BUTTON
             */
            $('#play').on('click', function() {

                if($("#audio").attr('src') != null){ //If song selected
                    if($('#audio').get(0).paused){
                        $('#play i').removeClass('fa-play-circle');
                        $('#play i').addClass('fa-pause');
                        $('#audio').get(0).play()
                    }else{
                        $('#play i').removeClass('fa-pause');
                        $('#play i').addClass('fa-play-circle');
                        $('#audio').get(0).pause();
                    }
                }else{ //No song selected

                    //Remove selected class
                    removeSelectedClass();

                    //Select first song from the list
                    var song = $.trim($('#musiclist li:nth-child(1)').text());
                    $("#audio").attr('src', '<?php echo base_url();?>music/'+song);

                    //Upload current song
                    updateArtistTitle(song);

                    $('#play i').removeClass('fa-play-circle');
                    $('#play i').addClass('fa-pause');

                    //Add selected class
                    addSelectedClass();
                }
            });

            /*
             * PLAY NEXT SONG FROM LIST
             */
            $("#audio").bind('ended',function(){
                var act = 'next';
                changeSong(act);
            });

            /*
             * NEXT BUTTON
             */
            $('#next').on('click', function() {
                var act = 'next';
                changeSong(act);
            });

            /*
             * PREV BUTTON
             */
            $('#prev').on('click', function() {
                var act = 'prev';
                changeSong(act);
            });

            /*
             * RANDOM SONG BUTTON
             */
             $('#rand').on('click', function() {
                if($(this).hasClass('selected-btn')){
                    $(this).removeClass('selected-btn');
                }else{
                    $(this).addClass('selected-btn');
                }
            });
        }
    });
    
    
    /********************************************
    * *******************************************
    *                FUNCTIONS                  *
    * *******************************************
    ********************************************/
    
    function updateArtistTitle(song){
        var artist = song.substring(0,song.indexOf('-')).replace('-','');
        var title = song.substring(song.indexOf('-'),song.indexOf('.')).replace('-','');
        $('#current-artist').html(artist);
        $('#current-song').html(title);
    }
    function addSelectedClass(){
        $('#musiclist li:contains('+$('#current-song').text()+')').addClass('selected');
    }
    function removeSelectedClass(){
        $('#musiclist li:contains('+$('#current-song').text()+')').removeClass('selected');
    }
    function changeSong(act){
        $('#player-buttons').show();
        //Remove selected class
        removeSelectedClass();

        var currentIndex = $('#musiclist li:contains('+$('#current-song').text()+')').index();
        
        //Next and previous buttons
        if(act == 'prev') var nextIndex = currentIndex; //next song
        if(act == 'next') var nextIndex = currentIndex+2; //next song
        
        //Random song selected
        if($('#rand').hasClass('selected-btn')){
            random = nextRandom();
            act = random;
            if(act !== 'end') var nextIndex = act;
            else return false;
        }
        
        var song = $.trim($('#musiclist li:nth-child('+nextIndex+')').text());

        //Upload current song
        updateArtistTitle(song);
        $("#audio").attr('src', '<?php echo base_url();?>music/'+song);

        $('#play i').removeClass('fa-play-circle');
        $('#play i').addClass('fa-pause');

        //Add selected class
        addSelectedClass();
    }
    function nextRandom(){
        
        var random = null;
        var maxid = $('#musiclist li:last-child').attr('id');
        var it = 0;
        
        while(it <= maxid){
            random = Math.round(Math.random()*(maxid-0)+parseInt(0));
            if(localStorage.getItem(random) == null){ //If random not played
                localStorage.setItem(random,random);
                return random;
            }
            it++;
        }
        
        localStorage.clear();
        return 'end'; //all playlist played
    }
    
    function songsUpdate(category){
        /*
         * LOADING SONG LIST
         */
        $.ajax({
            //petition url
            url : 'player/song_list',
             //type: get/post
            type : 'POST',
            //post data send
            data: { 
                category: category
            },
            // code to execute if sucess
            success : function(result) {
                $('#songs').html(result);
            },
            // code to execute if error
            // xhr: petition object
            // status: error code status
            error : function(xhr, status) {
            },
            //code to execute always
            complete : function(xhr, status) {
            }
        });
    }
    
    </script>
    
    <!-- Bootstrap 3.3.6 -->
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    
    
    <!-- toastr -->
    <script src="<?php echo base_url();?>assets/js/toastr.min.js"></script>
       
    <!-- toastr configuration -->
	<script>
	$(document).ready(function() {
		//On page load
        toastr.options = {
         "closeButton": false,
         "debug": false,
         "newestOnTop": true,
         "progressBar": true,
         "positionClass": "toast-bottom-right",
         "preventDuplicates": false,
         "onclick": null,
         "showDuration": "300",
         "hideDuration": "1000",
         "timeOut": "5000",
         "extendedTimeOut": "1000",
         "showEasing": "swing",
         "hideEasing": "linear",
         "showMethod": "fadeIn",
         "hideMethod": "fadeOut"
        }
        <?php
            //Show page sucess
            if ($this->session->flashdata('success') == TRUE){
                echo "toastr.success('".$this->session->flashdata('success')."','Success');";
            }
            //Show page info
            if ($this->session->flashdata('info') == TRUE){
                echo "toastr.info('".$this->session->flashdata('info')."','Info');";
            }
            //Show page warning
            if ($this->session->flashdata('warning') == TRUE){
                echo "toastr.warning('".$this->session->flashdata('warning')."','Warning');";
            }
            //Show page error
            if ($this->session->flashdata('error') == TRUE){
                echo "toastr.error('".$this->session->flashdata('error')."','Error');";
            }
        ?>
	});
	</script>
    
    </body>
</html>

