<?php
require_once 'autoload.php';
require_once './model/Database.php';

class Controller
{
    private $twig;
    private $action;
    private $db;
    
    /**
     * Instantiates a new controller
     */
    public function __construct() {
        $loader = new Twig\Loader\FilesystemLoader('./view');
        $this->twig = new Twig\Environment($loader);
        $this->setupConnection();
        $this->connectToDatabase();
        $this->action = $this->getAction();
        
        $this->twig->addGlobal('session', $_SESSION);
    }
    
    /**
     * Initiates the processing of the current action
     */
    public function invoke() {
        switch($this->action) {
            case 'Show Login':
                $this->processShowLogin();
                break;
            case 'Login':
                $this->processLogin();
                break;
            case 'Show Order':
                $this->processShowOrder();
                break;
            case 'Order':
                $this->processOrder();
                break;
            case 'Logout':
                $this->processLogout();
                break;            
            case 'Show Registration':
                $this->processShowRegistration();
                break;
            case 'Show Profile':
                $this->processShowProfile();
                break;
            case 'Home':
                $this->processShowHomePage();
                break;
            case 'Show Elguapo':
                $this->processShowElguapoPage();
                break;
            case 'Show Kupa':
                $this->processShowKupaPage();
                break;
            case 'Form':
                $this->processShowFormPage();
                break;
            case 'Register':
                $this->processRegistration();
                break;
            case 'Update':
                $this->processUpdate();
                break;
            default:
                $this->processShowHomePage();
                break;
        }
    }
    
    /****************************************************************
     * Process Request
     ***************************************************************/
    
    /**
     * Shows the login page
     */
    private function processShowLogin() {
        $login_message = '';   
        $template = $this->twig->load('login.twig');
        echo $template->render(['login_message' => $login_message]);
    }
    
    /**
     * Logs in the user with the credentials specified in the post array
     */
    private function processLogin() {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        if ($this->db->isValidUserLogin($username, $password)) {
            $_SESSION['is_valid_user'] = true;
            $_SESSION['username'] = $username;
            header("Location: .?action=Show Profile");
        } else {
            $login_message = 'Invalid username or password';
            $template = $this->twig->load('login.twig');
            echo $template->render(['login_message' => $login_message]);
        }
    }
    
    /**
     * Update
     */
    private function processUpdate() {
        $first_name = filter_input(INPUT_POST, 'first_name');
        $last_name = filter_input(INPUT_POST, 'last_name');
        $email = filter_input(INPUT_POST, 'email');
        $user_name = $_SESSION['username'];
        
        $this->db->updateInfo($user_name, $first_name, $last_name, $email);
        header("Location: .?action=Show Profile");
    }
    
    /**
     * Shows the registration page
     */
    private function processShowOrder() {
        $cost = 0;
        $error_username = '';
        $error_password = '';
        $template = $this->twig->load('order.twig');
        echo $template->render(['error_username' => $error_username, 'error_password' => $error_password, 'cost' => $cost]);
    }
    
    /**
     * Registers the user as specified in the post array
     */
    private function processOrder() {
        $error_username = '';
        $error_password = '';
        $char_name = filter_input(INPUT_POST, 'char_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $char_server = filter_input(INPUT_POST, 'char_server');
        $pl_leveler = filter_input(INPUT_POST, 'pl_leveler');
        $hours = filter_input(INPUT_POST, 'hours', FILTER_SANITIZE_NUMBER_INT);
        $multiplier = 1000;
        
        if($pl_leveler == "Both") {
            $multiplier = 1500;
        }
        $cost = $hours * $multiplier;
        $this->db->addOrder($_SESSION['username'], $char_name, $char_server, $pl_leveler, $cost);
        $template = $this->twig->load('order.twig');
        echo $template->render(['error_username' => $error_username, 'error_password' => $error_password, 'cost' => $cost]);
    }
    
    /**
     * Shows the home page
     */
    private function processShowHomePage() {
        $template = $this->twig->load('home.twig');
        echo $template->render();
    }
    
    /**
     * Shows the elguapo page
     */
    private function processShowElguapoPage() {
        $template = $this->twig->load('elguapo.twig');
        echo $template->render();
    }
    
    /**
     * Shows the kupa page
     */
    private function processShowKupaPage() {
        $template = $this->twig->load('kupa.twig');
        echo $template->render();
    }
    
    /**
     * Shows the kupa page
     */
    private function processShowFormPage() {
        $template = $this->twig->load('form.twig');
        echo $template->render();
    }
    
    /**
     * Clears all session data from memory and cleans up the session ID
     * in order to logout the current user
     */
    private function processLogout() {
        $_SESSION = array();
        session_destroy();
        $login_message = 'You have been logged out.';
        header("Location: .?action=Show Login");
    }
    
    /**
     * Shows the tasks of the logged in user. If no user is logged in,
     * shows the login page
     */
    private function processShowRegistration() {
        $template = $this->twig->load('registration.twig');
        echo $template->render();        
    }
    
    /**
     * Shows the tasks of the logged in user. If no user is logged in,
     * shows the login page
     */
    private function processRegistration() {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password');
        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        $this->db->addUser($username, $password);
        $this->db->addUserInfo($username, $first_name, $last_name, $email);
        
        $template = $this->twig->load('registration.twig');
        echo $template->render();
    }
    
    /**
     * Shows the tasks of the logged in user. If no user is logged in,
     * shows the login page
     */
    private function processShowProfile() {
        $info = $this->db->getInfoForUser($_SESSION['username']);
        $template = $this->twig->load('profile.twig');
        echo $template->render(['info' => $info]);     
    }
    
    /**
     * Gets the action from $_GET or $_POST array
     * 
     * @return string the action to be processed
     */
    private function getAction() {
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($action === NULL) {
            $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($action === NULL) {
                $action = '';
            }
        }
        return $action;
    }
    
    /**
     * Ensures a secure connection and start session
     */
    private function setupConnection() {
        $https = filter_input(INPUT_SERVER, 'HTTPS');
        if (!$https) {
            $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
            $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
            $url = 'https://' . $host . $uri;
            header("Location: " . $url);
            exit();
        }
        session_start();
    }
    
    /**
     * Connects to the database
     */
    private function connectToDatabase() {
        $this->db = new Database();
        if (!$this->db->isConnected()) {
            $error_message = $this->db->getErrorMessage();
            $template = $this->twig->load('database_error.twig');
            echo $template->render(['error_message' => $error_message]);
            exit();
        }
    }
}