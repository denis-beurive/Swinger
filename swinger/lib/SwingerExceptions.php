<?php

/**
 * \file SwingerExceptions.php
 * This file defines the Swinger's specific exceptions.
 * \note Each exception is associated with a view (within the directory "./views").
 */

/**
 * \class SwingerException
 * \brief This class is the base class for all Swinger's specific exceptions.
 */
class SwingerException extends Exception
{
	public $data = null;
}

/**
 * \class ExceptionOnRegexp
 * \brief An error occurred while using regular expression (class Regexp).
 */
class ExceptionOnRegexp extends SwingerException { }

/**
 * \class ExceptionOnControllerNoReturn
 * \brief A controller does not return a value.
 */
class ExceptionOnControllerNoReturn extends SwingerException { }

/**
 * \class ExceptionOnViewUnexpectedOutputFormat
 * \brief The output format (for a view) is not supported.
 */
class ExceptionOnViewUnexpectedOutputFormat extends SwingerException { }

/**
 * \class ExceptionOnControllerUnexpectedMethod
 * \brief The specified HTTP method is not supported.
 * \note Example: Controller::register('unexpecedMethod',  '/command/pnr', 'myController');
 */
class ExceptionOnControllerUnexpectedMethod extends SwingerException { }

/**
 * \class ExceptionOnControllerRedeclaredFunction
 * \brief A given controller has been declared twice.
 * \note Example:
 *       Controller::register('get',  '/command/pnr', 'duplicatedController');
 *       Controller::register('post', '/command/ul',  'duplicatedController');
 */
class ExceptionOnControllerRedeclaredFunction extends SwingerException { }

/**
 * \class ExceptionOnViewLoad
 * \brief Can not load the view (permissions ?).
 */
class ExceptionOnViewLoad extends SwingerException { }

/**
 * \class ExceptionOnLayoutLoad
 * \brief Can not load the layout (permissions ?).
 */
class ExceptionOnLayoutLoad extends SwingerException { }

/**
 * \class ExceptionOnAutoloadInvalidFileName
 * \brief Can not generate classes' index because a class' file's name is not valid.
 */
class ExceptionOnAutoloadInvalidFileName extends SwingerException { }

/**
 * \class ExceptionOnAutoloadCreateIndexFile
 * \brief Can not create the classes' index file (permissions ?).
 */
class ExceptionOnAutoloadCreateIndexFile extends SwingerException { }

/**
 * \class ExceptionOnAutoloadLoadIndexFile
 * \brief Can not load the classes' index file (permissions ?).
 */
class ExceptionOnAutoloadLoadIndexFile extends SwingerException { }

/**
 * \class ExceptionOnAutoloadInvalidFile
 * \brief The classes' index file can be loaded, but the file's content is not valid.
 */
class ExceptionOnAutoloadInvalidFile extends SwingerException { }

/**
 * \class ExceptionOnClassLoad
 * \brief Can not load a given class through the auto-load feature.
 */
class ExceptionOnClassLoad extends SwingerException { }

/**
 * \class ExceptionOnRegistry
 * \brief An attempt has been made to retrieve an available value from the registry.
 */
class ExceptionOnRegistry extends SwingerException { }

/**
 * \class ExceptionPhpTooOld
 * \brief Bad PHP's version.
 */
class ExceptionPhpTooOld extends SwingerException { }


?>