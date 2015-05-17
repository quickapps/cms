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

/**
 * Base class for packages providing name storage and default match implementation
 *
 * @author Nils Adermann <naderman@naderman.de>
 */
abstract class BasePackage implements PackageInterface
{

    const STABILITY_STABLE = 0;
    const STABILITY_RC = 5;
    const STABILITY_BETA = 10;
    const STABILITY_ALPHA = 15;
    const STABILITY_DEV = 20;

    public static $stabilities = [
        'stable' => self::STABILITY_STABLE,
        'RC' => self::STABILITY_RC,
        'beta' => self::STABILITY_BETA,
        'alpha' => self::STABILITY_ALPHA,
        'dev' => self::STABILITY_DEV,
    ];

    /**
     * READ-ONLY: The package id, public for fast access in dependency solver
     * @var int
     */
    public $id;

    protected $name;
    protected $prettyName;

    protected $repository;
    protected $transportOptions;

    /**
     * All descendants' constructors should call this parent constructor
     *
     * @param string $name The package's name
     */
    public function __construct($name)
    {
        $this->prettyName = $name;
        $this->name = strtolower($name);
        $this->id = -1;
        $this->transportOptions = [];
    }
}
