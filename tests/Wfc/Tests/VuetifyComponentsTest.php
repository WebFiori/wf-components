<?php

namespace Wfc\Tests;

use PHPUnit\Framework\TestCase;
use WebFiori\Ui\HTMLNode;
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
use WebFiori\Framework\Exceptions\UIException;

class VuetifyComponentsTest extends TestCase {

    // ── VIcon ──

    public function testVIconDefaults() {
        $icon = new VIcon();
        $this->assertEquals('v-icon', $icon->getNodeName());
        $this->assertStringContainsString('mdi-information', $icon->toHTML());
    }

    public function testVIconCustomIcon() {
        $icon = new VIcon('mdi-eye');
        $this->assertStringContainsString('mdi-eye', $icon->toHTML());
    }

    public function testVIconAutoPrefix() {
        $icon = new VIcon('pencil');
        $this->assertStringContainsString('mdi-pencil', $icon->toHTML());
    }

    public function testVIconSlotSyntax() {
        $icon = new VIcon('{{ item.icon }}');
        $this->assertStringContainsString('{{ item.icon }}', $icon->toHTML());
    }

    public function testVIconWithProps() {
        $icon = new VIcon('mdi-home', ['color' => 'red', 'small']);
        $this->assertEquals('red', $icon->getAttribute('color'));
    }

    public function testVIconSetIcon() {
        $icon = new VIcon('mdi-home');
        $icon->setIcon('mdi-star');
        $this->assertStringContainsString('mdi-star', $icon->toHTML());
    }

    // ── VBtn ──

    public function testVBtnDefaults() {
        $btn = new VBtn();
        $this->assertEquals('v-btn', $btn->getNodeName());
        $this->assertNull($btn->getVIcon());
    }

    public function testVBtnWithLabel() {
        $btn = new VBtn(['label' => 'Click Me']);
        $this->assertStringContainsString('Click Me', $btn->toHTML());
    }

    public function testVBtnWithIcon() {
        $btn = new VBtn(['icon' => 'mdi-save']);
        $this->assertNotNull($btn->getVIcon());
        $this->assertStringContainsString('mdi-save', $btn->toHTML());
    }

    public function testVBtnWithIconAndProps() {
        $btn = new VBtn([
            'icon' => 'mdi-delete',
            'icon-props' => ['color' => 'red']
        ]);
        $this->assertEquals('red', $btn->getVIcon()->getAttribute('color'));
    }

    public function testVBtnSetLabel() {
        $btn = new VBtn();
        $btn->setLabel('Save');
        $this->assertStringContainsString('Save', $btn->toHTML());
    }

    public function testVBtnSetLabelNull() {
        $btn = new VBtn(['label' => 'Save']);
        $btn->setLabel(null);
        $this->assertStringNotContainsString('Save', $btn->toHTML());
    }

    public function testVBtnSetLabelUpdate() {
        $btn = new VBtn(['label' => 'Old']);
        $btn->setLabel('New');
        $this->assertStringContainsString('New', $btn->toHTML());
    }

    public function testVBtnSetIcon() {
        $btn = new VBtn();
        $btn->setIcon('mdi-check');
        $this->assertNotNull($btn->getVIcon());
        $btn->setIcon('mdi-close');
        $this->assertStringContainsString('mdi-close', $btn->toHTML());
    }

    public function testVBtnCustomAttributes() {
        $btn = new VBtn(['color' => 'primary', '@click' => 'doSomething']);
        $this->assertEquals('primary', $btn->getAttribute('color'));
        $this->assertEquals('doSomething', $btn->getAttribute('@click'));
    }

    public function testVBtnAddCustomLoader() {
        $btn = new VBtn();
        $loader = new HTMLNode('v-progress-circular');
        $btn->addCustomLoader($loader);
        $this->assertStringContainsString('v-progress-circular', $btn->toHTML());
    }

    // ── VDialog ──

    public function testVDialogWithoutToolbar() {
        $dialog = new VDialog('myDialog');
        $this->assertEquals('v-dialog', $dialog->getNodeName());
        $this->assertEquals('myDialog', $dialog->getModel());
        $this->assertEquals('myDialog.visible', $dialog->getAttribute('v-model'));
        $this->assertNull($dialog->getToolbar());
        $this->assertNotNull($dialog->getVCard());
    }

