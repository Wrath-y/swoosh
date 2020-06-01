<?php

namespace App\Controllers\API;

use Src\App;
use App\Controllers\Controller;

class ElasticSearchController extends Controller
{
    /**
     * @Get('/api/es_index')
     */
    public function es_index()
    {
        $resp = \ES::index('test_index_1', 'test', ['name' => 'wrath']);

        return success($resp);
    }

    /**
     * @Get('/api/es_bulk')
     */
    public function es_bulk()
    {
        $resp = \ES::bulk([
            [
                'index' => 'test_index_2',
                'type' => 'test',
                'source' => [
                    'name' => 'wrath1'
                ]
            ]
        ]);
        
        return success($resp);
    }

    /**
     * @Get('/api/es_get')
     */
    public function es_get()
    {
        $resp = \ES::get('test_index_1', 'test', '196752254659723264');
        
        return success($resp);
    }

    /**
     * @Get('/api/es_update')
     */
    public function es_update()
    {
        $resp = \ES::update('test_index_1', 'test', '196752254659723264', [
            'name' => 'wrath1',
            'age' => '18'
        ]);
        
        return success($resp);
    }

    /**
     * @Get('/api/es_update_script')
     */
    public function es_update_script()
    {
        $resp = \ES::update_by_script('test_index_1', 'test', '196752254659723264', [
            'script' => 'ctx._source.age += 1'
        ]);
        
        return success($resp);
    }

    /**
     * @Get('/api/es_match')
     */
    public function es_match()
    {
        $resp = \ES::search('test_index_1', 'test', [
            'query' => [
                'match' => [
                    'name' => 'wrath1'
                ]
            ]
        ]);
        
        return success($resp);
    }

    /**
     * @Get('/api/es_bool_match')
     */
    public function es_bool_match()
    {
        $resp = \ES::search('test_index_1', 'test', [
            'query' => [
                'bool' => [
                    'must' => [
                        ['match' => ['name' => 'wrath1']],
                        ['match' => ['age' => '18']]
                    ]
                ]
            ]
        ]);
        
        return success($resp);
    }
}