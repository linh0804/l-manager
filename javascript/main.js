$("table.list-file tr").click(function () {
    $(this).addClass("active").siblings().removeClass("active");
});

$('#file-select-all').on('change', function () {
    for (let i = 0; i < document.form.elements.length; ++i) {
        if (document.form.elements[i].type === "checkbox") {
            document.form.elements[i].checked = document.form.all.checked === true; // === true;
        }
    }
});

function get_entries() {
    return $('form input[name="entries[]"]:checked').map(function () {
        return this.value;
    }).get();
}

function get_input(name) {
    let input = $('input[name="' + name + '"]');

    if (name.endsWith('[]')) {
        return input.map(function () {
            return this.value;
        }).get();
    }

    return input.val();
}

function get_select(entries) {
    var out = '<ul class="list">';
    $.each(entries, function(i, value) {
        let parent = $('input[name="entries[]"][value="' + value + '"]:checked').closest('td').next('td').clone();
        out += `<li>${parent.html()}</li>`;
    });
    out += '</ul>';
    return out;
}

var copyButton = $('#mutil_copy');
var moveButton = $('#mutil_move');
var zipButton = $('#mutil_zip');
var deleteButton = $('#mutil_delete');
var chmodButton = $('#mutil_chmod');
var rnameButton = $('#mutil_rename');


//copy
copyButton.on('click', function(e) {
    e.preventDefault();
    var entries = get_entries();
    const copy_html = `${get_select(entries)}
    <span class="path_seperator">${$(this).attr('path')}</span><br />
    <span class="bull">&bull; </span>Đường dẫn tập tin mới:<br/>
    <input name="path" id="box_input" type="text" value="${$(this).attr('path')}" />
    `;
    create_box('Sao chép', copy_html, entries, $(this).attr('path'), 'file-copy', function(url, data_post, new_path = false) {
        if(new_path.length) data_post = {...data_post, new_path};
        fm_ajax({
            url: url,
            content: data_post
        }, function (data) {
           console.log(data);
            if (data.error) {
                createBox(data.error);
                return;
            }
            if(data.status) {
                $('.overlay_box').last().remove();
                alert_box('Đã thực hiện thành công.<br> Bạn có muốn tải lại trang không?');
            }
        });
    });
});

//move
moveButton.on('click', function(e) {
    e.preventDefault();
    var entries = get_entries();
    const move_html = `${get_select(entries)}
    <span class="path_seperator">${$(this).attr('path')}</span><br />
    <span class="bull">&bull; </span>Đường dẫn tập tin mới:<br/>
    <input name="path" id="box_input" type="text" value="${$(this).attr('path')}" />
    `;
    create_box('Di chuyển', move_html, entries, $(this).attr('path'), 'file-move', function(url, data_post, new_path = false) {
        if(new_path) data_post = {...data_post, new_path};
        fm_ajax({
            url: url,
            content: data_post
        }, function (data) {
           console.log(data);
            if (data.error) {
                createBox(data.error);
                return;
            }
            if(data.status) {
                $('.overlay_box').last().remove();
                alert_box('Đã thực hiện thành công.<br> Bạn có muốn tải lại trang không?');
            }
        });
    });
});

//zip
zipButton.on('click', function(e) {
    e.preventDefault();
    var entries = get_entries();
    const zip_html = `${get_select(entries)}
    <span class="path_seperator">${$(this).attr('path')}</span><br />
    <span class="bull">&bull; </span>Tên tập tin nén:<br/>
    <input name="path" id="box_name_input" type="text" value="archive.zip" /><br />
    <span class="bull">&bull; </span>Đường dẫn lưu:<br/>
    <input name="path" id="box_input" type="text" value="${$(this).attr('path')}" />
    `;
    create_box('Nén zip', zip_html, entries, $(this).attr('path'), 'file-zip', function(url, data_post, new_path = false) {
        if(new_path.length) data_post = {...data_post, new_path};
        data_post = {...data_post, 'name': $('#box_name_input').val()};
        fm_ajax({
            url: url,
            content: data_post
        }, function (data) {
           console.log(data);
            if (data.error) {
                createBox(data.error);
                return;
            }
            if(data.status) {
                $('.overlay_box').last().remove();
                alert_box('Đã thực hiện thành công.<br> Bạn có muốn tải lại trang không?');
            }
        });
    });
});

