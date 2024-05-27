<?php

namespace EndeAuswertung\classes\model;


class EA_Message extends EA_Messages
{
    private string $message = "";
    private string $type = self::MESSAGE_SUCCESS;

    private int $code = 1;

    public const MESSAGE_SUCCESS = "S";
    public const MESSAGE_INFO = "I";
    public const MESSAGE_WARNINIG = "W";
    public const MESSAGE_ERROR = "E";

    public const ALERT_CLASS = [
        self::MESSAGE_SUCCESS => "alert-success",
        self::MESSAGE_INFO => "alert-info",
        self::MESSAGE_WARNINIG => "alert-warning",
        self::MESSAGE_ERROR => "alert-danger",
    ];

   public function __construct(string $message, int $code, string $type = self::MESSAGE_SUCCESS)
   {
    $this->message = $message;
    $this->code = $code;
    $this->type = $type;
   } 

   public function getType(): string
   {
    return $this->type;
   }

   public function getMessage(): string
   {
    return $this->message;
   }

   public function getCode(): int
   {
    return $this->code;
   }

   public function getAlertClass(): string
   {
    return self::ALERT_CLASS[$this->type];

   }
}