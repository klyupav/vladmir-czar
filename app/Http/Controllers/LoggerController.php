<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Models\ProcessParsingLog;

class LoggerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function processLog()
    {
        $query = ProcessParsingLog::query();
        $query = $query->orderBy('begin', 'desc');
        $logs = $query->paginate(50);
        return view('logs.process', [
            'logs' => $logs
        ]);
    }

    public function index()
    {
        $files = glob('storage/logs/*.log');
        foreach ($files as $key => $value) {
            $files[$key] = str_replace('storage/', '', $value);
        }
        rsort($files);
        return view('logs.index', [
            'files' => $files
        ]);
    }

    public function show($filename)
    {
        $log = file_get_contents("storage/logs/{$filename}");
        echo "<pre>";
        echo $log;
        echo "</pre>";
    }

    public function ajaxFilterLogs(Request $request)
    {
        $query = preg_replace("%[^\d\. \-]%is", '', $request['query']);
        $files = glob("storage/logs/{$query}*.log");
        foreach ($files as $key => $value) {
            $files[$key] = str_replace('storage/', '', $value);
        }
        rsort($files);
        return json_encode([
            'success' => true,
            'files' => $files
        ]);
    }

    public static function logToFile($message, $level = 'info', $context = [], $print_error = true)
    {
        // create a log channel
        $log = new Logger(config('app.name'));
        $today = date('Y-m-d');
        $log->pushHandler(new StreamHandler( "storage/logs/{$today}.log"));
        switch ( $level )
        {
            case 'info':
                $log->info($message, $context);
                break;
            case 'warning':
                $log->warning($message, $context);
                break;
            case 'error':
                $log->error($message, $context);
                break;
        }
        static::log($message, $context, $print_error, $level);
    }

    public static function log($message, $context = [], $print_error = true, $level = 'info')
    {
        if ($print_error)
        {
            echo "<pre style='color: red'>";
            echo $message;
            echo "</pre>";
            echo "<pre>";
            !empty($context) ? print_r($context) : '';
            echo "</pre>";
        }

        if ( @config('parser.log_to_mail') )
        {
            try
            {
                switch ( $level )
                {
                    case 'info':
//                        \Mail::send('emails.alert', array('text' => $message, 'context' => $context), function($message)
//                        {
//                            $to = explode(',', config('parser.admin_emails'));
//                            $message->to($to);
//                            $message->subject('info');
//                        });
                        break;
                    case 'warning':
                        \Mail::send('emails.alert', array('text' => $message, 'context' => $context), function($message)
                        {
                            $to = explode(',', config('parser.admin_emails'));
                            $message->to($to);
                            $message->subject('warning');
                        });
                        break;
                    case 'error':
                        \Mail::send('emails.alert', array('text' => $message, 'context' => $context), function($message)
                        {
                            $to = explode(',', config('parser.admin_emails'));
                            $message->to($to);
                            $message->subject('error');
                        });
                        break;
                }

            }
            catch (\Exception $e)
            {

            }
        }
    }
}
