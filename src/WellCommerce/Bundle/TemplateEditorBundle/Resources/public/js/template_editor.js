require.config({paths: {'vs': '/bundles/wellcommercetemplateeditor/js/monaco/dev/vs'}});

var oTemplateEditorDefaults = {
  sTreeNode: 'tree',
  sEditorTheme: 'vs-dark',
  sEditorNode: 'editor',
  sListFilesRoute: 'admin.template_editor.file_list',
  sGetFileRoute: 'admin.template_editor.file_content',
  sSaveFileRoute: 'admin.template_editor.file_save',
  sUploadFileRoute: 'admin.template_editor.file_upload',
  sTheme: 'wellcommerce-default-theme',
};

var GTemplateEditor = function(oOptions) {

  var gThis = this;

  gThis._Constructor = function() {
    gThis.InitializeEvents();
  };

  gThis.InitializeEditor = function() {
    require(['vs/editor/editor.main'], function() {
      gThis.oEditor = monaco.editor.create(document.getElementById(gThis.m_oOptions.sEditorNode), {
        value: [].join('\n'),
        language: 'html',
        theme: gThis.m_oOptions.sEditorTheme,
      });

      myBinding = gThis.oEditor.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KEY_S, function() {
        gThis.SaveFile();
      });
    });
  };

  gThis.InitializeFilesTree = function() {
    $('#' + gThis.m_oOptions.sTreeNode).fileTree({
      root: '',
      multiFolder: true,
      expandSpeed: -1,
      collapseSpeed: -1,
      script: Routing.generate(gThis.m_oOptions.sListFilesRoute,
          {theme: gThis.m_oOptions.sTheme}),
    }, function(file, type) {

      oRequest = {
        file: file,
        theme: gThis.m_oOptions.sTheme,
      };

      GF_Ajax_Request(Routing.generate(gThis.m_oOptions.sGetFileRoute, {}), oRequest, function(oResponse) {
        if (oResponse.error !== undefined) {
          GError(oResponse.error);
          return false;
        }

        gThis.sEditedFile = oResponse.file;
        newModel = monaco.editor.createModel(oResponse.content, gThis.EvaluateLanguage(oResponse.extension));
        gThis.oEditor.setModel(newModel);
      });
    });
  };

  gThis.EvaluateLanguage = function(sExtension) {
    switch (sExtension) {
      case 'html':
      case 'twig':
        return 'html';
      case 'css':
        return 'css';
      case 'less':
        return 'less';
      case 'js':
        return 'javascript';
      case 'xml':
        return 'xml';
      case 'json':
        return 'json';
    }
  };

  gThis.InitializeEvents = function() {
    gThis.InitializeFilesTree();
    gThis.InitializeEditor();
  };

  gThis.SaveFile = function() {
    if (gThis.sEditedFile === undefined) {
      GNotification('No file to save.');
      return;
    }

    oRequest = {
      content: gThis.oEditor.getValue(),
      file: gThis.sEditedFile,
      theme: gThis.m_oOptions.sTheme,
    };

    GF_Ajax_Request(Routing.generate(gThis.m_oOptions.sSaveFileRoute, {}), oRequest, function(oResponse) {
      if (oResponse.error !== undefined) {
        GError(oResponse.error);
        return false;
      }

      GNotification('File saved.');
    });
  };

  gThis._Constructor();
};

new GPlugin('GTemplateEditor', oTemplateEditorDefaults, GTemplateEditor);


