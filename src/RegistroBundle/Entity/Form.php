<?php

namespace RegistroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Form
 *
 * @ORM\Table(name="form")
 * @ORM\Entity(repositoryClass="RegistroBundle\Repository\FormRepository")
 * @Vich\Uploadable
 * @UniqueEntity("mail")
 * @ORM\HasLifecycleCallbacks
 */
class Form
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $apellidos;

    /**
     * @var bool
     *
     * @ORM\Column(name="sexo", type="boolean", nullable=true)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="text", nullable=true)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="resumen", type="text", nullable=true)
     */
    private $resumen;


    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="institucion", type="string", length=200)
     * @Assert\NotBlank()
     */
    private $institucion;

    /**
     * @var string
     *
     * @ORM\Column(name="profesor", type="string", length=100, nullable=true)
     */
    private $profesor;

    /**
     * @var string
     *
     * @ORM\Column(name="instprofesor", type="string", length=100 , nullable=true)
     */
    private $instprofesor;

    /**
     * @var string
     *
     * @ORM\Column(name="mailprofesor", type="string", length=50 , nullable=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true)
     */

    private $mailprofesor;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="fisteo19_historial", fileNameProperty="historialName")
     *
     * @Assert\File(
     *     maxSize = "2M",
     * uploadFormSizeErrorMessage = "El archivo debe ser menor a 2 MB",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     *
     * @var File
     */
    public $historialFile;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $historialName;

    /**
     * @var string
     *
     * @ORM\Column(name="beca", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $status;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="fisteo19_carta", fileNameProperty="cartaName")
     *
     * @Assert\File(
     *     maxSize = "2M",
     * uploadFormSizeErrorMessage = "El archivo debe ser menor a 2 MB",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Please upload a valid PDF"
     * )
     *
     * @var File
     */
    public $cartaFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cartaName;

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmado", type="boolean", nullable=true)
     */
    private $confirmado;

    /**
     * @var bool
     *
     * @ORM\Column(name="aceptado", type="boolean", nullable=true)
     */
    private $aceptado;

    /**
     * @var string
     *
     * @ORM\Column(name="infoadicional", type="text", nullable=true)
     * @Assert\Length(
     *      max = 2000,
     *      maxMessage = "No se permiten más de {{ limit }} caracteres"
     * )
     */
    private $infoadicional;

    /**
     * @var string
     *
     * @ORM\Column(name="comentarios", type="text", nullable=true)
     * @Assert\Length(
     *      max = 6000,
     *      maxMessage = "No se permiten más de {{ limit }} caracteres"
     * )
     */
    private $comentarios;

    /**
     * @var string
     *
     * @ORM\Column(name="recomendacion", type="text", nullable=true)
     * @Assert\Length(
     *      max = 6000,
     *      maxMessage = "No se permiten más de {{ limit }} caracteres"
     * )
     */
    private $recomendacion;

    /**
     * @Gedmo\Slug(fields={"nombre", "apellidos"})
     * @ORM\Column(name="slug", type="string", length=60, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $createdAt;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Form
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @param string $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * Set sexo
     *
     * @param boolean $sexo
     * @return Form
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return boolean 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @return string
     */
    public function getResumen()
    {
        return $this->resumen;
    }

    /**
     * @param string $resumen
     */
    public function setResumen($resumen)
    {
        $this->resumen = $resumen;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Form
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @return string
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * @param string $institucion
     */
    public function setInstitucion($institucion)
    {
        $this->institucion = $institucion;
    }


    /**
     * Set profesor
     *
     * @param string $profesor
     * @return Form
     */
    public function setProfesor($profesor)
    {
        $this->profesor = $profesor;

        return $this;
    }

    /**
     * Get profesor
     *
     * @return string 
     */
    public function getProfesor()
    {
        return $this->profesor;
    }

    /**
     * @return string
     */
    public function getInstprofesor()
    {
        return $this->instprofesor;
    }

    /**
     * @param string $instprofesor
     */
    public function setInstprofesor($instprofesor)
    {
        $this->instprofesor = $instprofesor;
    }

    /**
     * Set mailprofesor
     *
     * @param string $mailprofesor
     * @return Form
     */
    public function setMailprofesor($mailprofesor)
    {
        $this->mailprofesor = $mailprofesor;

        return $this;
    }

    /**
     * Get mailprofesor
     *
     * @return string 
     */
    public function getMailprofesor()
    {
        return $this->mailprofesor;
    }


    /**
     * Set beca
     *
     * @param string $beca
     * @return Form
     */
    public function setBeca($beca)
    {
        $this->beca = $beca;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getInfoadicional()
    {
        return $this->infoadicional;
    }

    /**
     * @param string $infoadicional
     */
    public function setInfoadicional($infoadicional)
    {
        $this->infoadicional = $infoadicional;
    }

    /**
     * Set cartaFile
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $recommendation
     */
    public function setCartaFile(File $carta = null)
    {
        $this->cartaFile = $carta;
        if ($carta) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * Get cartaFile
     *
     * @return File
     */
    public function getCartaFile()
    {
        return $this->cartaFile;
    }

    /**
     * @return mixed
     */
    public function getCartaName()
    {
        return $this->cartaName;
    }

    /**
     * @param mixed $cartaName
     */
    public function setCartaName($cartaName)
    {
        $this->cartaName = $cartaName;
    }


    /**
     * @return mixed
     */
    public function getHistorialName()
    {
        return $this->historialName;
    }

    /**
     * @param mixed $historialName
     */
    public function setHistorialName($historialName)
    {
        $this->historialName = $historialName;
    }

    /**
     * Set historialFile
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $recommendation
     */
    public function setHistorialFile(File $historial = null)
    {
        $this->historialFile = $historial;
        if ($historial) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTime('now');
        }
    }

    /**
     * Get historialFile
     *
     * @return File
     */
    public function getHistorialFile()
    {
        return $this->historialFile;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Form
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Form
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * @param string $comentarios
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
    }

    /**
     * @return boolean
     */
    public function isConfirmado()
    {
        return $this->confirmado;
    }

    /**
     * @param boolean $confirmado
     */
    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;
    }

    /**
     * @return boolean
     */
    public function isAceptado()
    {
        return $this->aceptado;
    }

    /**
     * @param boolean $aceptado
     */
    public function setAceptado($aceptado)
    {
        $this->aceptado = $aceptado;
    }

    /**
     * @return string
     */
    public function getRecomendacion()
    {
        return $this->recomendacion;
    }

    /**
     * @param string $recomendacion
     */
    public function setRecomendacion($recomendacion)
    {
        $this->recomendacion = $recomendacion;
    }

    /**
     * @return boolean
     */
    public function isExamen()
    {
        return $this->examen;
    }

    /**
     * @param boolean $examen
     */
    public function setExamen($examen)
    {
        $this->examen = $examen;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


}
