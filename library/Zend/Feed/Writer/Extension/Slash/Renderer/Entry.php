<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Feed
 */

namespace Zend\Feed\Writer\Extension\Slash\Renderer;

use DOMDocument;
use DOMElement;
use Zend\Feed\Writer\Extension;

/**
* @category Zend
* @package Zend_Feed_Writer
*/
class Entry extends Extension\AbstractRenderer
{

    /**
     * Set to TRUE if a rendering method actually renders something. This
     * is used to prevent premature appending of a XML namespace declaration
     * until an element which requires it is actually appended.
     *
     * @var bool
     */
    protected $_called = false;

    /**
     * Render entry
     *
     * @return void
     */
    public function render()
    {
        if (strtolower($this->getType()) == 'atom') {
            return; // RSS 2.0 only
        }
        $this->_setCommentCount($this->_dom, $this->_base);
        if ($this->_called) {
            $this->_appendNamespaces();
        }
    }

    /**
     * Append entry namespaces
     *
     * @return void
     */
    protected function _appendNamespaces()
    {
        $this->getRootElement()->setAttribute('xmlns:slash',
            'http://purl.org/rss/1.0/modules/slash/');
    }

    /**
     * Set entry comment count
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setCommentCount(DOMDocument $dom, DOMElement $root)
    {
        $count = $this->getDataContainer()->getCommentCount();
        if (!$count) {
            $count = 0;
        }
        $tcount = $this->_dom->createElement('slash:comments');
        $tcount->nodeValue = $count;
        $root->appendChild($tcount);
        $this->_called = true;
    }
}
