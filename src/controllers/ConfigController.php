<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;

class ConfigController extends Controller {
    
    private $loggedUser;

    public function __construct()
    {
        $this->loggedUser = UserHandler::checkLogin(true);
        
        if(!$this->loggedUser) $this->redirect('/login');
    }

    public function index() {
        $user = $this->loggedUser;

        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('config', [
            'loggedUser' => $user,
            'flash' => $flash
        ]);
    }

    public function save()
    {
        $user = $this->loggedUser;
        if(!$user) $this->redirect('/config');
        $updatedFields = [];

        // Campos obrigatórios
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        if(!$name || !$email || !$birthdate) {
            $_SESSION['flash'] = 'Nome, Email e/ou Data de nascimento são obrigatórios';
            $this->redirect('/config');
        }

        // ID | name
        $updatedFields['id'] = $user->id;
        $updatedFields['name'] = $name;

        // birthdate
        $birthdate = explode('/', $birthdate);
        if(count($birthdate) !== 3) {
            $_SESSION['flash'] = 'Data de nascimento inválida';
            $this->redirect('/config');
        }
        $birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0];
        if(!strtotime($birthdate)) {
            $_SESSION['flash'] = 'Data de nascimento inválida';
            $this->redirect('/config');
        }
        $updatedFields['birthdate'] = $birthdate;

        // email
        $updatedFields['email'] = $user->email;
        if($email !== $user->email) {
            if(UserHandler::emailExists($email)) {
                $_SESSION['flash'] = 'Email já cadastrado';
                $this->redirect('/config');
            }
            $updatedFields['email'] = $email;
        }

        // password
        $password = filter_input(INPUT_POST, 'password');
        $updatedFields['password'] = $user->password;
        $password_confirmation = filter_input(INPUT_POST, 'password_confirmation');
        if(!empty($password) || !empty($password_confirmation)) {
            if($password === $password_confirmation) {
                $updatedFields['password'] = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $_SESSION['flash'] = 'Senhas não coincidem';
                $this->redirect('/config');
            }
        }

        // city |  work (can be null)
        $updatedFields['city'] = filter_input(INPUT_POST, 'city') ?? null;
        $updatedFields['work'] = filter_input(INPUT_POST, 'work') ?? null;

        // avatar | cover
        $updatedFields['avatar'] = $user->avatar;
        $updatedFields['cover'] = $user->cover;

        // update
        UserHandler::updateUser($updatedFields);
        $this->redirect('/config');
    }


}