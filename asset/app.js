// copy
$(".copy-button").click(function (e) {
    e.preventDefault();

    let data = $(this).data("copy");

    navigator.clipboard
        .writeText(data)
        .then(function () {
            alert("Đã copy!");
        })
        .catch(function (err) {
            alert("Lỗi: ", err);
        });
});

// menu
function toggle_menu() {
    document.body.classList.toggle("has-menu");
}

document.addEventListener("click", function (e) {
    var target_id = e.target.id;
    if (target_id === "nav-menu" || target_id === "menu-overlay" || (document.body.classList.contains("has-menu") && e.target.closest(".menu-toggle a:not(.no-pusher)"))) {
        document.body.classList.toggle("has-menu");
    }
});

function redirect(url) {
    window.location.href = url;
}

function fm_ajax(data, success) {
    return $.ajax({
        url: `${Manager['home']}/api/${data.url}.php`,
        method: "post",
        data: data,
        success: success
    });
}

$(".list-file .btn-calc-size").on("click", function () {
    let e = $(this);

    fm_ajax(e.data(), function (res) {
        e.html(res.data.total_size_readable);
    });
});

function create_box(name, content, entries, path, url, callback) {
    if(!entries.length) return;
    $('body').append(`
        <div class="overlay_box">
            <div class="box_container">
                <div class="box_name">${name}</div>
                <div class="box_content">${content}</div>

                <div class="box_button">
                    <button class="btn_close">Đóng</button>
                    <button class="btn_ok">OK</button>
                </div>
            </div>
        </div>
    `);

    $('.btn_close').last().on('click', function () {
        $('.overlay_box').last().remove();
    });
    $('.btn_ok').last().on('click', function () {        
       callback(url, {'is_action': 1, entries, path}, $('#box_input').val());       
    })
}


function createBox(title, content = ''){

    let box = $(`
        <div class="toast show">
            <b>${!content ? 'Hệ thống' : title}</b><br>
            ${!content ? title : content}
        </div>
    `);

    $("#toast-container").prepend(box);

    box.on("animationend", function(e){
        if(e.originalEvent.animationName === "hide"){
            $(this).remove();
        }
    });

    box.on("click", function(){
        $(this).remove();
    });
}

function alert_box(name, handle = () => {location.reload()}) {
    $('body').append(`
        <div class="overlay_box">
            <div class="box_container">
                <div class="box_name">Thông báo</div>
                <div class="box_content">
                    ${name}
                </div>

                <div class="box_button">
                    <button class="btn_cancel">Hủy</button>
                    <button class="btn_ok">OK</button>
                </div>
            </div>
        </div>
    `);

    $('.btn_cancel').on('click', function () {
        $('.overlay_box').remove();
    });

    $('.btn_ok').on('click', function () {
        handle();
        $('.overlay_box').remove();
    });
}

