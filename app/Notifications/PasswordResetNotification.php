<?php

namespace App\Notifications;

use App\Mail\BareMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $token;
    public $mail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token, BareMail $mail)
    {
        // CI(Constructor Injection)
        $this->token = $token;
        $this->mail = $mail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return $this->mail
            // fromの第一引数には送信元メールアドレス, 第二引数にはメールの送信者名(省略可)
            // config関数を使って、config/mail.phpの値を取得している
            ->from(config('mail.from.address'), config('mail.from.name'))
            // toメソッドには送信先メールアドレスを渡す
            // $notifiableには、メール送信先となるUserモデルが代入されている
            ->to($notifiable->email)
            // subject = メールの件名
            ->subject('[memo]パスワード再設定')
            // textメソッドはテキスト形式のメールを送るときに使うメソッド
            // 'emails.password_reset'とすることで、resources/views/emailsディレクトリの該当ファイルでテンプレートとして使用される
            ->text('emails.password_reset')
            // テンプレートとなるBladeに渡す変数を、withメソッドに連想配列形式で渡す
            ->with([
                'url' => route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->email,
                ]),
                'count' => config(
                    'auth.passwords' .
                    config('auth.defaults.passwords') .
                    '.expire'
                ),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
