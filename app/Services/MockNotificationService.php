<?php


namespace App\Services;


class MockNotificationService extends BaseService
{
//    protected $baseUrl = "http://o4d9z.mocklab.io/notify";
    protected $baseUrl = "https://run.mocky.io/v3/";
    /**
     * @return mixed
     */
    public function sendNotification() : mixed
    {
        return $this->doRequest('GET', '/4eca95a0-49db-479c-ba4b-1ecd567ca239');
    }
}
