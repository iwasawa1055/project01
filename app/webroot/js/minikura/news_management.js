$(function() {
  // init
  var fullEditor = new Quill('#full-editor', {
    modules: {
      'toolbar': { container: '#full-toolbar' },
      'link-tooltip': true
    },
    theme: 'snow'
  });

  // edit:init
  fullEditor.setHTML($('#NewsDetail').val());
  // edit:submit
  $('#NewsEditForm').submit(function(){
    setInputData();
  });

  // add:submit
  $('#NewsAddForm').submit(function(){
    setInputData();
  });

  function setInputData(){
    $('#NewsDetail').val(fullEditor.getHTML() + '\r');
  };
});
