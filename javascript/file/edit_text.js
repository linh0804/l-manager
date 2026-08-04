const editorElement = document.getElementById("editor");
    const saveButton = document.getElementById("editor-save");
    const wrapButton = document.getElementById("editor-wrap");
    const syntaxButton = document.getElementById("editor-syntax");
    const formatButton = document.getElementById("editor-format");
    const messageElement = document.getElementById("code_check_message");
    let wrapEnabled = false;

    function showMessage(message) {
        messageElement.textContent = message || "";
        messageElement.style.display = "block";
    }

    function save() {
        messageElement.textContent = "";
        messageElement.style.display = "none";

        fm_ajax({
            url: "text-save",
            path: editorPath,
            content: editorElement.value
        }, function (data) {
            createBox(data.message);

            if (data.error) {
                showMessage(data.error);
            }
        });
    }

    function checkSyntax() {
        fm_ajax({
            url: "text-syntax",
            path: editorPath,
            content: editorElement.value
        }, function (data) {
            createBox(data.message);
            showMessage(data.error || data.message);
        });
    }

    function formatCode() {
        alert_box("Chức năng có thể thay đổi cấu trúc code, xác nhận dùng!", () => { 
            fm_ajax({
                url: "text-format",
                path: editorPath,
                format: Manager['text'],
                content: editorElement.value
            }, function (data) {
                if (data.error) {
                    createBox(data.error);
                    return;
                }
    
                editorElement.value = data.format;
            });

        });
    }

    saveButton.addEventListener("click", save);
    syntaxButton.addEventListener("click", checkSyntax);
    formatButton.addEventListener("click", formatCode);

    wrapButton.addEventListener("click", function () {
        wrapEnabled = !wrapEnabled;

        if (wrapEnabled) {
            editorElement.removeAttribute("wrap");
            editorElement.removeAttribute("style");
            wrapButton.style.borderColor = "green";
        } else {
            editorElement.setAttribute("wrap", "soft");
            editorElement.setAttribute("style", "white-space: nowrap; overflow-wrap: anywhere;");
            wrapButton.style.borderColor = "";
        }
    });

    document.addEventListener("keydown", function(event) {
        if (event.ctrlKey && event.key === "s") {
            event.preventDefault();
            save();
        }
    });