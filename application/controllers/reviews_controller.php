<?php

class Reviews extends Controller
{
    public function index() {
        $this->title = 'Review';

        $user = null;
        # Graham L.:
        # If user is logged in, fetch their page.
        # If they aren't redirect to login page.
        if (isset($_SESSION['username'])) {
            $user = $this->model->readUser($_SESSION['id']);
            $listings = $this->model->getItemsByUser($_SESSION['id']);
            # Not implemented yet.
            $orders = null;
            require 'application/views/account/index.php';
        } else {
            header('location: /account/login');
        }
    }

    public function review(){
        $this->title = 'Submit a Review';
        require 'application/views/items/review.php';

    }
    public function submit_review() {
        $title=filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $stars=filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_STRING);
        $comment=filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        $date=date("Y-m-d H:i:s");
        $reviewerID = $_SESSION['id'];
        $sellerID = 99; #TODO how to get this
        $this->model->createReview($reviewerID,$sellerID,$date,$comment,$stars,$title);

    }
    public function loadModel()
    {
        require 'application/models/Review.php';
        $this->model = new Review($this->db);
        return;
    }
}