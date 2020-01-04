<?php

namespace Src\RPCServer\Connections;

use Src\App;

class ConsulConnection extends Connection
{
    /**
     * @var string The id of this service
     */
    protected $service_id;

    /**
     * @var string The name of this service
     */
    protected $name;

    /**
     * @var array The tags of this service
     */
    protected $tags = [];

    /**
     * @var string The address of this register server
     */
    protected $remote_host = '127.0.0.1';

    /**
     * @var int The port of this register server
     */
    protected $remote_port = 8500;

    /**
     * @var string Health check url
     */
    protected $health_check_url = 'http://127.0.0.1';

    /**
     * @var string Health check interval
     */
    protected $health_check_interval = '10s';

    public function __construct()
    {
        $consul_config = App::get('config')->get('app.consul');
        $rpc_server_config = App::get('config')->get('app.rpc_server');
        $this->service_id = $consul_config['id'];
        $this->name = $consul_config['name'];
        $this->tags = $consul_config['tags'];
        $this->host = $rpc_server_config['host'];
        $this->port = (int)$rpc_server_config['port'];
        $this->remote_host = $consul_config['remote_host'];
        $this->remote_port = $consul_config['remote_port'];
        $this->health_check_url = $consul_config['health_check_url'];
        $this->health_check_interval = $consul_config['health_check_interval'];
    }

    /**
     * Generate the data required by register
     * @return array
     */
    public function generateRegisterData()
    {
        return [
            'ID' => $this->service_id,
            'Name' => $this->name,
            'Tags' => $this->tags,
            'Address' => $this->host,
            'Port' => $this->port,
            'Check' => [
                'HTTP' => $this->host . ':' . $this->port . $this->health_check_url,
                'Interval' => $this->health_check_interval
            ]
        ];
    }

    /**
     * Register service
     * @return string
     */
    public function register()
    {
        return $this->put('/v1/agent/service/register', $this->generateRegisterData());
    }

    /**
     * deregister service
     * @return string
     */
    public function destruct()
    {
        return $this->put('/v1/agent/service/deregister/'.$this->service_id);
    }

    /**
     * consul put api
     * @param string $uri
     * @param string $params It can be a detail of service
     * @return bool
     */
    public function put($uri, array $params = [])
    {
        try {
            $ch = curl_init();
            $header[] = 'Content-type:application/json';
    
            curl_setopt($ch, CURLOPT_URL, $this->remote_host.':'.$this->remote_port.$uri);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"PUT");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params, JSON_UNESCAPED_UNICODE));
    
            $res = curl_exec($ch);
            curl_close($ch);
            if ($res === '') {
                return true;
            }
        } catch(\Exception $e) {
            throw $e;
        }
    }
}