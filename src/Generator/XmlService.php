<?php

namespace CloudCastle\Xml\Generator;

use CloudCastle\Storage\DisksEnum;
use CloudCastle\Storage\Storage;
use CloudCastle\Xml\Exceptions\Generator\XmlServiceException;
use CloudCastle\Xml\Interfaces\XmlServiceInterface;
use XMLWriter;

/**
 *
 */
final class XmlService implements XmlServiceInterface
{
    /**
     * @var XMLWriter|null
     */
    private XMLWriter|null $obj = null;

    /**
     * @param string $version
     * @param string $encoding
     */
    public function __construct(string $version = '1.0', string $encoding = 'utf-8')
    {
        $this->obj = new XMLWriter();
        $this->obj->openMemory();
        $this->obj->startDocument($version, $encoding);
    }

    /**
     * @param string $name
     * @param string|null $text
     * @param array|null $attributes
     * @param string|null $comment
     * @param bool $close
     * @return $this
     */
    public function addElement(
        string      $name,
        string|null $text = null,
        array|null  $attributes = null,
        string|null $comment = null,
        bool        $close = true,
        bool        $closeIfNull = false,
    ): self
    {
        if ($text === null && $closeIfNull) {
            $this->startElement($name, $text, $attributes, $comment);

            if ($close) {
                $this->closeElement();
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string|null $text
     * @param array|null $attributes
     * @param string|null $comment
     * @return $this
     */
    public function startElement(
        string      $name,
        string|null $text = null,
        array|null  $attributes = null,
        string|null $comment = null
    ): self
    {
        if ($comment) {
            $this->addComment($comment);
        }

        $this->obj->startElement($name);

        if ($attributes) {
            $this->addAttributes($attributes);
        }

        if ($text) {
            $this->obj->text($text);
        }

        return $this;
    }

    /**
     * @param string $comment
     * @return $this
     */
    public function addComment(string $comment): self
    {
        $this->obj->startComment();
        $this->obj->text($comment);
        $this->obj->endComment();

        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes): self
    {
        foreach ($attributes as $k => $v) {
            if (is_string($k) && $v !== null) {
                $this->addAttribute($k, (string)$v);
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $text
     * @return $this
     */
    public function addAttribute(string $name, string $text): self
    {
        $this->obj->startAttribute($name);
        $this->obj->text($text);
        $this->obj->endAttribute();

        return $this;
    }

    /**
     * @return $this
     */
    public function closeElement(): self
    {
        $this->obj->endElement();

        return $this;
    }

    /**
     * @param string $file
     * @return string
     * @throws XmlServiceException
     */
    public function save(string $file): string
    {
        $disk = DisksEnum::LOCAL;

        if (Storage::disk($disk)->put($file, $this->get())) {
            return Storage::disk($disk)->path($file);
        }

        throw new XmlServiceException('Не удалось записать данные в файл "' . $file . '"');
    }

    /**
     * @return string
     */
    public function get(): string
    {
        $this->obj->endDocument();

        return $this->obj->outputMemory();
    }

}