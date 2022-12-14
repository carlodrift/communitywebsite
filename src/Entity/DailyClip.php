<?php

namespace App\Entity;

use App\Repository\DailyClipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DailyClipRepository::class)]
class DailyClip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        if (str_contains($this->link, 'youtube.com/watch?v=')) {
            $this->link = str_replace('watch?v=', 'embed/', $this->link);
        } elseif (str_contains($this->link, 'youtu.be/')) {
            $this->link = str_replace('youtu.be/', 'youtube.com/embed/', $this->link);
        } elseif (str_contains($this->link, 'vimeo.com/')) {
            $this->link = str_replace('vimeo.com/', 'player.vimeo.com/video/', $this->link);
        } elseif (str_contains($this->link, 'dailymotion.com/')) {
            $this->link = str_replace('dailymotion.com/', 'dailymotion.com/embed/', $this->link);
        } elseif (str_contains($this->link, 'youtube.com/shorts/')) {
            $this->link = str_replace('youtube.com/shorts/', 'youtube.com/embed/', $this->link);
        } else {
            $this->link = null;
        }
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
