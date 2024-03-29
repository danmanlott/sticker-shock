<?php require 'application/views/layouts/header.php'; ?>
<div class="container">
    <div class="h1">Edit Item<hr></div>
    <form enctype="multipart/form-data" action="/items/updateitem/<?php echo $item->item_id;?>/<?php echo $item->available;?>" method="POST">
        <div class="form-group row">
            <div class="col-md-6">
                <input required value="<?php echo $item->item_name;?>" maxlength="30" class="form-control" type="text" name="title">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <input required value="<?php echo $item->size;?>" maxlength="6" class="form-control" type="text" name="size">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <input required value="<?php echo $item->price;?>" maxlength="8" class="form-control" type="Number" step="any" name="price">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <input required value="<?php echo $item->shipping;?>" maxlength="8" class="form-control" type="Number" step="any" name="shipping">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <select required class="form-control" name="category">
                    <option selected disabled>Category</option>
                    <?php foreach($arr as $str): ?>
                    <option value=<?php echo ucwords($str); ?>><?php echo ucwords($str);?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <textarea class="form-control" maxlength="300" name="description" rows="6"><?php echo $item->description;?></textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <button type="submit" class="btn btn-bw" name="submit">Submit</button>
            </div>
        </div>
    </form>
</div>
<?php require 'application/views/layouts/footer.php'; ?>