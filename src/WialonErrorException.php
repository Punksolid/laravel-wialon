<?php

namespace Punksolid\Wialon;

use Exception;
use Throwable;

class WialonErrorException extends Exception
{
    public static $errors = array(
        0 => 'Successful operation (for example for logout it will be success exit)',
        1 => 'Invalid session',
        2 => 'Invalid service',
        3 => 'Invalid result',
        4 => 'Invalid input',
        5 => 'Error performing request',
        6 => 'Unknow error',
        7 => 'Access denied',
        8 => 'Invalid user name or password',
        9 => 'Authorization server is unavailable, please try again later',
        10 => 'Reached limit of concurrent requests',
        11 => 'Password reset error',
        14 => 'Billing error',
        1001 => 'No message for selected interval',
        1002 => 'Item with such unique property already exists',
        1003 => 'Only one request of given time is allowed at the moment',
        1004 => 'Limit of messages has been exceeded',
        1005 => 'Execution time has exceeded the limit',
        1006 => 'Exceeding the limit of attempts to enter a two-factor authorization code',
        1011 => 'Your IP has changed or session has expired',
        2014 => 'Selected user is a creator for some system objects, thus this user cannot be bound to a new account',
        2015 => 'Sensor deleting is forbidden because of using in another sensor or advanced properties of the unit'
    );

    public function __construct($response, string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
        $this->setMessage(self::error($response));
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        $message = self::error($this->response["error"]);

        $this->setMessage($message);

        \Log::warning($message);
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $message = self::error($this->response["error"]);
        $this->setMessage($message);

        return response()->json([
            "message" => $message
        ]);
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public static function error($code = '', $text = ''): string
    {
        $code = intval($code);
        if (isset(self::$errors[$code]))
            $text = self::$errors[$code] . ' ' . $text;
        $message = sprintf('%d: %s', $code, $text);
        return sprintf('WialonError( %s )', $message);
    }
}