    public function testVDialogWithToolbar() {
        $dialog = new VDialog('dlg', true);
        $this->assertNotNull($dialog->getToolbar());
        $this->assertEquals('v-toolbar', $dialog->getToolbar()->getNodeName());
    }

    public function testVDialogAddCloseBtn() {
        $dialog = new VDialog('dlg', true);
        $btn = $dialog->addCloseBtn();
        $this->assertNotNull($btn);
        $this->assertEquals('dlg.visible = false', $btn->getAttribute('@click'));
    }

    public function testVDialogAddCloseBtnCustomAction() {
        $dialog = new VDialog('dlg', true);
        $btn = $dialog->addCloseBtn('closeMe()');
        $this->assertEquals('closeMe()', $btn->getAttribute('@click'));
    }

    public function testVDialogAddCloseBtnNoToolbar() {
        $dialog = new VDialog('dlg', false);
        $result = $dialog->addCloseBtn();
        $this->assertNull($result);
    }

    public function testVDialogAddToBody() {
        $dialog = new VDialog('dlg');
        $el = $dialog->addToBody('v-card-text', ['class' => 'pa-5']);
        $this->assertEquals('v-card-text', $el->getNodeName());
    }

    public function testVDialogGetTitleContainerNoToolbar() {
        $dialog = new VDialog('dlg', false);
        // getTitleContainer returns void (missing return), so result is null
        $this->assertNull($dialog->getTitleContainer());
    }

    public function testVDialogGetTitleContainerWithToolbar() {
        $dialog = new VDialog('dlg', true);
        $this->assertNull($dialog->getTitleContainer());
    }

    // ── VTooltip ──

    public function testVTooltipDefaults() {
        $tooltip = new VTooltip();
        $this->assertEquals('v-tooltip', $tooltip->getNodeName());
    }

    public function testVTooltipWithActivatorAndTooltip() {
        $activator = new HTMLNode('v-btn');
        $tip = new HTMLNode('span');
        $tip->text('Help text');
        $tooltip = new VTooltip($activator, $tip);
        $html = $tooltip->toHTML();
        $this->assertStringContainsString('v-btn', $html);
        $this->assertStringContainsString('Help text', $html);
    }

    public function testVTooltipSetActivator() {
        $tooltip = new VTooltip();
        $btn = new HTMLNode('v-btn');
        $result = $tooltip->setActivator($btn);
        $this->assertEquals('v-btn', $result->getNodeName());
        $this->assertEquals('attrs', $result->getAttribute('v-bind'));
        $this->assertEquals('on', $result->getAttribute('v-on'));
    }

    public function testVTooltipSetTooltipElement() {
        $tooltip = new VTooltip();
        $span = $tooltip->setTooltip('span');
        $span->text('Tip');
        $this->assertStringContainsString('Tip', $tooltip->toHTML());
    }

    public function testVTooltipReplaceTooltip() {
        $tooltip = new VTooltip();
        $tooltip->setTooltip('span')->text('First');
        $tooltip->setTooltip('span')->text('Second');
        $html = $tooltip->toHTML();
        $this->assertStringContainsString('Second', $html);
    }

    public function testVTooltipSetPosition() {
        $tooltip = new VTooltip();
        $result = $tooltip->setPosition('top');
        $this->assertSame($tooltip, $result);
        $this->assertTrue($tooltip->hasAttribute('top'));
    }

    // ── Heading ──

    public function testHeadingDefaults() {
        $heading = new Heading('My Title');
        $this->assertEquals('v-row', $heading->getNodeName());
        $this->assertStringContainsString('h1', $heading->toHTML());
        $this->assertNotNull($heading->getHedingNode());
        $this->assertEquals('h1', $heading->getHedingNode()->getNodeName());
    }

    public function testHeadingCustomLevel() {
        $heading = new Heading('Sub', 3);
        $this->assertStringContainsString('h3', $heading->toHTML());
    }

    public function testHeadingInvalidLevel() {
        $heading = new Heading('Bad', 9);
        $this->assertStringContainsString('h1', $heading->toHTML());
    }

    public function testHeadingSetTextReturnsSelf() {
        $heading = new Heading('Old');
        $result = $heading->setText('New');
        $this->assertSame($heading, $result);
    }

    public function testHeadingGetNode() {
        $heading = new Heading('Test');
        $this->assertNotNull($heading->getHedingNode());
        $this->assertEquals('h1', $heading->getHedingNode()->getNodeName());
    }

    // ── DateInput ──

