<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OauthUsers
 *
 * @ORM\Table(name="oauth_users", uniqueConstraints={@ORM\UniqueConstraint(name="oauth_users_username_pk", columns={"username"})}, indexes={@ORM\Index(name="IDX_93804FF86A98335C", columns={"cidade"}), @ORM\Index(name="IDX_93804FF8265DE1E3", columns={"estado"}), @ORM\Index(name="IDX_93804FF84180C698", columns={"locale"})})
 * @ORM\Entity
 */
class OauthUsers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="oauth_users_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=2000, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var integer
     *
     * @ORM\Column(name="empresa", type="integer", nullable=true)
     */
    private $empresa = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="cep", type="string", length=500, nullable=true)
     */
    private $cep;

    /**
     * @var \Application\Entity\Cidade
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Cidade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cidade", referencedColumnName="id")
     * })
     */
    private $cidade;

    /**
     * @var \Application\Entity\Estado
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Estado")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="estado", referencedColumnName="id")
     * })
     */
    private $estado;

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
     * @ORM\ManyToMany(targetEntity="Application\Entity\Papel", inversedBy="id")
     * @ORM\JoinTable(name="oauth_papel",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id", referencedColumnName="id")
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
        $this->papel = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return OauthUsers
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return OauthUsers
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return OauthUsers
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return OauthUsers
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set empresa
     *
     * @param integer $empresa
     *
     * @return OauthUsers
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return integer
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set cep
     *
     * @param string $cep
     *
     * @return OauthUsers
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Get cep
     *
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Set cidade
     *
     * @param \Application\Entity\Cidade $cidade
     *
     * @return OauthUsers
     */
    public function setCidade(\Application\Entity\Cidade $cidade = null)
    {
        $this->cidade = $cidade;

        return $this;
    }

    /**
     * Get cidade
     *
     * @return \Application\Entity\Cidade
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * Set estado
     *
     * @param \Application\Entity\Estado $estado
     *
     * @return OauthUsers
     */
    public function setEstado(\Application\Entity\Estado $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \Application\Entity\Estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set locale
     *
     * @param \Application\Entity\Locale $locale
     *
     * @return OauthUsers
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

    /**
     * Add papel
     *
     * @param \Application\Entity\Papel $papel
     *
     * @return OauthUsers
     */
    public function addPapel(\Application\Entity\Papel $papel)
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
