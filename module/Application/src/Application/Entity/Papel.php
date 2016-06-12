<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Papel
 *
 * @ORM\Table(name="papel")
 * @ORM\Entity
 */
class Papel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="codigo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="papel_codigo_seq", allocationSize=1, initialValue=1)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=100, nullable=true)
     */
    private $descricao;

    /**
     * @var integer
     *
     * @ORM\Column(name="publico", type="integer", nullable=true)
     */
    private $publico = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\OauthUsers", mappedBy="papel")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Modulo", inversedBy="papel")
     * @ORM\JoinTable(
     *  name="modulo_papel",
     *  joinColumns={
     *      @ORM\JoinColumn(name="papel", referencedColumnName="codigo")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="modulo", referencedColumnName="codigo")
     *  }
     * )
     */
    private $modulo;

    
    /**
     * @var \Doctrine\Common\Collections\Collection|Papel[]
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Usuario", mappedBy="Papel")
     */
    protected $usuario;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = new \Doctrine\Common\Collections\ArrayCollection();
        $this->modulo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario = new ArrayCollection();
    }


    /**
     * @param \Application\Entity\Usuario $usuario
     */
    public function addUsuario(\Doctrine\Common\Collections\Collection $usuario)
    {
        if ($this->usuario->contains($usuario)) {
            return;
        }
        $this->usuario->add($usuario);
        $user->addUsuario($this);
    }
    /**
     * @param \Application\Entity\Usuario $usuario
     */
    public function removeUsuario(\Doctrine\Common\Collections\Collection $usuario)
    {
        if (!$this->usuario->contains($usuario)) {
            return;
        }
        $this->usuario->removeElement($usuario);
        $user->removeUsuario($this);
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
     * @return Papel
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
     * Set publico
     *
     * @param integer $publico
     *
     * @return Papel
     */
    public function setPublico($publico)
    {
        $this->publico = $publico;

        return $this;
    }

    /**
     * Get publico
     *
     * @return integer
     */
    public function getPublico()
    {
        return $this->publico;
    }

    /**
     * Add modulo
     *
     * @param \Doctrine\Common\Collections\Collection $modulo
     *
     */
    public function addModulo(\Doctrine\Common\Collections\Collection $modulo)
    {
        if ($this->modulo->contains($modulo)) {
            return;
        }
        foreach ($modulo as $tag) {
            $this->modulo->add($tag);
        }
        
        //return $this;
    }

    /**
     * Remove modulo
     *
     * @param \Doctrine\Common\Collections\Collection $modulo
     */
    public function removeModulo(\Doctrine\Common\Collections\Collection $modulo)
    {
     foreach ($modulo as $tag) {
            $this->modulo->removeElement($tag);
        }
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
