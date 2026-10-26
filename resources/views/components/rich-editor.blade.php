@props(['name' => 'content', 'value' => '', 'placeholder' => 'Tulis konten di sini...'])

<div class="rich-editor-wrapper" wire:ignore>
    <textarea 
        id="editor-{{ $name }}" 
        name="{{ $name }}" 
        class="hidden"
    >{{ $value }}</textarea>
</div>

@push('scripts')
<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof tinymce === 'undefined') return;

        tinymce.init({
            selector: '#editor-{{ $name }}',
            height: 500,
            license_key: 'gpl',
            promotion: false,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | link image media table | code fullscreen help',
            content_style: 'body { font-family: Poppins, sans-serif; font-size: 14px; }',
            branding: false,
            promotion: false,
            // Izinkan embed YouTube, Maps, Drive, dll
            media_live_embeds: true,
            // Upload gambar otomatis ke server
            images_upload_handler: function (blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route("admin.editor.upload") }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                    xhr.upload.onprogress = function(e) {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const json = JSON.parse(xhr.responseText);
                            resolve(json.location);
                        } else {
                            reject('Upload gagal: ' + xhr.status);
                        }
                    };

                    xhr.onerror = function() {
                        reject('Upload gagal.');
                    };

                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                });
            },
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    });
</script>
@endpush