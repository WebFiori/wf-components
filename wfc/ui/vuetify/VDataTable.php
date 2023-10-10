<?php

namespace wfc\ui\vuetify;

use webfiori\ui\HTMLNode;

/**
 * A class which can be used to have a data-table with pagination option.
 *
 * @author Ibrahim
 */
class VDataTable extends HTMLNode {
    
    private $pageSizeSelect;
    private $footer;
    private $vPagination;
    private $paginationModelName;
    /**
     * Creates new instance of the class.
     * 
     * @param array $attrs An array that holds the v-data-table attributes such as
     * :items or :headers.
     */
    public function __construct(array $attrs = []) {
        parent::__construct('v-data-table', $attrs);
        $this->setAttributes([
            'hide-default-footer',
            ':items-per-page' => 'page.size'
        ]);
        
        $this->footer = $this->addChild('template', [
            '#footer'
        ]);
        
        $this->getFooter()->addChild('v-divider', [
            'class' => 'my-5'
        ]);
        $footerRow = $this->getFooter()->addChild('v-container', [
            'class' => 'ma-0'
        ])->addChild('v-row');
        
        $this->pageSizeSelect = $footerRow->addChild('v-col', [
            'cols' => 12, 'sm' => 12, 'md' => '3'
        ])->addChild('v-select', [
            'label' => 'Items Per Page',
            'v-model' => 'page.size',
            ':items' => 'page.items',
            'outlined', 'dense',
        ]);
        $this->vPagination = $footerRow->addChild('v-col', [
            'cols' => 12, 'sm' => 12, 'md' => 9,
            'v-if' => 'page.pages_count > 1'
        ])->addChild('v-pagination', [
            'v-model' => 'page.page_number',
            ':length' => 'page.pages_count',
            '@input' => 'loadPage'
        ]);
        $this->setHasFooter(false);
    }
    /**
     * Sets the name of JavaScript function that will be get executed when
     * page number input changes value.
     * 
     * Note that the first parameter of the function will be page number.
     * 
     * @param string $func
     * 
     * @return VDataTable
     */
    public function setOnPageNumberClick(string $func) : VDataTable {
        $this->getVPagination()->setAttribute('@input', $func);
        return $this;
    }
    /**
     * Adds support for expanding table rows.
     * 
     * @param HTMLNode|string $el The element that will be shown when a row is expanded.
     * The element can have access to two properties, 'headers' and 'item'.
     * 
     * @param string $expandedCallback The name of JavaScript function to call in
     * case the row is expanded.
     * 
     * @return HTMLNode The method will return the added element.
     */
    public function addExpandedRow($el, string $expandedCallback = null) : HTMLNode {
        $this->setAttributes([
            'show-expand','single-expand',
        ]);
        
        if ($expandedCallback !== null) {
            $this->setAttribute('@item-expanded', $expandedCallback);
        } else {
            $this->removeAttribute('@item-expanded');
        }
        
        return $this->addChild('template', [
            '#expanded-item' => '{ headers, item }'
        ])->addChild('td', [
            ':colspan' => "headers.length"
        ])->addChild($el);
        
        
    }
    /**
     * Adds a custom slot to a column in the table.
     * 
     * @param string $slot The name of the slot as in 'item.slot'.
     * 
     * @param string|HTMLNode $el The element that will be added to the slot.
     * 
     * @param array $attrs An optional array of attributes to set for the element.
     * 
     * @return HTMLNode The method will return an object of type HTMLNode
     * that represents the added element.
     */
    public function addItemSlot(string $slot , $el, array $attrs = []) : HTMLNode {
        return $this->addChild('template', [
            '#item.'.$slot => '{ item }'
        ])->addChild($el, $attrs);
    }
    /**
     * Sets the name of JavaScript function that will be get executed when
     * page size input changes value.
     * 
     * @param string $func
     * 
     * @return VDataTable 
     */
    public function setOnPageSizeChanged(string $func) : VDataTable {
        $this->getPageSizeInput()->setAttribute('@input', $func);
        return $this;
    }
    /**
     * Returns the node that represents the footer of the table.
     * 
     * @return HTMLNode
     */
    public function getFooter() : HTMLNode {
        return $this->footer;
    }
    /**
     * Returns the element which is used to show page numbers at the bottom of
     * the table.
     * 
     * @return HTMLNode
     */
    public function getVPagination() : HTMLNode {
        return $this->vPagination;
    }
    /**
     * Returns the v-select element which is used to select number of items per
     * page 
     * @return HTMLNode
     */
    public function getPageSizeInput() : HTMLNode {
        return $this->pageSizeSelect;
    }
    /**
     * Sets the model that will hold pagination properties.
     * 
     * The model must have following properties:
     * <ul>
     * <li>page_number: Represent current active page.</li>
     * <li>pages_count: Number of pages. This usually is fetched from backend.</li>
     * <li>size: Size of one page. Simply, number of records in one page.</li>
     * <li>pages_options: An array that contain number of items per page like 5, 10, 20</li>
     * </ul>
     * @param string $name Name of the model. Defined in 'data' section.
     * 
     * @return VDataTable
     */
    public function setPagingModel(string $name) : VDataTable {
        $this->getVPagination()->setAttributes([
            'v-model' => $name.'.page_number',
            ':length' => $name.'.pages_count',
        ])->getParent()->setAttribute('v-if', $name.'.pages_count > 1');
        $this->getPageSizeInput()->setAttributes([
            'v-model' => $name.'.size',
            ':items' => $name.'.size_options',
        ]);
        $this->setAttributes([
            ':page.sync' => $name.'.page_number',
            ':items-per-page' => $name.'.size',
        ]);
        $this->paginationModelName = $name;
        return $this;
    }
    /**
     * Sets if the table will have a footer or not.
     * 
     * @param bool $withFooter If true is passed, the table will have footer.
     * Other than that, the table will not have footer.
     * 
     * @return VDataTable
     */
    public function setHasFooter(bool $withFooter) : VDataTable {
        if ($withFooter && $this->getFooter()->getParent() === null) {
            $this->addChild($this->getFooter());
            $this->setAttribute(':items-per-page', $this->paginationModelName.'.size');
        } else {
            $this->removeChild($this->getFooter());
            $this->removeAttribute(':items-per-page');
        }
        
        return $this;
    }
    /**
     * Sets the properties of the v-select which is used to select items
     * per page.
     * 
     * @param array $attrs
     * 
     * @return VDataTable 
     */
    public function setPageSizeInputProps(array $attrs) : VDataTable {
        $this->pageSizeSelect->setAttributes($attrs);
        return $this;
    }
}
