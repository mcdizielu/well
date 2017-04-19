/*
 * FEATURE EDITOR
 */

var oDefaults = {
    sName: '',
    sLabel: '',
    oClasses: {
        sFieldClass: 'field-technical-data-editor',
        sFieldSpanClass: 'field',
        sPrefixClass: 'prefix',
        sSuffixClass: 'suffix',
        sFocusedClass: 'focus',
        sInvalidClass: 'invalid',
        sDisabledClass: 'disabled',
        sRequiredClass: 'required',
        sWaitingClass: 'waiting',
        sFieldRepetitionClass: 'repetition',
        sAddRepetitionClass: 'add-repetition',
        sRemoveRepetitionClass: 'remove-repetition',
        sGroupClass: 'group',
        sAttributeClass: 'attribute'
    },
    oImages: {
        sDeleteIcon: '_images_panel/icons/datagrid/delete.png',
        sSaveIcon: '_images_panel/icons/datagrid/save.png',
        sAddIcon: '_images_panel/icons/datagrid/add.png',
        sBlankIcon: '_images_panel/icons/buttons/blank.png',
        sEditIcon: '_images_panel/icons/datagrid/edit.png',
        sDelete: '_images_panel/buttons/small-delete.png',
        sAdd: '_images_panel/buttons/small-add.png'
    },
    sDefault: '',
    aoRules: [],
    sComment: '',
    aTechnicalAttributes: [],
    aAttributeGroups: []
};

