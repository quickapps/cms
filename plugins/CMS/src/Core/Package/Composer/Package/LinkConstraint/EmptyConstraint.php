<?php
/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CMS\Core\Package\Composer\Package\LinkConstraint;

/**
 * Defines an absence of constraints
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class EmptyConstraint implements LinkConstraintInterface
{

    protected $prettyString;

    /**
     * {@inheritDoc}
     */
    public function matches(LinkConstraintInterface $provider)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function setPrettyString($prettyString)
    {
        $this->prettyString = $prettyString;
    }

    /**
     * {@inheritDoc}
     */
    public function getPrettyString()
    {
        if ($this->prettyString) {
            return $this->prettyString;
        }

        return $this->__toString();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return '[]';
    }
}
