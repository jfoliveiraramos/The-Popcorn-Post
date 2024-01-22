import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import FilePondPluginImageEdit from 'filepond-plugin-image-edit';
import 'filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css';

document.addEventListener('DOMContentLoaded', function () {

    const inputElement = document.querySelector('input[type="file"].filepond');

    if (!inputElement) {
        return;
    }

    const add_image_section = document.querySelector('#add-image-section');
    let submitButton = document.querySelector('button[type="submit"]');

    let existing_files = [];

    if (add_image_section) {
        const rawImages = add_image_section.dataset.images;

        if (rawImages) {
            const images = JSON.parse(rawImages);

            images.forEach(function (image) {
                existing_files.push({
                    source: image.id,
                    options: {
                        type: image.type,
                    },
                });
            });
        }

        const limbo_images = add_image_section.dataset.limbo_images;

        if (limbo_images) {
            const images = limbo_images.split(',');

            images.forEach(function (image) {
                existing_files.push({
                    source: image,
                    options: {
                        type: 'limbo',
                    },
                });
            });
        }
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    FilePond.registerPlugin(FilePondPluginImagePreview);
    FilePond.registerPlugin(FilePondPluginImageCrop);
    FilePond.registerPlugin(FilePondPluginImageTransform);
    FilePond.registerPlugin(FilePondPluginImageEdit);

    const pond = FilePond.create(inputElement);

    pond.setOptions({
        server: {
            url: '/api/filepond/',
            process: 'uploads/process',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            load: 'load/',
            restore: 'restore/',
            revert: 'delete',
            // remove: (source, load, error) => {
            //     sendAjaxRequest('DELETE', '/filepond/remove', { id: source }, function () {
            //         load();
            //     });
            // },
            acceptedFileTypes: ['image/jpg', 'image/jpeg', 'image/png'],
        },
        labelFileProcessingError: 'Error processing file. Check file format and size.',
        labelIdle: 'Drag & Drop your images or <span class="filepond--label-action">Browse</span><br><small>Maximum file size: 1MB. Accepted file types: .jpg, .png</small>',
        files: existing_files,
        allowMultiple: true,
        allowImageCrop: true,
        allowReorder: true,
        imageCropAspectRatio: '16:10',
        allowImagePreview: true,
        imagePreviewHeight: 150,
        imageTargetWidth: 700,
        allowImageEdit: true,
        maxFileSize: '1MB',
        acceptedFileTypes: ['image/jpg', 'image/jpeg', 'image/png'],
        credits: false,
        onprocessfileprogress: (file, progress) => {
            const isFileUploadPending = progress !== 1;

            submitButton.disabled = isFileUploadPending;
        }
    });
});

function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}