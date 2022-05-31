<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlaylistRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'playlist:list', 'enable_max_depth' => true]]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'playlist:item', 'enable_max_depth' => true]]],
)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["playlist:list", "playlist:item", "song:list", "song:item", "artist:list", "artist:item"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["playlist:list", "playlist:item", "song:list", "song:item", "artist:list", "artist:item"])]
    private $image;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'playlists')]
    private $owner;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'albums')]
    #[Groups(["playlist:list", "playlist:item", "song:list", "song:item"])]
    private $artist;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["playlist:list", "playlist:item", "song:list", "song:item"])]
    private $title;

    #[ORM\ManyToMany(targetEntity: Song::class, mappedBy: 'playlist')]
    #[Groups(["playlist:list", "playlist:item"])]
    private $songs;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(["playlist:list", "playlist:item", "song:list", "song:item"])]
    private $date;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->title;
        // to show the id of the Category in the select
        // return $this->id;
    }

    /**
     * @return Collection<int, Song>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs[] = $song;
            $song->addPlaylist($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->removeElement($song)) {
            $song->removePlaylist($this);
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
