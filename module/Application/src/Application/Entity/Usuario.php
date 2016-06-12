<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", indexes={@ORM\Index(name="IDX_2265B05DB8D75A50", columns={"empresa"}), @ORM\Index(name="IDX_2265B05D4180C698", columns={"locale"})})
 * @ORM\Entity
 */
class Usuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="codigo", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="usuario_codigo_seq", allocationSize=1, initialValue=1)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=100, nullable=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="senha", type="string", length=500, nullable=true)
     */
    private $senha;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="text", nullable=true)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="date", nullable=true)
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora", type="time", nullable=true)
     */
    private $hora;

    /**
     * @var \Application\Entity\Locale
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Locale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="locale", referencedColumnName="codigo")
     * })
     */
    private $locale;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Papel", inversedBy="usuario")
     * @ORM\JoinTable(
     *  name="usuario_papel",
     *  joinColumns={
     *      @ORM\JoinColumn(name="usuario", referencedColumnName="codigo")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="papel", referencedColumnName="codigo")
     *  }
     * )
     */

    private $papel;
    
    /**
     * Default constructor, initializes collections
     */
    public function __construct()
    {
        $this->papel = new ArrayCollection();
    }
    
    
    /**
     * @param \Application\Entity\Papel $papel
     */
    public function addPapel(\Doctrine\Common\Collections\Collection $papel)
    {
        if ($this->papel->contains($papel)) {
            return;
        }
        foreach ($papel as $tag) {
            $this->papel->add($tag);
        }
        //return $this;
    }
    /**
     * @param \Application\Entity\Papel $papel
     */
    public function removePapel(\Doctrine\Common\Collections\Collection $papel)
    {
//         if (!$this->papel->contains($papel)) {
//             return;
//         }
        foreach ($papel as $tag) {
            $this->papel->removeElement($tag);
        }
    }
    
    /**
     * Get papel
     *
     * @return integer
     */
    public function getPapel()
    {
        return $this->papel;
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
     * Set login
     *
     * @param string $login
     *
     * @return Usuario
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set senha
     *
     * @param string $senha
     *
     * @return Usuario
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }

    /**
     * Get senha
     *
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Usuario
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set data
     *
     * @param \DateTime $data
     *
     * @return Usuario
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     *
     * @return Usuario
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set empresa
     *
     * @param \Application\Entity\ClienteEmpresa $empresa
     *
     * @return Usuario
     */
    public function setEmpresa(\Application\Entity\ClienteEmpresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \Application\Entity\ClienteEmpresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set locale
     *
     * @param \Application\Entity\Locale $locale
     *
     * @return Usuario
     */
    public function setLocale(\Application\Entity\Locale $locale = null)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return \Application\Entity\Locale
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
