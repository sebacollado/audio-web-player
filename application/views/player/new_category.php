<div class="container">
    <div class="row">
        <h1>Create new category</h1>
        <hr>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <p>Images must be 64x64px and PNG format (for transparency).</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php echo validation_errors(); ?>
            <?php echo form_open_multipart(base_url().'player/new_category'); ?>
            <label>Select icon for the category</label>
            <input type="file" name="icon" size="20" required/>
            <br>
            <label>Category name</label><br>
            <input type="text" name="category" size="20" maxlength="15" required>
            <button type="submit" class="btn btn-default">Save</button>
            </form>
        </div>
    </div>
</div>