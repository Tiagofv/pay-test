<?php


namespace App\Services;



class MockAuthorizeService extends BaseService
{
    /**
     * @var string
     */
    protected $baseUrl = 'https://run.mocky.io';


    /**
     * @return mixed
     */
    public function authorizeTransaction() : mixed
    {
        return $this->doRequest('GET', 'v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
    }
}
