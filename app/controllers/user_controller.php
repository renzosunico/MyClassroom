<?php
class UserController extends AppController
{
    const REGISTRATION_PAGE         = 'registration';
    const SUCCESS_REGISTRATION_PAGE = 'registration_end';
    const LOGIN_PAGE                = 'login';
    const LOGIN_SUCCESS_PAGE        = 'login_end';
    const EDIT_ACCOUNT              = 'account';
    const EDIT_PROFILE              = 'profile';
    const EDIT_PASSWORD             = 'password';
    const EDIT_PAGE                 = 'edit';

    public function registration()
    {
        if(isset($_SESSION['username'])) {
            redirect(THREAD_PAGE);
        }

        $page = Param::get('page_next','registration');
        $user = new User();

        switch ($page) {
            case self::REGISTRATION_PAGE:
                break;
            case self::SUCCESS_REGISTRATION_PAGE:
                $user->fname    = Param::get('fname');
                $user->lname    = Param::get('lname');
                $user->username = Param::get('username');
                $user->email    = Param::get('email');
                $user->password = Param::get('password');
                $user->confirmpassword = Param::get('repassword');
                try {
                    $user->register();
                } catch (ValidationException $e) {
                    $page = self::REGISTRATION_PAGE;
                }
                break;
            default :
                throw new RecordNotFoundException('{$page} not found.');
                break;
        }

        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function login()
    {
        $user = new User();

        $clean_username = htmlentities(Param::get('username'));
        $clean_hashed_password = htmlentities(Param::get('password'));
        
        $page = Param::get('page_next','login');
        
        $isAuthorized = true;

        switch($page) {
            case self::LOGIN_PAGE:
                break;
            case self::LOGIN_SUCCESS_PAGE:
                $user->username = $clean_username;
                $user->password = $clean_hashed_password;
                $isAuthorized   = $user->isRegistered();
                
                if (!$isAuthorized) {
                    $page = self::LOGIN_PAGE;
                } else {
                    $_SESSION['username'] = $clean_username;
                    $_SESSION['userid'] = User::getIdByUsername($clean_username);
                    redirect('user/login_end');
                }
                break;
            default :
                throw new RecordNotFoundException;
                break;
        }
        
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function logout()
    {
        session_destroy();
        redirect('user/login');
    }

    public function profile()
    {
        $user = new User();
        $user->id = Param::get('user_id');
        $user->getProfile();
        $user->isUser($_SESSION['userid']);

        if (!isset($user->username)) {
            redirect('notfound/pagenotfound');
        }

        $user->getFollowedThreads($_SESSION['userid']);
        $user->getCreatedThreads($_SESSION['userid']);
        $this->set(get_defined_vars());
    }

    public function edit()
    {
        $process = Param::get('process', 'edit');
        $user = new User();

        switch ($process) {
            case self::EDIT_ACCOUNT:
                $user->id = get_authenticated_user_id($_SESSION['userid']);
                $user->fname = Param::get('firstname');
                $user->lname = Param::get('lastname');
                $user->new_username = Param::get('username');
                $user->new_email    = Param::get('email');
                try {
                    $user->updateAccount();
                    $_SESSION['username'] = $user->new_username;
                    $user->editSuccess    = true; 
                } catch(ValidationException $e) {
                }
                break;
            case self::EDIT_PROFILE:
                $user->id = get_authenticated_user_id($_SESSION['userid']);
                $user->company = Param::get('company');
                $user->division = Param::get('division');
                $user->specialization = Param::get('specialization');
                try {
                    $user->updateProfile();
                    $user->editSuccess = true;
                } catch(ValidationException $e) {
                }
                break;
            case self::EDIT_PASSWORD:
                $user->id = get_authenticated_user_id($_SESSION['userid']);

                //set username and old password to password
                //property to authenticate user
                $user->username = $_SESSION['username'];
                $user->password = htmlentities(Param::get('oldPassword'));

                if(!$user->isRegistered()) {
                    $user->validation_errors['notAuthorized']['authenticate'] = true;
                    break;
                }
                //Unset username so it won't be included in validation
                unset($user->username);
                $user->password        = htmlentities(Param::get('password'));
                $user->confirmpassword = htmlentities(Param::get('confirmPassword'));

                try {
                    $user->updatePassword();
                    $user->editSuccess = true;
                } catch (ValidationException $e) {

                }
                break;
            case self::EDIT_PAGE:
                $user->id = $_SESSION['userid'];
                break;
        }
        
        $user->getProfile();
        $this->set(get_defined_vars());
    }


    public function login_end()
    {
        
    }
}
