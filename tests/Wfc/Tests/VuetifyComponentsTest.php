<?php

namespace Wfc\Tests;

use PHPUnit\Framework\TestCase;
use Wfc\Ui\Vuetify\ActionsContainer;
use Wfc\Ui\Vuetify\CRUDList;
use Wfc\Ui\Vuetify\DateInput;
use Wfc\Ui\Vuetify\Heading;
use Wfc\Ui\Vuetify\HijriDateInput;
use Wfc\Ui\Vuetify\LoginForm;
use Wfc\Ui\Vuetify\MessageDialog;
use Wfc\Ui\Vuetify\PrivilegesTree;
use Wfc\Ui\Vuetify\VBtn;
use Wfc\Ui\Vuetify\VDataTable;
use Wfc\Ui\Vuetify\VDialog;
use Wfc\Ui\Vuetify\VIcon;
use Wfc\Ui\Vuetify\VTooltip;
use Wfc\Ui\Vuetify\ViewFileDialog;

class VuetifyComponentsTest extends TestCase {

    public function testActionsContainerCreation() {
        $component = new ActionsContainer();
        $this->assertInstanceOf(ActionsContainer::class, $component);
    }

    public function testCRUDListCreation() {
        $component = new CRUDList([
            'dialog' => 'test-dialog',
            'confirm-delete-dialog' => 'delete-dialog'
        ]);
        $this->assertInstanceOf(CRUDList::class, $component);
    }

    public function testDateInputCreation() {
        $component = new DateInput();
        $this->assertInstanceOf(DateInput::class, $component);
    }

    public function testHeadingCreation() {
        $component = new Heading('Test Heading');
        $this->assertInstanceOf(Heading::class, $component);
    }

    public function testHijriDateInputCreation() {
        $component = new HijriDateInput('test', 'Test Label');
        $this->assertInstanceOf(HijriDateInput::class, $component);
    }

    public function testLoginFormCreation() {
        $component = new LoginForm([]);
        $this->assertInstanceOf(LoginForm::class, $component);
    }

    public function testMessageDialogCreation() {
        $component = new MessageDialog('Test Message');
        $this->assertInstanceOf(MessageDialog::class, $component);
    }

    public function testPrivilegesTreeCreation() {
        $component = new PrivilegesTree();
        $this->assertInstanceOf(PrivilegesTree::class, $component);
    }

    public function testVBtnCreation() {
        $component = new VBtn();
        $this->assertInstanceOf(VBtn::class, $component);
    }

    public function testVDataTableCreation() {
        $component = new VDataTable();
        $this->assertInstanceOf(VDataTable::class, $component);
    }

    public function testVDialogCreation() {
        $component = new VDialog('Test Dialog');
        $this->assertInstanceOf(VDialog::class, $component);
    }

    public function testVIconCreation() {
        $component = new VIcon();
        $this->assertInstanceOf(VIcon::class, $component);
    }

    public function testVTooltipCreation() {
        $component = new VTooltip();
        $this->assertInstanceOf(VTooltip::class, $component);
    }

    public function testViewFileDialogCreation() {
        $component = new ViewFileDialog('test.txt');
        $this->assertInstanceOf(ViewFileDialog::class, $component);
    }
}
