/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

var oDefaults = {
    sName: '',
    sLabel: '',
    oClasses: {
        sFieldClass: 'field-console-output',
        sFieldSpanClass: 'field',
        sButtonClass: 'button',
        sGroupClass: 'group',
        sFocusedClass: 'focus',
        sInvalidClass: 'invalid',
        sRequiredClass: 'required',
        sWaitingClass: 'waiting',
        sFieldRepetitionClass: 'repetition',
        sThumbClass: 'thumb',
        sNameClass: 'name',
        sSelectedTableClass: 'selected',
        sAddFilesClass: 'add-pictures',
        sQueueClass: 'upload-queue',
        sProgressClass: 'progress',
        sProgressBarClass: 'progress-bar',
        sProgressBarIndicatorClass: 'indicator',
        sUploadErrorClass: 'upload-error',
        sUploadSuccessClass: 'upload-success'
    },
    oImages: {
        sDeleteIcon: 'images/icons/datagrid/delete.png',
        sUploadButton: 'images/buttons/add-pictures.png'
    },
    aoOptions: [],
    sDefault: '',
    aoRules: [],
    sComment: '',
    iWidth: 121,
    iPort: 0,
    iHeight: 27,
};

var GFormConsoleOutput = GCore.ExtendClass(GFormFile, function () {

    var gThis = this;

    gThis.m_bShown = false;

    gThis._PrepareNode = function () {
        gThis.m_jNode = $('<div/>').addClass(gThis._GetClass('Field'));
        gThis.m_jNode.css({marginBottom: 20});

        gThis.m_jButton = $('<a class="' + gThis._GetClass('Button') + '" href="#"/>');
        gThis.m_jButton.append('<span>' + GTranslation(gThis.m_oOptions.sButtonLabel) + '</span>');
        gThis.m_jNode.append($('<p></p>').append('<br />').append(gThis.m_jButton));

        gThis.m_jButton.css({
            top: -20,
            position: 'relative',
            marginBottom: -5
        });
    };

    gThis.OnProcess = function () {
        gThis.m_jButton.hide();
        gThis.m_jNode.append('<h4>' + GTranslation('package.label.output') + '</h4>');
        gThis.m_jConsoleOutput = $('<iframe/>').attr('src', gThis.m_oOptions.sRunUrl).addClass(gThis._GetClass('Field'));

        gThis.m_jNode.append(gThis.m_jConsoleOutput);

        gThis.m_jConsoleOutput.load(function(){
            GAlert(GTranslation('package.flash.operation.success'));
        });
    };

    gThis._InitializeEvents = function (sRepetition) {
        $('.navigation .button').remove();
        gThis.m_jButton.click(gThis.OnProcess);
    };

    gThis.OnShow = function () {
        gThis.m_bShown = true;

        $(window).bind("beforeunload", function (event) {

        });
    };

}, oDefaults);
