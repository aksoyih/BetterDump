<?php

echo '
<script>
    function toggleCollapse(element) {
        const content = element.nextElementSibling;
        content.style.display = content.style.display === "none" ? "block" : "none";
    }

    function toggleDebugInfo(element) {
        const debugInfo = document.querySelector(".debug-info");
        debugInfo.style.display = debugInfo.style.display === "none" ? "block" : "none";
    }

    function expandAll() {
        document.querySelectorAll(".content").forEach(element => {
            element.style.display = "block";
        });
    }
</script>';