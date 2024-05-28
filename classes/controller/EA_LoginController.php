<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_Message;

class EA_LoginController extends EA_Controller
{

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function checkIfAdminExist(): bool
    {
        return $this->EA_UserRepository->checkIfAdminExist() !== null ? true : false;
    }

    public function getLogin(): string
    {
        if($_POST){
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            if($username !== "" && $password !== ""){
                $userFound = $this->EA_UserRepository->loadByUsername($username);

                if ($userFound !== null && password_verify($password, $userFound->getPasswordHash())) {
                    // Verification success! User has logged-in!
                    // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                    session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['name'] = $userFound->getUsername();
                    $_SESSION['id'] = $userFound->getId();
                    $_SESSION['userroleId'] = $userFound->getUserroleId(); 
                    $this->EA_Messages->addMessage("Erfolgreich eingloggt ".htmlspecialchars($_SESSION['name'], ENT_QUOTES)."",1213456457,EA_Message::MESSAGE_SUCCESS);
                    if(isset($_SERVER['HTTP_REFERER'])) {
                        header('Location: '.$_SERVER['HTTP_REFERER']);  
                       } else {
                        header('Location: index.php');  
                       }
                } else {
                    // Incorrect password
                    $this->EA_Messages->addMessage("Passwort oder Username falsch",5376574321,EA_Message::MESSAGE_WARNING);
                }
            }
        }
        
        $content = $this->EA_FR->getFormUserLogin();
        return $content;
    }

}