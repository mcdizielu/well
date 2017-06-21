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
    sFieldClass: 'field-text',
    sFieldSpanClass: 'field',
    sPrefixClass: 'prefix',
    sSuffixClass: 'suffix',
    sFocusedClass: 'focus',
    sInvalidClass: 'invalid',
    sRequiredClass: 'required',
    sWaitingClass: 'waiting',
    sFieldRepetitionClass: 'repetition',
    sAddRepetitionClass: 'add-field-repetition',
    sRemoveRepetitionClass: 'remove-field-repetition'
  },
  oImages: {
    sAddRepetition: 'images/icons/buttons/add.png',
    sRemoveRepetition: 'images/icons/buttons/delete.png'
  },
  sFieldType: 'text',
  sDefault: '',
  aoRules: []
};

var GFormColourPicker = GCore.ExtendClass(GFormTextField, function () {

  var gThis = this;

  gThis._InitializeEvents = function (sRepetition) {
    if (gThis.m_jField === undefined) {
      return;
    }

    if (gThis.m_bRepeatable && (sRepetition === undefined)) {
      return;
    }

    gThis.m_jField.ColorPicker({
      color: '#' + gThis.m_jField.val(),
      onBeforeShow: function () {
        $(this).ColorPickerSetColor(this.value);
      },
      onShow: function(colpkr) {
        $(colpkr).fadeIn(250);
        return false;
      },
      onHide: function (colpkr) {
        $(colpkr).fadeOut(250);
        return false;
      },
      onChange: function(hsb, hex, rgb) {
        gThis.m_jField.val('#' + hex);
      }
    });
  };

}, oDefaults);
