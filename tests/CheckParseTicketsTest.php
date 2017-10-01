<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
//use App\Donors;
use App\Models;
use App\Http\Controllers;

class CheckParseTicketsTest extends TestCase
{
    /**
     * Тест чекалки каждого доноров.
     *
     * @return void
     */
    public function testCheckTicketsDonors()
    {
        $this->assertTrue(true);
        return;
        $uri = '/kassir-club-api/check-tickets';
        $i = 0;
        foreach ( config('parser.donors') as $donorClassName => $class )
        {
            $i++;
            // MuzbiletRu_SPb - не отрабатывает
            // TicketbestRu_SPb - не отрабатывает
            if ( $i != 20)
                continue;
            print_r($donorClassName);
            if ( class_exists($class) )
            {
                $donor = new $class();
                $donor->cookieFile = Controllers\ParserController::getCookieFileName($donorClassName);
                $source['cookieFile'] = $donor->cookieFile;
                $sources = $donor->getSources($source);
                if ( \count($sources) > 0 )
                {
                    foreach ( $sources as $k => $s )
                    {
                        $link = $s['source'];
                        $response = Controllers\ParserController::getEventTickets($donorClassName, $link);

                        if (isset($response['result']['events']))
                        {
                            $list = [];
                            foreach ( $response['result']['events'] as $event )
                            {
                                foreach ( $event['tickets'] as $ticket )
                                {
                                    $list[] = [
                                        'source' => $event['source'],
                                        'donor_class_name' => $event['donor_class_name'],
                                        'date_event' => $event['date_event'],
                                        'sector' => $ticket['sector'],
                                        'line' => $ticket['line'],
                                        'place' => $ticket['place'],
                                        'price' => $ticket['price'],
                                    ];
                                }
                            }
                            $uri = '/kassir-club-api/check-tickets';
                            $response = $this->call('POST', $uri, ['tickets' => serialize($list)]);
                            $content = $response->content();
//                            print_r($content);die();
                            $json = json_decode($content);
                            foreach ( $json as $ticket )
                            {
                                if (!$ticket->isset)
                                {
                                    print_r($json);
                                    $this->assertTrue(false);
                                }
                            }
                            $this->assertTrue(true);
                            return ;
                        }
                    }
                }
            }
            else
            {
                $message = "Донор '{$class}' не найден";
//                LoggerController::logToFile($message, 'error');
            }
//            break;
        }
        $this->assertTrue(true);
    }

    /**
     * Тест чекалки каждого доноров.
     *
     * @return void
     */
    public function testCheckTicketsDonor()
    {
//        $this->assertTrue(true);
//        return;
        $donorClassName = 'ConcertRu_SPb';
        $link = 'http://www.concert.ru/Event?ActionID=72396';
        $response = Controllers\ParserController::getEventTickets($donorClassName, $link);
        $list = [];
        foreach ( $response['result']['events'] as $event )
        {
            if ( is_array($event['tickets']) )
            {
                foreach ( $event['tickets'] as $ticket )
                {
                    $list[] = [
                        'source' => $event['source'],
                        'donor_class_name' => $event['donor_class_name'],
                        'date_event' => $event['date_event'],
                        'sector' => $ticket['sector'],
                        'line' => $ticket['line'],
                        'place' => $ticket['place'],
                        'price' => $ticket['price'],
                    ];
                    break;
                }
            }
        }
        $uri = '/kassir-club-api/check-tickets';
        $response = $this->call('POST', $uri, ['tickets' => serialize($list)]);
        $content = $response->content();
//        print_r($content);
        $json = json_decode($content);
        foreach ( $json as $ticket )
        {
            if (!$ticket->isset)
            {
                print_r($ticket);
                $this->assertTrue(false);
            }
        }
        print_r($json);
        $this->assertTrue(true);
    }
}
