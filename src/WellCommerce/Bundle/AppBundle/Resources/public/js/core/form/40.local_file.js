var oDefaults = {
  sName: '',
  sLabel: '',
  oClasses: {
    sFieldClass: 'field-localfile',
    sFieldSpanClass: 'field',
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
    sUploadSuccessClass: 'upload-success',
  },
  oImages: {
    sChooseIcon: 'images/icons/filetypes/directory.png',
    sDeleteIcon: 'images/icons/datagrid/delete.png',
    sUploadButton: 'images/buttons/add-pictures.png',
  },
  aoOptions: [],
  sDefault: '',
  aoRules: [],
  sComment: '',
  sUploadUrl: '',
  sSessionId: '',
  sSessionName: '',
  asFileTypes: [],
  sFileTypesDescription: '',
  fDeleteFile: GCore.NULL,
  fLoadFiles: GCore.NULL,
  iWidth: 131,
  iHeight: 34,
  iMaxFileSize: 100 * 1024	// kB
};

var GFormLocalFile = GCore.ExtendClass(GFormFile, function() {

  var gThis = this;

  gThis.m_bShown = false;
  gThis.m_jFilesDatagrid;
  gThis.m_gDataProvider;
  gThis.m_gFilesDatagrid;
  gThis.m_jSelectedFiles;
  gThis.m_jSwfUpload
  gThis.m_jCreateFolderButton;
  gThis.m_jQueue;
  gThis.m_iUploadsInProgress = 0;
  gThis.m_iLockId = -1;
  gThis.m_bLoadedDefaults = false;
  gThis.m_jSelectedFileName;

  gThis.m_sCWD;

  gThis._PrepareNode = function() {
    gThis.m_jNode = $('<div/>').addClass(gThis._GetClass('Field'));
    var jLabel = $('<label for="' + gThis.GetId() + '"/>');
    jLabel.text(GTranslation(gThis.m_oOptions.sLabel));
    gThis.m_jNode.append(jLabel);
    gThis.m_jNode.append(gThis._AddField());
  };

  gThis._AddField = function() {
    var jRepetition = $('<span class="repetition"/>');
    gThis.m_jSelectedFileName = $('<span class="filename"/>');
    jRepetition.append(gThis.m_jSelectedFileName);
    gThis.m_jSwfUpload = $('<div class="' + gThis._GetClass('AddFiles') + '"><a href="#" class="button expand"><span id="' + gThis.GetId() + '__upload"><img src="' + gThis._GetImage('ChooseIcon') + '" alt=""/>' + GTranslation('file_selector.add_from_disk') + '</span></a></div>');
    jRepetition.append(gThis.m_jSwfUpload);
    gThis.m_jCreateFolderButton = $('<a href="#" class="button expand"><span><img src="' + gThis._GetImage('ChooseIcon') + '" alt=""/>' + GTranslation('local_file.button.create_folder') + '</span></a>');
    jRepetition.append($('<span class="browse-pictures" style="float: right;margin-right: 5px;"/>').append(gThis.m_jCreateFolderButton));
    gThis.m_jQueue = $('<ul class="' + gThis._GetClass('Queue') + '" id="' + gThis.GetId() + '__queue"/>');
    jRepetition.append(gThis.m_jQueue);
    gThis.m_jFilesDatagrid = $('<div/>');
    jRepetition.append(gThis.m_jFilesDatagrid);
    gThis.m_jSelectedFiles = $('<div class="' + gThis._GetClass('SelectedTable') + '"/>');
    jRepetition.append(gThis.m_jSelectedFiles);
    gThis.m_jField = $('<input type="hidden" name="' + gThis.GetName() + '"/>');
    jRepetition.append(gThis.m_jField);
    return jRepetition;
  };

  gThis.GetValue = function() {
    return gThis.m_jField.val();
  };

  gThis.SetValue = function(mValue, sRepetition) {
    if (mValue == undefined) {
      return;
    }
    if (gThis.m_jField == undefined) {
      return;
    }
    if (mValue == null || mValue == '') {
      gThis.m_jField.val('');
      gThis.m_jSelectedFileName.html('<span class="none">' + GTranslation('local_file.label.not_selected') + '</span>');
    }
    else {
      gThis.m_jField.val(mValue).change();
      gThis.m_jSelectedFileName.text(mValue);
    }
  };

  gThis.Populate = function(mValue) {
    gThis.SetValue(mValue);
  };

  gThis._InitializeEvents = function () {
      gThis.m_jCreateFolderButton.click(gThis._OnCreateFolder);
  };

  gThis._OnCreateFolder = function() {
    GAlert.DestroyAll();
    GPrompt(GTranslation('local_file.flash.create_folder'), function(sName) {
      GCore.StartWaiting();

      oRequest = {
        cwd: gThis.m_sCWD,
        name: sName
      };

      gThis.m_gFilesDatagrid.MakeRequest(Routing.generate(gThis.m_oOptions.sCreateFolderRoute), oRequest, function(oResponse) {
        gThis._RefreshFiles();
        GCore.StopWaiting();
        GAlert.DestroyAll();
      });
    });

    return false;
  };

  gThis._Delete = function(iDg, sId) {
    var iAlertId = GWarning(GTranslation('local_file.flash.delete_warning'), GTranslation('local_file.flash.delete_warning_description'), {
      bAutoExpand: true,
      aoPossibilities: [
        {mLink: function() {
          GCore.StartWaiting();
          GAlert.Destroy(iAlertId);
          oRequest = {
            file: sId
          };
          gThis.m_gFilesDatagrid.MakeRequest(Routing.generate(gThis.m_oOptions.sDeleteRoute), oRequest, function(oResponse) {
            GCore.StopWaiting();
            var oValue = gThis.GetValue();
            if (sId == gThis.m_oOptions.sFilePath + oValue) {
              gThis.m_gFilesDatagrid.ClearSelection();
            }
            gThis._RefreshFiles();
          });
        }, sCaption: GTranslation('local_file.flash.confirm_delete')},
        {mLink: GAlert.DestroyThis, sCaption: GTranslation('local_file.flash.cancel_delete')}
      ]
    });
  };

  gThis._OnClickRow = function(gDg, sId) {
    var oFile = gThis.m_gFilesDatagrid.GetRow(sId);
    if (oFile.dir) {
      gThis.m_sCWD = oFile.path;
      gThis._RefreshFiles();
      return false;
    }
    return true;
  };

  gThis._OnSelect = function(gDg, sId) {
    var oFile = gDg.GetRow(sId);
    if (!oFile.dir) {
      gThis.SetValue(oFile.path.substr(gThis.m_oOptions.sFilePath.length));
    }
  };

  gThis._OnDeselect = function(gDg, sId) {
    gThis.SetValue('');
  };

  gThis._Initialize = function() {
    var oValue = gThis.GetValue();
    var sPath = gThis.m_oOptions.sFilePath + oValue;
    sPath = sPath.substr(0, sPath.lastIndexOf('/') + 1);
    gThis.m_sCWD = sPath;
  };

  gThis.OnShow = function() {
    if (!gThis.m_bShown) {
      gThis._InitUploader();
      gThis._InitFilesDatagrid();
      gThis.m_bShown = true;
    }
  };

  gThis._ProcessFile = function(oRow) {
    if (oRow.dir) {
      if (oRow.name == '..') {
        oRow.thumb = '<img src="' + GCore.DESIGN_PATH + gThis.m_oOptions.oTypeIcons['cdup'] + '" alt=""/>';
      }
      else {
        oRow.thumb = '<img src="' + GCore.DESIGN_PATH + gThis.m_oOptions.oTypeIcons['directory'] + '" alt=""/>';
      }
    }
    else {
      var sExtension = oRow.name.substr(oRow.name.lastIndexOf('.') + 1);
      if (gThis.m_oOptions.oTypeIcons[sExtension] == undefined) {
        sExtension = 'unknown';
      }

      oRow.thumb = '<img src="' + GCore.DESIGN_PATH + gThis.m_oOptions.oTypeIcons[sExtension] + '" alt=""/>';
    }
    return oRow;
  };

  gThis._InitUploader = function() {
    var uploader = new plupload.Uploader({
      runtimes: 'html5',
      browse_button: gThis.GetId() + '__upload',
      container: document.getElementById(gThis.GetId() + '__queue'),
      url: Routing.generate(gThis.m_oOptions.sUploadRoute),
      filters: {
        max_file_size: gThis.m_oOptions.iMaxUploadSize + 'mb',
        mime_types: [{
          title: "Files",
          extensions: gThis.m_oOptions.asFileTypes.join()
        }]
      },
      init: {
        FilesAdded: function (up, files) {
          plupload.each(files, function (file) {
            gThis.OnFileQueued(file);
          });
          up.start();
        },
        FileUploaded: function (up, files, response) {
          gThis.OnUploadSuccess(files, response);
        },
        UploadProgress: function (up, file) {
          gThis.OnUploadProgress(file);
        },
        Error: function (up, err) {
          gThis.OnUploadProgress(err);
        },
        UploadComplete: function () {
          gThis.OnUploadComplete();
        },

      }
    });

    uploader.bind('BeforeUpload', function(up, file) {
        up.settings.multipart_params = {
            "cwd": gThis.m_sCWD
        };
    });

    uploader.init();
  };

  gThis.OnFileQueued = function(oFile) {
    if (gThis.m_iUploadsInProgress++ == 0) {
      gThis.m_iLockId = gThis.m_gForm.Lock(GForm.Language.file_selector_form_blocked, GForm.Language.file_selector_form_blocked_description);
    }
    var jLi = $('<li class="upload__' + oFile.id + '"/>');
    jLi.append('<h4>' + oFile.name + '</h4>');
    jLi.append('<p class="' + gThis._GetClass('Progress') + '"/>');
    jLi.append('<div class="' + gThis._GetClass('ProgressBar') + '"><div class="' + gThis._GetClass('ProgressBarIndicator') + '"></div>');
    gThis.m_jQueue.append(jLi);
  };

  gThis.OnUploadProgress = function(oFile) {
    var jLi = gThis.m_jQueue.find('.upload__' + oFile.id);
    jLi.find('.' + gThis._GetClass('Progress')).text(oFile.percent + '%');
    jLi.find('.' + gThis._GetClass('ProgressBarIndicator')).css('width', oFile.percent + '%');
  };

  gThis.OnUploadError = function(oError) {
    GAlert(GForm.Language.file_selector_upload_error, oError.message);
  };

  gThis.OnUploadSuccess = function(oFile, oResponse) {
    var oServerResponse = $.parseJSON(oResponse.response);
    if (oServerResponse.sError != undefined) {
      return GAlert(oServerResponse.sError, oServerResponse.sMessage);
    }

    var jLi = gThis.m_jQueue.find('.upload__' + oFile.id);
    jLi.addClass(gThis._GetClass('UploadSuccess'));
    jLi.find('.' + gThis._GetClass('Progress')).text(GForm.Language.file_selector_upload_success);
    jLi.find('.' + gThis._GetClass('ProgressBarIndicator')).css('width', '100%');
    var sFile = (gThis.m_sCWD + '/' + oServerResponse.sFilename).substr(gThis.m_oOptions.sFilePath.length);
    if (sFile.indexOf('/') == 0){
      sFile = sFile.substring(1);
    }
    gThis.m_gFilesDatagrid.m_asSelected = [gThis.m_sCWD + '/' + oServerResponse.sFilename];
    gThis.SetValue(sFile);
    gThis._RefreshFiles();
    if (gThis.m_gFilesDatagrid) {
      gThis.m_gFilesDatagrid.LoadData();
    }
    jLi.delay(2000).fadeOut(250, function () {
      $(this).remove();
    });
  };

  gThis.OnUploadComplete = function(eEvent, oFile) {
    if (--gThis.m_iUploadsInProgress <= 0) {
      gThis.m_iUploadsInProgress = 0;
      gThis.m_gForm.Unlock(gThis.m_iLockId);
    }
  };

  gThis._InitColumns = function() {

    var column_path = new GF_Datagrid_Column({
      id: 'path',
      caption: GTranslation('local_file.label.path'),
      appearance: {
        width: 70,
        visible: false,
        align: GF_Datagrid.ALIGN_LEFT,
      },
    });

    var column_hierarchy = new GF_Datagrid_Column({
      id: 'hierarchy',
      caption: GTranslation('common.label.hierarchy'),
      appearance: {
        width: 70,
        visible: false,
        align: GF_Datagrid.ALIGN_LEFT,
      },
    });

    var column_thumb = new GF_Datagrid_Column({
      id: 'thumb',
      caption: GTranslation('local_file.label.thumb'),
      appearance: {
        width: 50,
        no_title: true,
      },
    });

    var column_name = new GF_Datagrid_Column({
      id: 'name',
      caption: GTranslation('local_file.label.name'),
      appearance: {
        align: GF_Datagrid.ALIGN_LEFT,
        width: GF_Datagrid.WIDTH_AUTO
      },
      filter: {
        type: GF_Datagrid.FILTER_INPUT,
      },
    });

    var column_size = new GF_Datagrid_Column({
      id: 'size',
      caption: GTranslation('local_file.label.size'),
      appearance: {
        align: GF_Datagrid.ALIGN_LEFT,
        width: 80
      }
    });

    var column_mtime = new GF_Datagrid_Column({
      id: 'mtime',
      caption: GTranslation('local_file.label.mtime'),
      appearance: {
        align: GF_Datagrid.ALIGN_LEFT,
        width: 120
      }
    });

    return [
      column_path,
      column_hierarchy,
      column_thumb,
      column_name,
      column_size,
      column_mtime,
    ];

  };

  gThis._RefreshFiles = function() {
    oRequest = {
      path: gThis.m_sCWD,
      types: gThis.m_oOptions.asFileTypes,
      rootPath: gThis.m_oOptions.sFilePath
    };

    gThis.m_gFilesDatagrid.MakeRequest(Routing.generate(gThis.m_oOptions.sLoadRoute), oRequest, function(oResponse) {
      if (gThis.m_gDataProvider) {
        gThis.m_gDataProvider.ChangeData(oResponse.files);
        gThis.m_gFilesDatagrid.LoadData();
      }
    });
  };

  gThis._OnDataLoaded = function(dDg) {
    dDg.m_jBody.find('.show-thumb').mouseenter(GTooltip.ShowThumbForThis).mouseleave(GTooltip.HideThumbForThis);
  };

  gThis._InitFilesDatagrid = function() {

    var aoColumns = gThis._InitColumns();

    var gActionDelete = new GF_Action({
      img: gThis._GetImage('DeleteIcon'),
      caption: GTranslation('local_file.button.delete_file'),
      action: gThis._Delete,
      condition: function(oRow) {
        return !oRow.dir;
      }
    });

    gThis.m_gDataProvider = new GF_Datagrid_Data_Provider({
      key: 'path',
    }, []);

    var oOptions = {
      id: gThis.GetId(),
      mechanics: {
        rows_per_page: 100,
        key: 'path',
        only_one_selected: true,
        no_column_modification: true,
        persistent: false,
        default_sorting: 'hierarchy',
      },
      event_handlers: {
        load: function(oRequest, sResponseHandler) {
          return gThis.m_gDataProvider.Load(oRequest, sResponseHandler);
        },
        loaded: gThis._OnDataLoaded,
        process: gThis._ProcessFile,
        select: gThis._OnSelect,
        deselect: gThis._OnDeselect,
        click_row: gThis._OnClickRow,
      },
      row_actions: [
        gActionDelete
      ],
      columns: aoColumns,
    };

    gThis.m_gFilesDatagrid = new GF_Datagrid(gThis.m_jFilesDatagrid, oOptions);

    var oValue = gThis.GetValue();
    gThis.m_gFilesDatagrid.m_asSelected = [gThis.m_oOptions.sFilePath + oValue];

    gThis._RefreshFiles();
  };

}, oDefaults);
