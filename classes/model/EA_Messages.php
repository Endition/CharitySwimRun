<?php

namespace CharitySwimRun\classes\model;


class EA_Messages
{
    //This is static -> All instances "fill" the list
    private static array $messageList = [];


    public function addMessage(string $message, int $code, string $type = EA_Message::MESSAGE_SUCCESS): void
    {
        self::$messageList[$type][] = new EA_Message($message, $code, $type);
    }

    public function renderMessageAlertList(): string
    {
       $content = "";
       foreach(self::$messageList as $messageListByType){
            foreach($messageListByType as $message){
                $content .= "<div class=\"alert {$message->getAlertClass()}\" role=\"alert\">
                {$message->getMessage()} <span style=\"font-size:0.6em\">({$message->getCode()})</span>
              </div>";
            }
       }
        return $content;
    }
}