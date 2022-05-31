<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SongRepository;
use App\Repository\PlaylistRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: SongRepository::class)]
#[ApiResource(
    attributes : ['enable_max_depth' => false],
    collectionOperations: ['get' => ['normalization_context' => ['groups' => 'song:list']]],
    itemOperations: ['get' => ['normalization_context' => ['groups' => 'song:item']]], 
)]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["user:list", "user:item", "playlist:list", "playlist:item", "song:list", "song:item", "artist:list", "artist:item"])]
    private $id;
    
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["artist:list", "artist:item", "playlist:list", "playlist:item", "song:list", "song:item"])]
    private $title;

    #[ORM\ManyToMany(targetEntity: Playlist::class, inversedBy: 'songs')]
    #[Groups(["song:list", "song:item", "artist:list", "artist:item"])]
    private $playlist;
    
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["song:list", "song:item", "artist:list", "artist:item", "playlist:list", "playlist:item"])]
    private $song;
    
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'loved_songs')]
    #[Groups(["artist:list", "artist:item"])]
    private $users_loved;
    
    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'songs')]
    #[Groups(["song:list", "song:item"])]
    private $Artist;

    public function __construct()
    {
        $this->playlist = new ArrayCollection();
        $this->users_loved = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylist(): Collection
    {
        return $this->playlist;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlist->contains($playlist)) {
            $this->playlist[] = $playlist;
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        $this->playlist->removeElement($playlist);

        return $this;
    }

    public function getSong(): ?string
    {
        return $this->song;
    }

    public function setSong(string $song): self
    {
        $this->song = $song;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersLoved(): Collection
    {
        return $this->users_loved;
    }

    public function addUsersLoved(User $usersLoved): self
    {
        if (!$this->users_loved->contains($usersLoved)) {
            $this->users_loved[] = $usersLoved;
            $usersLoved->addLovedSong($this);
        }

        return $this;
    }

    public function removeUsersLoved(User $usersLoved): self
    {
        if ($this->users_loved->removeElement($usersLoved)) {
            $usersLoved->removeLovedSong($this);
        }

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->Artist;
    }

    public function setArtist(?Artist $Artist): self
    {
        $this->Artist = $Artist;

        return $this;
    }
}
