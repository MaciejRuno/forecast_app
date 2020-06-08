<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ApiDataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApiDataRepository::class)
 */
class ApiData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $temperature;

    /**
     * @ORM\Column(type="float")
     */
    private $wind;

    /**
     * @ORM\Column(type="float")
     */
    private $humidity;

    /**
     * @ORM\Column(type="float")
     */
    private $rainfall;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=RequestData::class, inversedBy="api_data")
     */
    private $request;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getWind(): ?float
    {
        return $this->wind;
    }

    public function setWind(float $wind): self
    {
        $this->wind = $wind;

        return $this;
    }

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(float $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getRainfall(): ?float
    {
        return $this->rainfall;
    }

    public function setRainfall(float $rainfall): self
    {
        $this->rainfall = $rainfall;

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

    public function getRequestId(): ?RequestData
    {
        return $this->request_id;
    }

    public function setRequestId(?RequestData $request_id): self
    {
        $this->request_id = $request_id;

        return $this;
    }

    public function getRequest(): ?RequestData
    {
        return $this->request;
    }

    public function setRequest(?RequestData $request): self
    {
        $this->request = $request;

        return $this;
    }
}