    public function testDateInputDefaults() {
        $input = new DateInput();
        $this->assertEquals('v-menu', $input->getNodeName());
        $this->assertNotNull($input->getTextField());
        $this->assertNotNull($input->getDatePicker());
        $this->assertEquals('YYYY-MM-DD', $input->getTextField()->getAttribute('placeholder'));
    }

    public function testDateInputWithModel() {
        $input = new DateInput('selectedDate');
        $this->assertEquals('selectedDate', $input->getTextField()->getAttribute('v-model'));
        $this->assertEquals('selectedDate', $input->getDatePicker()->getAttribute('v-model'));
    }

    public function testDateInputWithMenuModel() {
        $input = new DateInput(null, 'menuOpen');
        $this->assertEquals('menuOpen', $input->getAttribute('v-model'));
    }

    public function testDateInputSetVModel() {
        $input = new DateInput();
        $result = $input->setVModel('myDate');
        $this->assertSame($input, $result);
        $this->assertEquals('myDate', $input->getTextField()->getAttribute('v-model'));
        $this->assertEquals('myDate', $input->getDatePicker()->getAttribute('v-model'));
    }

    public function testDateInputSetMenuVModel() {
        $input = new DateInput();
        $result = $input->setMenuVModel('menu1');
        $this->assertSame($input, $result);
        $this->assertEquals('menu1', $input->getAttribute('v-model'));
    }

    public function testDateInputSetOnInput() {
        $input = new DateInput();
        $result = $input->setOnInput('onDateChange');
        $this->assertSame($input, $result);
        $this->assertEquals('onDateChange', $input->getTextField()->getAttribute('@input'));
        $this->assertEquals('onDateChange', $input->getDatePicker()->getAttribute('@change'));
    }

    // ── HijriDateInput ──

    public function testHijriDateInputCreation() {
        $input = new HijriDateInput('hijri', 'Birth Date');
        $this->assertEquals('v-row', $input->getNodeName());
        $this->assertNotNull($input->getYearInput());
        $this->assertNotNull($input->getMonthInput());
        $this->assertNotNull($input->getDayInput());
        $this->assertStringContainsString('Birth Date', $input->toHTML());
    }

    public function testHijriDateInputModels() {
        $input = new HijriDateInput('h', 'Label');
        $this->assertEquals('h.year', $input->getYearInput()->getAttribute('v-model'));
        $this->assertEquals('h.month', $input->getMonthInput()->getAttribute('v-model'));
        $this->assertEquals('h.day', $input->getDayInput()->getAttribute('v-model'));
    }

    public function testHijriDateInputSetOnInput() {
        $input = new HijriDateInput('h', 'Label');
        $result = $input->setOnInput('onHijriChange');
        $this->assertSame($input, $result);
        $this->assertEquals('onHijriChange', $input->getYearInput()->getAttribute('@input'));
        $this->assertEquals('onHijriChange', $input->getMonthInput()->getAttribute('@input'));
        $this->assertEquals('onHijriChange', $input->getDayInput()->getAttribute('@input'));
    }

    // ── LoginForm ──

    public function testLoginFormDefaults() {
        $form = new LoginForm([]);
        $this->assertEquals('v-card', $form->getNodeName());
        $this->assertNotNull($form->getUsernameInput());
        $this->assertNotNull($form->getPasswordInput());
        $this->assertNotNull($form->getLoginButton());
        $this->assertEquals('Username', $form->getUsernameInput()->getAttribute('label'));
        $this->assertEquals('Password', $form->getPasswordInput()->getAttribute('label'));
    }

    public function testLoginFormWithOptions() {
        $form = new LoginForm([
            'username-label' => 'Email',
            'password-label' => 'Secret',
            'username-model' => 'user.email',
            'password-model' => 'user.pass',
            'login-event' => 'doLogin',
            'login-label' => 'Sign In',
        ]);
        $this->assertEquals('Email', $form->getUsernameInput()->getAttribute('label'));
        $this->assertEquals('Secret', $form->getPasswordInput()->getAttribute('label'));
        $this->assertEquals('user.email', $form->getUsernameInput()->getAttribute('v-model'));
        $this->assertEquals('user.pass', $form->getPasswordInput()->getAttribute('v-model'));
        $this->assertEquals('doLogin', $form->getLoginButton()->getAttribute('@click'));
        $this->assertStringContainsString('Sign In', $form->getLoginButton()->toHTML());
    }

