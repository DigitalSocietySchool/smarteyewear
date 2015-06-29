<?php

class UpdateScoreEventHandler {
 
    CONST EVENT = 'checklist.new';
    CONST CHANNEL = 'checklist.new';
 
    public function handle($data)
    {
        $redis = Redis::connection();
        $redis->publish(self::CHANNEL, $data);
    }
}

?>