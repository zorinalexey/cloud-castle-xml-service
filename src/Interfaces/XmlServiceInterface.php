<?php

namespace CloudCastle\Xml\Interfaces;

/**
 *
 */
interface XmlServiceInterface
{
    /**
     * @param string $version
     * @param string $encoding
     */
    public function __construct(string $version = '1.0', string $encoding = 'utf-8');

    /**
     * @return self
     */
    public function closeElement(): self;

    /**
     * @param string $name
     * @param string $text
     * @return self
     */
    public function addAttribute(string $name, string $text): self;

    /**
     * @param array $attributes
     * @return self
     */
    public function addAttributes(array $attributes): self;

    /**
     * @param string $name
     * @param string|int|bool|null $text
     * @param array|null $attributes
     * @param string|null $comment
     * @return $this
     */
    public function startElement(
        string      $name,
        string|int|bool|null  $text = null,
        array|null  $attributes = null,
        string|null $comment = null
    ): self;

    /**
     * @param string $comment
     * @return self
     */
    public function addComment(string $comment): self;

    /**
     * @param string $name
     * @param string|int|bool|null  $text
     * @param array|null $attributes
     * @param string|null $comment
     * @param bool $close
     * @param bool $createIfTextNull
     * @return self
     */
    public function addElement(
        string      $name,
        string|int|bool|null  $text = null,
        array|null  $attributes = null,
        string|null $comment = null,
        bool        $close = true,
        bool        $createIfTextNull = true
    ): self;

    /**
     * @param string $file
     * @return string Путь до XML файла
     */
    public function save(string $file): string;

    /**
     * @return string структура XML файла
     */
    public function get(): string;

}