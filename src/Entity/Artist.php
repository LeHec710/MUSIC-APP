<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
#[ApiResource(
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'artist:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'artist:item']]],
)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["playlist:list", "playlist:item", "artist:list", "artist:item", "song:list", "song:item"])]    
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["artist:list", "artist:item", "playlist:list", "playlist:item", "song:list", "song:item"])]
    private $name;

    #[ORM\OneToMany(mappedBy: 'artist', targetEntity: Playlist::class)]
    #[Groups(["artist:list", "artist:item"])]
    private $albums;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["artist:list", "artist:item", "playlist:list", "playlist:item"])]
    private $image;

    #[ORM\OneToMany(mappedBy: 'Artist', targetEntity: Song::class)]
    #[Groups(["artist:list", "artist:item", "playlist:list", "playlist:item"])]
    private $songs;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Playlist $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->setArtist($this);
        }

        return $this;
    }

    public function removeAlbum(Playlist $album): self
    {
        if ($this->albums->removeElement($album)) {
            // set the owning side to null (unless already changed)
            if ($album->getArtist() === $this) {
                $album->setArtist(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
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
            $song->setArtist($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->removeElement($song)) {
            // set the owning side to null (unless already changed)
            if ($song->getArtist() === $this) {
                $song->setArtist(null);
            }
        }

        return $this;
    }
}
