<?php

namespace Stampie;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
class Recipient implements RecipientInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $email
     * @param string $name
     */
    public function __construct($email = null, $name = null)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @param  string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
}
