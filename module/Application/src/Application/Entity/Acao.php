<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acao
 *
 * @ORM\Table(name="acao")
 * @ORM\Entity
 */
class Acao
{
    /**
     * @var integer
     *
     * @ORM\Column(name="codigo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="acao_codigo_seq", allocationSize=1, initialValue=1)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=100, nullable=true)
     */
    private $descricao;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Modulo", mappedBy="acao")
     */
    private $modulo;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->modulo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Acao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Add modulo
     *
     * @param \Application\Entity\Modulo $modulo
     *
     * @return Acao
     */
    public function addModulo(\Application\Entity\Modulo $modulo)
    {
        $this->modulo[] = $modulo;

        return $this;
    }

    /**
     * Remove modulo
     *
     * @param \Application\Entity\Modulo $modulo
     */
    public function removeModulo(\Application\Entity\Modulo $modulo)
    {
        $this->modulo->removeElement($modulo);
    }

    /**
     * Get modulo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModulo()
    {
        return $this->modulo;
    }
}