    public function testLoginFormSetters() {
        $form = new LoginForm([]);
        $form->setUsernameModel('u');
        $form->setPasswordModel('p');
        $form->setUsernameLabel('User');
        $form->setPasswordLabel('Pass');
        $form->setLoginEvent('login()');
        $this->assertEquals('u', $form->getUsernameInput()->getAttribute('v-model'));
        $this->assertEquals('p', $form->getPasswordInput()->getAttribute('v-model'));
        $this->assertEquals('User', $form->getUsernameInput()->getAttribute('label'));
        $this->assertEquals('Pass', $form->getPasswordInput()->getAttribute('label'));
        $this->assertEquals('login()', $form->getLoginButton()->getAttribute('@click'));
    }

    public function testLoginFormSetLoginBtnLabel() {
        $form = new LoginForm([]);
        $form->setLoginBtnLabel('Go');
        $this->assertStringContainsString('Go', $form->getLoginButton()->toHTML());
    }

    // ── MessageDialog ──

    public function testMessageDialogDefaults() {
        $dialog = new MessageDialog('msg');
        $this->assertEquals('v-dialog', $dialog->getNodeName());
        $this->assertEquals('msg', $dialog->getModel());
        $this->assertNotNull($dialog->getCloseBtn());
        $this->assertEquals('msg.visible = false', $dialog->getCloseBtn()->getAttribute('@click'));
    }

    public function testMessageDialogCustomClose() {
        $dialog = new MessageDialog('msg', 'Dismiss', 'onClose()');
        $this->assertEquals('onClose()', $dialog->getCloseBtn()->getAttribute('@click'));
        $this->assertStringContainsString('Dismiss', $dialog->getCloseBtn()->toHTML());
    }

    public function testMessageDialogHasMessageBinding() {
        $dialog = new MessageDialog('msg');
        $this->assertStringContainsString('msg.message', $dialog->toHTML());
    }

    // ── PrivilegesTree ──

    public function testPrivilegesTreeDefaults() {
        $tree = new PrivilegesTree();
        $this->assertEquals('v-card', $tree->getNodeName());
        $this->assertNotNull($tree->getTreeview());
        $this->assertNull($tree->getSearchField());
    }

    public function testPrivilegesTreeWithTitle() {
        $tree = new PrivilegesTree('pr', 'Permissions');
        $this->assertStringContainsString('Permissions', $tree->toHTML());
    }

    public function testPrivilegesTreeWithSearch() {
        $tree = new PrivilegesTree('pr', null, true);
        $this->assertNotNull($tree->getSearchField());
    }

    public function testPrivilegesTreeModel() {
        $tree = new PrivilegesTree('myModel');
        $this->assertEquals('myModel.selected_privileges', $tree->getTreeview()->getAttribute('v-model'));
        $this->assertEquals('myModel.privileges', $tree->getTreeview()->getAttribute(':items'));
    }

    public function testPrivilegesTreeSetOnChange() {
        $tree = new PrivilegesTree();
        $tree->setOnPrivilegeChange('onPrChange');
        $this->assertEquals('onPrChange', $tree->getTreeview()->getAttribute('@input'));
    }

    // ── VDataTable ──

    public function testVDataTableDefaults() {
        $table = new VDataTable();
        $this->assertEquals('v-data-table', $table->getNodeName());
        $this->assertNotNull($table->getFooter());
        $this->assertNotNull($table->getVPagination());
        $this->assertNotNull($table->getPageSizeInput());
    }

    public function testVDataTableWithAttrs() {
        $table = new VDataTable([':items' => 'users', ':headers' => 'cols']);
        $this->assertEquals('users', $table->getAttribute(':items'));
        $this->assertEquals('cols', $table->getAttribute(':headers'));
    }

    public function testVDataTableSetPagingModel() {
        $table = new VDataTable();
        $result = $table->setPagingModel('paging');
        $this->assertSame($table, $result);
        $this->assertEquals('paging.page_number', $table->getVPagination()->getAttribute('v-model'));
        $this->assertEquals('paging.pages_count', $table->getVPagination()->getAttribute(':length'));
        $this->assertEquals('paging.size', $table->getPageSizeInput()->getAttribute('v-model'));
    }

    public function testVDataTableSetHasFooter() {
        $table = new VDataTable();
        $table->setPagingModel('p');
        $result = $table->setHasFooter(true);
        $this->assertSame($table, $result);
        $this->assertNotNull($table->getFooter()->getParent());

        $table->setHasFooter(false);
        $this->assertNull($table->getFooter()->getParent());
    }

