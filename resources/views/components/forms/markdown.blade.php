@props(['label' => 'Content', 'name' => 'source_content'])

<div class="form-group">
    <label for="{{ $id ?? $name }}">{{ $label }}</label>
    {!!
           Form::textarea(
               $name,
               null,
               ['class' => 'form-control', 'id' => $id ?? $name]
           )
    !!}
    <small class="form-text text-muted">
        Use <a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" target="_blank">
        Markdown</a> to format this text.
    </small>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
{{-- Syntax highlighting --}}
<script src="https://cdn.jsdelivr.net/highlight.js/latest/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/highlight.js/latest/styles/github.min.css">
<script>
 var simplemde = new SimpleMDE({
     element: document.getElementById("source_content"),
     spellChecker: false,
     promptURLs: true,
     /* previewRender: ,*/
     /* previewRender: function(plainText) {*/
     /* console.log(plainText);*/
     /* return customMarkdownParser(plainText); // Returns HTML from a custom parser*/
     /* },*/
     previewRender: function(plainText) { // Async method
         var that = this;
         var parent = that.parent;

         var preview = document.getElementById("preview");
         var compiled = this.parent.markdown(plainText);
         preview.innerHTML = compiled;
         return compiled;
     },

     toolbar: [
         "bold", "italic", "strikethrough",
         "|",
         "heading-1", "heading-2", "heading-3",
         "|",
         "code", "quote", "link", "image", "table",
         "|",
         "unordered-list", "ordered-list",
         "|",
         "preview", "side-by-side", "fullscreen",
         "guide"
     ]
 });
</script>
<style type="text/css">
 .CodeMirror, .CodeMirror-scroll {
     max-height: 300px;
 }
</style>
@endpush
