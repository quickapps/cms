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

namespace CMS\Core\Package\Composer\Package;

use CMS\Core\Package\Composer\Package\LinkConstraint\LinkConstraintInterface;

/**
 * Represents a link between two packages, represented by their names
 *
 * @author Nils Adermann <naderman@naderman.de>
 */
class Link
{

    protected $source;
    protected $target;
    protected $constraint;
    protected $description;
    protected $prettyConstraint;

    /**
     * Creates a new package link.
     *
     * @param string                  $source           Source
     * @param string                  $target           Target
     * @param LinkConstraintInterface $constraint       Constraint applying to the target of this link
     * @param string                  $description      Used to create a descriptive string representation
     * @param string                  $prettyConstraint PrettyConstraint
     */
    public function __construct(
        $source,
        $target,
        LinkConstraintInterface $constraint = null,
        $description = 'relates to',
        $prettyConstraint = null
    ) {
        $this->source = strtolower($source);
        $this->target = strtolower($target);
        $this->constraint = $constraint;
        $this->description = $description;
        $this->prettyConstraint = $prettyConstraint;
    }
}
