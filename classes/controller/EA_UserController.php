<?php
namespace CharitySwimRun\classes\controller;

use Doctrine\ORM\EntityManager;
use CharitySwimRun\classes\model\EA_User;
use CharitySwimRun\classes\model\EA_Message;


class EA_UserController  extends EA_Controller
{


    
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }
    
    public function getPageUser(): string
    {
        $content = "";
        $user = new EA_User("neuer User","");
        //##################Verarbeitung alle Möglichkeiten#################
        if (isset($_POST['sendUserData'])) {
            $this->createAndUpdateUser();
        } elseif (isset($_GET['action']) && $_GET['action'] === "edit") {
            $user = $this->editUser();
        } elseif (isset($_GET['action']) && $_GET['action'] === "delete") {
            $this->deleteUser();
        } else {
            $user = new EA_User("User".rand(1000,100000),"");
        }
        //##################laden der User und Ausgabe der Tabelle#################

        $content .= $this->getUserList();

        //##################Ausgabe des Formulas#################
        $content .= $this->EA_FR->getFormUser($user);
        return $content;
    }

    private function createAndUpdateUser(): void
    {
        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $password2 = htmlspecialchars($_POST['password2']);
        $userroleId = filter_input(INPUT_POST,'userroleId',FILTER_SANITIZE_NUMBER_INT);
        $passwordHash = "";

        if(strlen($username) < 6){
            $this->EA_Messages->addMessage("Username muss min. 6 Zeichen lang sein",1546854634,EA_Message::MESSAGE_ERROR);
            return;
        }
        if(strlen($password) < 6){
            $this->EA_Messages->addMessage("Passwort muss min. 6 Zeichen lang sein.",134565277,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($userroleId === "" || !array_key_exists($userroleId,EA_User::USERROLE_LIST)){
            $this->EA_Messages->addMessage("Rolle ist leer oder falsch.",45554627,EA_Message::MESSAGE_ERROR);
            return;
        }
        if($password !== $password2){
            $this->EA_Messages->addMessage("Die Passwörter sind nicht gleich",554643287,EA_Message::MESSAGE_ERROR);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        if($id === null || $id === false || $id === ""){
            if($this->EA_UserRepository->loadByUsername($username) !== null){
                $this->EA_Messages->addMessage("Der Username ist schon vergeben",1233534523,EA_Message::MESSAGE_ERROR);
                return;
            }else{
                $user = new EA_User($username,$passwordHash);
                $user->setUserroleId((int)$userroleId);
                $this->EA_UserRepository->create($user);
                $this->EA_Messages->addMessage("User angelegt",1753354754,EA_Message::MESSAGE_SUCCESS);

            }
        }else{
            $user = $this->EA_UserRepository->loadById((int)$id);
            $user->setUsername($username);
            $user->setPasswordHash($passwordHash);
            $user->setUserroleId((int)$userroleId);
            $this->EA_UserRepository->update();
            $this->EA_Messages->addMessage("User geändert",1213772457,EA_Message::MESSAGE_SUCCESS);

        }
    }

    private function editUser(): ?EA_User
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $user = $this->EA_UserRepository->loadById($id);
        if($user === null){
            $this->EA_Messages->addMessage("Kein User gefunden.",1288839990,EA_Message::MESSAGE_WARNING);
        }
        return $user;
    }

    private function getUserList():  string
    {
        $content = "";
        $userList = $this->EA_UserRepository->loadList();
        if ($userList !== []) {
            $content = $this->EA_R->renderTabelleUser($userList);
        } else {
            $this->EA_Messages->addMessage("Es sind noch keine User angelegt.",1156666779,EA_Message::MESSAGE_WARNING);
        } 
        return $content;
    }

    private function deleteUser(): void
    {
        $id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
        $user = $this->EA_UserRepository->loadById($id);
        if($user === null){
            $this->EA_Messages->addMessage("Kein User gefunden.",1288839990,EA_Message::MESSAGE_WARNING);
        }else{
            $this->EA_UserRepository->delete($user);
            $this->EA_Messages->addMessage("User gelöscht.",8899234234234,EA_Message::MESSAGE_SUCCESS);
        }
    }
}