<?php

namespace Swoft\Web\Session\Handler;

use Swoft\Bean\Annotation\Bean;
use Swoft\Cache\Redis\RedisClient;


/**
 * @Bean()
 * @uses      FileSessionHandler
 * @version   2017年12月05日
 * @author    huangzhhui <huangzhwork@gmail.com>
 * @copyright Copyright 2010-2017 Swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class RedisSessionHandler implements \SessionHandlerInterface
{

    /**
     * @var string
     */
    private $prefix = 'swoft_session';

    /**
     * @var string
     */
    private $glue = ':';

    /**
     * @var int
     */
    private $minutes;

    /**
     * RedisSessionHandler constructor.
     *
     * @param int $minutes
     */
    public function __construct($minutes = 15)
    {
        $this->minutes = $minutes;
    }

    /**
     * @inheritdoc
     */
    public function close()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function destroy($sessionId)
    {
        return (bool)RedisClient::del($this->key($sessionId));
    }

    /**
     * @inheritdoc
     */
    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function open($savePath, $name)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function read($sessionId)
    {
        $value = RedisClient::get($this->key($sessionId));
        return $value ? $this->unserialize($value) : '';
    }

    /**
     * @inheritdoc
     */
    public function write($sessionId, $data)
    {
        return (bool)RedisClient::set($this->key($sessionId), $this->serialize($data));
    }

    /**
     * @param string $sessionId
     * @return string
     */
    protected function key(string $sessionId): string
    {
        return implode($this->glue, [$this->prefix, $sessionId]);
    }

    /**
     * Serialize the value
     *
     * @param $value
     * @return int|string
     */
    protected function serialize($value)
    {
        return is_numeric($value) ? $value : serialize($value);
    }

    /**
     * Unserialize the value
     *
     * @param $value
     * @return int|string
     */
    protected function unserialize($value)
    {
        return is_numeric($value) ? $value : unserialize($value, null);
    }

}