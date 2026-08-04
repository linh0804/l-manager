(function () {
        var form = document.getElementById("code_form");
        var editorPath = form.dataset.path;
        var fileExt = form.dataset.fileExt;

        var saveButton = document.getElementById("editor-save");
        var wrapButton = document.getElementById("editor-wrap");
        var syntaxButton = document.getElementById("editor-syntax");
        var formatButton = document.getElementById("editor-format");
        var messageElement = document.getElementById("code_check_message");
        var wrapEnabled = false;

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
                content: editor.state.doc.toString()
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
                content: editor.state.doc.toString()
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
                    content: editor.state.doc.toString()
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
            window.editorSetWrap(wrapEnabled);
            wrapButton.style.borderColor = wrapEnabled ? "green" : "";
        });

        document.addEventListener("keydown", function (event) {
            if (event.ctrlKey && event.key === "s") {
                event.preventDefault();
                save();
            }
        });
    })();