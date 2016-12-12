<?php
/**
 * 发送邮件
 */
class Mail_Sendmail
{
    protected $_mailer;
    protected $_sign = '';

    public $errorMsg = '';

    public function __construct($config = array())
    {
        $config = array_merge(Config::get('mail', array()), $config);

        $mailer = new Mail_PHPMailer();
        $mailer->IsSMTP();
        $mailer->CharSet = "UTF-8";
        $mailer->SMTPAuth = TRUE;
        $mailer->Username = $config['username'];
        $mailer->Password = $config['password'];
        $mailer->Host = $config['host'];
        $mailer->Port = d(@$config['port'], 25);
        $from = d(@$config['from'], $config['username']);
        $name = d(@$config['name'], $config['username']);
        $mailer->AddReplyTo($from, $name);
        $mailer->SetFrom($from, "=?UTF-8?B?".base64_encode($name)."?=");
        
        if (isset($config['sign'])) {
            $this->_sign = $config['sign'];
        }

        $this->_mailer = $mailer;
    }

	public function send($to, $subject, $content, $plaintext = '') 
    {
        $mailer = $this->_mailer; 

        $mailer->ClearAddresses();

        $this->errorMsg = '';

		if ( !is_array($to) ) {
			$to = array($to);
		}

		try {
             foreach ( $to as $dest ) {
                $destname = @ explode('@', $dest);
                $destname = $destname[0];
                $mailer->AddAddress($dest, "=?UTF-8?B?".base64_encode($destname)."?=");
            }

            $mailer->Subject = "=?UTF-8?B?".base64_encode($subject)."?=";
            
            $sign = $this->_sign;

            $mailer->MsgHTML($content . $sign);

            if (empty($plaintext)) {
                $plaintext = strip_tags($content);
            }

            $mailer->AltBody = $plaintext . strip_tags($sign);

            if (!$mailer->Send()) {
                $this->errorMsg = $mailer->ErrorInfo;                

                return FALSE;
            }

		} catch (phpmailerException $e) {
            
            $this->errorMsg = "$e";

		    return FALSE;
		} catch (Exception $e) {
            
            $this->errorMsg = "$e";

		    return FALSE;
		}

		return TRUE;
	}
}
