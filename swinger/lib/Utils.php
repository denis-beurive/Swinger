<?php

/** 
 * \file Utils.php
 * This file implements some useful functions.
 */

/**
 * \brief This function includes a given PHP file and captures the output (that otherwise would be printed to STDOUT).
 * \param[in] $in_path Path to the PHP file to include.
 * \return The function returns a string. This string contains the text that would have been printed to the standard output.
 */
function getIncludeContents($in_path)
{
    if (is_file($in_path))
	{
        ob_start();
        include $in_path;
        return ob_get_clean();
    }
    return FALSE;
}

/**
 * \brief This function lists all the files in a given directory, including all the subdirectories.
 * \param[in] $in_from_path Path to the "top level" directory.
 * \param[in] $in_opt_mask Optional mask used to specify the files to keep.
 *            For, example, if you want to keep only the PHP files :
 *            '/^.*\.php$/i'
 * \return The function returns an array that contains all files.
 * \note Example: var_dump(find(__DIR__ . '/../', '/^.+\.php/i'));
 */
function fsFind($in_from_path, $in_opt_mask=FALSE)
{
	$result = array();
	$in_from_path = realpath($in_from_path);
	$root = scandir($in_from_path); 
	
    foreach($root as $basename) 
    { 
        if ($basename === '.' || $basename === '..') { continue; }
 		$path = $in_from_path . DIRECTORY_SEPARATOR . $basename;
		
        if (is_file($path))
		{
			if (FALSE !== $in_opt_mask)
			{ if (0 === preg_match($in_opt_mask, $basename)) { continue; } }
			$result[] = $path;
			continue;
		}
		
        foreach(fsFind($path, $in_opt_mask) as $basename) 
        { $result[] = $basename; } 
    } 
    return $result;
}

/**
 * \brief Generate a random string.
 * \param[in] $in_length Length of the string.
 * \return The function returns a string.
 */
function getRandomString($in_length)
{
	$valid_characters  = 'abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789';
	$valid_char_number = strlen($valid_characters) - 1;
	$result            = '';

	for ($i = 0; $i < $in_length; $i++)
	{ $result .= $valid_characters[mt_rand(0, $valid_char_number)]; }
 
	return $result;
}

/**
 * \brief Test if the current process can read and write files in a given directory.
 * \param[in] $in_path Path to the directory.
 * \return If the current process can read and write files in the given directory, then the function returns the
 *         value TRUE. Otherwise, it returns the value FALSE.
 */
function isDirectoryReadWrite($in_path)
{
	$file   = NULL;
	$status = FALSE;
	
	// Try to find a file's name for the test.
	for ($i=0; $i<10; $i++)
	{
		$file = $in_path . DIRECTORY_SEPARATOR . getRandomString(15);
		if (FALSE === file_exists($file)) { $status = TRUE; break; }
	}
	if (! $status) { return FALSE; }
	
	// Try to create the file.
	if (FALSE === touch($file)) { return FALSE; }
	
	// Try to read the file.
	if (FALSE === file_get_contents($file)) { return FALSE; }
	
	// Remove the file.
	if (FALSE === unlink($file)) { return FALSE; }
	
	return TRUE;
}



?>