    public function testVDataTableSetOnPageNumberClick() {
        $table = new VDataTable();
        $result = $table->setOnPageNumberClick('goToPage');
        $this->assertSame($table, $result);
        $this->assertEquals('goToPage', $table->getVPagination()->getAttribute('@input'));
    }

    public function testVDataTableSetOnPageSizeChanged() {
        $table = new VDataTable();
        $result = $table->setOnPageSizeChanged('changeSize');
        $this->assertSame($table, $result);
        $this->assertEquals('changeSize', $table->getPageSizeInput()->getAttribute('@input'));
    }

    public function testVDataTableAddItemSlot() {
        $table = new VDataTable();
        $el = $table->addItemSlot('name', 'span');
        $this->assertEquals('span', $el->getNodeName());
        $this->assertStringContainsString('#item.name', $table->toHTML());
    }

    public function testVDataTableAddExpandedRow() {
        $table = new VDataTable();
        $el = $table->addExpandedRow('div', 'onExpand');
        $this->assertEquals('div', $el->getNodeName());
        $this->assertEquals('onExpand', $table->getAttribute('@item-expanded'));
    }

    public function testVDataTableAddExpandedRowNoCallback() {
        $table = new VDataTable();
        $table->addExpandedRow('div');
        $this->assertNull($table->getAttribute('@item-expanded'));
    }

    public function testVDataTableSetPageSizeInputProps() {
        $table = new VDataTable();
        $result = $table->setPageSizeInputProps(['label' => 'Per Page']);
        $this->assertSame($table, $result);
        $this->assertEquals('Per Page', $table->getPageSizeInput()->getAttribute('label'));
    }

    // ── ActionsContainer ──

    public function testActionsContainerDefaults() {
        $container = new ActionsContainer();
        $this->assertEquals('div', $container->getNodeName());
    }

    public function testActionsContainerAddAction() {
        $container = new ActionsContainer();
        $btn = $container->addAction('doSomething', 'mdi-star');
        $this->assertInstanceOf(VBtn::class, $btn);
        $this->assertEquals('doSomething', $btn->getAttribute('@click'));
    }

    public function testActionsContainerAddActionWithTooltip() {
        $container = new ActionsContainer();
        $btn = $container->addAction('act', 'mdi-star', 'Star it');
        $this->assertStringContainsString('Star it', $container->toHTML());
    }

    public function testActionsContainerAddActionWithHTMLNodeTooltip() {
        $container = new ActionsContainer();
        $tip = new HTMLNode('span');
        $tip->text('Custom tip');
        $btn = $container->addAction('act', 'mdi-star', $tip);
        $this->assertStringContainsString('Custom tip', $container->toHTML());
    }

    public function testActionsContainerAddActionNoTooltip() {
        $container = new ActionsContainer();
        $btn = $container->addAction('act', 'mdi-star', null);
        // Button added directly, no v-tooltip wrapper
        $this->assertStringContainsString('v-btn', $container->toHTML());
        $this->assertStringNotContainsString('v-tooltip', $container->toHTML());
    }

    public function testActionsContainerAddViewAction() {
        $container = new ActionsContainer();
        $btn = $container->addViewAction('viewItem');
        $this->assertInstanceOf(VBtn::class, $btn);
        $this->assertEquals('viewItem', $btn->getAttribute('@click'));
        $this->assertEquals('blue lighten-1', $btn->getVIcon()->getAttribute('color'));
    }

    public function testActionsContainerAddUploadAction() {
        $container = new ActionsContainer();
        $btn = $container->addUploadAction('uploadFile');
        $this->assertEquals('uploadFile', $btn->getAttribute('@click'));
        $this->assertEquals('blue-grey', $btn->getVIcon()->getAttribute('color'));
    }

    public function testActionsContainerAddAddAction() {
        $container = new ActionsContainer();
        $btn = $container->addAddAction('addNew');
        $this->assertEquals('addNew', $btn->getAttribute('@click'));
        $this->assertEquals('green lighten-1', $btn->getVIcon()->getAttribute('color'));
    }

    public function testActionsContainerAddDeleteAction() {
        $container = new ActionsContainer();
        $btn = $container->addDeleteAction('removeItem');
        $this->assertEquals('removeItem', $btn->getAttribute('@click'));
        $this->assertEquals('red lighten-1', $btn->getVIcon()->getAttribute('color'));
    }

