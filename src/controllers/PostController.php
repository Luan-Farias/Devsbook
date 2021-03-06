<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use src\handlers\PostHandler;

class PostController extends Controller {
    
    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin();
        
        if(!$this->loggedUser) $this->redirect('/login');
    }

    public function new()
    {
        $body = filter_input(INPUT_POST, 'body');

        if($body) {
            PostHandler::addPost(
                $this->loggedUser->id,
                'text',
                $body
            );
        }

        $this->redirect('/');
    }

    public function delete($attributes) {
        if (!empty($attributes['id'])) {
            $postId = $attributes['id'];

            PostHandler::delete($postId, $this->loggedUser->id);
        }
        
        $this->redirect('/');
    }

}