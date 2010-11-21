<?php
/**
 * View scripts loader for Twig's Zend Framework integration
 * Adds specific methods to fit with Zend_View logic.
 *
 * @package     Ano_ZFTwig
 * @subpackage  Loader
 * @author      Benjamin Dulau <benjamin.dulau@gmail.com>
 */
class Ano_ZFTwig_Loader_FileLoader extends Twig_Loader_Filesystem
                                   implements Twig_LoaderInterface
{
    /**
     * Add to the stack of view paths.
     *
     * @param string $path The directory to add
     */
    public function addPath($path)
    {
        if (!is_dir($path)) {
            throw new Twig_Error_Loader(sprintf('The "%s" directory does not exist.', $path));
        }

        $this->paths[] = realpath($path);
    }
}
