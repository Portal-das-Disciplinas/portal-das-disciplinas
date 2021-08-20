<?php

namespace App\Services\FileReader;

use Illuminate\Support\Collection;

class CsvReader
{
    const SEPARATOR_SEMICOLON = ';';
    const SEPARATOR_COMMA = ',';

    /**
     * @var array|array[]
     */
    private $headers = [];

    /**
     * @var array|array[]
     */
    private $data = [];

    /**
     * CsvReader constructor.
     * @param string $filePath
     * @param string $separator
     * @param false $header
     */
    public function __construct(string $filePath, string $separator = self::SEPARATOR_COMMA, $header = false)
    {
        $this->data = array_map(function ($v) use ($separator) {
            return str_getcsv($v, $separator);
        }, file($filePath));

        if ($header) {
            $this->headers = array_shift($this->data);
        }
    }

    /**
     * @return array|array[]
     */
    public function getAll(): array
    {
        return array_merge($this->getHeaders(), $this->getData());
    }

    /**
     * @return array
     */
    public function getAllAsAssociativeArray(): array
    {
        $return = $this->data;
        $keys = $this->headers;

        array_walk($return, function (&$values) use ($keys) {
            $values = array_combine($keys, $values);
        });

        return $return;
    }

    /**
     * @return Collection
     */
    public function getAsCollection(): Collection
    {
        return collect($this->getAllAsAssociativeArray());
    }

    /**
     * @param array $header
     */
    public function setHeader(array $header)
    {
        $this->headers = $header;
    }

    /**
     * @return array|array[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array|array[]
     */
    public function getData(): array
    {
        return $this->data;
    }
}
