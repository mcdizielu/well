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
        sFieldClass: 'field-product-select',
        sFieldSpanClass: 'field',
        sGroupClass: 'group',
        sFocusedClass: 'focus',
        sInvalidClass: 'invalid',
        sRequiredClass: 'required',
        sWaitingClass: 'waiting',
        sFieldRepetitionClass: 'repetition'
    },
    oImages: {
        sDeselectIcon: 'images/icons/datagrid/delete.png'
    },
    aoOptions: [],
    sDefault: '',
    aoRules: [],
    sComment: '',
    fLoadProducts: GCore.NULL
};

var GFormProductSelect = GCore.ExtendClass(GFormField, function () {

    var gThis = this;

    gThis.m_bShown = false;

    gThis.m_fLoadProducts;
    gThis.m_fProcessProduct;
    gThis.m_jDatagrid;
    gThis.m_jSelectedDatagrid;
    gThis.m_gDatagrid;
    gThis.m_gSelectedDatagrid;
    gThis.m_gDataProvider;
    gThis.m_bFirstLoad = true;
    gThis.m_sDependentSelector;

    gThis.GetValue = function (sRepetition) {
        if (gThis.m_jField == undefined) {
            return '';
        }
        if (gThis.m_bRepeatable) {
            if (sRepetition != undefined) {
                return gThis.m_jField.find('input[name="' + gThis.GetName(sRepetition) + '"]').val();
            }
            var aValues = [];
            var jValues = gThis.m_jField.find('input');
            for (var i = 0; i < jValues.length; i++) {
                aValues.push(jValues.eq(i).val());
            }

            return aValues;
        }
        else {
            return gThis.m_jField.val();
        }
    };

    gThis.PopulateErrors = function (mData) {
        if ((mData == undefined) || (mData == '')) {
            return;
        }
        gThis.SetError(mData);
    };

    gThis.SetValue = function (mValue, sRepetition) {
        if (gThis.m_jField == undefined) {
            return;
        }
        if (gThis.m_bRepeatable) {
            for (var i in mValue) {
                if (i == 'toJSON') {
                    continue;
                }

                gThis.m_jField.append('<input type="hidden" name="' + gThis.GetName(i) + '" value="' + mValue[i] + '"/>');
            }

        }
        else {
            gThis.m_bSkipValidation = true;
            gThis.m_jField.val(mValue).change();
            gThis.m_bSkipValidation = false;
        }
    };

    gThis._OnSelect = function (gDg, sId) {
        if (gThis.m_bRepeatable) {
            var oSelectedRow = gDg.GetRow(sId);
            oSelectedRow.quantity = 1;
            oSelectedRow.variant = '';
            gThis.m_gDataProvider.AddRow(oSelectedRow);
            gThis.m_gSelectedDatagrid.LoadData();
        }
        else {
            gThis.SetValue(sId);
        }
    };

    gThis._OnDeselect = function (gDg, sId) {
        if (gThis.m_bRepeatable) {
            gThis.m_gDataProvider.DeleteRow(sId);
            gThis.m_gSelectedDatagrid.LoadData();
        }
        else {
            gThis.SetValue('');
        }
    };

    gThis._OnChange = function (eEvent) {
        if (gThis.m_bRepeatable) {
            gThis.m_jField.empty();
        }
        var asIds = [];
        for (var i in eEvent.rows) {
            asIds.push(eEvent.rows[i].id);
        }
        gThis.SetValue(asIds);
    };

    gThis._PrepareNode = function () {
        gThis.m_oOptions.oParsedFilterData = {};
        for (var i in gThis.m_oOptions.oFilterData) {
            $.globalEval('var oParsed = [' + gThis.m_oOptions.oFilterData[i] + '];');
            gThis.m_oOptions.oParsedFilterData[i] = $.extend({}, oParsed);
        }
        gThis.m_jNode = $('<div/>').addClass(gThis._GetClass('Field'));
        var jLabel = $('<label/>');
        jLabel.text(GTranslation(gThis.m_oOptions.sLabel));
        if ((gThis.m_oOptions.sComment != undefined) && (gThis.m_oOptions.sComment.length)) {
            jLabel.append(' <small>' + GTranslation(gThis.m_oOptions.sComment) + '</small>');
        }
        gThis.m_jNode.append(jLabel);
        if (gThis.m_bRepeatable) {
            gThis.m_jField = $('<div/>');
            gThis.m_jDatagrid = $('<div/>');
            jLabel = $('<label/>');
            jLabel.text(GForm.Language.product_select_selected + ':');
            gThis.m_jSelectedDatagrid = $('<div/>');
            gThis.m_jNode.append(gThis.m_jDatagrid);
            gThis.m_jNode.append(jLabel);
            gThis.m_jNode.append(gThis.m_jSelectedDatagrid);
        }
        else {
            gThis.m_jField = $('<input type="hidden" name="' + gThis.GetName() + '"/>');
            gThis.m_jDatagrid = $('<div/>');
            gThis.m_jNode.append(gThis.m_jDatagrid);
        }
        gThis.m_jNode.append(gThis.m_jField);
    };

    gThis.OnReset = function () {
        gThis.m_bFirstLoad = true;
    };

    gThis.Populate = function (mValue) {
        if (gThis.m_bRepeatable) {
            gThis.m_jField.empty();
            if (gThis.m_gDatagrid) {
                gThis.m_oOptions.asDefaults = GCore.Duplicate(mValue);
            }
        }
        else {
            gThis.m_oOptions.sDefault = mValue;
        }
        if (gThis.m_gDatagrid) {
            gThis._UpdateDatagridSelection(mValue);
        }
        gThis.SetValue(mValue);
        if (gThis.m_gDatagrid && gThis.m_bRepeatable) {
            gThis.m_gSelectedDatagrid.LoadData();
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
        if (gThis.m_gDatagrid) {
            gThis.m_gDatagrid.m_asSelected = [];
            for (var i = 0; i < mValue.length; i++) {
                gThis.m_gDatagrid.m_asSelected[i] = mValue[i];
            }
        }
    };

    gThis.OnShow = function () {
        if (!gThis.m_bShown) {
            gThis._InitDatagrid();
            if (gThis.m_bRepeatable) {
                gThis._InitSelectedDatagrid();
                gThis.Populate(gThis.m_oOptions.sValue);
            }
            else {
                gThis.Populate(gThis.m_oOptions.sValue);
            }
            gThis.m_bShown = true;
        }
        else {
            gThis.m_gDatagrid.LoadData();
        }
    };

    gThis._ProcessProduct = function (oProduct) {
        if (oProduct.photo !== '') {
            oProduct.name = '<a title="" href="' + oProduct.photo + '" class="show-thumb"><img src="'+ GCore.DESIGN_PATH + 'images/icons/datagrid/details.png" style="vertical-align: middle;" /></a> '+ oProduct.name;
        }else{
            oProduct.name = '<img style="opacity: 0.2;vertical-align: middle;" src="'+ GCore.DESIGN_PATH + 'images/icons/datagrid/details.png" style="vertical-align: middle;" /> '+ oProduct.name;
        }

        return oProduct;
    };

    gThis._ProcessSelectedProduct = function (oProduct) {
        oProduct = gThis.m_fProcessProduct(oProduct);
        return oProduct;
    };

    gThis._LoadedRecord = function (dDg) {
        dDg.m_jBody.find('.show-thumb').mouseenter(GTooltip.ShowThumbForThis).mouseleave(GTooltip.HideThumbForThis);
    };

    gThis._InitColumns = function () {

        var column_id = new GF_Datagrid_Column({
            id: 'id',
            caption: GTranslation('common.label.id'),
            appearance: {
                width: 40,
                visible: false
            },
            filter: {
                type: GF_Datagrid.FILTER_BETWEEN
            }
        });

        var column_name = new GF_Datagrid_Column({
            id: 'name',
            caption: GTranslation('common.label.name'),
            appearance: {
                align: GF_Datagrid.ALIGN_LEFT,
                width: 240
            },
            filter: {
                type: GF_Datagrid.FILTER_INPUT
            }
        });

        var column_category = new GF_Datagrid_Column({
            id: 'category',
            caption: GTranslation('common.label.categories'),
            appearance: {
                width: GF_Datagrid.WIDTH_AUTO
            },
            filter: {
                type: GF_Datagrid.FILTER_INPUT
            }
        });

        var column_sku = new GF_Datagrid_Column({
            id: 'sku',
            caption: GTranslation('common.label.sku'),
            appearance: {
                width: 120
            },
            filter: {
                type: GF_Datagrid.FILTER_INPUT
            }
        });

        var column_gross_amount = new GF_Datagrid_Column({
            id: 'grossAmount',
            caption: GTranslation('common.label.gross_price'),
            appearance: {
                width: 140
            },
            filter: {
                type: GF_Datagrid.FILTER_BETWEEN
            }
        });

        return [
            column_id,
            column_name,
            column_category,
            column_sku,
            column_gross_amount
        ];
    };

    gThis._InitDatagrid = function () {

        gThis.m_fProcessProduct = gThis._ProcessProduct;
        gThis.m_fLoadProducts = gThis.m_oOptions.fLoadProducts;

        var aoColumns = gThis._InitColumns();
        var oOptions = {
            id: gThis.GetId(),
            mechanics: {
                rows_per_page: 10,
                key: 'id',
                only_one_selected: !gThis.m_bRepeatable,
                no_column_modification: true,
                persistent: false
            },
            event_handlers: {
                load: function (oData, sProcessFunction) {
                    gThis.m_gDatagrid.MakeRequest(Routing.generate(gThis.m_oOptions.sLoadRoute), oData, GF_Datagrid.ProcessIncomingData);
                },
                process: gThis.m_fProcessProduct,
                select: gThis._OnSelect,
                deselect: gThis._OnDeselect,
                loaded: gThis._LoadedRecord
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

    gThis._InitSelectedDatagrid = function () {

        gThis.m_gDataProvider = new GF_Datagrid_Data_Provider({
            key: 'id',
            event_handlers: {
                change: gThis._OnChange
            }
        }, []);

        var aoColumns = gThis._InitColumns();

        var gActionDeselect = new GF_Action({
            img: gThis._GetImage('DeselectIcon'),
            caption: GForm.Language.product_select_deselect,
            action: gThis._Deselect
        });

        var oOptions = {
            id: gThis.GetId() + '_selected',
            appearance: {
                filter: false
            },
            mechanics: {
                rows_per_page: 1000,
                key: 'id',
                no_column_modification: true,
                persistent: false
            },
            event_handlers: {
                load: function (oRequest, sResponseHandler) {
                    if (gThis.m_bFirstLoad) {
                        gThis.m_bFirstLoad = false;
                        gThis._LoadSelected(oRequest, sResponseHandler);
                    }
                    else {
                        gThis.m_gDataProvider.Load(oRequest, sResponseHandler);
                    }
                },
                update_row: function (sId, oRow) {
                    gThis.m_gDataProvider.UpdateRow(sId, oRow);
                },
                process: gThis._ProcessSelectedProduct,
                loaded: gThis._LoadedRecord
            },
            columns: aoColumns,
            row_actions: [
                gActionDeselect
            ],
            context_actions: [
                gActionDeselect
            ],
            group_actions: [
                gActionDeselect
            ]
        };

        gThis.m_gSelectedDatagrid = new GF_Datagrid(gThis.m_jSelectedDatagrid, oOptions);

    };

    gThis._LoadSelected = function (oRequest, sResponseHandler) {
        oRequest.where = [{
            column: 'id',
            value: gThis.m_oOptions.sValue,
            operator: 'IN'
        }];

        gThis.m_gSelectedDatagrid.MakeRequest(Routing.generate(gThis.m_oOptions.sLoadRoute), oRequest, function (eEvent) {
            gThis.m_gDataProvider.ChangeData(eEvent.rows);
            gThis.m_gSelectedDatagrid.LoadData();
        });
    };

}, oDefaults);
