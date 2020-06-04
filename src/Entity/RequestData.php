<?php

namespace App\Entity;

use App\Repository\RequestDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequestDataRepository::class)
 */
class RequestData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_ip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity=ApiData::class, mappedBy="request_id")
     */
    private $api_data;

    public function __construct()
    {
        $this->api_data = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIp(): ?int
    {
        return $this->user_ip;
    }

    public function setUserIp(int $user_ip): self
    {
        $this->user_ip = $user_ip;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|ApiData[]
     */
    public function getApiData(): Collection
    {
        return $this->api_data;
    }

    public function addApiData(ApiData $apiData): self
    {
        if (!$this->api_data->contains($apiData)) {
            $this->api_data[] = $apiData;
            $apiData->setRequestId($this);
        }

        return $this;
    }

    public function removeApiData(ApiData $apiData): self
    {
        if ($this->api_data->contains($apiData)) {
            $this->api_data->removeElement($apiData);
            // set the owning side to null (unless already changed)
            if ($apiData->getRequestId() === $this) {
                $apiData->setRequestId(null);
            }
        }

        return $this;
    }
}
