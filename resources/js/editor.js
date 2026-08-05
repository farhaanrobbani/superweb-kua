import tinymce from 'tinymce';

import 'tinymce/icons/default/icons.min.js';
import 'tinymce/themes/silver/theme.min.js';
import 'tinymce/models/dom/model.min.js';

import 'tinymce/skins/ui/oxide/skin.js';
import 'tinymce/skins/ui/oxide/content.js';
import 'tinymce/skins/content/default/content.js';

import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/table';
import 'tinymce/plugins/code';

function uploadImageHandler(blobInfo, success, failure) {
    const textarea = document.querySelector('textarea[data-editor]');
    const url = textarea?.dataset.uploadUrl || '/announcements/gambar';

    const data = new FormData();
    data.append('upload', blobInfo.blob(), blobInfo.filename());

    const xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader(
        'X-CSRF-TOKEN',
        document.querySelector('meta[name="csrf-token"]')?.content || ''
    );
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onload = () => {
        try {
            const response = JSON.parse(xhr.responseText);
            if (xhr.status === 200 && response.url) {
                success(response.url);
            } else {
                failure(response.message || 'Gagal mengunggah gambar.');
            }
        } catch {
            failure('Respon unggahan tidak valid.');
        }
    };
    xhr.onerror = () => failure('Gagal mengunggah gambar.');
    xhr.send(data);
}

tinymce.init({
    selector: 'textarea[data-editor]',
    license_key: 'gpl',
    plugins: 'advlist autolink lists link image table code',
    toolbar:
        'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | fontfamily fontsize | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link blockquote hr table image | code',
    menubar: false,
    height: 480,
    skin_url: 'default',
    content_css: 'default',
    promotion: false,
    font_family_formats:
        'Arial=Arial, Helvetica, sans-serif; Tahoma=Tahoma, Geneva, sans-serif; Times New Roman=Times New Roman, Times, serif; Georgia=Georgia, serif; Verdana=Verdana, Geneva, sans-serif; Courier New=Courier New, Courier, monospace',
    font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt',
    automatic_uploads: true,
    relative_urls: false,
    convert_urls: false,
    images_upload_handler: uploadImageHandler,
});
