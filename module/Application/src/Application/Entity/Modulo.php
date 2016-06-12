<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modulo
 *
 * @ORM\Table(name="modulo")
 * @ORM\Entity
 */
class Modulo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="codigo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="modulo_codigo_seq", allocationSize=1, initialValue=1)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=100, nullable=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="controller", type="string", length=500, nullable=true)
     */
    private $controller;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Acao", inversedBy="modulo")
     * @ORM\JoinTable(name="modulo_acao",
     *   joinColumns={
     *     @ORM\JoinColumn(name="modulo", referencedColumnName="codigo")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="acao", referencedColumnName="codigo")
     *   }
     * )
     */
    private $acao;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Papel", inversedBy="modulo")
     * @ORM\JoinTable(name="modulo_papel",
     *   joinColumns={
     *     @ORM\JoinColumn(name="modulo", referencedColumnName="codigo")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="papel", referencedColumnName="codigo")
     *   }
     * )
     */
    private $papel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acao = new \Doctrine\Common\Collections\ArrayCollection();
        $this->papel = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get codigo
     *
     * @return integer
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nome
     *
     * @param string $nome
     *
     * @return Modulo
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set controller
     *
     * @param string $controller
     *
     * @return Modulo
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Add acao
     *
     * @param \Doctrine\Common\Collections\Collection $acao
     *
     * @return Modulo
     */
    public function addAcao(\Doctrine\Common\Collections\Collection $acao)
    {
        if ($this->acao->contains($acao)) {
            return;
        }
        foreach ($acao as $tag) {
            $this->acao->add($tag);
        }
        
        return $this;
    }

    /**
     * Remove acao
     *
     * @param \Doctrine\Common\Collections\Collection $acao
     */
    public function removeAcao(\Doctrine\Common\Collections\Collection $acao)
    {
        foreach ($acao as $tag) {
            $this->acao->removeElement($tag);
        }
    }

    /**
     * Get acao
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * Add papel
     *
     * @param \Application\Entity\Papel $papel
     *
     * @return Modulo
     */
    public function addPapel(\Doctrine\Common\Collections\Collection $papel)
    {
        $this->papel[] = $papel;

        return $this;
    }

    /**
     * Remove papel
     *
     * @param \Application\Entity\Papel $papel
     */
    public function removePapel(\Application\Entity\Papel $papel)
    {
        $this->papel->removeElement($papel);
    }

    /**
     * Get papel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPapel()
    {
        return $this->papel;
    }
}
