(function($) {
"use strict";

// CKEDITOR
CKEDITOR.replace('content', {
      // Define the toolbar groups as it is a more accessible solution.
      extraPlugins: 'autogrow,image2,embed,youtube',
      removePlugins: 'resize',
      embed_provider : '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
      enterMode: CKEDITOR.ENTER_BR,

      // Toolbar adjustments to simplify the editor.
 toolbar: [{
     name: 'document',
     items: ['Undo', 'Redo']
   },
   {
     name: 'basicstyles',
     items: ['Bold', 'Italic', 'Strike', 'Underline', '-', 'RemoveFormat']
   },
   {
     name: 'links',
     items: ['Link', 'Unlink', 'Anchor']
   },
   {
     name: 'paragraph',
     items: ['BulletedList', 'NumberedList']
   },
   {
     name: 'insert',
     items: ['Image', 'Youtube', 'Embed']
   },
   {
     name: 'tools',
     items: ['Maximize', 'ShowBlocks']
   }
 ],

  // Upload dropped or pasted images to the CKFinder connector (note that the response type is set to JSON).
  filebrowserImageUploadUrl : url_file_upload,
  filebrowserUploadMethod: 'xhr',

  // Remove the redundant buttons from toolbar groups defined above.
  removeButtons: 'Subscript,Superscript,Anchor,Styles,Specialchar',
    });

    var data = CKEDITOR.instances.content.getData();

})(jQuery);
