<?php
    echo "<div class='docs'>";
    if ($Read->getResult()) {
        foreach ($Read->getResult() as $Doc) {
            extract($Doc);
            echo "
                    <div class='doc'>
                        <p>{$doc_title}</p>
                        <p><a href='" . BASE . "/uploads/documents/{$doc_pdf}' title='{$doc_title}' class='btn_docs' style='background: {$doc_btn_bg_color}; color:{$doc_btn_text_color};' download='1' onmouseover=\"this.style.background='{$doc_btn_bg_color_hover}'\" onmouseout=\"this.style.background='{$doc_btn_bg_color}'\">{$doc_btn_text}</a></p>
                    </div>
                ";
        }
    }
    echo "</div>";
?>