var GFormFeatureEditor = GCore.ExtendClass(GFormField, function() {

    var gThis = this;

    gThis.m_bShown = false;
    gThis.m_aoSets = [];
    gThis.m_aoAttributes = [];
    gThis.m_oValues = {};

    gThis.m_sCurrentSet = '';

    gThis.m_jSets;
    gThis.m_jAttributes;
    gThis.m_jAdd;
    gThis.m_jFields;

    gThis.m_sGroupOptions;

    gThis.m_iLoads = ((gThis.m_oOptions.sSetId != undefined) && gThis.m_oOptions.sSetId) ? 0 : 1;

    gThis._PrepareNode = function() {
        gThis.m_sCurrentSet = gThis.m_oOptions.sSetId;
        gThis.m_jNode = $('<div/>').addClass(gThis._GetClass('Field'));
        gThis.m_jSets = $('<div/>');
        gThis.m_jAttributes = $('<fieldset/>');
        gThis.m_jFields = $('<div/>');
        gThis.m_jNode.append(gThis.m_jSets);
        gThis.m_jNode.append($('<div class="technical-data-info"><div class="groups">'+ GTranslation('feature_editor.label.group') +'</div><div class="attributes">'+ GTranslation('feature_editor.label.feature') +'</div><div class="values">'+ GTranslation('feature_editor.label.value') +'</div></div>'));
        gThis.m_jNode.append(gThis.m_jAttributes);
        gThis.m_jNode.append(gThis.m_jFields);
    };

    gThis.UpdateFields = function() {
        gThis.m_jFields.empty();
        var sFields = '';
        sFields += ('<input type="hidden" name="' + gThis.GetName() + '[set]" value="' + gThis.m_sCurrentSet + '"/>');
        var m_jAttributeSetSelect = gThis.m_gForm.GetField(gThis.m_oOptions.sFeatureSetField);
        m_jAttributeSetSelect.SetValue(gThis.m_sCurrentSet);
        for (var i = 0; (gThis.m_aoAttributes[i] != undefined); i++) {
            sFields += ('<input type="hidden" name="' + gThis.GetName() + '[groups][' + i + '][id]" value="' + gThis.m_aoAttributes[i].id + '"/>');
            for (var j = 0; (gThis.m_aoAttributes[i].children[j] != undefined); j++) {
                sFields += ('<input type="hidden" name="' + gThis.GetName() + '[groups][' + i + '][features][' + j + '][id]" value="' + gThis.m_aoAttributes[i].children[j].id + '"/>');
                sFields += ('<input type="hidden" name="' + gThis.GetName() + '[groups][' + i + '][features][' + j + '][type]" value="' + gThis.m_aoAttributes[i].children[j].type + '"/>');
                switch (gThis.m_aoAttributes[i].children[j].type) {
                    case GFormFeatureEditor.FIELD_TEXT_FIELD:
                    case GFormFeatureEditor.FIELD_TEXT_AREA:
                        sFields += ('<input type="hidden" name="' + gThis.GetName() + '[groups][' + i + '][features][' + j + '][value]" value="' + ((gThis.m_aoAttributes[i].children[j].value != undefined) ? gThis.m_aoAttributes[i].children[j].value : '') + '"/>');
                        break;
                    case GFormFeatureEditor.FIELD_CHECKBOX:
                        sFields += ('<input type="hidden" name="' + gThis.GetName() + '[groups][' + i + '][features][' + j + '][value]" value="' + (gThis.m_aoAttributes[i].children[j].value ? '1' : '0') + '"/>');
                        break;
                }
            }
        }
        gThis.m_jFields.html(sFields);
    };

    gThis.OnShow = function() {
        if (gThis.m_bShown) {
            return;
        }
        gThis.m_bShown = true;
        gThis.LoadSets();
    };

    gThis.OnFocus = function(eEvent) {
        gThis._ActivateFocusedTab(eEvent);
    };

    gThis.OnBlur = function(eEvent) {};

    gThis.OnReset = function() {
        gThis.m_sCurrentSet = gThis.m_oOptions.sSetId;
        gThis.m_iLoads = 0;
        gThis.LoadSets();
    };

    gThis.SetValue = function(mValue, sRepetition) {
        if (mValue == undefined) {
            return;
        }
        gThis.m_aoAttributes = [];
        for (var i = 0; i < mValue.length; i++) {
            var bFound = false;
            var l;
            for (l in gThis.m_oOptions.aAttributeGroups) {
                if (gThis.m_oOptions.aAttributeGroups[l].id == mValue[i].id) {
                    bFound = true;
                    break;
                }
            }
            if (!bFound) {
                continue;
            }
            var aoChildren = [];
            if (mValue[i].children != undefined) {
                for (var j = 0; j < mValue[i].children.length; j++) {
                    var oAttribute = mValue[i].children[j];
                    var bFound = false;
                    var k;
                    for (k in gThis.m_oOptions.aTechnicalAttributes) {
                        if (gThis.m_oOptions.aTechnicalAttributes[k].id == oAttribute.id) {
                            bFound = true;
                            break;
                        }
                    }
                    if (!bFound) {
                        continue;
                    }
                    aoChildren.push($.extend({}, gThis.m_oOptions.aTechnicalAttributes[k], {
                        value: oAttribute.value,
                        set_id: oAttribute.set_id
                    }));
                }
            }
            gThis.m_aoAttributes.push($.extend({},gThis.m_oOptions.aAttributeGroups[l], {
                children: aoChildren,
                set_id: mValue[i].set_id
            }));
        }
        gThis._WriteTechnicalAttributes();
    };

    gThis._InitializeEvents = function(sRepetition) {};

    gThis.LoadSets = function(fOnSuccess) {
        gThis.m_jSets.html('<div class="field-select"><label>'+ GTranslation('feature_editor.label.sets')+'</label><span class="repetition"><span class="waiting"></span></span></div>');
        GF_Ajax_Request(Routing.generate(gThis.m_oOptions.sGetSetsRoute), {}, gThis._OnSetsLoad);
    };

    gThis._OnSetsLoad = GEventHandler(function(eEvent) {
        gThis.m_aoSets = eEvent.sets;
        var m_jAttributeSetSelect = gThis.m_gForm.GetField(gThis.m_oOptions.sFeatureSetField);
        gThis.m_sCurrentSet = m_jAttributeSetSelect.GetValue();
        gThis._WriteSets();
        if ((gThis.m_sCurrentSet == undefined) && (gThis.m_aoSets.length > 0)) {
            //gThis.LoadTechnicalAttributesForSet(gThis.m_aoSets[0].id);
        }
        else {
            var bFound = false;
            for (var i in gThis.m_aoSets) {
                if (gThis.m_aoSets[i].id == gThis.m_sCurrentSet) {
                    bFound = true;
                    break;
                }
            }
            if (bFound) {
                gThis.LoadTechnicalAttributesForSet(gThis.m_sCurrentSet);
            }
        }
        if (eEvent.fOnSuccess != undefined) {
            eEvent.fOnSuccess(eEvent);
        }
    });

    gThis._WriteSets = function() {
        gThis.m_jSets.empty();
        var jSelect = $('<select id="' + gThis.GetName() + '__set"/>');
        jSelect.append('<option value="">---</option>');
        for (var i = 0; i < gThis.m_aoSets.length; i++) {
            var oSet = gThis.m_aoSets[i];
            jSelect.append('<option' + ((oSet.id == gThis.m_sCurrentSet) ? ' selected="selected"' : '') + ' value="' + oSet.id + '">' + oSet.name + '</option>');
        }
        var jField = $('<div class="field-select"><label for="' + gThis.GetName() + '__set">' + GTranslation('feature_editor.label.choose_set') + '</label><span class="repetition"><span class="field"></span></span></div>');

        jField.find('.field').append(jSelect).after($('<span class="suffix"></span>'));
        gThis.m_jSets.append(jField);
        jSelect.GSelect();
        jSelect.change(function(eEvent) {
            gThis._OnSetchange(eEvent);
        });

    };

    gThis._OnSetchange = new GEventHandler(function(eEvent) {
        var sChosenSet = $(eEvent.currentTarget).val();
        if (sChosenSet == '') {
            gThis.m_sCurrentSet = '';
            gThis.UpdateFields();
            return;
        }

        gThis.LoadTechnicalAttributesForSet(sChosenSet);
    });

    gThis.LoadTechnicalAttributesForSet = function(sId) {
        gThis.m_sCurrentSet = sId;
        if (gThis.m_iLoads++ < 1) {
            gThis._WriteTechnicalAttributes();
            return;
        }

        GF_Ajax_Request(Routing.generate(gThis.m_oOptions.sGetGroupsRoute), {
            setId: gThis.m_sCurrentSet,
            productId: gThis.m_oOptions.sProductId
        }, gThis._OnTechnicalAttributesLoad);
    };

    gThis._OnTechnicalAttributesLoad = GEventHandler(function(eEvent) {
        gThis.m_aoAttributes = eEvent.groups;
        gThis._WriteTechnicalAttributes();
    });

    gThis._WriteTechnicalAttributes = function() {
        gThis.m_jAttributes.empty();

        for (var i = 0; (gThis.m_aoAttributes[i] != undefined); i++) {
            var oGroup = gThis.m_aoAttributes[i];
            gThis.AddAttributeGroup(oGroup);
        }
        gThis._UpdateIndices();
        gThis.UpdateValues();
    };

    gThis.AddAttributeGroup = function(oGroup) {
        if (oGroup == undefined) {
            oGroup = {};
        }

        var jGroup = $('<div class="' + gThis._GetClass('Group') + ' GFormRepetition"/>');
        jGroup.append($('<div class="field-technical-group"/>').prepend($('<span class="constant" />').html(oGroup.name)));
        gThis.m_jAttributes.append(jGroup);
        var jAttributes = $('<div class="attributes"/>');
        jGroup.append(jAttributes);
        var jAddAttribute = $('<a href="#" class="add-attribute"><img src="' + gThis._GetImage('Icon') + '"/></a>');
        jAttributes.append(jAddAttribute);
        jAddAttribute.hide();
        if (oGroup.children != undefined) {
            for (var j = 0; (oGroup.children[j] != undefined); j++) {
                var oAttribute = oGroup.children[j];
                gThis.AddAttribute(jAttributes, oAttribute, oGroup.id);
            }
        }
    };

    gThis.AddAttribute = function(jGroup, oAttribute, iGroupIndex) {
        if (oAttribute == undefined) {
            oAttribute = {};
        }
        var jAttribute = $('<div class="' + gThis._GetClass('Attribute') + '"/>');

        var aoActiveGroupAttributes = [];
        for (var j = 0; j < gThis.m_oOptions.aAttributeGroups.length; j++) {
            var oGroup = gThis.m_oOptions.aAttributeGroups[j];
            if(oGroup.id == iGroupIndex){
                aoActiveGroupAttributes = oGroup.attributes;
            }
        }

        jAttribute.append($('<div class="field-technical-attribute"/>').prepend($('<span class="constant"/>').html(oAttribute.name)));
        jGroup.children('.add-attribute').before(jAttribute);
        var jDelete = $('<img src="' + gThis._GetImage('BlankIcon') + '" />');
        jAttribute.find('.field-select:first').prepend($('<span class="prefix"/>').append(jDelete));
        var jValue = $('<div class="value"/>');
        jAttribute.append(jValue);

        gThis._UpdateValueField(oAttribute, jAttribute);
    };

    gThis._UpdateValueField = function(oAttribute, jAttribute) {
        var jValue = jAttribute.find('.value').empty();
        switch (oAttribute.type) {
            case GFormFeatureEditor.FIELD_TEXT_FIELD:
                gThis._WriteValueTypeTextField(jValue);
                break;
            case GFormFeatureEditor.FIELD_TEXT_AREA:
                gThis._WriteValueTypeTextArea(jValue);
                break;
            case GFormFeatureEditor.FIELD_CHECKBOX:
                gThis._WriteValueTypeCheckbox(jValue);
                break;
        }
    };

    gThis._WriteValueTypeTextField = function(jTarget) {
        var jInput = $('<input type="text"/>');
        jInput.focus(GEventHandler(function(eEvent) {
            $(this).closest('.field').addClass('focus');
        })).blur(GEventHandler(function(eEvent) {
            $(this).closest('.field').removeClass('focus');
        }));
        var jInputNode = $('<div class="field-text"><span class="field"/></div>').append($('<span class="suffix"/>')).find('.field').append(jInput);
        jTarget.append(jInputNode.parent());
        jInput.change(gThis._OnChangeValue);
    };

    gThis._WriteValueTypeTextArea = function(jTarget) {
        var jInput = $('<textarea rows="5" cols="5" style="width: 485px;" />');
        jInput.focus(GEventHandler(function(eEvent) {
            $(this).closest('.field').addClass('focus');
        })).blur(GEventHandler(function(eEvent) {
            $(this).closest('.field').removeClass('focus');
        }));
        var jInputNode = $('<div class="field-textarea"><span class="field"/></div>').append($('<span class="suffix"/>')).find('.field').append(jInput);
        jTarget.append(jInputNode.parent());
        jInput.change(gThis._OnChangeValue);
    };

    gThis._WriteValueTypeCheckbox = function(jTarget) {
        var jInput = $('<input type="checkbox"/>');
        var jInputNode = $('<div class="field-checkbox"><span class="field"/></div>').find('.field').append(jInput);
        jTarget.append(jInputNode.parent());
        jInput.change(gThis._OnChangeValue);
    };

    gThis._OnChangeValue = GEventHandler(function(eEvent) {
        var iGroupIndex = $(this).closest('.attribute').data('iGroupIndex');
        var iAttributeIndex = $(this).closest('.attribute').data('iAttributeIndex');
        switch (gThis.m_aoAttributes[iGroupIndex].children[iAttributeIndex].type) {
            case GFormFeatureEditor.FIELD_TEXT_FIELD:
                gThis.m_aoAttributes[iGroupIndex].children[iAttributeIndex].value = $(this).val();
                break;
            case GFormFeatureEditor.FIELD_TEXT_AREA:
                gThis.m_aoAttributes[iGroupIndex].children[iAttributeIndex].value = $(this).val();
                break;
            case GFormFeatureEditor.FIELD_CHECKBOX:
                gThis.m_aoAttributes[iGroupIndex].children[iAttributeIndex].value = $(this).is(':checked');
                break;
        }
        gThis.UpdateFields();
    });

    gThis.UpdateValues = function() {
        for (var i in gThis.m_aoAttributes) {
            for (var j in gThis.m_aoAttributes[i].children) {
                if (gThis.m_aoAttributes[i].children[j].value != undefined) {
                    gThis._UpdateValue(i, j, gThis.m_aoAttributes[i].children[j].value);
                }
            }
        }
        gThis.UpdateFields();
    };

    gThis._UpdateValue = function(iGroupIndex, iAttributeIndex, mValue) {
        switch (gThis.m_aoAttributes[iGroupIndex].children[iAttributeIndex].type) {
            case GFormFeatureEditor.FIELD_TEXT_FIELD:
                gThis.m_jAttributes.find('.group:eq(' + iGroupIndex + ') .attribute:eq(' + iAttributeIndex + ') .value input:text').val(mValue);
                break;
            case GFormFeatureEditor.FIELD_TEXT_AREA:
                gThis.m_jAttributes.find('.group:eq(' + iGroupIndex + ') .attribute:eq(' + iAttributeIndex + ') .value textarea').val(mValue);
                break;
            case GFormFeatureEditor.FIELD_CHECKBOX:
                if (Number(mValue)) {
                    gThis.m_jAttributes.find('.group:eq(' + iGroupIndex + ') .attribute:eq(' + iAttributeIndex + ') .value').checkCheckboxes();
                }
                else {
                    gThis.m_jAttributes.find('.group:eq(' + iGroupIndex + ') .attribute:eq(' + iAttributeIndex + ') .value').unCheckCheckboxes();
                }
                break;
        }
    };

    gThis._UpdateIndices = function() {
        gThis.m_jAttributes.children('.group').each(function(i) {
            $(this).data('iGroupIndex', i);
            $(this).find('.attribute').each(function(j) {
                $(this).data('iAttributeIndex', j);
                $(this).data('iGroupIndex', $(this).closest('.group').data('iGroupIndex'));
            });
        });
    };

}, oDefaults);

GFormFeatureEditor.FIELD_TEXT_FIELD = 1;
GFormFeatureEditor.FIELD_TEXT_AREA = 2;
GFormFeatureEditor.FIELD_CHECKBOX = 3;
GFormFeatureEditor.s_iNewId = 0;
