<?php
namespace Magebit\PageListWidget\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Cms\Model\PageFactory;

class PageList extends Template implements BlockInterface
{
    /**
     * Display mode options
     */
    const DISPLAY_ALL = 'all';
    const DISPLAY_SPECIFIC = 'specific';

    /**
     * @var string
     */
    protected $_template = "page-list.phtml";

    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        PageFactory $pageFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->pageFactory = $pageFactory;
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    public function getDisplayModeOptions()
    {
        return [
            self::DISPLAY_ALL => __('All Pages'),
            self::DISPLAY_SPECIFIC => __('Specific Pages')
        ];
    }

    /**
     * Retrieve selected pages
     *
     * @return array|null
     */
    public function getSelectedPages()
    {
        $selectedPages = $this->getData('selected_pages');
        if (!is_array($selectedPages)) {
            $selectedPages = explode(',', $selectedPages);
        }
        return $selectedPages;
    }

    /**
     * Retrieve CMS pages
     *
     * @return \Magento\Cms\Model\ResourceModel\Page\Collection
     */
    public function getCmsPages()
    {
        return $this->pageFactory->create()->getCollection()->addFieldToSelect('*');
    }

    /**
     * Retrieve the page factory
     *
     * @return \Magento\Cms\Model\PageFactory
     */
    public function getPageFactory()
    {
        return $this->pageFactory;
    }
}
