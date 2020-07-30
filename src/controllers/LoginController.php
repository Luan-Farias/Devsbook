<?php
namespace src\controllers;

use \core\Controller;
use src\handlers\LoginHandler;

class LoginController extends Controller {

    public function signin() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('login', [
            'flash' => $flash
        ]);
    }

    public function signinAction() {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');


        if(!$email || !$password) {
            $_SESSION['flash'] = 'Digite os campos de e-mail e/ou senha.';
            $this->redirect('/login');
        }

        $token = LoginHandler::verifyLogin($email, $password);
        if(!$token) {
            $_SESSION['flash'] = 'Email e/ou senha não conferem.';
            $this->redirect('/login');
        }
        
        $_SESSION['token'] = $token;
    }

    public function signup() {
        
    }

}