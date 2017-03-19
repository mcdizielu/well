/*
 * CLIENT SELECT
 */

var oDefaults = {
    sName: '',
    sLabel: '',
    oClasses: {
        sFieldClass: 'field-client-select',
        sFieldSpanClass: 'field',
        sGroupClass: 'group',
        sFocusedClass: 'focus',
        sInvalidClass: 'invalid',
        sRequiredClass: 'required',
        sWaitingClass: 'waiting',
        sFieldRepetitionClass: 'repetition',
        sHiddenClass: 'hidden',
        sButtonClass: 'button',
        sTriggerClass: 'trigger'
    },
    oImages: {
        sAddIcon: '_images_panel/icons/buttons/add-customer.png',
        sDeselectIcon: '_images_panel/icons/datagrid/delete.png'
    },
    aoOptions: [],
    sDefault: '',
    aoRules: [],
    sComment: '',
    fLoadClientData: GCore.NULL
};

var GFormClientSelect = GCore.ExtendClass(GFormField, function () {

    var gThis = this;

    gThis.m_bShown = false;

    gThis.m_jDatagrid;
    gThis.m_jDatagridWrapper;
    gThis.m_jTrigger;
    gThis.m_jSelectedDatagrid;
    gThis.m_gDatagrid;
    gThis.m_gSelectedDatagrid;
    gThis.m_gDataProvider;
    gThis.m_bFirstLoad = true;
    gThis.m_jClientUsername;
    gThis.m_jClientDiscount;
    gThis.m_aoAddresses = [];
    gThis.m_agListeners = [];

    gThis.m_bFirstLoad = true;

    gThis.GetValue = function (sRepetition) {
        if (gThis.m_jField == undefined) {
            return '';
        }

        return gThis.m_jField.val();
    };

    gThis.SetValue = function (mValue) {
        if (gThis.m_jField == undefined) {
            return;
        }

        gThis.m_jField.val(mValue).change();
    };

    gThis._OnDataLoaded = function (oData) {
        gThis.m_jClientUsername.text(oData.clientDetails.username);
        gThis.m_jClientDiscount.text(oData.clientDetails.discount + "%");

        $('#contactDetails__contactDetails\\.firstName').val(oData.contactDetails.firstName);
        $('#contactDetails__contactDetails\\.lastName').val(oData.contactDetails.lastName);
        $('#contactDetails__contactDetails\\.phone').val(oData.contactDetails.phone);
        $('#contactDetails__contactDetails\\.secondaryPhone').val(oData.contactDetails.secondaryPhone);
        $('#contactDetails__contactDetails\\.email').val(oData.contactDetails.email);

        $('#addresses__billingAddress__billingAddress\\.firstName').val(oData.billingAddress.firstName);
        $('#addresses__billingAddress__billingAddress\\.lastName').val(oData.billingAddress.lastName);
        $('#addresses__billingAddress__billingAddress\\.line1').val(oData.billingAddress.line1);
        $('#addresses__billingAddress__billingAddress\\.line2').val(oData.billingAddress.line2);
        $('#addresses__billingAddress__billingAddress\\.postalCode').val(oData.billingAddress.postalCode);
        $('#addresses__billingAddress__billingAddress\\.state').val(oData.billingAddress.state);
        $('#addresses__billingAddress__billingAddress\\.city').val(oData.billingAddress.city);
        $('#addresses__billingAddress__billingAddress\\.vatId').val(oData.billingAddress.vatId);
        $('#addresses__billingAddress__billingAddress\\.companyName').val(oData.billingAddress.companyName);

        $('#addresses__shippingAddress__shippingAddress\\.firstName').val(oData.shippingAddress.firstName);
        $('#addresses__shippingAddress__shippingAddress\\.lastName').val(oData.shippingAddress.lastName);
        $('#addresses__shippingAddress__shippingAddress\\.line1').val(oData.shippingAddress.line1);
        $('#addresses__shippingAddress__shippingAddress\\.line2').val(oData.shippingAddress.line2);
        $('#addresses__shippingAddress__shippingAddress\\.postalCode').val(oData.shippingAddress.postalCode);
        $('#addresses__shippingAddress__shippingAddress\\.state').val(oData.shippingAddress.state);
        $('#addresses__shippingAddress__shippingAddress\\.city').val(oData.shippingAddress.city);
        $('#addresses__shippingAddress__shippingAddress\\.companyName').val(oData.shippingAddress.companyName);
    };

    gThis._OnSelect = function (gDg, sId) {
        gThis.SetValue(sId);
        gThis.m_gDatagrid.MakeRequest(Routing.generate(gThis.m_oOptions.sGetClientDetailsRoute, {id: sId}), {}, gThis._OnDataLoaded);
    };

    gThis._OnDeselect = function (gDg, sId) {

    };

    gThis._OnChange = function (eEvent) {
        if (gThis.m_bRepeatable) {
            gThis.m_jField.empty();
        }
        var aoData = [];
        for (var i in eEvent.rows) {
            aoData.push({
                id: eEvent.rows[i].idproduct,
                quantity: eEvent.rows[i].quantity,
                variant: eEvent.rows[i].variant
            });
        }
        gThis.SetValue(aoData);
    };

    gThis._PrepareNode = function () {
        gThis.m_jNode = $('<div/>').addClass(gThis._GetClass('Field'));
        gThis.m_jField = $('<input type="hidden" name="' + gThis.GetName() + '"/>');
        gThis.m_jDatagrid = $('<div/>');
        gThis.m_jNode.append(gThis.m_jField);
        gThis.m_jDatagridWrapper = $('<div class="existing-users"/>');
        gThis.m_jDatagridWrapper.append(gThis.m_jDatagrid);
        gThis.m_jNode.append(gThis.m_jDatagridWrapper);
        gThis.m_jDatagridWrapper.addClass(gThis._GetClass('Hidden'));
        gThis.m_jTrigger = $('<p class="' + gThis._GetClass('Trigger') + '"/>');

        var jA = $('<a href="#" id="__select" class="' + gThis._GetClass('Button') + '"/>');
        jA.append('<span><img src="' + gThis._GetImage('AddIcon') + '" alt=""/>' + GTranslation('form.client_select.select') + '</span>');
        jA.click(GEventHandler(function (eEvent) {
            var jImg = gThis.m_jTrigger.find('a#__select span img');
            if (gThis.m_jDatagridWrapper.hasClass(gThis._GetClass('Hidden'))) {
                gThis.m_jDatagridWrapper.css('display', 'none').removeClass(gThis._GetClass('Hidden'));
            }
            if (!gThis.m_jDatagridWrapper.get(0).bHidden) {
                gThis.m_gDatagrid.LoadData();
                gThis.m_jDatagridWrapper.get(0).bHidden = true;
                gThis.m_jTrigger.find('a#__select span').empty().append(jImg).append(GTranslation('form.client_select.deselect'));
            }
            else {
                gThis.m_jDatagridWrapper.get(0).bHidden = false;
                gThis.m_jTrigger.find('a#__select span').empty().append(jImg).append(GTranslation('form.client_select.select'));
            }
            gThis.m_jDatagridWrapper.slideToggle(250);
            return false;
        }));

        gThis.m_jTrigger.append(jA);
        gThis.m_jNode.append(gThis.m_jTrigger);
        var jColumns = $('<div class="layout-two-columns"/>');
        var jLeftColumn = $('<div class="column"/>');
        jColumns.append(jLeftColumn);
        gThis.m_jClientUsername = $('<span class="constant"/>').css('line-height', '34px');
        jLeftColumn.append($('<div class="field-text"/>').append('<label>' + GTranslation('common.label.email') + '</label>').append($('<span class="repetition"/>').append(gThis.m_jClientUsername)));
        gThis.m_jClientDiscount = $('<span class="constant"/>').css('line-height', '34px');
        jLeftColumn.append($('<div class="field-text"/>').append('<label>' + GTranslation('common.label.discount') + '</label>').append($('<span class="repetition"/>').append(gThis.m_jClientDiscount)));
        
        gThis.m_jNode.append(jColumns);
    };

    gThis.OnReset = function () {
        gThis.m_bFirstLoad = true;
    };

    gThis.Populate = function (mValue) {
        if (!gThis.m_gDatagrid) {
            return;
        }

        gThis.m_oOptions.sDefault = mValue;
        gThis._UpdateDatagridSelection(mValue);
        gThis.SetValue(mValue);

        if (mValue != undefined && mValue > 0) {
            gThis.m_gDatagrid.MakeRequest(Routing.generate(gThis.m_oOptions.sGetClientDetailsRoute, {id: mValue}), {}, function(oResponse){
                gThis.m_jClientUsername.text(oResponse.clientDetails.username);
                gThis.m_jClientDiscount.text(oResponse.clientDetails.discount + "%");
            });
        }
    };

    gThis._UpdateDatagridSelection = function (mValue) {
        if (!(mValue instanceof Array)) {
            if ((mValue == undefined) || !mValue.length) {
                mValue = [];
            }
            else {
                mValue = [mValue];
            }
        }
        gThis.m_gDatagrid.m_asSelected = [];
        for (var i = 0; i < mValue.length; i++) {
            gThis.m_gDatagrid.m_asSelected[i] = mValue[i];
        }
        gThis.m_gDatagrid.LoadData();
    };

    gThis.OnShow = function () {
        gThis._InitDatagrid();
        gThis.Populate(gThis.m_oOptions.sDefault);
    };

    gThis._InitColumns = function () {

        var column_id = new GF_Datagrid_Column({
            id: 'id',
            caption: GTranslation('common.label.id'),
            sorting: {
                "default_order": GF_Datagrid.SORT_DIR_DESC
            },
            appearance: {
                "visible": false,
                "width": 60,
                "align": GF_Datagrid.ALIGN_LEFT
            },
            filter: {
                "type": GF_Datagrid.FILTER_BETWEEN
            }
        });


        var column_firstName = new GF_Datagrid_Column({
            id: 'firstName',
            caption: GTranslation('common.label.first_name'),
            appearance: {
                "width": 100,
                "align": GF_Datagrid.ALIGN_LEFT
            },
            filter: {
                "type": GF_Datagrid.FILTER_INPUT
            }
        });


        var column_lastName = new GF_Datagrid_Column({
            id: 'lastName',
            caption: GTranslation('common.label.last_name'),
            appearance: {
                "width": 100,
                "align": GF_Datagrid.ALIGN_LEFT
            },
            filter: {
                "type": GF_Datagrid.FILTER_INPUT
            }
        });

        var column_companyName = new GF_Datagrid_Column({
            id: 'companyName',
            caption: GTranslation('client.label.address.company_name'),
            appearance: {
                "width": 100,
                "align": GF_Datagrid.ALIGN_CENTER
            },
            filter: {
                "type": GF_Datagrid.FILTER_INPUT
            }
        });


        var column_vatId = new GF_Datagrid_Column({
            id: 'vatId',
            caption: GTranslation('client.label.address.vat_id'),
            appearance: {
                "width": 80,
                "align": GF_Datagrid.ALIGN_CENTER
            },
            filter: {
                "type": GF_Datagrid.FILTER_INPUT
            }
        });


        var column_email = new GF_Datagrid_Column({
            id: 'email',
            caption: GTranslation('common.label.email'),
            appearance: {
                "width": 80,
                "align": GF_Datagrid.ALIGN_CENTER
            },
            filter: {
                "type": GF_Datagrid.FILTER_INPUT
            }
        });


        var column_phone = new GF_Datagrid_Column({
            id: 'phone',
            caption: GTranslation('common.label.phone'),
            appearance: {
                "width": 80,
                "align": GF_Datagrid.ALIGN_CENTER
            },
            filter: {
                "type": GF_Datagrid.FILTER_INPUT
            }
        });

        return [
            column_id,
            column_firstName,
            column_lastName,
            column_companyName,
            column_vatId,
            column_email,
            column_phone
        ];
    };

    gThis._InitDatagrid = function () {

        var aoColumns = gThis._InitColumns();

        var oOptions = {
            id: gThis.GetId(),
            mechanics: {
                rows_per_page: 15,
                key: 'id',
                only_one_selected: true,
                persistent: false
            },
            event_handlers: {
                load: function (oRequest) {
                    gThis.m_gDatagrid.MakeRequest(Routing.generate(gThis.m_oOptions.sLoadClientsRoute), oRequest, GF_Datagrid.ProcessIncomingData);
                },
                select: gThis._OnSelect,
                deselect: gThis._OnDeselect
            },
            columns: aoColumns
        };

        gThis.m_gDatagrid = new GF_Datagrid(gThis.m_jDatagrid, oOptions);

    };

    gThis._Deselect = function (iDg, mId) {
        if (!(mId instanceof Array)) {
            mId = [mId];
        }
        for (var i = 0; i < mId.length; i++) {
            gThis.m_gDatagrid.DeselectRow(mId[i]);
        }
        gThis.m_gSelectedDatagrid.ClearSelection();
        gThis.m_gDatagrid.LoadData();
    };

    gThis.AddListener = function (gNode) {
        gThis.m_agListeners.push(gNode);
    };

}, oDefaults);
