<?php
require_once 'autoload.php';

class Controller
{
    private $twig;
    private $action;
    
    /**
     * Instantiates a new controller
     */
    public function __construct() {
        $loader = new Twig\Loader\FilesystemLoader('./view');
        $this->twig = new Twig\Environment($loader);
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
            case 'Show PL Service':
                $this->processShowPLService();
                break;
            case 'PL Service':
                $this->processPLService();
                break;
            case 'Logout':
                $this->processLogout();
                break;            
            case 'Show Purchases':
                $this->processShowTasks();
                break;
            case 'Home':
                $this->processShowHomePage();
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
        
    }
    
    /**
     * Shows the registration page
     */
    private function processShowPLService() {
        $error_username = '';
        $error_password = '';
        $template = $this->twig->load('pl_service.twig');
        echo $template->render(['error_username' => $error_username, 'error_password' => $error_password]);
    }
    
    /**
     * Registers the user as specified in the post array
     */
    private function processPLService() {
        
    }
    
    /**
     * Shows the home page
     */
    private function processShowHomePage() {
        $template = $this->twig->load('home.twig');
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
        $template = $this->twig->load('login.twig');
        echo $template->render(['login_message' => $login_message]);
    }
    
    /**
     * Shows the tasks of the logged in user. If no user is logged in,
     * shows the login page
     */
    private function processShowPurchases() {
        
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
}