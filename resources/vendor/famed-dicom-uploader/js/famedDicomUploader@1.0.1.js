'use strict';

const DICOMUploader = function (settings) {

    settings = settings || {};
    if (!('inputFiles' in settings)) {
        throw 'settings.inputFiles is not defined';
    }
    if (!('outputFiles' in settings)) {
        throw 'settings.outputFiles is not defined';
    }
    if (!('uploadProgress' in settings)) {
        //throw 'settings.uploadProgress is not defined';
        settings.uploadProgress = '';
    }
    if (!('uploadButton' in settings)) {
        throw 'settings.uploadButton is not defined';
    }
    if (!('uploadUrl' in settings)) {
        throw 'settings.uploadUrl is not defined';
    }
    if (!('uploadSuccessCallback' in settings)) {
        settings.uploadSuccessCallback = null;
    }
    if (!('deleteUrl' in settings)) {
        throw 'settings.deleteUrl is not defined';
    }
    if (!('thumbnailLimit' in settings)) {
        settings.thumbnailLimit = -1;
    }
    if (!('thumbnailSize' in settings)) {
        throw 'settings.thumbnailSize is not defined';
    }
    if (!('uploadChunkSize' in settings)) {
        settings.uploadChunkSize = -1;
    }
    if (!('clearTags' in settings)) {
        settings.clearTags = [];
    }
    if (!('uploadedFiles' in settings)) {
        settings.uploadedFiles = {};
    }

    const getLength = function (obj) {
        let length = 0;
        for (let key in obj) {
            if (obj.hasOwnProperty(key)) {
                length++;
            }
        }
        return length;
    };

    const studies = {};

    const hasNotUploaded = function () {
        let length = 0;
        $.each(studies, function (id, study) {
            $.each(study.series, function (id, ser) {
                $.each(ser.instances, function (id, instance) {
                    if (!instance.isUploaded) {
                        length++;
                    }
                });
            });
        });
        return length;
    };

    const deleteStudyUploaded = async function (study) {
        const instances = [];
        $.each(study.series, function (id, ser) {
            $.each(ser.instances, function (id, instance) {
                if (instance.isUploaded) {
                    instances.push(instance.uuid);
                }
            });
        });
        if (!instances.length) {
            return true;
        }
        try {
            return await axios.delete(settings.deleteUrl, {data: instances});
        } catch (e) {
            return false;
        }
    };

    const deleteSerUploaded = async function (ser) {
        const instances = [];
        $.each(ser.instances, function (id, instance) {
            if (instance.isUploaded) {
                instances.push(instance.uuid);
            }
        });
        if (!instances.length) {
            return true;
        }
        try {
            return await axios.delete(settings.deleteUrl, {data: instances});
        } catch (e) {
            return false;
        }
    };

    const deleteInstanceUploaded = async function (instance) {
        const instances = [];
        if (instance.isUploaded) {
            instances.push(instance.uuid);
        }
        if (!instances.length) {
            return true;
        }
        try {
            return await axios.delete(settings.deleteUrl, {data: instances});
        } catch (e) {
            return false;
        }
    };

    const getDateFormatted = function (studyDate) {
        const regex = /^([0-9]{4})([0-9]{2})([0-9]{2})$/;
        if (!regex.test(studyDate) || studyDate === '00000000') {
            return 'Не указано';
        }
        return studyDate.replace(regex, '$3/$2/$1');
    };

    const getStudyModalityDescription = function (studyModality) {
        switch (studyModality) {
            case 'CR':
                return 'Компьютерная радиография';
            case 'CT':
                return 'Компьютерная томография';
            case 'DX':
                return 'Цифровая радиография';
            case 'ES':
                return 'Эндоскопия';
            case 'IO':
                return 'Ультра-оральная радиография';
            case 'MG':
                return 'Маммография';
            case 'MR':
                return 'Магнитно-резонансная томография';
            case 'NM':
                return 'Медицинская радиология OT – Другое';
            case 'PX':
                return 'Панорамный рентгеновский снимок RF – Радиофлюорография';
            case 'RG':
                return 'Рентгенография';
            case 'SC':
                return 'Вторичная фиксация изображения US – УЗИ';
            case 'XA':
                return 'Рентгеноангиография';
            case 'XC':
                return 'Фотография на внешнюю камеру ECG – Электрокардиография';
            default:
                return 'Другое';
        }
    };

    const generateHtml = function () {
        if (getLength(studies)) {
            $(settings.outputFiles).prop('hidden', false);
        }
        $.each(studies, function (id, study) {
            if (!study.children) {
                $('<section>', {
                    class: 'dicom-study card shadow-sm mb-3',
                    append: [
                        $('<header>', {
                            class: 'card-header px-3 py-2 text-left position-relative',
                            append: [
                                $('<h6>', {
                                    class: 'm-0',
                                    html: 'Исследование',
                                    append: [
                                        $('<span>', {
                                            html: study.description,
                                        }),
                                    ],
                                }),
                                $('<small>', {
                                    class: 'd-block text-muted',
                                    html: study.id,
                                }),
                                $('<button>', {
                                    class: 'btn btn-danger btn-lg p-0 rounded',
                                    click: async function () {
                                        const $this = $(this);
                                        if (!confirm('Вы действительно хотите удалить все файлы исследования?')) {
                                            $this.blur();
                                            return;
                                        }
                                        if (await deleteStudyUploaded(studies[study.id])) {
                                            const $study = $this.closest('.dicom-study');
                                            $study.remove();
                                            delete studies[study.id];
                                        } else {
                                            alert('Не удалось удалить файлы исследования!');
                                        }
                                        $(settings.outputFiles).prop('hidden', !getLength(studies));
                                        $(settings.uploadButton).prop('disabled', !hasNotUploaded());
                                    },
                                }),
                            ],
                        }),
                        study.children = $('<article>', {
                            class: 'card-body px-2 py-0',
                            append: [
                                $('<div>', {
                                    class: 'dicom-study-summary small text-left m-2',
                                    append: [
                                        $('<span>', {
                                            class: 'd-block',
                                            html: 'Часть тела: ' + study.bodyPart,
                                        }),
                                        $('<span>', {
                                            class: 'd-block',
                                            html: 'Вид исследования: ' + getStudyModalityDescription(study.modality),
                                        }),
                                        $('<span>', {
                                            class: 'd-block',
                                            html: 'Дата исследования: ' + getDateFormatted(study.date),
                                        }),
                                    ],
                                }),
                            ],
                        }),
                    ],
                }).appendTo(settings.outputFiles);
            }
            $.each(study.series, function (id, ser) {
                if (!ser.children) {
                    $('<section>', {
                        class: 'dicom-ser card shadow-sm mx-2 my-3',
                        append: [
                            $('<header>', {
                                class: 'card-header px-3 py-2 text-left position-relative',
                                append: [
                                    $('<h6>', {
                                        class: 'm-0',
                                        html: 'Серия',
                                        append: [
                                            $('<span>', {
                                                html: ser.description,
                                            }),
                                        ],
                                    }),
                                    $('<small>', {
                                        class: 'd-block text-muted',
                                        html: ser.id,
                                    }),
                                    $('<button>', {
                                        class: 'btn btn-danger btn-lg p-0 rounded',
                                        click: async function () {
                                            const $this = $(this);
                                            if (!confirm('Вы действительно хотите удалить все файлы серии?')) {
                                                $this.blur();
                                                return;
                                            }
                                            if (await deleteSerUploaded(studies[study.id].series[ser.id])) {
                                                const $ser = $this.closest('.dicom-ser');
                                                const $study = $ser.closest('.dicom-study');
                                                $ser.remove();
                                                delete studies[study.id].series[ser.id];
                                                if (!getLength(studies[study.id].series)) {
                                                    $study.remove();
                                                    delete studies[study.id];
                                                }
                                            } else {
                                                alert('Не удалось удалить файлы серии!');
                                            }
                                            $(settings.outputFiles).prop('hidden', !getLength(studies));
                                            $(settings.uploadButton).prop('disabled', !hasNotUploaded());
                                        },
                                    }),
                                ],
                            }),
                            $('<article>', {
                                class: 'card-body p-2',
                                append: [
                                    ser.children = $('<div>', {
                                        class: 'd-flex',
                                    }),
                                ],
                            }),
                        ],
                    }).appendTo(study.children);
                }
                $.each(ser.instances, function (id, instance) {
                    if (!instance.child) {
                        instance.child = $('<div>', {
                            class: 'dicom-instance dummy p-2',
                            css: {
                                order: instance.number,
                            },
                            append: [
                                $('<button>', {
                                    class: 'btn btn-secondary d-block p-0 position-relative',
                                    click: async function () {
                                        const $this = $(this);
                                        const $instance = $this.closest('.dicom-instance');
                                        if ($instance.hasClass('dummy')) {
                                            if (await instance.showThumbnail()) {
                                                $instance.removeClass('dummy');
                                            } else {
                                                alert('Не удалось создать изображение!');
                                            }
                                            $this.blur();
                                        } else {
                                            if (!confirm('Вы действительно хотите удалить файл?')) {
                                                $this.blur();
                                                return;
                                            }
                                            if (!await instance.remove()) {
                                                alert('Не удалось удалить файл!');
                                            }
                                            $(settings.outputFiles).prop('hidden', !getLength(studies));
                                            $(settings.uploadButton).prop('disabled', !hasNotUploaded());
                                        }
                                    },
                                    append: [
                                        $('<canvas>', {
                                            class: 'd-block rounded cornerstone-canvas',
                                        }).prop({
                                            width: settings.thumbnailSize.width,
                                            height: settings.thumbnailSize.height,
                                        }),
                                    ],
                                }),
                            ],
                        }).appendTo(ser.children);
                        instance.remove = async function (clientOnly) {
                            if (!clientOnly && !await deleteInstanceUploaded(studies[study.id].series[ser.id].instances[instance.id])) {
                                return false;
                            }
                            const $instance = instance.child;
                            const $ser = $instance.closest('.dicom-ser');
                            const $study = $ser.closest('.dicom-study');
                            $instance.remove();
                            delete studies[study.id].series[ser.id].instances[instance.id];
                            if (!getLength(studies[study.id].series[ser.id].instances)) {
                                $ser.remove();
                                delete studies[study.id].series[ser.id];
                            }
                            if (!getLength(studies[study.id].series)) {
                                $study.remove();
                                delete studies[study.id];
                            }
                            return true;
                        };
                    }
                });
            });
        });
        $(settings.uploadButton).prop('disabled', !hasNotUploaded());
        $.each(studies, function (id, study) {
            $.each(study.series, function (id, ser) {
                const instances = [];
                let notDummyCount = 0;
                $.each(ser.instances, function (id, instance) {
                    instances.push(instance);
                    const $instance = instance.child;
                    if (!$instance.hasClass('dummy')) {
                        notDummyCount++;
                    }
                });
                let i = settings.thumbnailLimit - notDummyCount;
                if (settings.thumbnailLimit < 0 || i > 0) {
                    instances.sort(function (a, b) {
                        const $a = $(a.child), $b = $(b.child);
                        if (parseInt($a.css('order'), 10) < parseInt($b.css('order'), 10)) {
                            return -1;
                        }
                        if (parseInt($a.css('order'), 10) > parseInt($b.css('order'), 10)) {
                            return 1;
                        }
                        return 0;
                    });
                    for (let j = 0; j < instances.length; j++) {
                        if (!i) {
                            break;
                        }
                        const $instance = instances[j].child;
                        if ($instance.hasClass('dummy')) {
                            $instance.removeClass('dummy');
                            if (instances[j].showThumbnail()) {
                                i--;
                            }
                        }
                    }
                }
            });
        });
    };

    if (settings.uploadedFiles) {
        $.each(settings.uploadedFiles, function (i, dicomData) {
            const studyId = (dicomData.study_uid || '0').trim();
            const studyDescription = (dicomData.study_description || 'Не указано').trim();
            const studyBodyPart = (dicomData.study_body_part || 'Не указано').trim();
            const studyModality = (dicomData.study_modality || 'Не указано').trim();
            const studyDate = (dicomData.study_date || '00000000').trim();
            const serId = (dicomData.series_uid || '0').trim();
            const serDescription = (dicomData.series_description || 'Не указано').trim();
            const uuid = (dicomData.uuid || '0').trim();
            const thumbnailUrl = (dicomData.preview_url || '').trim();
            const instanceNumber = (dicomData.instance_number || '0').trim();
            const instanceId = (dicomData.instance_number || uuid).trim();
            if (!studies[studyId]) {
                studies[studyId] = {
                    id: studyId,
                    description: studyDescription,
                    bodyPart: studyBodyPart,
                    modality: studyModality,
                    date: studyDate,
                    series: {},
                };
            }
            if (!studies[studyId].series[serId]) {
                studies[studyId].series[serId] = {
                    id: serId,
                    description: serDescription,
                    instances: {},
                };
            }
            if (!studies[studyId].series[serId].instances[instanceId]) {
                studies[studyId].series[serId].instances[instanceId] = {
                    id: instanceId,
                    number: instanceNumber,
                    thumbnailUrl: thumbnailUrl,
                    isUploaded: true,
                    uuid: uuid,
                    showThumbnail: async function () {
                        const instance = this;
                        const canvas = instance.child.find('canvas')[0];
                        const canvasContext = canvas.getContext('2d');
                        return await new Promise(function (resolve) {
                            const image = new Image;
                            image.onload = function() {
                                canvasContext.drawImage(image, 0, 0, settings.thumbnailSize.width, settings.thumbnailSize.height);
                                resolve(true);
                            };
                            image.onerror = function(){
                                resolve(false);
                            };
                            image.src = instance.thumbnailUrl;
                        });
                    },
                };
            }
        });
        generateHtml();
    }

    let getDicomDataErrorCount = 0;

    const getDicomData = function (file) {
        const fileReader = new FileReader();
        fileReader.readAsArrayBuffer(file);
        return new Promise(function (resolve) {
            fileReader.onloadend = function (event) {
                const arrayBuffer = event.target.result;
                const byteArray = new Uint8Array(arrayBuffer);
                if (String.fromCharCode.apply(null, byteArray.slice(128, 132)) !== 'DICM') {
                    getDicomDataErrorCount++;
                    resolve(null);
                } else {
                    resolve(dicomParser.parseDicom(byteArray));
                }
            };
        });
    };

    $(settings.inputFiles).find('input[type="file"]').on('change', function (event) {
        const $inputFiles = $(settings.inputFiles), $outputFiles = $(settings.outputFiles), $uploadButton = $(settings.uploadButton);
        $inputFiles.prop('hidden', true);
        $outputFiles.prop('hidden', true);
        $uploadButton.prop('hidden', true);
        let uploadedCount = 0, uploadedSize = 0, uploadedPercent = 0;
        let totalCount = 0;
        let totalSize = 0;
        $.each(event.target.files, function (i, file) {
            totalCount++;
            totalSize += file.size;
        });
        const $uploadProgress = $(settings.uploadProgress);
        $uploadProgress.prop('hidden', false);
        const $progressBar = $uploadProgress.find('.progress-bar').css({width: 0});
        const $progressPercent = $uploadProgress.find('.progress-percent').html(0);
        const $progressCount = $uploadProgress.find('.progress-count').html(0);
        const $progressTotalCount = $uploadProgress.find('.progress-total-count').html(totalCount);
        const $progressSize = $uploadProgress.find('.progress-size').html(0);
        const $progressTotalSize = $uploadProgress.find('.progress-total-size').html(Math.round(totalSize / 1024 / 1024));
        const promises = [];
        $.each(event.target.files, function (i, file) {
            promises.push(new Promise(function (resolve) {
                getDicomData(file).then(function (dicomData) {
                    if (dicomData) {
                        const studyId = (dicomData.string('x0020000d') || '0').trim();
                        const studyDescription = (dicomData.string('x00081030') || 'Не указано').trim();
                        const studyBodyPart = (dicomData.string('x00180015') || 'Не указано').trim();
                        const studyModality = (dicomData.string('x00080060') || 'Не указано').trim();
                        const studyDate = (dicomData.string('x00080020') || '00000000').trim();
                        const serId = (dicomData.string('x0020000e') || '0').trim();
                        const serDescription = (dicomData.string('x0008103e') || 'Не указано').trim();
                        const instanceId = (dicomData.string('x00200013') || '0').trim();
                        const instanceNumber = (dicomData.string('x00200013') || '0').trim();
                        if (!studies[studyId]) {
                            studies[studyId] = {
                                id: studyId,
                                description: studyDescription,
                                bodyPart: studyBodyPart,
                                modality: studyModality,
                                date: studyDate,
                                series: {},
                            };
                        }
                        if (!studies[studyId].series[serId]) {
                            studies[studyId].series[serId] = {
                                id: serId,
                                description: serDescription,
                                instances: {},
                            };
                        }
                        if (!studies[studyId].series[serId].instances[instanceId]) {
                            studies[studyId].series[serId].instances[instanceId] = {
                                id: instanceId,
                                number: instanceNumber,
                                showThumbnail: async function () {
                                    const canvasContainer = this.child.children('button')[0];
                                    const imageId = cornerstoneWADOImageLoader.wadouri.fileManager.add(file);
                                    const image = await cornerstone.loadImage(imageId);
                                    if (image) {
                                        cornerstone.enable(canvasContainer);
                                        cornerstone.displayImage(canvasContainer, image);
                                        return true;
                                    }
                                    return false;
                                },
                                file: file,
                            };
                        }
                    }
                    uploadedCount++;
                    uploadedSize += file.size;
                    uploadedPercent = 100 * uploadedCount / totalCount;
                    $progressCount.html(uploadedCount);
                    $progressSize.html(Math.round(uploadedSize / 1024 / 1024));
                    $progressPercent.html(Math.round(uploadedPercent));
                    $progressBar.css({width: Math.round(uploadedPercent) + '%'});
                    resolve();
                });
            }));
        });
        Promise.all(promises).then(function () {
            setTimeout(function () {
                if (getDicomDataErrorCount) {
                    alert('Некоторые файлы (' + getDicomDataErrorCount + ') были отклонены, т.к. не являются файлами формата DICOM!');
                    getDicomDataErrorCount = 0;
                }
                $inputFiles.prop('hidden', false);
                $outputFiles.prop('hidden', false);
                $uploadButton.prop('hidden', false);
                $uploadProgress.prop('hidden', true);
                generateHtml();
            }, 1000);
        });
        $(this).val('');
    });

    $(settings.uploadButton).on('click', async function () {
        if (!hasNotUploaded()) {
            alert('Не выбрано ни одного файла!');
            return;
        }
        const $inputFiles = $(settings.inputFiles), $outputFiles = $(settings.outputFiles), $uploadButton = $(settings.uploadButton);
        $inputFiles.prop('hidden', true);
        $outputFiles.prop('hidden', true);
        $uploadButton.prop('hidden', true);
        const files = [];
        let i = 0, j = 0;
        let uploadedCount = 0, uploadedSize = 0, totalCount = 0, totalSize = 0, uploadedPercent = 0;
        $.each(studies, function (id, study) {
            $.each(study.series, function (id, ser) {
                $.each(ser.instances, function (id, instance) {
                    if (instance.isUploaded) {
                        return;
                    }
                    j = settings.uploadChunkSize < 1 ? 0 : Math.floor(i++ / settings.uploadChunkSize);
                    (files[j] = files[j] || []).push({
                        file: instance.file,
                        removeInstanceFromClient: function () {
                            instance.remove(true);
                        },
                    });
                    totalCount += 1;
                    totalSize += instance.file.size;
                });
            });
        });
        const $uploadProgress = $(settings.uploadProgress);
        $uploadProgress.prop('hidden', false);
        const $progressBar = $uploadProgress.find('.progress-bar').css({width: 0});
        const $progressPercent = $uploadProgress.find('.progress-percent').html(0);
        const $progressCount = $uploadProgress.find('.progress-count').html(0);
        const $progressTotalCount = $uploadProgress.find('.progress-total-count').html(totalCount);
        const $progressSize = $uploadProgress.find('.progress-size').html(0);
        const $progressTotalSize = $uploadProgress.find('.progress-total-size').html(Math.round(totalSize / 1024 / 1024));
        try {
            for (let i = 0; i < files.length; i++) {
                let totalCountChunk = 0, totalSizeChunk = 0;
                const data = new FormData(), promises = [];
                for (let j = 0; j < files[i].length; j++) {
                    totalCountChunk++;
                    totalSizeChunk += files[i][j].file.size;
                    promises.push(new Promise(function (resolve) {
                        getDicomData(files[i][j].file).then(function (dicomData) {
                            for (let i = 0; i < settings.clearTags.length; i++) {
                                const tag = settings.clearTags[i];
                                const re = /^\(([0-9]{4}),([0-9]{4})\)$/;
                                if (!re.test(tag)) {
                                    return;
                                }
                                const element = dicomData.elements[tag.replace(re, 'x$1$2')];
                                for (let i = 0; i < element.length; i++) {
                                    dicomData.byteArray[element.dataOffset + i] = 32;
                                }
                            }
                            data.append('files[]', new Blob([dicomData.byteArray], {type: 'application/dicom'}), files[i][j].file.name);
                            resolve();
                        });
                    }));
                }
                await Promise.all(promises);
                let _uploadedCount = 0, _uploadedSize = 0, _uploadedPercent = 0;
                const response = await axios.post(settings.uploadUrl, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onUploadProgress: function (event) {
                        const p = event.loaded / event.total;
                        _uploadedCount = uploadedCount + totalCountChunk * p;
                        _uploadedSize = uploadedSize + totalSizeChunk * p;
                        _uploadedPercent = 100 * _uploadedCount / totalCount;
                        $progressCount.html(Math.round(_uploadedCount));
                        $progressSize.html(Math.round(_uploadedSize / 1024 / 1024));
                        $progressPercent.html(Math.round(_uploadedPercent));
                        $progressBar.css({width: Math.round(_uploadedPercent) + '%'});
                    }
                });
                uploadedCount = _uploadedCount;
                uploadedSize = _uploadedSize;
                uploadedPercent = _uploadedPercent;
                for (let j = 0; j < files[i].length; j++) {
                    files[i][j].removeInstanceFromClient();
                }
            }
            setTimeout(function () {
                alert('Все файлы успешно загружены!');
                if (settings.uploadSuccessCallback) {
                    settings.uploadSuccessCallback();
                } else {
                    $uploadProgress.prop('hidden', true);
                    $uploadButton.prop('hidden', false).prop('disabled', true);
                    $inputFiles.prop('hidden', false);
                    $outputFiles.prop('hidden', false);
                }
            }, 1000);
        } catch (e) {
            setTimeout(function () {
                alert('Не все файлы были загружены! Попробуйте загрузить их ещё раз.');
                $uploadProgress.prop('hidden', true);
                $uploadButton.prop('hidden', false).prop('disabled', false);
                $inputFiles.prop('hidden', false);
                $outputFiles.prop('hidden', false);
            }, 1000);
        }

    });

    $(window).on('beforeunload', function() {
        if (hasNotUploaded()) {
            return 'Если покинуть страницу сейчас, то незагруженные файлы будут потеряны!';
        }
    });

};


