<?php
namespace Drupal\twig_polymer\Twig\Tags;

use Twig_Environment;
use Twig_LoaderInterface;
use Drupal\Core\Cache\CacheBackendInterface;
/**
 * Twig environment.
 *
 * Overrides the built in environment to set our custom lexer.
 */
class Environment
    extends \Drupal\Core\Template\TwigEnvironment
{

    /**
     * @var array
     */
    private $_options = [];

    /**
     * Constructor
     *
     * @param Twig_LoaderInterface          $loader  A Twig_LoaderInterface instance
     * @param array                         $options An array of options
     */
    public function __construct($root, CacheBackendInterface $cache, $twig_extension_hash, \Twig_LoaderInterface $loader = NULL, $options = array())
    {
        parent::__construct($root, $cache, $twig_extension_hash, $loader, $options);

        $this->_options = $options;
        //$this->setConfiguration($configuration);
    }

    /**
     * Gets the Lexer instance.
     *
     * @return \Twig_LexerInterface A Twig_LexerInterface instance
     */
    public function getLexer()
    {
        if (null === $this->lexer) {
            $this->lexer = new Lexer($this, $this->_options);
        }

        return $this->lexer;
    }
}
