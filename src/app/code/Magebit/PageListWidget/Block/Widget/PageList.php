<?php
/**
 * @copyright Copyright (c) 2024 Magebit, Ltd. (https://magebit.com/)
 * @author    Magebit <info@magebit.com>
 * @license   MIT
 */

declare(strict_types=1);

namespace Magebit\PageListWidget\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Cms\Model\ResourceModel\Page\Collection;
use Magento\Cms\Model\Page;

class PageList extends Template implements BlockInterface
{
    /**
     * Display mode options
     */
    public const DISPLAY_ALL = 'all';
    public const DISPLAY_SPECIFIC = 'specific';

    /**
     * @var string
     */
    protected $_template = "Magebit_PageListWidget::page-list.phtml";

    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->pageFactory = $pageFactory;
    }

    /**
     * Retrieve selected pages
     *
     * @return array|null
     */
    public function getSelectedPages(): ?array
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
     * @return Collection
     */
    public function getCmsPages(): Collection
    {
        return $this->pageFactory->create()->getCollection()->addFieldToSelect('*');
    }

    /**
     * Retrieve the page factory
     *
     * @return PageFactory
     */
    public function getPageFactory(): PageFactory
    {
        return $this->pageFactory;
    }

    /**
     * Retrieve the CMS page data by page ID
     *
     * @param string $pageId
     * @return Page|null
     */
    public function getPageData(string $pageId): ?Page
    {
        $page = $this->getPageFactory()->create()->load($pageId);
         return $page->getId() ? $page : null;
    }

    /**
     * Generate the URL for a CMS page by page ID
     *
     * @param string $pageId
     * @return string
     */
    public function getPageUrl(string $pageId): string
    {
        $page = $this->getPageData($pageId);
         return $page ? $this->getUrl('cms/page/view', ['page_id' => $page->getId()]) : '#';
    }

    /**
     * Get CMS pages based on display mode
     *
     * @return Collection|array|null
     */
    public function getPagesByDisplayMode()
    {
        if ($this->getDisplayMode() === 'all') {
            return $this->getCmsPages()->getItems();
        } elseif ($this->getDisplayMode() === 'specific') {
            $selectedPageIds = $this->getSelectedPages();
            $pages = [];
            foreach ($selectedPageIds as $pageId) {
                $page = $this->getPageData($pageId);
                if ($page !== null) {
                    $pages[] = $page;
                }
            }
             return $pages;
        }
         return null;
    }
}
