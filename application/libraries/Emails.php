<?php namespace GoCart
/**
 * Emails Class
 *
 * @package     Emails
 * @subpackage  Library
 * @category    GoCart
 * @author      Clear Sky Designs
 * @link        http://gocartdv.com
 */

class Emails {

    static function sendEmail($email)
    {
        $mailType = config_item('email_method');
        if($mailType == 'smtp')
        {
            $transport = Swift_SmtpTransport::newInstance(config_item('smtp_server'), config_item('smtp_port'))->setUsername(config_item('smtp_username'))->setPassword(config_item('smtp_password'));
        }
        elseif($mailType == 'sendmail')
        {
            $transport = Swift_SendmailTransport::newInstance(config_item('sendmail_path'));
        }
        else //Mail
        {
            $transport = Swift_MailTransport::newInstance();
        }
        //get the mailer
        $mailer = Swift_Mailer::newInstance($transport);

        //send the message
        $mailer->send($email);
    }

    static function registration($customer)
    {
        $email = \Swift_Message::newInstance();
        $cannedMessage = \CI::db()->where('id', '6')->get('canned_messages')->row_array();

        // set replacement values for subject & body
        // {customer_name}
        $cannedMessage['subject'] = str_replace('{customer_name}', $customer['firstname'].' '. $customer['lastname'], $cannedMessage['subject']);
        $cannedMessage['content'] = str_replace('{customer_name}', $customer['firstname'].' '. $customer['lastname'], $cannedMessage['content']);

        // {url}
        $cannedMessage['subject'] = str_replace('{url}', config_item('base_url'), $cannedMessage['subject']);
        $cannedMessage['content'] = str_replace('{url}', config_item('base_url'), $cannedMessage['content']);

        // {site_name}
        $cannedMessage['subject'] = str_replace('{site_name}', config_item('company_name'), $cannedMessage['subject']);
        $cannedMessage['content'] = str_replace('{site_name}', config_item('company_name'), $cannedMessage['content']);

        $email->setFrom(config_item('email_from')); //email address the website sends from
        $email->setTo($customer['email']);
        //$email->setBcc(config_item('email_to')); //admin email the website sends to
        $email->setReturnPath(config_item('email_to')) //this is the bounce if they submit a bad email

        $email->setSubject($cannedMessage['subject']);
        $email->setBody($cannedMessage['content'], 'text/html');

        self::sendEmail($email);
    }

    static function giftCardNotification($gift_card)
    {
      $email = \Swift_Message::newInstance();
      $cannedMessage = \CI::db()->where('id', '1')->get('canned_messages')->row_array();

      // set replacement values for subject & body
      $cannedMessage['subject'] = str_replace('{from}', $gift_card['from'], $cannedMessage['subject']);
      $cannedMessage['subject'] = str_replace('{site_name}', config_item('company_name'), $cannedMessage['subject']);

      $cannedMessage['content'] = str_replace('{code}', $gift_card['code'], $cannedMessage['content']);
      $cannedMessage['content'] = str_replace('{amount}', $gift_card['beginning_amount'], $cannedMessage['content']);
      $cannedMessage['content'] = str_replace('{from}', $gift_card['from'], $cannedMessage['content']);
      $cannedMessage['content'] = str_replace('{personal_message}', nl2br($gift_card['personal_message']), $cannedMessage['content']);
      $cannedMessage['content'] = str_replace('{url}', config_item('base_url'), $cannedMessage['content']);
      $cannedMessage['content'] = str_replace('{site_name}', config_item('company_name'), $cannedMessage['content']);

      $email->setFrom(config_item('email_from')); //email address the website sends from
      $email->setTo($gift_card['to_email']);
      //$email->setBcc(config_item('email_to')); //admin email the website sends to
      $email->setReturnPath(config_item('email_to')) //this is the bounce if they submit a bad email

      $email->setSubject($cannedMessage['subject']);
      $email->setBody($cannedMessage['content'], 'text/html');

      self::sendEmail($email);

    }

    /*
    This function send an email notification when the admins resets password
    */
    static function resetPassword($password, $admin_email)
    {
      $email = \Swift_Message::newInstance();
      $cannedMessage['content'] = 'Your password has been reset to '. $new_password .'.';
      $cannedMessage['subject'] = config_item('site_name').': Admin Password Reset';

      $email->setFrom(config_item('email_from')); //email address the website sends from
      $email->setTo($admin_email);
      //$email->setBcc(config_item('email_to')); //admin email the website sends to
      $email->setReturnPath(config_item('email_to')) //this is the bounce if they submit a bad email
      $email->setSubject($cannedMessage['subject']);
      $email->setBody($cannedMessage['content'], 'text/html');

      self::sendEmail($email);

    }

    /*
    This function send an email notification when the customer resets password
    */
    static function resetPasswordCustomer($password, $customer_email)
    {
      $email = \Swift_Message::newInstance();
      $cannedMessage['content'] = 'Your password has been reset to <strong>'. $password .'</strong>.';
      $cannedMessage['subject'] = config_item('site_name').': Password Reset';

      $email->setFrom(config_item('email_from')); //email address the website sends from
      $email->setTo($admin_email);
      //$email->setBcc(config_item('email_to')); //admin email the website sends to
      $email->setReturnPath(config_item('email_to')) //this is the bounce if they submit a bad email
      $email->setSubject($cannedMessage['subject']);
      $email->setBody($cannedMessage['content'], 'text/html');

      self::sendEmail($email);

    }

    /*
    Order email notification
    */
    static function sendOrderNotification($order)
    {
      $email = \Swift_Message::newInstance();
      $cannedMessage['content'] = html_entity_decode(order['content']);
      $cannedMessage['subject'] = $order['subject'];

      $email->setFrom(config_item('email_from')); //email address the website sends from
      $email->setTo($order['recipient']);
      //$email->setBcc(config_item('email_to')); //admin email the website sends to
      $email->setReturnPath(config_item('email_to')) //this is the bounce if they submit a bad email
      $email->setSubject($cannedMessage['subject']);
      $email->setBody($cannedMessage['content'], 'text/html');

      self::sendEmail($email);

    }

}
