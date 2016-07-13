<?php

namespace Tdn\PhpTypes\Memory;

/**
 * Class Memory.
 *
 * Credit for original class goes to https://github.com/alquerci/php-types-autoboxing
 */
class Memory
{
    /**
     * Cycles in which to run gc_collect_cycles.
     */
    const GC_ROOT_BUFFER_MAX_ENTRIES = 10000;

    /**
     * @var int
     */
    private static $entriesCount = 0;

    /**
     * @var int
     */
    private static $lastAddress = 0;

    /**
     * @var array
     */
    private static $collection = [];

    /**
     * @var bool
     */
    private static $registered = false;

    /**
     * @var bool
     */
    private static $gcEnabled = true;

    private function __construct()
    {
    }

    /**
     * @param bool $gcEnabled
     */
    public static function setGcEnabled(bool $gcEnabled)
    {
        self::$gcEnabled = $gcEnabled;
    }

    /**
     * @return bool
     */
    public static function isGcEnabled() : bool
    {
        return self::$gcEnabled;
    }

    /**
     * @return string
     */
    public static function getLastAddress() : string
    {
        return self::$lastAddress;
    }

    /**
     * @return int
     */
    public static function getEntriesCount() : int
    {
        return self::$entriesCount;
    }

    /**
     * @return array
     */
    public static function getCollection() : array
    {
        return self::$collection;
    }

    /**
     * Sets a memory address and pointer.
     *
     * @param $address
     * @param $pointer
     */
    public static function setAddress($address, &$pointer)
    {
        self::register();

        if (array_key_exists($address, self::$collection)) {
            throw new \RuntimeException('Address already exists in memory.');
        }

        self::$collection[$address] = &$pointer;
        self::$lastAddress = $address;
        ++self::$entriesCount;
    }

    /**
     * Gets a new address for a pointer, and assigns pointer to it.
     *
     * @param mixed $pointer
     *
     * @return string The address.
     */
    public static function getNewAddress(&$pointer) : string
    {
        self::register();

        if (self::isGcEnabled() && (self::$entriesCount % self::GC_ROOT_BUFFER_MAX_ENTRIES) === 0) {
            //Force some GC collection.
            gc_collect_cycles();
        }

        $address = self::createAddress();
        self::$collection[$address] = &$pointer;
        ++self::$entriesCount;

        return $address;
    }

    /**
     * Returns a pointer by reference.
     *
     * @param string $id
     *
     * @return pointer via reference.
     */
    public static function &getPointer(string $id)
    {
        if (!array_key_exists($id, self::$collection)) {
            throw new \OutOfBoundsException('Address does not exist.');
        }

        return self::$collection[$id];
    }

    /**
     * Removes a pointer from current collection if exists.
     *
     * @param string $address
     *
     * @return bool
     */
    public static function shouldFree(string $address) : bool
    {
        if (!array_key_exists($address, self::$collection)) {
            return false;
        }

        unset(self::$collection[$address]);
        --self::$entriesCount;

        return true;
    }

    /**
     * Called when php shuts down.
     *
     * Cleans left over pointers not explicitly destroyed. Keeps memory leaks at bay.
     */
    public static function shutdown()
    {
        foreach (self::$collection as $address => &$pointer) {
            $pointer = null;
            unset(self::$collection[$address]);
        }

        gc_collect_cycles();
    }

    /**
     * Register shutdown function with php.
     */
    private static function register()
    {
        if (!self::$registered) {
            register_shutdown_function([self::class, 'shutdown']);
            self::$registered = true;
        }
    }

    /**
     * Clears object. Mainly for testing.
     */
    public static function close()
    {
        self::$collection = [];
        self::$lastAddress = 0;
        self::$entriesCount = 0;
        self::$registered = false;
        self::$gcEnabled = false;
    }

    /**
     * Creates a new address for the a pointer.
     *
     * @return string
     */
    private static function createAddress() : string
    {
        if (self::$lastAddress === PHP_INT_MAX || !is_numeric(self::$lastAddress)) {
            do {
                $address = hash('sha1', uniqid(mt_rand(), true));
            } while (isset(self::$collection[$address]) || array_key_exists($address, self::$collection));
        } else {
            $address = ++self::$lastAddress;
        }

        return strval($address);
    }
}
