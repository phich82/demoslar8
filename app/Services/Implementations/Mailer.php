<?php

namespace App\Services\Implementations;

use Exception;
use Illuminate\Support\Facades\Mail;
use App\Services\Contracts\Mailer as MailerContract;

class Mailer implements MailerContract
{
    /**
     * Send email
     *
     * @param  string $to
     * @param  array|\Illuminate\Mail\Mailable $params [
     *  '_type_' => <view|html|text>,
     *  '_view_' => <path_to_view_file>,
     *  'key1' => 'value1',
     *  'key2' => 'value2',
     *  'key3' => 'value3',
     * ]
     * @return void
     * @throws \Exception
     */
    public function send($to, $params = [])
    {
        // Using mailable
        if (is_object($params)) {
            return Mail::to($to)->send($params);
        }
        if (!is_array($params)) {
            throw new Exception("Second parameter should be an array or an object.");
        }

        [$type, $view, $data] = $this->_resolveView($params);

        if ($type === 'view') {
            // Mail::plain();
            return Mail::send($view, $data, function ($message) use ($to, $data) {
                $message->to($to)->subject($data['subject']);
                // Process more options (attachments)
            });
        }
        return Mail::html($view, function ($message) use ($to, $data) {
            $message->to($to)->subject($data['subject']);
            // Process more options (attachments)
        });
    }

    /**
     * _Resolve view
     *
     * @param  array $params
     * @return array
     */
    private function _resolveView($params = [])
    {
        $type = $params['_type_'] ?? 'html';
        $view = $params['_view_'] ?? '';

        unset($params['_type_'], $params['_view_']);

        return [$type, $view, $params];
    }
}
