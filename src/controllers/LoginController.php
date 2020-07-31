<?php
namespace src\controllers;

use \core\Controller;
use src\handlers\UserHandler;

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

        $token = UserHandler::verifyLogin($email, $password);
        if(!$token) {
            $_SESSION['flash'] = 'Email e/ou senha não conferem.';
            $this->redirect('/login');
        }
        
        $_SESSION['token'] = $token;
        $this->redirect('/');
    }

    public function signup() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('signup', [
            'flash' => $flash
        ]);
    }

    public function signupAction() {
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $birthdate = filter_input(INPUT_POST, 'birthdate');

        if(!$email || !$password || !$password || !$birthdate) {
            $_SESSION['flash'] = 'Preencha todos os campos';
            $this->redirect('/cadastro');
        }

        $birthdate = explode('/', $birthdate);
        if(count($birthdate) !== 3) {
            $_SESSION['flash'] = 'Data de nascimento inválida';
            $this->redirect('/cadastro');
        }

        $birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0];
        if(!strtotime($birthdate)) {
            $_SESSION['flash'] = 'Data de nascimento inválida';
            $this->redirect('/cadastro');
        }

        if(UserHandler::emailExists($email)) {
            $_SESSION['flash'] = 'Email já cadastrado';
            $this->redirect('/cadastro');
        }

        $token = UserHandler::addUser($name, $email, $password, $birthdate);
        $_SESSION['token'] = $token;
        $this->redirect('/');
    }

}