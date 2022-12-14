<?php

namespace App\Entity;


class DisplayedArticle
{

    //constructor with default values$
    private ?string $title = null;
    private ?string $content = null;
    private ?string $id = null;
    private ?string $author = null;
    private ?string $thumbnailsFileName = null;
    private ?string $category = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title, bool $short = false): self
    {
        $this->title = $title;

        if ($short && strlen($title) > 70) {
            $this->title = substr($this->title, 0, 70) . '...';
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content, bool $short = false): self
    {
        $this->content = $content;

        if ($short && strlen($this->content) > 90) {
            $this->content = substr($this->content, 0, 90) . '...';
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getThumbnailsFileName(): ?string
    {
        return $this->thumbnailsFileName;
    }

    public function setThumbnailsFileName(string $thumbnailsFileName): self
    {
        $this->thumbnailsFileName = $thumbnailsFileName;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }
}
