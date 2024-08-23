<?php
/*
Plugin Name: Highlight Search On Page
Description: Adds a search box to highlight text matches on the page.
Version: 1.0
Author: Germain-Italic
*/

function highlight_search_shortcode() {
    ob_start();
    ?>
    <input type="text" id="searchInput" placeholder="Enter text to search">
    <button id="searchButton">Search</button>

    <script>
    document.getElementById('searchButton').addEventListener('click', function() {
        var searchValue = document.getElementById('searchInput').value;
        if (searchValue) {
            removeHighlights();
            highlightText(searchValue);
        }
    });

    function highlightText(searchValue) {
        var regex = new RegExp(searchValue, 'gi');
        var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
        var node;

        while (node = walker.nextNode()) {
            var parent = node.parentNode;
            if (parent && parent.nodeName !== 'SCRIPT' && parent.nodeName !== 'STYLE') {
                var matches = node.nodeValue.match(regex);
                if (matches) {
                    var span = document.createElement('span');
                    span.innerHTML = node.nodeValue.replace(regex, function(matched) {
                        return '<mark>' + matched + '</mark>';
                    });
                    parent.replaceChild(span, node);
                }
            }
        }
    }

    function removeHighlights() {
        var marks = document.querySelectorAll('mark');
        marks.forEach(function(mark) {
            var parent = mark.parentNode;
            parent.replaceChild(document.createTextNode(mark.textContent), mark);
            parent.normalize();
        });
    }
    </script>

    <style>
    mark {
        background-color: yellow;
    }
    </style>
    <?php
    return ob_get_clean();
}

add_shortcode('highlight_search', 'highlight_search_shortcode');
