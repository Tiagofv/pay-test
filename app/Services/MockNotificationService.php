<?php


namespace App\Services;


class MockNotificationService extends BaseService
{
    protected $baseUrl = "http://o4d9z.mocklab.io/notify";
    /**
     * @return mixed
     */
    public function sendNotification() : mixed
    {
        return $this->doRequest('GET', '');
    }
}
