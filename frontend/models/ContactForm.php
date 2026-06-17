<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $phone;
    public $direction;
    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required on the contact page
            [['name', 'email', 'subject', 'body'], 'required', 'except' => 'homepage'],
            // homepage callback form asks for only the fields visible in that block
            [['name', 'phone', 'body'], 'required', 'on' => 'homepage'],
            [['phone', 'direction'], 'string', 'max' => 255],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly outside of the homepage form
            ['verifyCode', 'captcha', 'except' => 'homepage'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Phone',
            'direction' => 'Direction',
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$this->email ?: Yii::$app->params['senderEmail'] => $this->name])
            ->setSubject($this->subject ?: 'Homepage consultation request')
            ->setTextBody($this->buildMessageBody())
            ->send();
    }

    private function buildMessageBody()
    {
        $lines = [
            'Name: ' . $this->name,
        ];

        if ($this->phone) {
            $lines[] = 'Phone: ' . $this->phone;
        }

        if ($this->direction) {
            $lines[] = 'Direction: ' . $this->direction;
        }

        if ($this->email) {
            $lines[] = 'Email: ' . $this->email;
        }

        $lines[] = '';
        $lines[] = $this->body;

        return implode(PHP_EOL, $lines);
    }
}