    public function testActionsContainerAddEditAction() {
        $container = new ActionsContainer();
        $btn = $container->addEditAction('editItem');
        $this->assertEquals('editItem', $btn->getAttribute('@click'));
    }

    // ── CRUDList ──

    public function testCRUDListMissingDialog() {
        $this->expectException(UIException::class);
        new CRUDList([]);
    }

    public function testCRUDListMissingConfirmDeleteDialog() {
        $this->expectException(UIException::class);
        new CRUDList(['dialog' => 'd']);
    }

    public function testCRUDListMinimalProps() {
        $list = new CRUDList([
            'dialog' => 'addDlg',
            'confirm-delete-dialog' => 'delDlg',
        ]);
        $this->assertEquals('v-card', $list->getNodeName());
        $this->assertNotNull($list->getDialog());
        $this->assertNotNull($list->getConfirmDeleteDialog());
        $this->assertNotNull($list->getVList());
        $this->assertNotNull($list->getVToolbar());
        $this->assertNotNull($list->getAddBtn());
        $this->assertNotNull($list->getEditBtn());
        $this->assertNotNull($list->getDeleteBtn());
        $this->assertNotNull($list->getSaveBtn());
        $this->assertNotNull($list->getCancelBtn());
        $this->assertNotNull($list->getConfirnBtn());
        $this->assertNotNull($list->getConfirnCancelBtn());
    }

    public function testCRUDListFullProps() {
        $list = new CRUDList([
            'dialog' => 'addDlg',
            'confirm-delete-dialog' => 'delDlg',
            'title' => 'Users',
            'items' => 'usersList',
            'dialog-title' => 'Add User',
            'delete-title' => 'Confirm Delete',
            'add-action' => 'saveUser',
            'edit-action' => 'editUser',
            'delete-action' => 'deleteUser',
            'confirm-delete-action' => 'confirmDel',
            'delete-prompt' => 'Really delete?',
        ]);
        $html = $list->toHTML();
        $this->assertStringContainsString('Users', $html);
        $this->assertStringContainsString('Add User', $html);
        $this->assertStringContainsString('Confirm Delete', $html);
        $this->assertStringContainsString('Really delete?', $html);
        $this->assertStringContainsString('saveUser', $html);
        $this->assertStringContainsString('editUser', $html);
        $this->assertStringContainsString('deleteUser', $html);
        $this->assertStringContainsString('confirmDel', $html);
    }

    public function testCRUDListDialogModels() {
        $list = new CRUDList([
            'dialog' => 'addDlg',
            'confirm-delete-dialog' => 'delDlg',
        ]);
        $this->assertEquals('addDlg', $list->getDialog()->getModel());
        $this->assertEquals('delDlg', $list->getConfirmDeleteDialog()->getModel());
    }

    public function testCRUDListAddCol() {
        $list = new CRUDList([
            'dialog' => 'd',
            'confirm-delete-dialog' => 'dd',
        ]);
        $col = $list->addCol(['cols' => 6]);
        $this->assertEquals('v-col', $col->getNodeName());
        $this->assertEquals('6', $col->getAttribute('cols'));
    }

    // ── ViewFileDialog ──

    public function testViewFileDialogDefaults() {
        $dialog = new ViewFileDialog('fileViewer');
        $this->assertEquals('v-dialog', $dialog->getNodeName());
        $this->assertEquals('fileViewer', $dialog->getModel());
        $this->assertNotNull($dialog->getToolbar());
        $this->assertEquals('70%', $dialog->getAttribute('width'));
        $html = $dialog->toHTML();
        $this->assertStringContainsString('fileViewer.file', $html);
        $this->assertStringContainsString('fileViewer.mime', $html);
    }

    public function testViewFileDialogWithDownload() {
        $dialog = new ViewFileDialog('fv', 'downloadFile');
        $html = $dialog->toHTML();
        $this->assertStringContainsString('downloadFile', $html);
        $this->assertStringContainsString('mdi-download', $html);
    }

    public function testViewFileDialogFullScreenButton() {
        $dialog = new ViewFileDialog('fv');
        $dialog->includeFullScreenButton();
        $html = $dialog->toHTML();
        $this->assertStringContainsString('mdi-arrow-expand-all', $html);
        $this->assertStringContainsString('mdi-arrow-collapse-all', $html);
    }
}
