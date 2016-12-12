<?php

function sendMail($receiver, $subject, $content)
{
    log_message(json_encode($receiver) . ", $subject, $content", LOG_DEBUG);

    $mailer = new Mail_Sendmail();

    return $mailer->send($receiver, $subject, $content);
}
