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
 * Defines a constraint on a link between two packages.
 *
 * @author Nils Adermann <naderman@naderman.de>
 */
interface LinkConstraintInterface
{

    /**
     * matches().
     *
     * @param LinkConstraintInterface $provider Provider
     * @return bool
     */
    public function matches(LinkConstraintInterface $provider);

    /**
     * setPrettyString().
     *
     * @param string $prettyString prettyString
     * @return void
     */
    public function setPrettyString($prettyString);

    /**
     * getPrettyString().
     *
     * @return string
     */
    public function getPrettyString();

    /**
     * __toString().
     *
     * @return string
     */
    public function __toString();
}
