<?php

namespace Partitech\FixUnSerialize;

class UnSerializeService
{
    /**
     * @param string $data
     * @return mixed
     */
    public function unserialize(string $data)
    {
        $data = $this->fixIfInvalid($data);
        return unserialize($data);
    }

    /**
     * @param string $data
     * @return string|null
     */
    public function fixIfInvalid(string $data): ?string
    {
        if (!$this->isValid($data)) {
            $data = $this->fix($data);
        }
        return $data;
    }

    /**
     * @param string $data
     * @return bool
     */
    public function isValid(string $data): bool
    {
        if (!@unserialize($data)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $data
     * @return string
     */
    public function fix(string $data): string
    {
        $pattern = '/s\:(\d+)\:\"(.*?)\";/s';
        return preg_replace_callback($pattern, [$this, 'fixLength'], $data);
    }

    /**
     * @param array $values
     * @return string
     */
    public function fixLength(array $values): string
    {
        $string = $values[2];
        $length = strlen($string);
        return 's:' . $length . ':"' . $string . '";';
    }
}