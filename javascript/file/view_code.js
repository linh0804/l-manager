    document.addEventListener("DOMContentLoaded", function() {
        hljs.configure({
            ignoreUnescapedHTML: true
        });
        hljs.highlightAll();

        const codeElements = document.querySelector("code");

        // doi theme
        var elementTheme = document.querySelector("#themes");
        elementTheme.addEventListener("change", function () {
            var currentHref = document.getElementById("classHl").href;
            var newHref = currentHref.replace(/\/[^\/]*$/, "/" + elementTheme.value);
            document.getElementById("classHl").href = newHref + ".min.css";
        });

        // doi cu phap
        var elementCode = document.querySelector("#coder");
        elementCode.addEventListener("change", function () {
            codeElements.className = elementCode.value;
            delete codeElements.dataset.highlighted;
            hljs.highlightAll();
        });
    });