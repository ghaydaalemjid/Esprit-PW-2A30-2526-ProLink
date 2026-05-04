<?php
class Notification {
    private $id_user;
    private $type;
    private $message;
    private $lien;
    private $is_read;

    public function __construct($id_user, $type, $message, $lien = null) {
        $this->id_user = $id_user;
        $this->type = $type;
        $this->message = $message;
        $this->lien = $lien;
        $this->is_read = 0;
    }

    public function getIdUser() { return $this->id_user; }
    public function getType() { return $this->type; }
    public function getMessage() { return $this->message; }
    public function getLien() { return $this->lien; }
    public function getIsRead() { return $this->is_read; }
}
?>