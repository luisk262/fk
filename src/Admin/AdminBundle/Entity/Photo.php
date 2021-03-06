<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Photo {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $image
     * @Assert\Image(
     *     maxSize ="5M",
     *     mimeTypes = {
     *              "image/png",
     *              "image/jpeg",
     *              "image/jpg",
     *              "image/bmp",
     *    },
     *    mimeTypesMessage = "La imagen no es valida"    *    
     * )
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    public $image;
    private $imagename;
    private $aux;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaUpdate", type="datetime", nullable=true)
     */
    private $fechaupdate;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Photo
     */
    public function setImage($image) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param string $imagename
     * @return photo
     */
    public function setImageName($imagename) {
        $this->imagename = $imagename;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImageName() {
        return $this->imagename;
    }

    public function getFullImagePath() {
        return null === $this->image ? null : $this->getUploadRootDir() . $this->image;
        //retorna la imagen para mostrar en web
    }

    protected function getUploadRootDir() {
        //ruta del dicrectorio donde se van a guardar los archivos

        return $this->getTmpUploadRootDir() . "../" . $this->id . "/";
    }

    protected function getTmpUploadRootDir() {
        //la ruta del directorio absoluta donde se deben guardar los documentos cargados
        return __DIR__ . '/../../../../web/upload/Photos/tmp/';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadImage() {
        //validamos si es una actualizacion o una nueva
        //si las fehcas son diferentes es actualizacion de lo contrario es nuevo
        if ($this->fechaupdate != $this->fecha) {
            if ($this->image != null) {
                ///asignamos los nombres nuevos
                $tempImageName = time() . '_1.' . pathinfo((string) $this->image->getClientOriginalName(), PATHINFO_EXTENSION);
                //borramos imagenes viejas
                $dir = $this->getUploadRootDir();
                if (is_file($dir . $this->aux)) {
                    unlink($dir . $this->aux);
                }
                /// guardamos en el servidor los archivos
                $this->setImageName($this->image->getClientOriginalName());
                $this->image->move($this->getUploadRootDir(), $tempImageName);
                //grabamos los nombres de archivo en el servidor
                $this->setImage($tempImageName);
            } else {
                $this->setImage($this->aux);
            }
        } else {
            if (null === $this->image) {
                return;
            }

            $tempImageName = time() . '_1.' . pathinfo((string) $this->image->getClientOriginalName(), PATHINFO_EXTENSION);
            if (!$this->id) {
                $this->setImageName($this->image->getClientOriginalName());
                $this->image->move($this->getTmpUploadRootDir(), $this->image->getClientOriginalName());
            } else {
                $this->image->move($this->getUploadRootDir(), $tempImageName);
                unlink($this->getUploadRootDir() . $tempImageName);
            }
            $this->setImage($tempImageName);
        }
    }

    /**
     * 
     * @ORM\PreRemove()
     */
    public function removeImage() {
        if (is_file($this->getFullImagePath())) {
            unlink($this->getFullImagePath());
        }
        if(is_dir(__DIR__ ."/../../../../web/upload/Photos/". $this->id."/")){
            rmdir($this->getUploadRootDir());
        }
       
    }

    /**
     * @ORM\PostPersist()
     */
    public function moveImage() {
        if (null === $this->image) {
            return;
        }
        if (!is_dir($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir(), 0777, true);
        }
        copy($this->getTmpUploadRootDir() . $this->getImageName(), $this->getFullImagePath());
        unlink($this->getTmpUploadRootDir() . $this->getImageName());
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Photo
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set fechaupdate
     *
     * @param \DateTime $fechaupdate
     *
     * @return Photo
     */
    public function setFechaupdate($fechaupdate) {
        $this->fechaupdate = $fechaupdate;

        return $this;
    }

    /**
     * Get fechaupdate
     *
     * @return \DateTime
     */
    public function getFechaupdate() {
        return $this->fechaupdate;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Hojadevida
     */
    public function setAux($aux) {
        $this->aux = $aux;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getAux() {
        return $this->aux;
    }

}
