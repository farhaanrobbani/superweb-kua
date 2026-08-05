import {
    Alignment,
    AutoImage,
    BlockQuote,
    Bold,
    ClassicEditor,
    Essentials,
    Font,
    Heading,
    HorizontalLine,
    Image,
    ImageBlock,
    ImageCaption,
    ImageInline,
    ImageResize,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    Indent,
    IndentBlock,
    Italic,
    Link,
    List,
    Paragraph,
    SourceEditing,
    Strikethrough,
    Table,
    TableCellProperties,
    TableProperties,
    TableToolbar,
    Underline,
    Undo,
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

class CsrfUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file.then(
            (file) =>
                new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '/admin/announcements/gambar', true);
                    xhr.setRequestHeader(
                        'X-CSRF-TOKEN',
                        document.querySelector('meta[name="csrf-token"]')?.content || ''
                    );
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                    xhr.onload = () => {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (xhr.status === 200 && response.url) {
                                resolve({ default: response.url });
                            } else {
                                reject(new Error(response.message || 'Gagal mengunggah gambar.'));
                            }
                        } catch {
                            reject(new Error('Respon unggahan tidak valid.'));
                        }
                    };
                    xhr.onerror = () => reject(new Error('Gagal mengunggah gambar.'));
                    xhr.send(data);
                })
        );
    }

    abort() {}
}

function uploadPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) =>
        new CsrfUploadAdapter(loader);
}

const editorElements = document.querySelectorAll('textarea[data-editor]');

editorElements.forEach((element) => {
    ClassicEditor.create(element, {
        plugins: [
            Essentials,
            Paragraph,
            Heading,
            Bold,
            Italic,
            Underline,
            Strikethrough,
            Font,
            Alignment,
            List,
            Link,
            BlockQuote,
            HorizontalLine,
            Indent,
            IndentBlock,
            Table,
            TableToolbar,
            TableProperties,
            TableCellProperties,
            Image,
            ImageBlock,
            ImageInline,
            ImageCaption,
            ImageStyle,
            ImageToolbar,
            ImageResize,
            ImageUpload,
            AutoImage,
            SourceEditing,
            Undo,
            uploadPlugin,
        ],
        toolbar: {
            items: [
                'undo',
                'redo',
                '|',
                'heading',
                '|',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                '|',
                'fontSize',
                'fontFamily',
                'fontColor',
                'fontBackgroundColor',
                '|',
                'alignment',
                'bulletedList',
                'numberedList',
                '|',
                'outdent',
                'indent',
                '|',
                'link',
                'blockQuote',
                'horizontalLine',
                'insertTable',
                'imageUpload',
                '|',
                'sourceEditing',
            ],
        },
        image: {
            toolbar: ['imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight', '|', 'imageTextAlternative'],
            styles: ['alignLeft', 'alignCenter', 'alignRight'],
        },
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties'],
        },
        language: 'id',
    })
        .then((editor) => {
            const form = element.closest('form');
            if (form) {
                form.addEventListener('submit', () => {
                    editor.updateSourceElement();
                });
            }
        })
        .catch((error) => {
            console.error('CKEditor gagal dimuat:', error);
        });
});
