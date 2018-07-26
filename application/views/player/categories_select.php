<div class="container">
    <div class="row">
        <h1>Songs without category</h1>
        <hr>
    </div>
    <?php echo validation_errors(); ?>
    <?php echo form_open(base_url().'player/song_categories'); ?>
        <div class="row">

            <?php
            if($songs_nocat->result() == null){
                echo "<h3>No songs without category</h3>";
            }
            foreach($songs_nocat->result() as $song){
            ?>
                <li class='song-list'><?php echo $song->song_name; ?>
                    <select name="<?php echo $song->rowid; ?>">
                        <option value=""></option>
                        <?php
                        foreach($categoriesList->result() as $category){
                        ?>
                            <option value="<?php echo $category->cat_name; ?>"><?php echo $category->cat_name; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </li>
            <?php
            }
            ?>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-default">Save</button>
            </div>
        </div>
    </form>
</div>