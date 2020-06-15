<?php

class SendEmail{
    private $to;
    private $subject;
    private $message;
    private $from;
    private $header;
    private $firstname;

    public function __construct($to, $subject, $from, $header, $firstname = "", $message = 1){
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->from = $from;
        $this->header = $header;
        $this->firstname = $firstname;
    }

    function SendEmailFromTemplate(){
        $txt = "<html>
                    <head>
                    <title>Gobinit Password Reset</title>
                    </head>
                    <body>
                    <p>Hello, " . $this->firstname . ", this is a test email.</p>
                    <table>
                    <tr>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    </tr>
                    <tr>
                    <td>John</td>
                    <td>Doe</td>
                    </tr>
                    </table>
                    </body>
                </html>";

        mail($this->to,$this->subject,$txt,$this->header);
    }
}

?>