<?php

class Items extends Controller {
    
    public $id = null;
    public $category = null;
    public $subcategory = null;
    public $search = null;

    public function index($category = null, $args = null) {
        require 'application/models/User.php';
        $user = new User($this->db);
        # Graham L.:
        # The following if/else clusterfuck is the simplest way I could come up
        # with to implement categories/subcategories.
        # If the category/subcategory doesn't exist, it redirects to the 
        # generic error page. This behavior could be improved by going to a
        # page with all the categories or something along those lines.

        if (isset($_GET['search'])) {
           $this->search = $_GET['search'];
        }

        if (isset($category)) {
            if (! $this->category = Category::isValid($category)) {
                header('location: /pages/error');
                return;
            }

            if (isset($args[0])) {
                if (! $this->subcategory = Subcategory::isValid($args[0])) {
                    header('location: /pages/error');
                    return;
                }

                $this->title = ucwords("$this->category - $this->subcategory");
            } else {
                $this->title = ucfirst("$this->category");
            }
        } else {
            $this->title="Browse";
        }

        $items = $this->model->readAllItems($this->category, $this->subcategory, $this->search);
        require 'application/views/items/index.php';
    }

    public function item($id) {
        require 'application/models/User.php';
        $user = new User($this->db);
        $item = $this->model->readItem($id);
        if (!$item) {
            header('location: /pages/error');
            return;
        }

        $this->title = $item->item_name;


        require 'application/class/Review.php';

        $reviews = array();

        if ($item->reviewers != null) {
            $reviewers = explode(',', $item->reviewers);
            $ratings = explode(',', $item->ratings);
            $comments = explode('----', $item->comments);
            $review_dates = explode(',', $item->review_dates);
            $review_titles = explode(',', $item->review_titles);


            if (sizeof($reviewers) != sizeof($ratings) || sizeof($reviewers) != sizeof($comments) || sizeof($reviewers) != sizeof($review_dates)) {
                header('location: /pages/error');
            }

            $iterator = new MultipleIterator;
            $iterator->attachIterator(new ArrayIterator($reviewers));
            $iterator->attachIterator(new ArrayIterator($ratings));
            $iterator->attachIterator(new ArrayIterator($comments));
            $iterator->attachIterator(new ArrayIterator($review_dates));
            $iterator->attachIterator(new ArrayIterator($review_titles));

            foreach ($iterator as $values) {
                $reviews[] = new Review($values[0], $values[1], $values[2], $values[3], $values[4]);
            } 
        }

        require 'application/views/items/item.php';
    }

    public function submititem()
    {
        $account_id = $_SESSION['id'];
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_STRING);
        $price = $_POST['price'];
        $shipping = $_POST['shipping'];
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
        $subcategory = filter_input(INPUT_POST, 'subcategory', FILTER_SANITIZE_STRING);
        $id = $this->model->createItem($account_id, $title, $size, $price, $shipping, $description, $category, $subcategory);
        $target_dir = "/var/www/html/uploads/";
        $target_file = $target_dir . basename('item_' . $id);
        move_uploaded_file($_FILES['item_img']['tmp_name'], $target_file);
        $this->item($id);
    }

    public function edititem($id){
        $arr = Category::getConstants();
        $arr2 = Subcategory::getConstants();
        $item = $this->model->readItem($id);
        if (!$item) {
            header('location: /pages/error');
            return;
        }
        $this->title = 'Edit Item: '.$item->item_name;
        require 'application/views/items/edit.php';
    }

    public function editsolditem($id){
        $item = $this->model->getItemById($id);
        if (!$item) {
            header('location: /pages/error');
            return;
        }
        $this->title = 'Update Item: '.$item->item_name;
        require 'application/views/items/update.php';
    }

    public function updateitem($id, $status){
        $account_id = $_SESSION['id'];
        $item_id = $id;
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_STRING);
        $price = $_POST['price'];
        $shipping = $_POST['shipping'];
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
        $subcategory = filter_input(INPUT_POST, 'subcategory', FILTER_SANITIZE_STRING);
        $tracking = filter_input(INPUT_POST, 'tracking', FILTER_SANITIZE_STRING);
        $this->model->updateItem($account_id, $item_id, $title, $size, $price, $shipping, $description, $category, $subcategory, $status, $tracking);
        $this->item($item_id);
    }

    public function updatesolditem($id){
        require 'application/models/Order.php';
        require 'application/models/User.php';
        $user_helper = new User($this->db);
        $order_helper = new Order($this->db);
        $tracking = filter_input(INPUT_POST, 'tracking', FILTER_SANITIZE_STRING);
        $comments = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        $item = $this->model->getItemById($id);
        $account_id = $item->account_id;
        $item_id = $id;
        $title = $item->item_name;
        $size = $item->size;
        $price = $item->price;
        $shipping = $item->shipping;
        $description = $item->description;
        $category = $item->category;
        $subcategory = $item->subcategory;
        $status = 0;
        $this->model->updateItem($account_id, $item_id, $title, $size, $price, $shipping, $description, $category, $subcategory, $status, $tracking);
        $message = $comments;
        $message = wordwrap($message, 70, "\r\n");
        $order_details = $order_helper->getOrderByItemId($item_id);
        $order_details = $order_helper->getOrderById($order_details->order_id);
        $recipient = $user_helper->readUser($order_details->account_id);
        $subject = "Item \"$title\" Shipped!";
        $recipient_email = $recipient->email;
        $mailheader = "From: stickershock2@gmail.com \r\n";
        $formcontent = "Your item has been shipped!\r\nTracking Number: $tracking\n\r";
        if($comments !=''){
            $formcontent .= "Comments From Seller: $message";
        }
        $mail = mail($recipient_email, $subject, $formcontent, $mailheader);
        header('location: /account');
    }

    public function updatestatus($item_id){
        $this->model->purchaseItem($item_id);
        header('location: /orders/createorder');
    }

    public function purchaseitem(){
        include 'application/models/User.php';
        if(isset($_SESSION['id']) && $_SESSION['id'] != '') {
            require 'application/helper/checkout.php';
        }else{
            $_SESSION['login_error'] = 'You must be logged in to purchase an item';
            header('location: /account/login?page=items,item,' .$_POST['item_id']);
        }

    }

    public function purchasesuccess(){
        require 'application/models/User.php';
        $user = new User($this->db);
        $item = $this->model;
        require 'application/views/items/success.php';
    }


    public function deleteitem($id){
        $this->model->deleteItem($id);
        header('location: /account');
        return;
    }

    public function loadModel()
    {
        require 'application/models/Item.php';
        $this->model = new Item($this->db);
    }

}