//delete
deleteButton.on('click', function(e) {
    e.preventDefault();
    var entries = get_entries();
    const delete_html = `${get_select(entries)}
    <span class="path_seperator">${$(this).attr('path')}</span>
    <input name="path" id="box_input" type="hidden" value="null" />
    `;
    create_box('Xóa', delete_html, entries, $(this).attr('path'), 'file-delete', function(url, data_post, new_path = false) {
        if(new_path) data_post = {...data_post, new_path};
        fm_ajax({
            url: url,
            content: data_post
        }, function (data) {
           console.log(data);
            if (data.error) {
                createBox(data.error);
                return;
            }
            if(data.status) {
                $('.overlay_box').last().remove();
                alert_box('Đã thực hiện thành công.<br> Bạn có muốn tải lại trang không?');
            }
        });
    });
});

//chmod
chmodButton.on('click', function(e) {
    e.preventDefault();
    var entries = get_entries();
    let chmod_list = '';

   

    const chmod_html = `${get_select(entries)}
    <span class="path_seperator">${$(this).attr('path')}</span><br />
    <span class="bull">&bull; </span>Thư mục:<br/>
    <input type="text" name="folder" value="755" size="18"/><br/>
    <span class="bull">&bull; </span>Tập tin:<br/>
    <input type="text" name="file" value="644" size="18"/><br/>            
    <input name="path" id="box_input" type="hidden" value="null" />
    `;
    create_box('Chmod', chmod_html, entries, $(this).attr('path'), 'file-chmod', function(url, data_post, new_path = false) {
        if(new_path.length) data_post = {...data_post, new_path};
        var folder = get_input('folder');
        var file = get_input('file');
        data_post = {...data_post, folder, file};
        fm_ajax({
            url: url,
            content: data_post
        }, function (data) {
            if (data.error) {                
                createBox(data.error);
                return;
            }
            if(data.status) {
                $('.overlay_box').last().remove();
                alert_box('Đã thực hiện thành công.<br> Bạn có muốn tải lại trang không?');
            }
        });
    });
});


//rname
rnameButton.on('click', function(e) {
    e.preventDefault();
    var entries = get_entries();
    let rname_list = '';

    $.each(entries, function (i, value) {
        let parent = $('input[name="entries[]"][value="' + value + '"]:checked')
            .closest('td')
            .next('td')
            .clone();
        rname_list += `
            <li>${parent.html()}</li>
            <input type="text" name="modifier[]" value="${entries[i]}" size="18"/>
            <hr/>
        `;
    });

    const rname_html = `
    <span class="path_seperator">${$(this).attr('path')}</span><br />
    <span class="bull">&bull; </span>Đổi tên mới:<br/>
    ${ rname_list }
    <input name="path" id="box_input" type="hidden" value="null" />
    `;
    create_box('Đổi tên', rname_html, entries, $(this).attr('path'), 'file-rname', function(url, data_post, new_path = false) {
        if(new_path.length) data_post = {...data_post, new_path};
        var modifier = get_input('modifier[]');
        data_post = {...data_post, modifier};
        fm_ajax({
            url: url,
            content: data_post
        }, function (data) {
            if (data.error.length) {
                data.error.map(function(value, index, array) {
                    createBox(value);
                });
                return;
            }
            if (data.message.length) {
                data.message.map(function(value, index, array) {
                    createBox(value);
                });
            }
            if(data.status) {
                $('.overlay_box').last().remove();
                alert_box('Đã thực hiện thành công.<br> Bạn có muốn tải lại trang không?');
            }
        });
    });
});