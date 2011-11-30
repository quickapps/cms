<?php
/**
 * Debug Level:
 *
 * Production Mode:
 * 	0: No error messages, errors, or warnings shown. Flash messages redirect.
 *
 * Development Mode:
 * 	1: Errors and warnings shown, model caches refreshed, flash messages halted.
 * 	2: As in 1, but also with full debug messages and SQL output.
 *
 * In production mode, flash messages redirect after a time interval.
 * In development mode, you need to click the flash message to continue.
 */
Configure::write('debug', 0);

/**
 * The level of security.
 *
 * Valid values: 'high', 'medium', 'low'
 *
 */
Configure::write('Security.level', 'medium');

/**
 * A random string used in security hashing methods.
 *
 */
Configure::write('Security.salt', 'alknLsnÑQUCMdnweuNsd83P390dm');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 *
 */
Configure::write('Security.cipherSeed', '8701467490498213157498310');